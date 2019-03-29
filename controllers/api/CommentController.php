<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 11:16
 */
namespace app\controllers\api;
use app\models\Articles;
use app\models\Cates;
use app\models\Comments;


class CommentController extends BaseActiveController
{
    /**
     * 提交评论
     * @return array
     */
    public function actionSaveComment(){
        $data['code'] =500;
        $comment =array_filter(\Yii::$app->request->post());
        if(!isset($comment['article_id'])){
            $data['message'] ='文章id不能为空';
            return $data;
        }

        if(!isset($comment['content'])){
            $data['message'] ='评论内容不能为空';
            return $data;
        }
        $comm =new Comments();
        try {
            $comm->article_id =$comment['article_id'];
            $comm->content =$comment['content'];
            $comm->parent_id = isset($comment['parent_id'])? $comment['parent_id']:0;
            $comm->user_id =\Yii::$app->user->id;
            $comm->save();
        } catch (\Exception $e) {
            \Yii::info($e);
            $data['message'] ='错误';
            return $data;
        }
        return [
            'code' => 200,
            'message' => '成功',
            'data' =>$comment
        ];

    }

    /**
     * 获取评论列表
     * @param $id
     * @return array
     */

    public function actionGetLists($id){
        $comment =new Comments();
        $comments = $comment->getList($id);
        if ($comments) {
            return [
                'code' => 200,
                'message' => '成功',
                'data' =>$comments
            ];
        }
        return [
            'code' => 500,
            'message' => '暂无评论存在'
        ];
    }
}