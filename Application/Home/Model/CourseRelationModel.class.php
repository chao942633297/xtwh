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
            'mapping_fileds'=>'title',
            'as_fields'=>'title',
        ),
        'category'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'category',
            'foreign_key'=>'categoryid',
            'mapping_fileds'=>'name',
            'as_fields'=>'name',
        ),
    );











}






?>