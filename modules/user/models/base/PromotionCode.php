<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "promotion_code".
 *
 * @property string $id
 * @property integer $type_target
 * @property integer $type_amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $valid_until
 * @property integer $count_total
 * @property integer $count_left
 *
 * @property \user\models\base\UserHasPromotionCode[] $userHasPromotionCodes
 * @property \user\models\User[] $users
 */
class PromotionCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['type_target', 'type_amount', 'created_at', 'updated_at', 'valid_until', 'count_total', 'count_left'], 'integer'],
            [['id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_target' => 'Type Target',
            'type_amount' => 'Type Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'valid_until' => 'Valid Until',
            'count_total' => 'Count Total',
            'count_left' => 'Count Left',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasPromotionCodes()
    {
        return $this->hasMany(\user\models\base\UserHasPromotionCode::className(), ['promotion_code_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\user\models\User::className(), ['id' => 'user_id'])->viaTable('user_has_promotion_code', ['promotion_code_id' => 'id']);
    }




}
