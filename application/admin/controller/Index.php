<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }


    public function content(){
        return $this->fetch();
    }


    public function info(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['password'] == ''){
                return error('密码不为空');
            }else{
                $data['password'] = md5($data['password']);
                $result = db('admin')->where('name',session('user.name'))->update($data);
                if($result !== false){
                    return success('更新成功！');
                }else{
                    return error('更新失败！');
                }
            }
        }else{
            return view();
        }
    }
}
