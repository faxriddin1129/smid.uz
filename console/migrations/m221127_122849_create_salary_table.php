<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%salary}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221127_122849_create_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%salary}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'money' => $this->float(),
            'month' => $this->string(55),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-salary-user_id}}',
            '{{%salary}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-salary-user_id}}',
            '{{%salary}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-salary-created_by}}',
            '{{%salary}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-salary-created_by}}',
            '{{%salary}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-salary-updated_by}}',
            '{{%salary}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-salary-updated_by}}',
            '{{%salary}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-salary-user_id}}',
            '{{%salary}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-salary-user_id}}',
            '{{%salary}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-salary-created_by}}',
            '{{%salary}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-salary-created_by}}',
            '{{%salary}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-salary-updated_by}}',
            '{{%salary}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-salary-updated_by}}',
            '{{%salary}}'
        );

        $this->dropTable('{{%salary}}');
    }
}
