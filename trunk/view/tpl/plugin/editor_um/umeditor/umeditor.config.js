(function() {
    window.UMEDITOR_CONFIG = {
        UMEDITOR_HOME_URL : window.UMEDITOR_HOME_URL

        //图片上传配置区
        ,imageUrl:window.admurl + "/index.php?u=attach-up_image&ajax=1"      //图片上传提交地址
        ,imagePath:window.weburl + "/"                                       //图片修正地址，引用了fixedImagePath,如有特殊需求，可自行配置
        ,imageFieldName:"upfile"                                             //图片数据的key,若此处修改，需要在后台对应文件修改对应参数

        //工具栏上的所有的功能按钮和下拉框，可以在new编辑器的实例时选择自己需要的从新定义
        ,toolbar:[
            'source | undo redo | bold italic underline strikethrough | superscript subscript',
            '| forecolor backcolor | justifyleft justifycenter justifyright | link unlink | removeformat',
            '| insertorderedlist insertunorderedlist | fontfamily fontsize paragraph' ,
            '| image insertvideo  | map',
            '| horizontal print preview fullscreen'
        ]

        ,initialFrameWidth:600
        ,initialFrameHeight:200
    };
})();
