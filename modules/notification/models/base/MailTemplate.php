<?php

namespace notification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_log".
 *
 * @property string $id
 * @property string $template
 * @property integer $created_at
 * @property integer $updated_at
 */
class MailTemplate extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at'], 'required'],
            [['template'], 'string'],
            [['updated_at', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template' => 'Template',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->created_at = time();
        }
        $this->updated_at = time();
        return parent::beforeValidate();
    }
}
