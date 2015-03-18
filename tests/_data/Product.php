<?php
namespace data;

class Product extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['categories_list'], 'safe']
        ];
    }

    public function behaviors()
    {
    return
        [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'categories_list' => [
                        'categories',
                    ],
                ]
            ]
        ];
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id_cat' => 'category_id'])
                    ->viaTable('product_has_category', ['product_id' => 'id_prod']);
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id_img' => 'image_id']);
    }

}
