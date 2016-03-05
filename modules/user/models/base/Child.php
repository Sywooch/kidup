<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "child".
 *
 * @property integer $id
 * @property string $name
 * @property integer $birthday
 * @property string $gender
 * @property integer $user_id
 *
 * @property \user\models\User $user
 */
class Child extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['name'], 'string', 'max' => 45],
            [['gender'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' =>'ID',
            'name' =>'Name',
            'birthday' =>'Birthday',
            'gender' =>'Gender',
            'user_id' =>'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'user_id']);
    }
}
