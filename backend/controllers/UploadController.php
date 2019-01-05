<?php
namespace backend\controllers;
use yii\web\Controller;
use yii\web\UploadedFile;
class UploadController extends Controller{
    private $type_arr=['images','video','app','files'];
    private $maxsize_arr=['images'=>'3145728','video'=>'100000000','app'=>'100000000','files'=>'3145728'];
    private $exts_arr=['images'=>['jpg', 'gif', 'png', 'jpeg','bmp'],
                        'video'=>['mp4','avi','rmvb','flv','wmv','mkv','mov'],
                        'app'=>['apk'],'files'=>['xls','xlsx','doc','docx','txt']
    ];

    public function uploadFiles($name,$dir_type,$multiple=false){
        $dirname='/uploads/'.$dir_type.'/'.date('Ymd');//本次上传的路径
        $full_name=\Yii::getAlias("@webroot").$dirname;//拼上项目的路径
        if(!is_dir($full_name)){
            mkdir($full_name,0777,true);
        }
        $res=['status'=>0,'message'=>'上传成功','files'=>[]];//定义一个上传的返回数组
        if($multiple){
            $files=UploadedFile::getInstancesByName($name);
            foreach($files as $file){
                // 发现错误 直接返回
                if($file->hasError){
                    $res=['status'=>1,'message'=>$file->error,'files'=>[]];
                    break;
                }
                // 检查文件大小
                if(!$file->size || $file->size > $this->maxsize_arr[$dir_type]){
                    $res=['status'=>1,'message'=>'文件大小有误','files'=>[]];
                    break;
                }
                // 检查文件格式
                if(!in_array($dir_type,$this->type_arr)){
                    $res=['status'=>1,'message'=>'文件格式有误','files'=>[]];
                    break;
                }
                // 检查文件后缀
                if(!in_array($file->getExtension(), $this->exts_arr[$dir_type])){
                    $res=['status'=>1,'message'=>'文件后缀不正确','files'=>[]];
                    break;
                }

                $filename = '/'.time().rand(1000,9999).".".$file->getExtension();//文件名
                if($file->saveAs($full_name.$filename) === true){
                    $res['files'][]=$dirname.$filename;//只保存相对路径即可 且只改files这个字段 因为不出错就使用默认的了
                }else{
                    $res=['status'=>1,'message'=>'上传出错','files'=>[]];
                    break;//有错误发生 终止循环
                }
            }
        }else{
            $file=UploadedFile::getInstanceByName($name);
            // 发现错误 直接返回
            if($file->hasError){
                $res=['status'=>1,'message'=>$file->error,'files'=>[]];
            }
            // 检查文件大小
            if(!$file->size || $file->size > $this->maxsize_arr[$dir_type]){
                $res=['status'=>1,'message'=>'文件大小有误','files'=>[]];
            }
            // 检查文件格式
            if(!in_array($dir_type,$this->type_arr)){
                $res=['status'=>1,'message'=>'文件格式有误','files'=>[]];
            }
            // 检查文件后缀
            if(!in_array($file->getExtension(), $this->exts_arr[$dir_type])){
                $res=['status'=>1,'message'=>'文件后缀不正确','files'=>[]];
            }

            $filename = '/'.time().rand(1000,9999).".".$file->getExtension();//文件名
            if($file->saveAs($full_name.$filename) === true){
                $res['files'][]=$dirname.$filename;//只保存相对路径即可 且只改files这个字段 因为不出错就使用默认的了
            }else{
                $res=['status'=>1,'message'=>'上传出错','files'=>[]];
            }
        }
        // 防止一个都没传
        if($res['status']==0 && !count($res['files'])){
            $res=['status'=>1,'message'=>'请上传文件','files'=>[]];
        }
        return $res;
    }

}