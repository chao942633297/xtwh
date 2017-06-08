<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class OrganizationViewModel extends ViewModel{
    public $viewFields = array(
        'order'=>array('status','message','paytype','courseid','addressid','goodprice','goodid','_as'=>'a','_type'=>'LEFT'),
        'backmoney'=>array('sum(money)'=>'allmoney','_as'=>'b','_on'=>'a.id=b.u2id'),
    );
}