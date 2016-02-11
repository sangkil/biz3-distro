<?php

namespace backend\models\accounting;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "acc_periode".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_from
 * @property string $date_to
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property GlHeader[] $glHeaders
 */
class AccPeriode extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait;

    const STATUS_OPEN = 10;
    const STATUS_CLOSE = 20;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'acc_periode';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'date_from', 'date_to', 'DateFrom', 'DateTo', 'status'], 'required'],
            [['date_from', 'date_to'], 'safe'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlHeaders() {
        return $this->hasMany(GlHeader::className(), ['periode_id' => 'id']);
    }

    public function getNmStatus() {
        return $this->getLogical('status', 'STATUS_');
    }

    public static function find() {
        return new AccPeriodeQuery(get_called_class());
    }

    public static function selectOptions($type = 'open') {
        return ArrayHelper::map(static::find()->$type()->asArray()->all(), 'id', 'name');
    }

    public function beforeSave($insert) {
        parent::beforeSave($insert);
        //Insert New Record
        if($this->isNewRecord){
            $this->status = self::STATUS_OPEN;
            return true;
        }
        
        $oldPeriode = $this->findOne($this->id);
        
        //Closing
        if ($this->status == self::STATUS_CLOSE && $oldPeriode->status == self::STATUS_OPEN) {
            $cekBfor = $this->find()->where('status=:dstatus AND id<:did', [':dstatus' => self::STATUS_OPEN, ':did' => $this->id])->one();
            if ($cekBfor) {
                $this->status = $oldPeriode->status;
                $this->addError('status', 'Periode hanya dapat ditutup secara berurutan.');

                return false;
            }
        }

        //Cancel Closing
        if ($this->status == self::STATUS_OPEN && $oldPeriode->status == self::STATUS_CLOSE) {
            $cekAfter = $this->find()->where('status=:dstatus AND id>:did', [':dstatus' => self::STATUS_CLOSE, ':did' => $this->id])->one();
            if ($cekAfter) {
                $this->status = $oldPeriode->status;
                $this->addError('status', 'Periode yang telah ditutup hanya dapat dibatalkan jika periode sesudahnya masih open.');

                return false;
            }
        }
        return true;
    }
    
    public function beforeDelete() {
        parent::beforeDelete();
        $ada = GlHeader::find()->where('periode_id = :did',[':did'=>  $this->id])->exists();
        if($ada){
            $this->addError('id', $this->name. 'telah digunakan dalam journal');
            return false;
        }
        return true;
    }

    public function behaviors() {
        return [
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'DateFrom' => 'date_from',
                    'DateTo' => 'date_to',
                ]
            ],
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
