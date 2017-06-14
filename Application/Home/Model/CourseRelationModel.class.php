<?php
namespace Home\Model;
use Think\Model\RelationModel;
class CourseRelationModel extends RelationModel{

    protected $tableName = 'Course';
    protected $_link = array(
        'user1'=>array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user1',
            'foreign_key' => 'user_id',
        ),
    );











}






?>