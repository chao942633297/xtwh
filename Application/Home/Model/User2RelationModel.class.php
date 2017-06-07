<?php
namespace Home\Model;
use Think\Model\RelationModel;
class User2RelationModel extends RelationModel{
    protected $tableName = 'User2';
    protected $_link = array(
        'user1'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'user1',
            'foreign_key'=>'u1id',

        ),
    );

}






?>