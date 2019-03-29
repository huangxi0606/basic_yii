<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cates".
 *
 * @property int $id 分类ID
 * @property int $parents_id 父id
 * @property string $cate_name 分类名称
 * @property int $status 1 正常 2 禁用
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Articles[] $articles
 */
class Cates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parents_id', 'status'], 'integer'],
            [['cate_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['cate_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parents_id' => '父级id',
            'cate_name' => '分类名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Articles::className(), ['cates_id' => 'id']);
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
     * 获取父级id为0的数组
     * @return array
     */
    public static function forDropDownList()
    {
        $arr = static::find()->where(['parents_id' => 0,'status'=>0])->select('id,cate_name')->asArray()->all();
        $arr_name = array_column($arr, 'cate_name', 'id');
        return $arr_name;
    }

    /**
     * 获取子分类
     * @param $data
     * @param $parent
     * @param $son
     * @param int $pid
     * @return array
     */

    public function getSubTree($data , $parent , $son , $pid = 0) {
        $tmp = array();
        foreach ($data as $key => $value) {
            if($value[$parent] == $pid) {
                $value['child'] =  $this->getSubTree($data , $parent , $son , $value[$son]);
                $tmp[] = $value;
            }
        }
        return $tmp;
    }

    /**
     * 获取子分类(带有分隔符)
     * @param $data
     * @param $parent
     * @param $son
     * @param int $pid
     * @param int $lev
     * @return array
     */
    function getSubTreeTwo($data , $parent , $son , $pid = 0, $lev = 0) {
        $tmp = array();
        foreach ($data as $key => $value) {
            if($value[$parent] == $pid) {
                $value['lev'] = $lev;
                $value['cate_name'] = $lev ==1?'----'.$value['cate_name']:$value['cate_name'];
                $tmp[] = $value;
                $tmp = array_merge($tmp , $this->getsubTreeTwo($data , $parent , $son , $value[$son] , $lev+1));
            }
        }
        return $tmp;
    }

}
