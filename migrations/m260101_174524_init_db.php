<?php

use yii\db\Migration;

/**
 * Class m260101_174524_init_db
 */
class m260101_174524_init_db extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // 1. User Table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'authKey' => $this->string(32)->notNull(),
            'access_token' => $this->string(),
            'role' => $this->string()->defaultValue('user'),
        ], $tableOptions);

        // 2. Products Table
        $this->createTable('{{%products}}', [
            'ProductID' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'Description' => $this->text(),
            'category' => $this->string(100),
            'stock' => $this->integer()->defaultValue(0),
            'ImageURL' => $this->string(),
            'createdAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 3. Addresses Table
        $this->createTable('{{%addresses}}', [
            'address_id' => $this->primaryKey(),
            'UserID' => $this->integer()->notNull(),
            'recipient_name' => $this->string(100)->notNull(),
            'street_address' => $this->string(255)->notNull(),
            'city' => $this->string(100)->notNull(),
            'state' => $this->string(100),
            'postal_code' => $this->string(20)->notNull(),
            'country' => $this->string(100)->notNull(),
            'phone_number' => $this->string(20),
            'is_default' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 4. Cart Table
        $this->createTable('{{%Cart}}', [
            'CartID' => $this->primaryKey(),
            'UserID' => $this->integer()->notNull(),
            'CreatedAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'UpdatedAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'Status' => "ENUM('open', 'checked_out') DEFAULT 'open'",
        ], $tableOptions);

        // 5. CartItem Table
        $this->createTable('{{%CartItem}}', [
            'CartItemID' => $this->primaryKey(),
            'CartID' => $this->integer()->notNull(),
            'ProductID' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->decimal(10, 2)->notNull(),
            'AddedAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 6. Orders Table
        $this->createTable('{{%orders}}', [
            'order_id' => $this->primaryKey(),
            'UserID' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'order_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => "ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'",
            'subtotal' => $this->decimal(10, 2)->notNull(),
            'tax_amount' => $this->decimal(10, 2)->notNull(),
            'shipping_cost' => $this->decimal(10, 2)->defaultValue(15.00),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'notes' => $this->text(),
        ], $tableOptions);

        // 7. Order Items Table
        $this->createTable('{{%order_items}}', [
            'order_item_id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unit_price' => $this->decimal(10, 2)->notNull(),
            'total_price' => $this->decimal(10, 2)->notNull(),
        ], $tableOptions);

        // 8. Payments Table
        $this->createTable('{{%payments}}', [
            'payment_id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'payment_method' => "ENUM('cash_on_delivery', 'visa', 'checkout_com') NOT NULL",
            'amount' => $this->decimal(10, 2)->notNull(),
            'payment_status' => "ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending'",
            'payment_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'cardholder_name' => $this->string(100),
            'last_four_digits' => $this->string(4),
            'expiry_month' => $this->string(2),
            'expiry_year' => $this->string(4),
        ], $tableOptions);

        // Foreign Keys
        $this->addForeignKey('fk-addresses-user', '{{%addresses}}', 'UserID', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-cart-user', '{{%Cart}}', 'UserID', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-cartitem-cart', '{{%CartItem}}', 'CartID', '{{%Cart}}', 'CartID', 'CASCADE');
        $this->addForeignKey('fk-cartitem-product', '{{%CartItem}}', 'ProductID', '{{%products}}', 'ProductID', 'CASCADE');
        $this->addForeignKey('fk-orders-user', '{{%orders}}', 'UserID', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-orders-address', '{{%orders}}', 'address_id', '{{%addresses}}', 'address_id', 'CASCADE');
        $this->addForeignKey('fk-order_items-order', '{{%order_items}}', 'order_id', '{{%orders}}', 'order_id', 'CASCADE');
        $this->addForeignKey('fk-order_items-product', '{{%order_items}}', 'product_id', '{{%products}}', 'ProductID', 'CASCADE');
        $this->addForeignKey('fk-payments-order', '{{%payments}}', 'order_id', '{{%orders}}', 'order_id', 'CASCADE');

        // DUMP DATA
        // 1. Products
        $this->batchInsert('{{%products}}', ['name', 'price', 'Description', 'category', 'stock', 'ImageURL'], [
            ['Apple', 2.50, 'Fresh Green Apple', 'Fruits', 100, 'assets_static/img/products/product-img-1.jpg'],
            ['Banana', 1.20, 'Sweet Banana', 'Fruits', 150, 'assets_static/img/products/product-img-2.jpg'],
            ['Orange', 3.00, 'Juicy Orange', 'Fruits', 80, 'assets_static/img/products/product-img-3.jpg'],
            ['Strawberry', 5.50, 'Red Strawberry', 'Berries', 50, 'assets_static/img/products/product-img-4.jpg'],
            ['Blueberry', 6.00, 'Fresh Blueberry', 'Berries', 40, 'assets_static/img/products/product-img-5.jpg'],
            ['Watermelon', 10.00, 'Large Watermelon', 'Fruits', 20, 'assets_static/img/products/product-img-6.jpg'],
        ]);

        // 2. Default Admin User (password: admin123)
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin123'),
            'authKey' => Yii::$app->security->generateRandomString(),
            'role' => 'admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payments}}');
        $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%orders}}');
        $this->dropTable('{{%CartItem}}');
        $this->dropTable('{{%Cart}}');
        $this->dropTable('{{%addresses}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%user}}');
    }
}
