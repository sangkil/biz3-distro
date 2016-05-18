<?php

namespace app\models\master;

use Yii;
/**
 * This is the model class for table "user_to_branch".
 *
 * @property integer $branch_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Branch $branch
 */
use backend\models\master\Branch;
use mdm\admin\models\User;

class U2Branch extends \yii\db\ActiveRecord {

    public $user_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_to_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['branch_id', 'user_id'], 'required'],
            [['branch_id', 'user_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'branch_id' => 'Branch ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch() {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
