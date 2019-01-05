<?php
namespace backend\controllers;
use yii\web\Controller;
use yii\caching\DbDependency;
use yii\caching\ExpressionDependency;
use yii\caching\FileDependency;
use yii\caching\TagDependency;
use yii\caching\ChainedDependency;
class CachingController extends Controller{
    public function actionIndex(){
        // db依赖
//        $cache=\Yii::$app->cache;
//        $dependency=new DbDependency(['sql'=>'select count(*) from web_test']);
//        $list=\Yii::$app->db->createCommand('select * from web_test')->queryAll();
//        $cache->add('testList',$list,3600,$dependency);

        // 表达式依赖
//        $cache=\Yii::$app->cache;
//        $dependency=new ExpressionDependency(['expression'=>'\Yii::$app->request->get("id")']);
//        $list=\Yii::$app->db->createCommand('select * from web_test')->queryAll();
//        $cache->set('testList',$list,3600,$dependency);

        // 文件依赖
//        $cache=\Yii::$app->cache;
//        $dependency=new FileDependency(['fileName'=>'@app/../dependency.txt']);
//        $list=\Yii::$app->db->createCommand('select * from web_test')->queryAll();
//        $cache->set('testList_file',$list,3600,$dependency);

        // 标签依赖
//        $cache=\Yii::$app->cache;
//        $dependency=new TagDependency(['tags'=>'aaa']);
//        $list=\Yii::$app->db->createCommand('select * from web_access')->queryAll();
//        $cache->set('testList_tags',$list,3600,$dependency);

        // 链式依赖
//        $cache=\Yii::$app->cache;
//        $dbDependency=new DbDependency(['sql'=>'select count(*) from web_test']);
//        $fileDependency=new FileDependency(['fileName'=>'@app/../dependency.txt']);
//        $dependency=new ChainedDependency(['dependOnAll'=>true,'dependencies'=>[$dbDependency,$fileDependency]]);
//        $list=\Yii::$app->db->createCommand('select * from web_test_info')->queryAll();
//        $cache->set('testList_chained',$list,3600,$dependency);
    }

    public function actionGetList(){
        $cache=\Yii::$app->cache;
        $list=$cache->get('newname');
        dump($list);
    }

    public function actionIndex2(){
        $cache=\Yii::$app->cache;
//        $data=$cache->get('aaa');
        $name='tojoki';
        $age=22;
        $data=$cache->getOrSet('aaa',function() use ($name,$age) {
            $info=$name.'/'.$age;
            return $info;
        });
        dump($data);
    }

    public function actionGetCookie(){
        $cookies=\Yii::$app->request->cookies;
        $name=$cookies->getValue('name','test');
        dump($name);
    }

    public function actionSetCookie(){
        $cookies=\Yii::$app->response->cookies;
//        $cookie=new \yii\web\Cookie([
//            'name'=>'name',
//            'value'=>'heyi0104'
//        ]);
//        $cookies->add($cookie);
        $cookies->remove('name');
    }
}