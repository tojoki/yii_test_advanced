<?php

return [
    'class' => 'yii\db\Connection',
     'dsn' => 'mysql:host=47.92.119.129;dbname=db_yjsh',
     'username' => 'yjsh',
     'password' => 'JDpva82TNYLKJh6r',
     'charset' => 'utf8',
     'tablePrefix'=>'web_',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',

//    'queryBuilder' => [
//        'expressionBuilders' => [
//            'app\db\conditions\AllNotNullCondition' => 'app\db\conditions\AllNotNullConditionBuilder',
//            'app\db\conditions\AllGreaterCondition' => 'app\db\conditions\AllGreaterConditionBuilder',
//        ],
//        'conditionClasses' => [
//            'ALL>' => 'app\db\conditions\AllGreaterCondition',
//            'ALL NOT NULL'=>'app\db\conditions\AllNotNullCondition'
//        ],
//    ],
];
