<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221127_120257_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'bet_user_id' => $this->integer(),
            'mow_user_id' => $this->integer(),
            'packaging_user_id' => $this->integer(),
            'bet_date' => $this->integer(),
            'mow_date_integer' => $this->integer(),
            'packaging_date' => $this->integer(),
            'month' => $this->string(55),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-order-product_id}}',
            '{{%order}}',
            'product_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-order-product_id}}',
            '{{%order}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-order-created_by}}',
            '{{%order}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-order-created_by}}',
            '{{%order}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-order-updated_by}}',
            '{{%order}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-order-updated_by}}',
            '{{%order}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bet_user_id`
        $this->createIndex(
            '{{%idx-order-bet_user_id}}',
            '{{%order}}',
            'bet_user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-order-bet_user_id}}',
            '{{%order}}',
            'bet_user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `mow_user_id`
        $this->createIndex(
            '{{%idx-order-mow_user_id}}',
            '{{%order}}',
            'mow_user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-order-mow_user_id}}',
            '{{%order}}',
            'mow_user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `packaging_user_id`
        $this->createIndex(
            '{{%idx-order-packaging_user_id}}',
            '{{%order}}',
            'packaging_user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-order-packaging_user_id}}',
            '{{%order}}',
            'packaging_user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-order-product_id}}',
            '{{%order}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-order-product_id}}',
            '{{%order}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-order-created_by}}',
            '{{%order}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-order-created_by}}',
            '{{%order}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-order-updated_by}}',
            '{{%order}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-order-updated_by}}',
            '{{%order}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-order-bet_user_id}}',
            '{{%order}}'
        );

        // drops index for column `bet_user_id`
        $this->dropIndex(
            '{{%idx-order-bet_user_id}}',
            '{{%order}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-order-mow_user_id}}',
            '{{%order}}'
        );

        // drops index for column `mow_user_id`
        $this->dropIndex(
            '{{%idx-order-mow_user_id}}',
            '{{%order}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-order-packaging_user_id}}',
            '{{%order}}'
        );

        // drops index for column `packaging_user_id`
        $this->dropIndex(
            '{{%idx-order-packaging_user_id}}',
            '{{%order}}'
        );

        $this->dropTable('{{%order}}');
    }
}
