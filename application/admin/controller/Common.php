<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Common  extends Controller
{
    public function _initialize(){
        //先假设存在session
        if(session('user.name')==null || session('user.name')!='admin'){
            session(null);
            $this->redirect('login/index');
        }
    }

    //单图片上传
    public function upload_image(){
        //获取表单上传的文件
        $file = request()->file(input('name'));
        //将图片转移到指定的目录当中
        $info = $file->move(ROOT_PATH.'public/uploads');
        if($info){
            $resname = str_replace('\\', '/', $info->getSaveName());
            return json_encode($resname);
        }
    }

    // 多图片或文件异步上传
    public function upload_images(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public/uploads');
        if($info){
            $fileName = str_replace('\\', '/', $info->getSaveName());
            return json_encode($fileName); //文件名
        }
    }

    //公用的删除功能
    public function delete($id=0){
        //获取数据表
        $table = request()->controller();
        if(Db::name($table)->where('id',$id)->delete()){
            return success('删除成功',url('index'));
        }else{
            return error('删除失败');
        }
    }
}