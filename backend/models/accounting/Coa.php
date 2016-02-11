<?php

namespace backend\models\accounting;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "coa".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $code
 * @property string $name
 * @property integer $type
 * @property string $normal_balance
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Coa $parent
 * @property Coa[] $coas
 * @property EntriSheetDtl[] $entriSheetDtls
 * @property GlDetail[] $glDetails
 */
class Coa extends \yii\db\ActiveRecord {

    use \mdm\converter\EnumTrait;

    const BALANCE_DEBIT = 'D';
    const BALANCE_KREDIT = 'K';
     
    const TYPE_REAL = 1;
    const TYPE_NOMINAL = 2;


        /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'coa';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent_id', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name', 'type', 'normal_balance'], 'required'],
            [['code'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 64],
            [['normal_balance'], 'string', 'max' => 1],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'code' => 'Code',
            'name' => 'Name',
            'type' => 'Type',
            'normal_balance' => 'Normal Balance',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Coa::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoas() {
        return $this->hasMany(Coa::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntriSheetDtls() {
        return $this->hasMany(EntriSheetDtl::className(), ['coa_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlDetails() {
        return $this->hasMany(GlDetail::className(), ['coa_id' => 'id']);
    }

    public function getNmBalance() {
        return $this->getLogical('normal_balance', 'BALANCE_');
    }
    
    public function getAccType() {
        return $this->getLogical('type', 'TYPE_');
    }

    public static function find() {
        return new CoaQuery(get_called_class());
    }

    public static function selectOptions() {
//        return ArrayHelper::map(static::find()->with(['parent'])->codeOrdered()->asArray()->all(), 'id', 'name');
        $options = [];
        $coas = static::find()->codeOrdered()->asArray()->all();
        foreach ($coas as $dmodel) {
            $lvl = $i = $plus = 0;
            $first_nol = false;
            foreach (str_split($dmodel['code']) as $val) {
                if ($val == '0' && !$first_nol) {
                    $lvl = $i;
                    $first_nol = true;
                }
                $plus = ($val !== '0' && $first_nol) ? 5 : 0;
                $i+=1;
            }
            $options[$dmodel['id']] = \yii\helpers\Html::encode(str_repeat(".", $lvl + $plus -1) . $dmodel['name']);
        }
        return $options;
    }

    public static function getHierarchy() {
        $options = [];

        $parents = self::find()->where("parent_id is null")->all();
        foreach ($parents as $id => $p) {
            $children = self::find()->where("parent_id=:parent_id", [":parent_id" => $p->id])->all();
            $child_options = [];
            foreach ($children as $child) {
                $child_options[$child->id] = $child->name;
            }
            $options[$p->name] = $child_options;
        }
        return $options;
    }

    public function behaviors() {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => BlameableBehavior::className()]
        ];
    }

}
