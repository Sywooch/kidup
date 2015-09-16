<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "category_tag".
 *
 * @property string $language_id
 * @property string $tag
 * @property integer $category_id
 * @property integer $id
 *
 * @property Category $category
 * @property Language $language
 */
class CategoryTag extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'tag', 'category_id'], 'required'],
            [['category_id'], 'integer'],
            [['language_id'], 'string', 'max' => 5],
            [['tag'], 'string', 'max' => 45],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'language_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => 'Language ID',
            'tag' => 'Tag',
            'category_id' => 'Category ID',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['language_id' => 'language_id']);
    }




}
