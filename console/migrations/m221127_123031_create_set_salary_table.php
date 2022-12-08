<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%set_salary}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%salary}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221127_123031_create_set_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%set_salary}}', [
            'id' => $this->primaryKey(),
            'salary_id' => $this->integer(),
            'user_id' => $this->integer(),
            'money' => $this->float(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `salary_id`
        $this->createIndex(
            '{{%idx-set_salary-salary_id}}',
            '{{%set_salary}}',
            'salary_id'
        );

        // add foreign key for table `{{%salary}}`
        $this->addForeignKey(
            '{{%fk-set_salary-salary_id}}',
            '{{%set_salary}}',
            'salary_id',
            '{{%salary}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-set_salary-user_id}}',
            '{{%set_salary}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-set_salary-user_id}}',
            '{{%set_salary}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-set_salary-created_by}}',
            '{{%set_salary}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-set_salary-created_by}}',
            '{{%set_salary}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-set_salary-updated_by}}',
            '{{%set_salary}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-set_salary-updated_by}}',
            '{{%set_salary}}',
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
        // drops foreign key for table `{{%salary}}`
        $this->dropForeignKey(
            '{{%fk-set_salary-salary_id}}',
            '{{%set_salary}}'
        );

        // drops index for column `salary_id`
        $this->dropIndex(
            '{{%idx-set_salary-salary_id}}',
            '{{%set_salary}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-set_salary-user_id}}',
            '{{%set_salary}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-set_salary-user_id}}',
            '{{%set_salary}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-set_salary-created_by}}',
            '{{%set_salary}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-set_salary-created_by}}',
            '{{%set_salary}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-set_salary-updated_by}}',
            '{{%set_salary}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-set_salary-updated_by}}',
            '{{%set_salary}}'
        );

        $this->dropTable('{{%set_salary}}');
    }
}
