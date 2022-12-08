<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expencess}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221127_113508_create_expencess_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expencess}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(),
            'money' => $this->float(),
            'comment' => $this->string(),
            'category_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-expencess-category_id}}',
            '{{%expencess}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-expencess-category_id}}',
            '{{%expencess}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-expencess-created_by}}',
            '{{%expencess}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expencess-created_by}}',
            '{{%expencess}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-expencess-updated_by}}',
            '{{%expencess}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expencess-updated_by}}',
            '{{%expencess}}',
            'updated_by',
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
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-expencess-category_id}}',
            '{{%expencess}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-expencess-category_id}}',
            '{{%expencess}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-expencess-created_by}}',
            '{{%expencess}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-expencess-created_by}}',
            '{{%expencess}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-expencess-updated_by}}',
            '{{%expencess}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-expencess-updated_by}}',
            '{{%expencess}}'
        );

        $this->dropTable('{{%expencess}}');
    }
}
