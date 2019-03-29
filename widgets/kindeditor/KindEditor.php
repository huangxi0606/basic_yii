<?php

namespace app\widgets\kindeditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * KindEditor编辑器组件
 * @author DuanMu
 * @see http://kindeditor.net/ke4/examples/default.html
 */
class KindEditor extends InputWidget {

    public $clientOptions = [];

    //开始执行
    public function run() {
        //注册脚本
        $this->registerClientScript();
        //输出文本框
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
    }

    //加载相关资源文件和注册JS脚本
    public function registerClientScript() {
        $view = $this->getView();
        $asset = KindEditorAsset::register($view);
        $this->initClientOptions();
        $themeType = $this->clientOptions['themeType'];
        $langType = $this->clientOptions['langType'];
        $id = $this->options['id'];
        $varName = str_replace('-', '_', $id) . '_editor';
        //加载其他主题样式
        if ($themeType !== 'default') {
            $view->registerCssFile($asset->baseUrl . '/themes/' . $themeType . '/' . $themeType . '.css', ['depends' => '\app\widgets\kindeditor\KindEditorAsset']);
        }
        //加载语言包
        $view->registerJsFile($asset->baseUrl . '/lang/' . $langType . '.js', ['depends' => '\app\widgets\kindeditor\KindEditorAsset']);
        //初始化JS代码
        $js = "var {$varName} = KindEditor.create('#{$id}', " . Json::encode($this->clientOptions) . ");";
        //加载JS代码
        $view->registerJs($js);
    }

    //初始化编辑器参数
    public function initClientOptions() {
        $params = [
            'width',
            'height',
            'minWidth',
            'minHeight',
            'items',
            'itemType',
            'noDisableItems',
            'filterMode',
            'htmlTags',
            'wellFormatMode',
            'resizeType',
            'themeType',
            'langType',
            'designMode',
            'fullscreenMode',
            'basePath',
            'themesPath',
            'pluginsPath',
            'langPath',
            'minChangeSize',
            'urlType',
            'newlineTag',
            'pasteType',
            'dialogAlignType',
            'shadowMode',
            'zIndex',
            'useContextmenu',
            'syncType',
            'indentChar',
            'cssPath',
            'cssData',
            'bodyClass',
            'colorTable',
            'afterCreate',
            'afterChange',
            'afterTab',
            'afterFocus',
            'afterBlur',
            'afterUpload',
            'uploadJson',
            'fileManagerJson',
            'allowPreviewEmoticons',
            'allowImageUpload',
            'allowFlashUpload',
            'allowMediaUpload',
            'allowFileUpload',
            'allowFileManager',
            'fontSizeTable',
            'imageTabIndex',
            'formatUploadUrl',
            'fullscreenShortcut',
            'extraFileUploadParams',
            'filePostName',
            'fillDescAfterUploadImage',
            'afterSelectFile',
            'pagebreakHtml',
            'allowImageRemote',
            'autoHeightMode',
        ];
        $options = [];
        $options['width'] = '100%';
        $options['height'] = '350px';
        $options['itemType'] = 'full';
        $options['themeType'] = 'default';
        $options['langType'] = 'zh_CN';
        $options['resizeType'] = 1;
        $options['afterChange'] = new JsExpression("function(){this.sync();}");
        //合并参数
        foreach ($params as $key) {
            if (isset($this->clientOptions[$key])) {
                $options[$key] = $this->clientOptions[$key];
            }
        }
        //简单工具条
        if ($options['itemType'] == 'simple') {
            $options['resizeType'] = 1;
            $options['allowPreviewEmoticons'] = false;
            $options['allowImageUpload'] = true;
            $options['items'] = [
                'source', 'fontname', 'fontsize', '|',
                'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'removeformat', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
                'emoticons', 'image', 'link',
            ];
        }
        //自动调整高度
        if (isset($options['autoHeightMode']) && $options['autoHeightMode']) {
            $options['afterCreate'] = new JsExpression("function(){this.loadPlugin('autoheight');}");
        }
        unset($options['itemType']);
        $this->clientOptions = $options;
    }

}
