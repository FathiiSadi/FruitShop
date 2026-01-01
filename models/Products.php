<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string|null $description
 * @property string|null $category
 * @property int|null $stock
 * @property string|null $image_url
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Products extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'category', 'image_url'], 'default', 'value' => null],
            [['stock'], 'default', 'value' => 0],
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['description'], 'string'],
            [['stock'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image_url'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 100],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'price',
            'description' => 'description',
            'category' => 'Category',
            'stock' => 'Stock',
            'image_url' => 'Image Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }



    public function getAllProducts()
    {
        return Products::find()->all();
    }
    public function getProductById($id)
    {
        return Products::findOne($id);
    }
    public function getProductsByCategory($category)
    {
        return Products::find()->where(['category' => $category])->all();
    }
    public function getProductsByName($name)
    {
        return Products::find()->where(['like', 'name', $name])->all();
    }
    public function getProductsByPriceRange($minPrice, $maxPrice)
    {
        return Products::find()->where(['between', 'price', $minPrice, $maxPrice])->all();
    }
    public function getProductsByStock($stock)
    {
        return Products::find()->where(['stock' => $stock])->all();
    }

    public function getProductsBycreated_at($created_at)
    {
        return Products::find()->where(['created_at' => $created_at])->all();
    }
    public function getProductsByupdated_at($updated_at)
    {
        return Products::find()->where(['updated_at' => $updated_at])->all();
    }
    public function getProductsByNameAndCategory($name, $category)
    {
        return Products::find()->where(['like', 'name', $name])->andWhere(['category' => $category])->all();
    }
}
