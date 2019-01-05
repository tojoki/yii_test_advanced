<?php
namespace backend\controllers;
use yii\caching\MemCache;
use yii\web\Controller;
use backend\models\YjUser;
use yii\web\UploadedFile;
use backend\models\Test;
use yii\captcha\CaptchaValidator;
header("Content-type:text/html;charset=utf-8");
class TestController extends Controller{
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                //'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor' => 0x000000,//背景颜色
                'maxLength' => 6, //最大显示个数
                'minLength' => 5,//最少显示个数
                'padding' => 5,//间距
                'height' => 40,//高度
                'width' => 130,  //宽度
                'foreColor' => 0xffffff,     //字体颜色
                'offset' => 4,        //设置字符偏移量 有效果
            ],
        ];
    }

    public function actionIndex(){
        $redis=new \Redis();
        $redis->connect('localhost',6379);
        $redis->auth('momo107');
//        $redis->delete('userList');
        $list=$redis->lGetRange('userList',0,-1);
        if(!$list){
            $list=YjUser::find()->limit(10)->asArray()->all();
            foreach($list as $v){
                $redis->rpush('userList',serialize($v));
            }
            echo 'from database<br>';
        }else{
            foreach($list as &$v){
                $v=unserialize($v);
            }
            echo 'from redis<br>';
        }
        dump($list);
    }

    public function actionUpload(){
        if(\Yii::$app->request->isPost){
//            $this->uploadFiles('pic','pic');
//            $file=UploadedFile::getInstanceByName('pic');
////            dump($file);
//            $dirname=\Yii::getAlias("@webroot").'/uploads/'.date('Ymd');
//            if(!is_dir($dirname)){
//                mkdir($dirname,0777,true);
//            }
//            $filename = $dirname.'/'.time().rand(1000,9999).".".$file->getExtension();
//            if($file->saveAs($filename) === true){
//                succ_back('操作成功');
//            }else{
////                dump($file->error);
//                error_back('操作失敗',404);
//            }
//            $result=$this->uploadFiles('pic','pic',true);
//            dump($result);
            $controller=new UploadController();
            $result=$controller->uploadFiles('file','files',true);
            dump($result);
        }
    }

    protected function uploadFiles($name,$type,$multiple=false){
        $dirname='/uploads/'.$type.'/'.date('Ymd');//本次上传的路径
        $full_name=\Yii::getAlias("@webroot").$dirname;//拼上项目的路径
        if(!is_dir($full_name)){
            mkdir($full_name,0777,true);
        }
        $array=[];
        $error=false;
        if($multiple){
            $files=UploadedFile::getInstancesByName($name);
            foreach($files as $file){
                $filename = '/'.time().rand(1000,9999).".".$file->getExtension();//文件名
                if($file->saveAs($full_name.$filename) === true){
                    $array[]=$dirname.$filename;//只保存相对路径即可
                }else{
                    $error=true;
                    break;//有错误发生 终止循环
                }
            }
        }else{
            $file=UploadedFile::getInstanceByName($name);
            $filename = '/'.time().rand(1000,9999).".".$file->getExtension();//文件名
            if($file->saveAs($full_name.$filename) === true){
                $array[]=$dirname.$filename;//只保存相对路径即可
            }else{
                $error=true;
            }
        }
        if(!$error && count($array)){
            return $array;
        }
    }

    public function actionIndex2(){
//        $redis=\Yii::$app->redis;
//        $name=$redis->get('name');
//        if($name){
//             $from= '/from redis';
//        }else{
//            $name='pile816';
//            $redis->set('name',$name);
//            $from='/from action';
//        }
//        echo $name.$from;
        $cache=\Yii::$app->cache;
        if($cache->exists('name_cache')){
            echo $cache->get('name_cache').'/from cache';
        }else{
            $name='pile816';
            $cache->set('name_cache',$name,10);
            echo $name.'/from action';
        }

    }

    public function actionIndex3(){
        $mem=new \MemCache();
        $mem->connect('localhost',11211);
        $name=$mem->get('name');
        if(!$name){
            $name='123123';
            $mem->set('name',$name);
            $from='/from action';
        }else{
            $from='/from memcache';
        }
        echo $name.$from;

    }

    public function actionApiPage(){
        return $this->render('index',['token'=>self::TOKEN]);
    }

    public function actionSafeApi(){
//        $params=\Yii::$app->request->get();
        $params=$this->check_require('time,randomStr,signature');
        //时间戳
        $timeStamp = $params['time'];
        //随机数
        $randomStr = $params['randomStr'];
        //生成签名
        $signature = $this -> arithmetic($timeStamp,$randomStr);
        //判断生成的签名和前台传递的签名是否一致
        if($signature==$params['signature']){
            echo '验证成功';
        }else{
            echo '验证失败';
        }

    }
    const TOKEN='tojoki';
    //获取签名
    private function arithmetic($timeStamp,$randomStr){
        $arr=['timeStamp'=>$timeStamp,'randomStr'=>$randomStr,'token'=>self::TOKEN];
        //按照首字母大小写顺序排序
        sort($arr,SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密 再转换成大写
        $signature = strtoupper(md5(sha1($str)));
        return $signature;
    }

    protected function check_require($keys){
        $request=\Yii::$app->request;
        $keyarr = explode(",",$keys);
        $data = array();
        foreach($keyarr as $key){
            $value = $request->post($key)!=''?$request->post($key):$request->get($key);
            if($value==''){
//                 err('缺少必填项:'.$key);
                die('缺少必填项:'.$key);
            }
            $data[$key] = $value;
        }
        return $data;
    }

    public function actionIndex4(){
//        $email=\Yii::$app->mailer->compose();
//        $email->setTo('tojoki@sina.com');
//        $email->setSubject('测试邮件');
//        $email->setHtmlBody('测试数据请勿回复');
//        $email->setFrom('543313797@qq.com');
//        echo $email->send();
        if(\Yii::$app->request->isPost){
            $code=\Yii::$app->request->post('code');
            if(!$code){
                die('请输入验证码');
            }
//            $status=$this->createAction('captcha')->validate( $code, false);

            $captcha = new CaptchaValidator();
            $captcha->captchaAction='test/captcha';
            $status=$captcha->validate($code);
            dump($status);
        }
        return $this->render('index4');
    }

    public function actionForgetPass(){
        $param=\Yii::$app->request->get();
        $id=$param['id']?$param['id']:die('请输入用户id');
        $userInfo=Test::findOne($id);
        if(!$userInfo){
            die('账号出错 请核对后再试');
        }
        $code=uniqid('mail').time();
        $session=\Yii::$app->session;
        $session->set($code,$userInfo['id']);
        $email=\Yii::$app->mailer->compose()
            ->setTo('543313797@qq.com')
            ->setSubject('reset_password')
            ->setHtmlBody('<a href="http://tojoki.yab.com/test/reset-pass?code='.$code.'" >click here to reset your password</a>')
            ->setFrom('543313797@qq.com')
            ->send();
        if($email){
            die('ok');
        }else{
            die('not ok');
        }
    }

    public function actionResetPass(){
        $param=\Yii::$app->request->get();
        $session=\Yii::$app->session;
        $id=$session->get($param['code']);
        if(!$id){
            die('验证失效 请重新发送邮件');
        }
        $userInfo=Test::findOne($id);
        if(!$userInfo){
            die('找不到要重设密码的用户');
        }
        $userInfo->password='123456';
        $reset=$userInfo->save();
        if($reset){
            \Yii::$app->session->remove($param['code']);
            die('密码已重设为123456 请尽快修改');
        }else{
            die('操作失败 请重试');
        }
    }

    public function actionIndex5(){
        $abc=\Yii::$app->db->createCommand()->update('web_test',['password'=>'momo107'],'id=:id',[':id'=>1])->execute();
        var_dump($abc);

    }

}












