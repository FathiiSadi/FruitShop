<?php

use yii\db\Migration;

/**
 * Handles the creation of all tables for the FruitShop application.
 */
class m260102_000000_create_all_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create users table
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(255),
            'access_token' => $this->string(255),
            'role' => $this->string(50)->defaultValue('user'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create products table
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'description' => $this->text(),
            'category' => $this->string(100),
            'stock' => $this->integer()->defaultValue(0),
            'image_url' => $this->string(255),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create cart table
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'session_id' => $this->string(255),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create cart_item table
        $this->createTable('{{%cart_item}}', [
            'id' => $this->primaryKey(),
            'cart_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create orders table
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->string(50)->defaultValue('pending'),
            'payment_method' => $this->string(50),
            'shipping_address_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create order_items table
        $this->createTable('{{%order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'subtotal' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create addresses table
        $this->createTable('{{%addresses}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'full_name' => $this->string(255)->notNull(),
            'phone' => $this->string(50)->notNull(),
            'address_line1' => $this->string(255)->notNull(),
            'address_line2' => $this->string(255),
            'city' => $this->string(100)->notNull(),
            'state' => $this->string(100),
            'postal_code' => $this->string(20)->notNull(),
            'country' => $this->string(100)->notNull(),
            'is_default' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Create payments table
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'payment_method' => $this->string(50)->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->string(50)->defaultValue('pending'),
            'transaction_id' => $this->string(255),
            'payment_date' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Add foreign keys
        $this->addForeignKey(
            'fk-cart-user_id',
            '{{%cart}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cart_item-cart_id',
            '{{%cart_item}}',
            'cart_id',
            '{{%cart}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cart_item-product_id',
            '{{%cart_item}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-orders-user_id',
            '{{%orders}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-orders-shipping_address_id',
            '{{%orders}}',
            'shipping_address_id',
            '{{%addresses}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-order_items-order_id',
            '{{%order_items}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_items-product_id',
            '{{%order_items}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-addresses-user_id',
            '{{%addresses}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-payments-order_id',
            '{{%payments}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );

        // Create indexes for better performance
        $this->createIndex('idx-users-username', '{{%users}}', 'username');
        $this->createIndex('idx-users-email', '{{%users}}', 'email');
        $this->createIndex('idx-products-category', '{{%products}}', 'category');
        $this->createIndex('idx-orders-user_id', '{{%orders}}', 'user_id');
        $this->createIndex('idx-orders-status', '{{%orders}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-payments-order_id', '{{%payments}}');
        $this->dropForeignKey('fk-addresses-user_id', '{{%addresses}}');
        $this->dropForeignKey('fk-order_items-product_id', '{{%order_items}}');
        $this->dropForeignKey('fk-order_items-order_id', '{{%order_items}}');
        $this->dropForeignKey('fk-orders-shipping_address_id', '{{%orders}}');
        $this->dropForeignKey('fk-orders-user_id', '{{%orders}}');
        $this->dropForeignKey('fk-cart_item-product_id', '{{%cart_item}}');
        $this->dropForeignKey('fk-cart_item-cart_id', '{{%cart_item}}');
        $this->dropForeignKey('fk-cart-user_id', '{{%cart}}');

        // Drop tables
        $this->dropTable('{{%payments}}');
        $this->dropTable('{{%addresses}}');
        $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%orders}}');
        $this->dropTable('{{%cart_item}}');
        $this->dropTable('{{%cart}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%users}}');
    }
}
