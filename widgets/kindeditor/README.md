Yii2 Kindeditor
===========

视图中使用方法
-----

1.在视图中使用KindEditor编辑器 :
(其他参数参考http://http://kindeditor.net/docs/option.html)
```php
<?=
$form->field($model, 'body')->widget(\app\widgets\kindeditor\KindEditor::className(), [
    'clientOptions' => [
    'width' => '680px',
    'height' => '380px',
    'themeType' => 'default',
    'itemType' => 'full',
    'langType' => 'zh_CN',
    'autoHeightMode' => true,
    'filePostName' => 'fileData',
    'uploadJson' => Url::to(['upload']),
    ],
]);
?>
```

2.在视图中使用UploadButton
(其他参数参考http://kindeditor.net/ke4/examples/uploadbutton.html)
```php
<?=
$form->field($model, 'thumb')->widget(\app\widgets\kindeditor\UploadButton::className(), [
    'buttonLabel' => '选择文件',
    'clientOptions' => [
        'url' => Url::to(['upload', 'dir' => 'image']), //共有image，flash，media，file四种类型，每种类型支持的后缀名不一样
    ],
])
?>
```

3.在视图中使用多文件上传弹窗
```php
<?=
MultiImageDialog::widget([
    'clientOptions' => [
        'uploadJson' => Url::to(['upload', 'dir' => 'image']),
    ],
    'returnJs' => new JsExpression("
        function(urlList) {
            KindEditor.each(urlList, function(i, data) {
                var size = $('.table-list tbody tr').length + 1;
                var str = '<tr>';
                str += '<td><input type=\"text\" class=\"form-control\" name=\"listorder[]\" value=\"0\"></td>';
                str += '<td><input type=\"text\" class=\"form-control\" name=\"name[]\"></td>';
                str += '<td><input type=\"text\" class=\"form-control\" name=\"thumb[]\" value=\"'+data.url+'\"></td>';
                str += '<td><img src=\"'+data.url+'\" class=\"user-thumb\" onclick=\"preview(\''+data.url+'\')\"></td>';
                str += '<td><input type=\"text\" class=\"form-control\" name=\"introduce[]\"></td>';
                str += '<td><a href=\"javascript:;\" onclick=\"$(this).parent().parent().remove();\">删除</a></td>';
                str += '</tr>';
                $('.table-list tbody').append(str);
            });
            editor.hideDialog();
        }
    "),
])
?>
```

控制器中使用方法
-----

1.普通上传:
(使用环境:上传图片、文件等常规环境)
```php
return [
    'upload' => [
        'class' => 'app\widgets\kindeditor\UploadAction',
        'uploadPath' => '@webroot/uploads',//文件保存路径
        'uploadLog' => 0, //是否开始上传日志
    ],
];
```

2.上传自动产生缩略图：
(使用环境:上传新闻首页图片，只需要一张或多张小图)
```php
return [
    'upload' => [
        'class' => 'app\widgets\kindeditor\UploadAction',
        'thumbOptions'=>[
            'size'=>['100x100','120x90','300x300'],//可以生成多个尺寸 默认返回第一张缩略图地址
            'mode'=>0,//0为自动补白 1为裁剪
            'del'=>true,//是否删除原图 
        ]
    ],
];
```

3.上传自动加水印：
(使用环境:编辑器里上传图片自动加水印等)
```php
return [
    'upload' => [
        'class' => 'app\widgets\kindeditor\UploadAction',
        'waterOptions'=>[
            'file'=>'@webroot/images/mark.png', //水印图片地址 默认加在图片右下角
        ],
    ],
];
```