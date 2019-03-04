<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Ad extends Common
{

    public function index($id=0,$tab=1){
        if(request()->isPost()){
            foreach(input('post.sort/a') as $key=>$value){
                Db::name('ad')->where('id',$key)->update(['sort'=>$value]);

            }
            return success('排序更新成功',url('index'));
        }else{
            $lists = Db::name('ad')->order('sort')->select();
            $this->assign('lists',$lists);
        }

        //编辑广告
        if($tab==3){
            $info = Db::name('ad')->where('id',$id)->find();
            if($info){
                $this->assign('info',$info);
            }
        }

        return $this->fetch();
    }

    //添加操作
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            //strict为false不进行字段严格检查
            $result = Db::name('ad')->strict(false)->insert($data);

            if($result){
                return success('添加成功',url('index'));
            }else{
                return error('添加失败');
            }
        }
    }



    //编辑功能
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            $result = Db::name('ad')->strict(false)->update($data);

            if($result !==false){
                return success('更新成功',url('index'));
            }else{
                return error('更新失败');
            }
        }
    }

    //删除功能
    public function delete($id=0){
        $imgUrl = ROOT_PATH . 'public/uploads/' . Db::name('ad')->where('id', $id)->value('img');
        try {
            //文件删除操作
            unlink($imgUrl);
        } catch (Exception $e) {}
        if(Db::name('ad')->where('id', $id)->delete()){
            return success('删除成功',url('index'));
        }else{
            return error('删除失败');
        }
    }

}