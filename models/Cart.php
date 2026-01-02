<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use yii\web\Response;

/**
 * This is the model class for table "Cart".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $created_at
 * @property string|null $status
 *
 * @property string|null $updated_at
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
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'open'],
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cart ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'status' => 'Status',
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
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function setStatusToOpen()
    {
        $this->status = self::STATUS_OPEN;
    }
    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['cart_id' => 'id']);
    }
    /**
     * @return bool
     */
    public function isStatusCheckedout()
    {
        return $this->status === self::STATUS_CHECKED_OUT;
    }

    public function setStatusToCheckedout()
    {
        $this->status = self::STATUS_CHECKED_OUT;
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



    /**
     * Helper method to get cart item count
     */
    private function getCartItemCount($cartId)
    {
        return CartItem::find()
            ->where(['cart_id' => $cartId])
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
    //         ->where(['id' => $this->id])
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
            CartItem::deleteAll(['cart_id' => $this->id]);
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
        $newCart->user_id = $userId;
        $newCart->status = 'open';
        $newCart->created_at = date('Y-m-d H:i:s');

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
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
