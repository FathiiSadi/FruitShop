<?php

namespace app\services;

use Yii;
use yii\base\BaseObject;
use app\models\Payments;
use app\models\Cart;
use app\components\PaymentComponent;
use yii\helpers\Url;
use Exception;

class PaymentProcessor extends BaseObject
{
    /** @var PaymentComponent */
    public $paymentComponent;
}
