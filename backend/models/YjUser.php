<?php
namespace backend\models;
use yii\db\ActiveRecord;
class YjUser extends ActiveRecord{
    public static function tableName(){
        return '{{%yj_user}}';
    }
}