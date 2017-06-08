<?php
namespace Home\Model;

use Think\Model;

class GetCashModel extends Model
{
    public function getFoundCash()//得到总基金
    {
        $date=M('backmoney')->field('money')->where(['id'=>1])->select();//session('home_user_id')
        $totalCash=0;
        foreach($date as $v){
            $totalCash+=$v['money'];
        }
        var_dump($totalCash);
    }
}

?>