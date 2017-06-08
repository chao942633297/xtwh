<?php
namespace Home\Model;

use Think\Model;

class BackmoneyModel extends Model
{
    public function getFoundCash()//得到总基金
    {
        $date=M('backmoney')->field('money')->where(['u2id'=>session('home_user_id')])->select();//session('home_user_id')
        $totalCash=0;
        foreach($date as $v){
            $totalCash+=$v['money'];
        }
        return $totalCash;
    }
}

?>