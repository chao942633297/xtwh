<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

class PublicModel extends Model
{
    public function moreTablePage($model, $date, $where)//公共多表用table的联查分页
    {
        $tot = $model->table($date['table'])->where($date['where'])->where($where)->count();
        return $this->commonPageConfig($tot);
    }

    protected function commonPageConfig($tot)
    {
        $page = new Page($tot, 8);
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '末页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        return $page;
    }
}

?>