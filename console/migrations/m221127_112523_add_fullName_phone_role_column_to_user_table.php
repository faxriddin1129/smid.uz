<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m221127_112523_add_fullName_phone_role_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'fullName', $this->string(55));
        $this->addColumn('{{%user}}', 'role', $this->integer());
        $this->addColumn('{{%user}}', 'phone', $this->string(13));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'fullName');
        $this->dropColumn('{{%user}}', 'role');
        $this->dropColumn('{{%user}}', 'phone');
    }
}
