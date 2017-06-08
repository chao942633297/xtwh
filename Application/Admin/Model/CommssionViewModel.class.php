<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CommssionViewModel extends ViewModel{
    public $viewFields = array(
        'user2'=>array('id','name','nickname','phone','headimg','_as'=>'a','_type'=>'LEFT'),
        'backmoney'=>array('sum(money)'=>'allmoney','_as'=>'b','_on'=>'a.id=b.u2id'),
    );
}