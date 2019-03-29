<?php

namespace app\widgets\kindeditor;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\ManipulatorInterface;
use app\models\Upload;

/**
 * KindEditor上传操作
 * @author DuanMu
 */
class UploadAction extends Action {

    public $uploadPath = '/uploads';
    public $maxSize = 4096000;
    public $uploadType = 'image';
    public $allowExt = [
        'image' => ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'svg'],
        'flash' => ['swf', 'flv'],
        'media' => ['swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4'],
        'file' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'],
        'csv' => ['csv'],
    ];
    public $thumbOptions = [];
    public $waterOptions = [];
    public $uploadLog = 0;

    //初始化
    public function init() {
        parent::init();
        //关闭Csrf校验以解决swf上传丢失cookie的问题
        Yii::$app->request->enableCsrfValidation = false;
    }

    //开始执行
    public function run() {
        $dir = Yii::$app->request->get('dir');
        if ($dir) {
            $this->uploadType = $dir;
        }
        $upload = UploadedFile::getInstanceByName('fileData');
        if (!is_object($upload)) {
            $this->halt('请选择文件');
        }
        if ($upload->error) {
            switch ($upload->error) {
                case '1':
                    $this->halt('超过php.ini允许的大小');
                    break;
                case '2':
                    $this->halt('超过表单允许的大小');
                    break;
                case '3':
                    $this->halt('图片只有部分被上传');
                    break;
                case '4':
                    $this->halt('请选择文件');
                    break;
                case '6':
                    $this->halt('找不到临时目录');
                    break;
                case '7':
                    $this->halt('写文件到硬盘出错');
                    break;
                case '8':
                    $this->halt('文件上传扩展停止');
                    break;
                default:
                    $this->halt('未知错误');
            }
        }
        if (!$upload->size) {
            $this->halt('文件大小为0');
        }
        if ($upload->size > $this->maxSize) {
            $this->halt('文件大小超出系统限制');
        }
        if (in_array($upload->getExtension(), $this->allowExt[$this->uploadType]) === false) {
            $this->halt("该类型的文件不允许上传！\n只允许" . implode(',', $this->allowExt[$this->uploadType]) . "格式。");
        }
        //设置路径名字上传
        $savePath = Yii::getAlias('@webroot') . $this->uploadPath . '/' . date('Ymd');
        $saveName = date('Ymdhis') . mt_rand(10000, 99999);
        $saveExt = $upload->getExtension();
        $saveSize = $upload->size;
        $saveFile = $savePath . '/' . $saveName . '.' . $saveExt;
        if (!is_dir($savePath)) {
            FileHelper::createDirectory($savePath);
        }
        $upload->saveAs($saveFile);
        $saveUrl = str_replace(Yii::getAlias('@webroot'), '', $saveFile);
        //创建缩略图
        if (in_array($saveExt, ['gif', 'jpg', 'jpeg', 'png']) !== false) {
            //缩略图
            if ($this->thumbOptions && isset($this->thumbOptions['size']) && $this->thumbOptions['size']) {
                $mode = isset($this->thumbOptions['mode']) ? $this->thumbOptions['mode'] : 1;
                $mode = $mode == 0 ? ManipulatorInterface::THUMBNAIL_INSET : ManipulatorInterface::THUMBNAIL_OUTBOUND;
                foreach ($this->thumbOptions['size'] as $k => $v) {
                    if (strpos($v, 'x') !== false) {
                        $exp = explode('x', $v);
                        $width = intval($exp[0]);
                        $height = intval($exp[1]);
                        $thumbFile = $savePath . '/' . $saveName . '-thumb-' . $v . '.' . $saveExt;
                        Image::thumbnail($saveFile, $width, $height, $mode)->save($thumbFile);
                        if ($k == 0) {
                            $saveName = $saveName . '-thumb-' . $v;
                            $saveUrl = str_replace(Yii::getAlias('@webroot'), '', $thumbFile);
                        }
                    }
                }
                if (isset($this->thumbOptions['del']) && $this->thumbOptions['del']) {
                    @unlink($saveFile);
                }
            }
            //加水印
            if ($this->waterOptions && isset($this->waterOptions['file']) && $this->waterOptions['file']) {
                $waterFile = Yii::getAlias($this->waterOptions['file']);
                if (is_file($waterFile)) {
                    $saveInfo = getimagesize($saveFile);
                    $waterInfo = getimagesize($waterFile);
                    $x = $saveInfo[0] - $waterInfo[0] - 10;
                    $y = $saveInfo[1] - $waterInfo[1] - 10;
                    if ($x > 0 && $y > 0) {
                        Image::watermark($saveFile, $waterFile, [$x, $y])->save($saveFile);
                    }
                }
            }
        }
        //保存日志
        $this->log($saveName . '.' . $saveExt, $saveUrl, $saveSize, $saveExt);
        //保存SESSION
        Yii::$app->session->open();
        $_SESSION['uploads'][] = $saveUrl;
        //输出文件地址
        header('Content-type: text/html; charset=UTF-8');
        $data = ['error' => 0, 'url' => $saveUrl];
        echo Json::encode($data);
        Yii::$app->end();
    }

    //输出错误信息
    public function halt($message) {
        header('Content-type: text/html; charset=UTF-8');
        $data = ['error' => 1, 'message' => $message];
        echo Json::encode($data);
        Yii::$app->end();
    }

    //检测是否图片
    public function isImage($file) {
        return preg_match("/^(jpg|gif|jpeg|png|svg)$/i", $file);
    }

    //保存上传日志
    public function log($name, $url, $size, $ext) {
        if ($this->uploadLog) {
            $model = new Upload();
            $model->uid = intval(Yii::$app->user->id);
            $model->name = $name;
            $model->url = $url;
            $model->size = $size;
            $model->ext = $ext;
            $model->is_image = $this->isImage($ext);
            $model->save();
        }
    }

}
