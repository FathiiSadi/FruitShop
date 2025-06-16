<?php

namespace app\modules;

use Yii;
use yii\helpers\Url;

use yii\filters\AccessControl;

/**
 * modules module definition class
 */
class Admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->layout = '@app/views/layouts/main';
        // Yii::$app->response->redirect(Url::home());
        // Yii::$app->response->send();
        // exit;

        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->role != 'admin') {
        //     Yii::$app->response->redirect(Url::home());
        //     exit;
        // }
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === 'admin';
                        },
                        'denyCallback' => function ($rule, $action) {
                            return Yii::$app->response->redirect(['/site/index']);
                        }
                    ],
                ],
            ],
        ];
    }
}
