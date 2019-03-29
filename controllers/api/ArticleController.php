<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 11:16
 */
namespace app\controllers\api;
use app\models\Articles;

class ArticleController extends BaseActiveController
{
    /**
     * 获取文章列表
     * @return array
     */
    public function actionGetList(){
        $article =new Articles();
        $articles=$article->getList();
        if ($articles) {
            return [
                'code' => 200,
                'message' => '成功',
                'data' =>$articles
            ];
        }
        return [
            'code' => 500,
            'message' => '暂无文章存在'
        ];
    }
}