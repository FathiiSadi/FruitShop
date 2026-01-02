<?php

namespace app\controllers;

use app\models\Cart;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use app\models\Products;

use app\models\CartItem;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cart models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id; // Assuming user is logged in
        $cart = Cart::find()->where([
            'user_id' => $userId,
            'status' => 'open'
        ])->with('cartItems.product')->one();

        return $this->render('index', [
            'cart' => $cart,
            'userId' => $userId,
        ]);
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
                $product->stock -= 1;
                if ($product->stock < 0) {
                    throw new \Exception('Insufficient stock available.');
                }
                $product->save();
                // Check if item already exists in the cart
                $cartItem = CartItem::find()
                    ->where(['cart_id' => $cart->id, 'product_id' => $productId])
                    ->one();

                if ($cartItem) {
                    // Update existing item quantity
                    $cartItem->quantity += $quantity;
                } else {
                    // Create new cart item
                    $cartItem = new CartItem();
                    $cartItem->cart_id = $cart->id;
                    $cartItem->product_id = $productId;
                    $cartItem->quantity = $quantity;
                    $cartItem->price = $product->price;
                }

                if (!$cartItem->save()) {
                    throw new \Exception('Failed to save cart item: ' . json_encode($cartItem->errors));
                }

                return [
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'cartCount' => $this->getCartItemCount($cart->id)
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
                    ->where(['cart_id' => $cart->id, 'product_id' => $productId])
                    ->one();

                $product = Products::findOne($productId);
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found.'];
                }
                $product->stock -= 1;
                if ($product->stock < 0) {
                    throw new \Exception('Insufficient stock available.');
                }
                $product->save();

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
                        'cartCount' => $this->getCartItemCount($cart->id)
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
                    ->where(['cart_id' => $cart->id, 'product_id' => $productId])
                    ->one();

                if ($cartItem) {
                    $cartItem->delete();

                    return [
                        'success' => true,
                        'message' => 'Item removed from cart!',
                        'cartCount' => $this->getCartItemCount($cart->id)
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
            $count = $this->getCartItemCount($cart->id);

            return ['success' => true, 'count' => $count];
        } catch (\Exception $e) {
            return ['success' => false, 'count' => 0];
        }
    }

    private function getOrCreateCart()
    {
        $session = Yii::$app->session;

        if (!Yii::$app->user->isGuest) {
            $cart = Cart::find()
                ->where(['user_id' => Yii::$app->user->id, 'status' => Cart::STATUS_OPEN])
                ->one();
        } else {
            $cartId = $session->get('cart_id');
            if ($cartId) {
                $cart = Cart::findOne($cartId);
                if ($cart && $cart->status !== Cart::STATUS_OPEN) {
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
                $session->set('cart_id', $cart->id);
            }
        }

        return $cart;
    }


    /**
     * Helper method to get cart item count
     */
    public static function getCartItemCount($cartId)
    {
        return CartItem::find()
            ->where(['cart_id' => $cartId])
            ->sum('quantity') ?: 0;
    }
}
