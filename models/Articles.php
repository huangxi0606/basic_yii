<?php

namespace app\models;

use Yii;
use yii\filters\AccessControl;

/**
 * This is the model class for table "articles".
 *
 * @property int $id 文章ID
 * @property string $article_name 文章名称
 * @property int $cates_id 文章所属分类ID
 * @property string $pic 文章图片
 * @property string $search_address 搜索地点
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $address_name 地点名称
 * @property string $detail_position 详细位置
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property Cates $articleCate
 */
class Articles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_name', 'cates_id'], 'required'],
            [['cates_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['article_name', 'longitude', 'latitude', 'province', 'city', 'county'], 'string', 'max' => 32],
            [['pic', 'search_address'], 'string', 'max' => 255],
            [['address_name', 'detail_position'], 'string', 'max' => 64],
            [['cates_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cates::className(), 'targetAttribute' => ['cates_id' => 'id']],
        ];
    }

    /*
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_name' => '文章标题',
            'cates_id' => '文章分类id',
            'pic' => '图片',
            'search_address' => '查找地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'address_name' => '地址名字',
            'detail_position' => '详细位置',
            'province' => '省',
            'city' => '市',
            'county' => '县/区',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCates()
    {
        return $this->hasOne(Cates::className(), ['id' => 'cates_id']);
    }

    /**
     * 保存之前
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s',time());
                $this->updated_at = $this->created_at;
            } else {
                $this->updated_at = date('Y-m-d H:i:s',time());
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取分类下拉列表
     * @return array
     */

    public static function forDropDownList()
    {
        $arr = Cates::find()->where(['status'=>0])->select('id,cate_name,parents_id')->asArray()->all();
        $cates =new Cates();
        return array_column($cates->getSubTreeTwo($arr,'parents_id','id'), 'cate_name','id');
    }

    /**
     * 获取列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function GetList(){
        $articles =Articles::find()->all();
        return $articles;
    }


}
