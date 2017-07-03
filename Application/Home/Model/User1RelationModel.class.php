<?php
namespace Home\Model;
use Think\Model\RelationModel;
class User1RelationModel extends RelationModel{

    protected $tableName = 'User1';
    protected $_link = array(
        'course'=>array(
            'mapping_type'=>self::HAS_MANY,
            'class_name'=>'course',
            'foreign_key'=>'user_id',
        ),
        'user1'=>array(
            'mapping_type'=>self::HAS_MANY,
            'class_name'=>'user1',
            'foreign_key'=>'pid',
        ),
        // 'user2'=>array(
        //     'mapping_type'=>self::HAS_MANY,
        //     'class_name'=>'user2',
        //     'foreign_key'=>'u1id',
        // ),
    );















}




?>