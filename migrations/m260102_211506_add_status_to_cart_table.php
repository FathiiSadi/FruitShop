<?php

use yii\db\Migration;

class m260102_211506_add_status_to_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add status column to cart table if it doesn't exist
        $table = $this->db->getTableSchema('{{%cart}}');
        if (!isset($table->columns['status'])) {
            $this->addColumn('{{%cart}}', 'status', $this->string(50)->defaultValue('open'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cart}}', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260102_211506_add_status_to_cart_table cannot be reverted.\n";

        return false;
    }
    */
}
