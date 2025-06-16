<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $ProductID
 * @property string $name
 * @property float $price
 * @property string|null $Description
 * @property string|null $category
 * @property int|null $stock
 * @property string|null $ImageURL
 * @property string|null $createdAt
 * @property string|null $updatedAt
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
            [['Description', 'category', 'ImageURL'], 'default', 'value' => null],
            [['stock'], 'default', 'value' => 0],
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['Description'], 'string'],
            [['stock'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'ImageURL'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 100],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ProductID' => 'ID',
            'name' => 'Name',
            'price' => 'price',
            'Description' => 'Description',
            'category' => 'Category',
            'stock' => 'Stock',
            'ImageURL' => 'Image Url',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
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

    public function getProductsByCreatedAt($createdAt)
    {
        return Products::find()->where(['createdAt' => $createdAt])->all();
    }
    public function getProductsByUpdatedAt($updatedAt)
    {
        return Products::find()->where(['updatedAt' => $updatedAt])->all();
    }
    public function getProductsByNameAndCategory($name, $category)
    {
        return Products::find()->where(['like', 'name', $name])->andWhere(['category' => $category])->all();
    }
}
