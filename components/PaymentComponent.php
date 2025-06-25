<?php

namespace app\components;

use yii\base\Component;

class PaymentComponent extends Component
{
    public $apiUrl;
    public $privateKey;
    public $processingId;
    public $publicKey;
}
