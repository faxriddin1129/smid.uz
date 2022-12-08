<?php

namespace common\modules\user\migrations;

use Yii;
use yii\db\Migration;

class UserEmailConfirmation extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_phone_confirmation}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'phone_number' => $this->string(),
            'code' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'expires_at' => $this->integer(),
            'status' => $this->integer(),
            'counter' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-user_phone_confirmation-user_id--user-id',
            'user_phone_confirmation',
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_phone_confirmation-user_id--user-id',
            'user_phone_confirmation',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_phone_confirmation}}');
    }
}
