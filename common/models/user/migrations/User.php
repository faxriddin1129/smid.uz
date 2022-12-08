<?php

namespace common\modules\user\migrations;

use Yii;
use yii\db\Migration;

class User extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(45)->unique(),
            'phone' => $this->string(45)->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'step' => $this->smallInteger()->notNull()->defaultValue(1),
            'role' => $this->smallInteger()->notNull()->defaultValue(\common\modules\user\models\User::ROLE_USER),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'deleted_at' => $this->integer(),

            'token' => $this->string(255),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(),
        ], $tableOptions);

        $this->insert('{{%user}}', [
            'phone' => '+998120000000',
            'username' => 'admin',
            'token' => Yii::$app->security->generateRandomString(64),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin123'),
            'created_at' => date('U'),
            'updated_at' => date('U'),
            'status' => \common\modules\user\models\User::STATUS_ACTIVE,
            'role' => \common\modules\user\models\User::ROLE_ADMIN,
            'step' => \common\modules\user\models\User::STEP_REGISTRATION_END
        ]);

        // creates index for column `phone`
        $this->createIndex(
            '{{%idx-user-phone}}',
            '{{%user}}',
            'phone'
        );

        // creates index for column `username`
        $this->createIndex(
            '{{%idx-user-username}}',
            '{{%user}}',
            'username'
        );
    }

    public function safeDown()
    {
        // drops index for column `phone`
        $this->dropIndex(
            'idx-user-phone',
            '{{%user}}'
        );

        // drops index for column `username`
        $this->dropIndex(
            'idx-user-username',
            '{{%user}}'
        );

        $this->dropTable('{{%user}}');
    }
}
