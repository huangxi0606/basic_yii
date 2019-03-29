<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id 评论表id
 * @property int $user_id 评论用户id
 * @property int $parent_id 评论评论人id
 * @property int $article_id 评论文章id
 * @property string $content 评论内容
 * @property string $created_at 评论发表时间
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'article_id'], 'integer'],
            [['content'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'article_id' => 'Article ID',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
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
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 树
     * @param $data
     * @param $parent string 父级元素的名称 如 parent_id
     * @param $son    string 子级元素的名称 如 id
     * @param int $pid   父级元素的id 实际上传递元素的主键
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
     * 获取评论列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function GetList($id){
        $data= Comments::find()->where(['article_id'=>$id])->select('parent_id,user_id,id,content')->asArray()->all();
        return $this->getSubTree($data,'parent_id','id');

    }



}
