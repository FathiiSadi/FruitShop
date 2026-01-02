<?php

use yii\db\Migration;

/**
 * Seeds the database with initial data from JSON files
 */
class m260102_000001_seed_initial_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Seed users
        $usersJson = file_get_contents(__DIR__ . '/../data/users.json');
        $users = json_decode($usersJson, true);

        foreach ($users as $user) {
            $this->insert('{{%users}}', [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password_hash' => $user['password_hash'],
                'auth_key' => $user['auth_key'],
                'access_token' => $user['access_token'],
                'role' => $user['role'],
            ]);
        }

        // Seed products
        $productsJson = file_get_contents(__DIR__ . '/../data/products.json');
        $products = json_decode($productsJson, true);

        foreach ($products as $product) {
            $this->insert('{{%products}}', [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'description' => $product['description'],
                'category' => $product['category'],
                'stock' => $product['stock'],
                'image_url' => $product['image_url'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ]);
        }

        // Seed addresses
        $addressesJson = file_get_contents(__DIR__ . '/../data/addresses.json');
        $addresses = json_decode($addressesJson, true);

        foreach ($addresses as $address) {
            $this->insert('{{%addresses}}', [
                'id' => $address['id'],
                'user_id' => $address['user_id'],
                'full_name' => $address['full_name'],
                'phone' => $address['phone'],
                'address_line1' => $address['address_line1'],
                'address_line2' => $address['address_line2'] ?? null,
                'city' => $address['city'],
                'state' => $address['state'] ?? null,
                'postal_code' => $address['postal_code'],
                'country' => $address['country'],
                'is_default' => $address['is_default'] ?? false,
            ]);
        }

        // Seed cart
        $cartJson = file_get_contents(__DIR__ . '/../data/cart.json');
        $carts = json_decode($cartJson, true);

        foreach ($carts as $cart) {
            $this->insert('{{%cart}}', [
                'id' => $cart['id'],
                'user_id' => $cart['user_id'],
                'session_id' => $cart['session_id'] ?? null,
            ]);
        }

        // Seed cart items
        $cartItemsJson = file_get_contents(__DIR__ . '/../data/cart_item.json');
        $cartItems = json_decode($cartItemsJson, true);

        foreach ($cartItems as $item) {
            $this->insert('{{%cart_item}}', [
                'id' => $item['id'],
                'cart_id' => $item['cart_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Seed orders
        $ordersJson = file_get_contents(__DIR__ . '/../data/orders.json');
        $orders = json_decode($ordersJson, true);

        foreach ($orders as $order) {
            $this->insert('{{%orders}}', [
                'id' => $order['id'],
                'user_id' => $order['user_id'],
                'total_amount' => $order['total_amount'],
                'status' => $order['status'],
                'payment_method' => $order['payment_method'] ?? null,
                'shipping_address_id' => $order['shipping_address_id'] ?? null,
                'created_at' => $order['created_at'],
            ]);
        }

        // Seed order items
        $orderItemsJson = file_get_contents(__DIR__ . '/../data/order_items.json');
        $orderItems = json_decode($orderItemsJson, true);

        foreach ($orderItems as $item) {
            $this->insert('{{%order_items}}', [
                'id' => $item['id'],
                'order_id' => $item['order_id'],
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Seed payments
        $paymentsJson = file_get_contents(__DIR__ . '/../data/payments.json');
        $payments = json_decode($paymentsJson, true);

        foreach ($payments as $payment) {
            $this->insert('{{%payments}}', [
                'id' => $payment['id'],
                'order_id' => $payment['order_id'],
                'payment_method' => $payment['payment_method'],
                'amount' => $payment['amount'],
                'status' => $payment['status'],
                'transaction_id' => $payment['transaction_id'] ?? null,
                'payment_date' => $payment['payment_date'] ?? null,
            ]);
        }

        // Reset sequences for PostgreSQL
        $this->execute("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
        $this->execute("SELECT setval('products_id_seq', (SELECT MAX(id) FROM products))");
        $this->execute("SELECT setval('addresses_id_seq', (SELECT MAX(id) FROM addresses))");
        $this->execute("SELECT setval('cart_id_seq', (SELECT MAX(id) FROM cart))");
        $this->execute("SELECT setval('cart_item_id_seq', (SELECT MAX(id) FROM cart_item))");
        $this->execute("SELECT setval('orders_id_seq', (SELECT MAX(id) FROM orders))");
        $this->execute("SELECT setval('order_items_id_seq', (SELECT MAX(id) FROM order_items))");
        $this->execute("SELECT setval('payments_id_seq', (SELECT MAX(id) FROM payments))");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%payments}}');
        $this->truncateTable('{{%order_items}}');
        $this->truncateTable('{{%orders}}');
        $this->truncateTable('{{%cart_item}}');
        $this->truncateTable('{{%cart}}');
        $this->truncateTable('{{%addresses}}');
        $this->truncateTable('{{%products}}');
        $this->truncateTable('{{%users}}');
    }
}
