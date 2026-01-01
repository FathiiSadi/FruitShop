<?php

namespace app\modules\models;

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
 * @property string|null $createdAt
 * @property string|null $updatedAt
 */
class Products extends \yii\db\ActiveRecord
{


    public $imageFile;

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
            [['createdAt', 'updatedAt'], 'safe'],
            [['name', 'image_url'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 100],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'checkExtensionByMimeType' => false],


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
            'price' => 'Price',
            'description' => 'description',
            'category' => 'Category',
            'stock' => 'Stock',
            'image_url' => 'Image Url',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }


    public function upload()
    {
        if ($this->imageFile === null) {
            return true; // No file uploaded is valid in this case
        }

        // Generate unique filename
        $filename = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
        $uploadPath = Yii::getAlias('uploads/events/');

        // Create directory if it doesn't exist
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fullPath = $uploadPath . $filename;

        if ($this->imageFile->saveAs($fullPath)) {
            $this->image_url = 'uploads/events/' . $filename; // Web-accessible path
            return true;
        }

        return false;
    }
    public static function getProducts()
    {
        return self::find()->all();
    }
    /**
     * Calculates the expected profit based on the products' price and stock.
     * The profit is calculated as (price * stock) - (stock * 0.1) - (stock * 0.16).
     *
     * @return float The total expected profit.
     */
    public static function getExpectedProfit()
    {
        $totalProfit = 0;
        foreach (self::getProducts() as $product) {
            $totalProfit += $product->price * $product->stock - $product->stock * 0.1 - $product->stock * 0.16;
        }
        return $totalProfit;
    }

    /**
     * Returns all products.
     * @return Products[]
     */
}
