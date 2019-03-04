<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

class Category extends Common
{
    /**
     * 获取商品分类列表
     */
    public function getCategory(){
        //接收传入的一级分类ID
        $pid = input('pid', 0);
        //获取父级分类信息
        $category = Db::name('category')->where('pid',$pid)->order('sort')->select();
        if($pid==0){
            //当没有传入pid时，默认为打开状态
            $subCategory = Db::name('category')->where('pid', $category[0]['id'])->order('sort')->select();
            exit(json_encode(['code'=>200, 'msg'=>'分类获取成功', 'list'=>$category, 'sublist'=>$subCategory]));
        }else{
            exit(json_encode(['code'=>200, 'msg'=>'分类获取成功', 'sublist'=>$category]));
        }
    }

}
