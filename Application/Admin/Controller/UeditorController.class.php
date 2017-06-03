<?php
namespace Admin\Controller;
use Think\Controller;

class UeditorController extends Controller {
    
    //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }

}