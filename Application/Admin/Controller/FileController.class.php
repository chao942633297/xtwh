<?php
namespace Admin\Controller;
use Think\Controller;

class FileController extends Controller
{
    public function Upload(){
        $inputname=I('imgtype');
        $upload =new \Think\Upload();
        $upload->maxSize=3145728000;
        $upload->exts= array('jpg','gif','png');
        $upload->rootPath = './Uploads/';
        $upload->savePath = '';

        $info=$upload->upload();
        //等到上传图片在服务器的路径
        $savePath=__ROOT__.'/Uploads/'.$info[$inputname]["savepath"].$info[$inputname]["savename"];
        $this->ajaxReturn($savePath);
    }
}