<?php
use yii\captcha\Captcha;
use yii\helpers\Url;

echo Captcha::widget([
    'name'=>'captchaimg',
    'captchaAction'=>'test/captcha',
    'imageOptions'=>[
        'id'=>'captchaimg',
        'title'=>'换一个',
        'alt'=>'换一个',
        'style'=>'cursor:pointer;margin-left:25px;'
    ],
    'template'=>'{image}'
]);
?>
<input name="code" type="text">
<input type="button" value="ok" class="sub">
<script src="/js/jquery.min.js"></script>
<script>
    $('.sub').on('click',function(){
        var code=$('input[name=code]').val();
        if(!code){
            alert('请输入验证码');
            return false;
        }
        $.ajax({
            url:"<?=Url::toRoute(['index4'])?>",
            data:{code:code},
            type:'post',
            success:function(res){
                console.log(res);
            },
            dataType:'json'
        })
    })
</script>