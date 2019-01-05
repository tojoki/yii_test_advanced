<?php
    use yii\helpers\Url;
?>
<input name="time" type="text" value="" placeholder="时间戳" ><input value="生成" type="button" class="time"><br>
<input name="randomStr" type="text" value="" placeholder="随机数"><input value="生成" type="button" class="randomStr"><br>
<input name="token" type="hidden" value="<?=$token?>">
<input value="ok" type="button" class="sub">
<script type='text/javascript' src="/js/jquery.min.js"></script>
<script type='text/javascript' src="/js/jquery.sha1.md5.js"></script>
<script type='text/javascript'>
    var md5=$.md5;
    var sha1=$.sha1;
    // 点击获取时间戳
    $('input[class=time]').on('click',function () {
        var timestamp = Date.parse(new Date())/1000;
        $('input[name=time]').val(timestamp);
    })
    // 点击获取随机数
    $('input[class=randomStr]').on('click',function () {
        var randomStr = Math.floor(Math.random()*90000)+10000;
        $('input[name=randomStr]').val(randomStr);
    })
    //点击提交
    $('input[class=sub]').on('click',function(){
        var time=$('input[name=time]').val();
        if(!time){
            alert('请输入时间戳');
            return false;
        }
        var randomStr=$('input[name=randomStr]').val();
        if(!randomStr){
            alert('请输入随机数');
            return false;
        }
        var token=$('input[name=token]').val();
        if(!token){
            alert('token未定义');
            return false;
        }
        var arr=[String(time),String(randomStr),String(token)];
        arr.sort();
        arr=arr.join('');
        var signature=md5(sha1(arr)).toUpperCase();
        // console.log(signature);
        $.ajax({
            url:"<?=Url::toRoute(['safe-api'])?>",
            data:{time:time,randomStr:randomStr,signature:signature},
            type:'post',
            success:function(res){
                console.log(res);
            },
            dataType:'json'
        })
    });
</script>