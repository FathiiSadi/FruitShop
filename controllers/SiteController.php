<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\models\CartSearch;
use app\models\Orders;
use app\models\Products;
use app\models\CheckoutForm;
use app\models\ProductsSearch;
use app\models\Addresses;
use app\models\Payments;
use app\models\OrderItem;
use app\models\SignupForm;
use Error;
use yii\data\ActiveDataProvider;

use app\models\User;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    // public function actionCheckout()
    // {
    //     if (Yii::$app->user->isGuest) {
    //         return $this->redirect(['site/login']);
    //     }


    //     return $this->render('checkout');
    // }


    /**
     * Process the checkout.
     */



    /**
     * Order success page
     */

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if (Yii::$app->user->identity->role === 'admin') return $this->redirect(['/admin/default/index']);
            else {
                return $this->redirect(['/site/index']);
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user instanceof \yii\web\IdentityInterface) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionOrders()
    {
        $orders = Orders::find()->where(['user_id' => Yii::$app->user->id, 'status' => 'processing'])->all();
        return $this->render('orders', [
            'orders' => $orders,
        ]);
    }

    public function actionError(){
        return $this->render('error');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    // public function actionCheckout()
    // {
    //     // $orderModel = new \app\models\Order();
    //     $userId = Yii::$app->user->id;

    //     $addressModel = new Addresses();
    //     $orderModel = new Order();
    //     $paymentModel = new Payments();

    //     if (Yii::$app->request->post()) {
    //         if ($addressModel->load(Yii::$app->request->post()) && $addressModel->save()) {
    //             $orderModel->user_id = Yii::$app->user->id;
    //             $orderModel->id = $addressModel->id;
    //             $orderModel->order_date = date('Y-m-d H:i:s');
    //             $orderModel->status = 'Pending';
    //             $orderModel->subtotal = Yii::$app->request->post('hidden-subtotal');
    //             $orderModel->tax_amount = Yii::$app->request->post('hidden-tax');
    //             $orderModel->shipping_cost = Yii::$app->request->post('hidden-shipping');
    //             $orderModel->total_amount = Yii::$app->request->post('hidden-total');
    //             $orderModel->notes = Yii::$app->request->post('notes');

    //             if ($orderModel->save()) {
    //                 if ($paymentModel->load(Yii::$app->request->post())) {
    //                     $paymentModel->id = $orderModel->id;
    //                     $paymentModel->amount = $orderModel->total_amount;
    //                     $paymentModel->payment_date = date('Y-m-d H:i:s');
    //                     if ($paymentModel->save()) {
    //                         return $this->redirect(['order-success']);
    //                     }
    //                 }
    //             }
    //         }
    //     }


    //     return $this->render('checkout', [
    //         'cart' => Cart::find()->where(['user_id' => $userId, 'Status' => 'open'])->with('cartItems.product')->one(),
    //         'userId' => $userId,
    //         'addressModel' => $addressModel,
    //         'paymentModel' => $paymentModel,
    //     ]);
    // }

    public function actionOrderSuccess($id)
    {
        $order = Orders::findOne($id);

        if (!$order) {
            throw new error('Order not found.');
        }

        return $this->render('order-success', [
            'order' => $order,
        ]);
    }


    public function actionContact()
    {
        $model = new ContactForm();
        return $this->render(
            'contact',
            [
                'model' => $model
            ]
        );
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionShop()
    {
        return $this->render('shop');
    }


    public function actionSingleProduct()
    {
        // This action can be used to display a single product's details
        // You can pass the product ID as a parameter and fetch the product from the database
        // For now, we'll just render a placeholder view
        return $this->render('single-product');
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
                    ->where(['id' => $cart->id, 'id' => $productId])
                    ->one();

                if ($cartItem) {
                    // Update existing item quantity
                    $cartItem->quantity += $quantity;
                } else {
                    // Create new cart item
                    $cartItem = new CartItem();
                    $cartItem->id = $cart->id;
                    $cartItem->id = $productId;
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
                    ->where(['id' => $cart->id, 'id' => $productId])
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
                    ->where(['id' => $cart->id, 'id' => $productId])
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
                ->where(['user_id' => Yii::$app->user->id, 'Status' => Cart::STATUS_OPEN])
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
                $session->set('cart_id', $cart->id);
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
            ->where(['id' => $cartId])
            ->sum('quantity') ?: 0;
    }
}
