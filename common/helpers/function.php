<?php
error_reporting(E_ERROR|E_PARSE);
// 浏览器友好的变量输出
if (!function_exists('dump')) {
    function dump($var){
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die;
    }
}
/*
 * 返回json数据
 * $data    格式为数组/字符串/数字类型的的数据
 * $msg     提示信息 默认success
 * $status  0成功 1失败
 * $code    状态码 默认200
 */
if(!function_exists('response')){
    function response($data=NULL,$msg='success',$status=0,$code=200){
        exit(json_encode(['status'=>$status,'code'=>$code,'time'=>time(),'msg'=>$msg,'data'=>$data]));
    }
}
/*
 * 返回成功提示
 * $msg     提示信息
 */
if(!function_exists('succ_back')){
    function succ_back($msg=''){
        response(NULL,$msg);
    }
}
/*
 * 返回错误提示
 * $msg     提示信息
 * $code    状态码
 * $status  0成功 1失败
 */
if(!function_exists('error_back')){
    function error_back($msg,$code,$status=1){
        response(NULL, $msg, $status,$code);
    }
}


