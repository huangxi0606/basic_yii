<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bands".
 *
 * @property int $id 品牌ID
 * @property string $bank_name 品牌名称
 */
class Bands extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bands';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'band_name' => 'Band Name',
        ];
    }
}
