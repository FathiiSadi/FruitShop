<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use yii\web\Response;

/**
 * This is the model class for table "Cart".
 *
 * @property int $CartID
 * @property int $UserID
 * @property string|null $CreatedAt
 * @property string|null $Status
 *
 * @property string|null $UpdatedAt
 * @property CartItem[] $cartItems
 * @property User $user
 */
class Cart extends \yii\db\ActiveRecord
{

    public $subtotal;
    public $total;
    public $tax;
    /**
     * ENUM field values
     */
    const STATUS_OPEN = 'open';
    const STATUS_CHECKED_OUT = 'checked_out';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Status'], 'default', 'value' => 'open'],
            [['UserID'], 'required'],
            [['UserID'], 'integer'],
            [['CreatedAt'], 'safe'],
            [['Status'], 'string'],
            ['Status', 'in', 'range' => array_keys(self::optsStatus())],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['UserID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CartID' => 'Cart ID',
            'UserID' => 'User ID',
            'CreatedAt' => 'Created At',
            'Status' => 'Status',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'UserID']);
    }


    /**
     * column Status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_OPEN => 'open',
            self::STATUS_CHECKED_OUT => 'checked_out',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->Status];
    }

    /**
     * @return bool
     */
    public function isStatusOpen()
    {
        return $this->Status === self::STATUS_OPEN;
    }

    public function setStatusToOpen()
    {
        $this->Status = self::STATUS_OPEN;
    }
    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['CartID' => 'CartID']);
    }
    /**
     * @return bool
     */
    public function isStatusCheckedout()
    {
        return $this->Status === self::STATUS_CHECKED_OUT;
    }

    public function setStatusToCheckedout()
    {
        $this->Status = self::STATUS_CHECKED_OUT;
    }

    /**
     * Calculate the subtotal of all items in the cart
     *
     * @return float
     */
    public function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->cartItems as $item) {
            $subtotal += ($item->price * $item->quantity);
        }

        return $subtotal;
    }



    public function actionAddToCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');
            $quantity = Yii::$app->request->post('quantity', 1);

            try {
                // Get or create cart for the current user/session
                $cart = $this->getOrCreateCart();
                if (!$cart) {
                    throw new \Exception('Failed to create or retrieve the cart.');
                }

                // Check if the product exists
                $product = Products::findOne($productId);
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found.'];
                }
                $tempProduct = $product->stock;
                $tempProduct -= 1;
                if ($tempProduct < 0) {
                    throw new \Exception('Insufficient stock available.');
                }


                // Check if item already exists in the cart
                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();


                if ($cartItem) {

                    $cartItem->quantity += $quantity;
                } else {
                    // Create new cart item
                    $cartItem = new CartItem();
                    $cartItem->CartID = $cart->CartID;
                    $cartItem->ProductID = $productId;
                    $cartItem->quantity = $quantity;
                    $cartItem->price = $product->price;
                }

                if (!$cartItem->save()) {
                    throw new \Exception('Failed to save cart item: ' . json_encode($cartItem->errors));
                }

                return [
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'cartCount' => $this->getCartItemCount($cart->CartID)
                ];
            } catch (\Exception $e) {
                Yii::error('Add to cart error: ' . $e->getMessage(), __METHOD__);
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

    /**
     * Update cart item quantity
     */
    public function actionUpdateCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');
            $quantity = Yii::$app->request->post('quantity');

            try {
                $cart = $this->getOrCreateCart();

                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                $product = Products::findOne($productId);
                $tempProduct = $product->stock;
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found.'];
                }
                $tempProduct -= 1;
                if ($tempProduct < 0) {
                    throw new \Exception('Insufficient stock available.');
                }

                if ($cartItem) {
                    if ($quantity > 0) {
                        $cartItem->quantity = $quantity;
                        $cartItem->save();
                    } else {
                        $cartItem->delete();
                    }


                    return [
                        'success' => true,
                        'message' => 'Cart updated successfully!',
                        'cartCount' => $this->getCartItemCount($cart->CartID)
                    ];
                }

                return ['success' => false, 'message' => 'Item not found in cart.'];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Failed to update cart.'];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

    /**
     * Remove item from cart
     */
    public function actionRemoveFromCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');

            try {
                $cart = $this->getOrCreateCart();

                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                if ($cartItem) {
                    $cartItem->delete();

                    return [
                        'success' => true,
                        'message' => 'Item removed from cart!',
                        'cartCount' => $this->getCartItemCount($cart->CartID)
                    ];
                }

                return ['success' => false, 'message' => 'Item not found in cart.'];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Failed to remove item.'];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }
    /**
     * Get cart item count for AJAX updates
     */
    public function actionCartCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $cart = $this->getOrCreateCart();
            $count = $this->getCartItemCount($cart->CartID);

            return ['success' => true, 'count' => $count];
        } catch (\Exception $e) {
            return ['success' => false, 'count' => 0];
        }
    }

    /**
     * Helper method to get or create cart
     */
    private function getOrCreateCart()
    {
        $session = Yii::$app->session;

        if (!Yii::$app->user->isGuest) {
            $cart = Cart::find()
                ->where(['UserID' => Yii::$app->user->id, 'Status' => Cart::STATUS_OPEN])
                ->one();
        } else {
            $cartId = $session->get('cart_id');
            if ($cartId) {
                $cart = Cart::findOne($cartId);
                if ($cart && $cart->Status !== Cart::STATUS_OPEN) {
                    $cart = null;
                    $session->remove('cart_id');
                }
            } else {
                $cart = null;
            }
        }

        if (!$cart) {
            $cart = Cart::createNewCart(!Yii::$app->user->isGuest ? Yii::$app->user->id : null);
            if (!$cart) {
                throw new \Exception('Unable to create a new cart.');
            }
            if (Yii::$app->user->isGuest) {
                $session->set('cart_id', $cart->CartID);
            }
        }

        return $cart;
    }


    /**
     * Helper method to get cart item count
     */
    private function getCartItemCount($cartId)
    {
        return CartItem::find()
            ->where(['CartID' => $cartId])
            ->sum('quantity') ?: 0;
    }

    /**
     * Alternative method using database aggregation (more efficient for large carts)
     *
     * @return float
     */
    // public function getSubtotalFromDb()
    // {
    //     $result = CartItem::find()
    //         ->where(['CartID' => $this->CartID])
    //         ->select('SUM(price * quantity) as subtotal')
    //         ->scalar();

    //     return $result ? (float)$result : 0.00;
    // }

    /**
     * Get total number of items in cart
     *
     * @return int
     */
    public function getTotalItems()
    {
        $total = 0;

        foreach ($this->cartItems as $item) {
            $total += $item->quantity;
        }

        return $total;
    }

    /**
     * Alternative method using database aggregation
     *
     * @return int
     */


    public function getTotal($taxRate = 0.1)
    {
        $subtotal = $this->getSubtotal();
        return $subtotal + ($subtotal * $taxRate) + 15;
    }


    /**
     * Get total with tax
     *
     * @param float $taxRate Tax rate (e.g., 0.1 for 10%)
     * @return float
     */
    public function getTotalWithTax($taxRate = 0.1)
    {
        $subtotal = $this->getSubtotal();
        return $subtotal + ($subtotal * $taxRate);
    }

    /**
     * Get tax amount
     *
     * @param float $taxRate Tax rate (e.g., 0.1 for 10%)
     * @return float
     */
    public function getTaxAmount($taxRate = 0.1)
    {
        return $this->getSubtotal() * $taxRate;
    }

    /**
     * Check if cart is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->cartItems) === 0;
    }

    /**
     * Clear all items from cart
     *
     * @return bool
     */
    public function clearCart()
    {
        try {
            CartItem::deleteAll(['CartID' => $this->CartID]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cart summary data
     *
     * @param float $taxRate
     * @return array
     */
    public function getSummary($taxRate = 0.1)
    {
        $subtotal = $this->getSubtotal();
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'taxRate' => $taxRate,
            'total' => $total,
            'itemCount' => $this->getTotalItems(),
            'isEmpty' => $this->isEmpty()
        ];
    }


    public static function createNewCart($userId)
    {
        $newCart = new self();
        $newCart->UserID = $userId;
        $newCart->Status = 'open';
        $newCart->CreatedAt = date('Y-m-d H:i:s');

        if ($newCart->save()) {
            return $newCart;
        }

        return null;
    }

    /**
     * Before save event
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->CreatedAt = date('Y-m-d H:i:s');
            }
            $this->UpdatedAt = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
