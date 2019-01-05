<?php
namespace backend\modules\admin\controllers;
use yii\web\Controller;
class IndexController extends Controller{
    public function actionIndex(){
        echo 'this is the index action of admin controller';
    }

    public function actionIndex2(){
        echo 'this is the index2 action of admin controller';
    }
}