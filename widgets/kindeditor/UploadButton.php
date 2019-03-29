<?php

namespace app\widgets\kindeditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\web\JsExpression;

/**
 * KindEditor上传按钮调用
 * @author DuanMu
 * @see http://kindeditor.net/ke4/examples/uploadbutton.html
 */
class UploadButton extends InputWidget {


    public $clientOptions = [];
    public $buttonLabel = '';
    public $buttonOptions = [];
    public $previewLabel = '';
    public $previewOptions = [];
    public $template = '<div class="input-group">{input}<span class="input-group-btn">{button}{preview}</span></div>';

    //开始执行
    public function run() {
        var_dump('66');
        //注册脚本
        $this->registerClientScript();
        //输出文本框和按钮
        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::textInput($this->name, $this->value, $this->options);
        }
        $button = Html::buttonInput($this->buttonLabel, $this->buttonOptions);
        $preview = Html::button($this->previewLabel, $this->previewOptions);
        echo strtr($this->template, [
            '{input}' => $input,
            '{button}' => $button,
            '{preview}' => $preview,
        ]);
    }

    //加载相关资源文件和注册JS脚本
    public function registerClientScript() {
        $view = $this->getView();
        $asset = KindEditorAsset::register($view);
        $this->initButtonOptions();
        $this->initClientOptions();
        $varName = str_replace('-', '_', $this->options['id']) . '_upbtn';
        //加载扩展样式
        $view->registerCssFile($asset->baseUrl . '/themes/extend/button.css', ['depends' => '\app\widgets\kindeditor\KindEditorAsset']);
        //初始化JS代码
        $js = "var {$varName} = KindEditor.uploadbutton(" . Json::encode($this->clientOptions) . ");{$varName}.fileBox.change(function(e){{$varName}.submit();u=dialog({content: '<div class=\"upload-loading\"><span class=\"ui-dialog-loading\">Loading..</span>正在上传...</div>'}).show();});";
        //加载JS代码
        $view->registerJs($js);
    }

    //初始化文本框和按钮
    public function initButtonOptions() {
        $this->options['class'] = isset($this->options['class']) ? $this->options['class'] : 'form-control';
        $this->buttonLabel = $this->buttonLabel ? $this->buttonLabel : '上传';
        $this->buttonOptions['id'] = $this->options['id'] . '-upbtn';
        $this->buttonOptions['style'] = 'display:none;';
        $this->previewLabel = $this->previewLabel ? $this->previewLabel : '<i class="fa fa-eye"></i> 预览';
        $this->previewOptions = $this->previewOptions ? $this->previewOptions : ['class' => 'btn btn-default', 'onclick' => 'preview($(\'#' . $this->options['id'] . '\').val())'];
    }

    //初始上传参数
    public function initClientOptions() {
        $params = [
            'button',
            'fieldName',
            'url',
            'afterUpload',
            'afterError',
            'extraParams',
        ];
        $options = [];
        $options['button'] = new JsExpression("KindEditor('#" . $this->buttonOptions['id'] . "')[0]");
        $options['fieldName'] = 'fileData';
        $options['url'] = '';
        $options['afterUpload'] = new JsExpression("function(data){if(data.error === 0){var url = KindEditor.formatUrl(data.url, 'absolute');KindEditor('#" . $this->options['id'] . "').val(url);}else{alert(data.message);}u.close().remove();}");
        $options['afterError'] = new JsExpression("function(str){alert(str);u.close().remove();}");
        //合并参数
        foreach ($params as $key) {
            if (isset($this->clientOptions[$key])) {
                $options[$key] = $this->clientOptions[$key];
            }
        }
        $this->clientOptions = $options;
    }

}
