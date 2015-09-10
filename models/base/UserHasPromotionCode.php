<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "user_has_promotion_code".
 *
 * @property integer $user_id
 * @property string $promotion_code_id
 *
 * @property \app\models\User $user
 * @property \app\models\PromotionCode $promotionCode
 */
class UserHasPromotionCode extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_promotion_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'promotion_code_id'], 'required'],
            [['user_id'], 'integer'],
            [['promotion_code_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['promotion_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromotionCode::className(), 'targetAttribute' => ['promotion_code_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'promotion_code_id' => Yii::t('app', 'Promotion Code ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionCode()
    {
        return $this->hasOne(\app\models\PromotionCode::className(), ['id' => 'promotion_code_id']);
    }




}
