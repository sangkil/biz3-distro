<?php

namespace backend\models\master;

use Yii;
use mdm\admin\models\User;

/**
 * This is the model class for table "user_notification".
 *
 * @property integer $user_id
 * @property string $message
 * @property integer $id
 * @property integer $start_at
 * @property integer $finish_at
 */
class UserNotification extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'integer'],
            [['message'], 'string', 'max' => 256],
            [['start_at', 'finish_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User ID',
            'message' => 'Message',
            'id' => 'ID',
            'start_at' => 'Start At',
            'finish_at' => 'Finish At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($param) {
        parent::beforeSave($param);
        $this->start_at = strtotime($this->start_at);
        $this->finish_at = strtotime($this->finish_at);
        return true;
    }

}
