<?php
namespace Home\Model;
use Think\Model\RelationModel;
class OrderRelationModel extends RelationModel{

    protected $tableName = 'Order';
    protected $_link = array(
        'user2'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'user2',
            'foreign_key'=>'u2id',
        ),
        'course'=>array(
          'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'course',
            'foreign_key'=>'courseid',
        ),
    );


}



?>