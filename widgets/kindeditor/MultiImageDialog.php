<?php

namespace app\widgets\kindeditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * KindEditor批量图片上传弹窗调用
 * @author DuanMu
 * @see http://kindeditor.net/ke4/examples/multi-image-dialog.html
 */
class MultiImageDialog extends InputWidget {

    public $name = '';
    public $value = '批量上传';
    public $returnJs = '';
    public $clientOptions = [];

    //开始执行
    public function run() {
        $this->registerClientScript();
        echo Html::button($this->value, $this->options);
    }

    //加载相关资源文件和注册JS脚本
    public function registerClientScript() {
        $view = $this->getView();
        $asset = KindEditorAsset::register($view);
        $this->initButtonOptions();
        $this->initClientOptions();
        $themeType = $this->clientOptions['themeType'];
        $langType = $this->clientOptions['langType'];
        //加载其他主题样式
        if ($themeType !== 'default') {
            $view->registerCssFile($asset->baseUrl . '/themes/' . $themeType . '/' . $themeType . '.css', ['depends' => '\app\widgets\kindeditor\KindEditorAsset']);
        }
        //加载语言包
        $view->registerJsFile($asset->baseUrl . '/lang/' . $langType . '.js', ['depends' => '\app\widgets\kindeditor\KindEditorAsset']);
        //初始化JS代码
        $js = "var editor = KindEditor.editor(" . Json::encode($this->clientOptions) . ");KindEditor('#" . $this->options['id'] . "').click(function(){editor.loadPlugin('multiimage',function(){editor.plugin.multiImageDialog({clickFn:" . $this->returnJs . "});});});";
        //加载JS代码
        $view->registerJs($js);
    }

    //初始化按钮参数
    public function initButtonOptions() {
        $this->options['id'] = $this->options['id'] . '-btn-multi';
        $this->options['class'] = isset($this->options['class']) ? $this->options['class'] : 'btn btn-success';
    }

    //初始化编辑器参数
    public function initClientOptions() {
        $params = [
            'themeType',
            'langType',
            'filePostName',
            'uploadJson',
        ];
        $options = [];
        $options['themeType'] = 'simple';
        $options['langType'] = 'zh_CN';
        $options['filePostName'] = 'fileData';
        $options['uploadJson'] = '';
        foreach ($params as $key) {
            if (isset($this->clientOptions[$key])) {
                $options[$key] = $this->clientOptions[$key];
            }
        }
        $this->clientOptions = $options;
    }

}
