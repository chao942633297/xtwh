<?php
namespace Home\Model;
use Think\Model\RelationModel;
class ArticleRelationModel extends RelationModel{

	protected $tableName = 'Article';
    protected $_link = array(
        'user2'=>array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user1',
            'foreign_key' => 'u2id',
        ),
    );




}



