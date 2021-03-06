<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

class Index extends Common
{
    public function index()
    {
        //轮播图
        $banner = Db::name('ad')->where('type',0)->order('sort','desc')->limit(4)->cache(7200)->select();
        //广告
        $ad = Db::name('ad')->where('type',1)->order('sort','desc')->limit(4)->cache(7200)->select();

        //获取分类
        $category = Db::name('category')->where('is_show_index',1)->order('sort')->cache(7200)->select();
        foreach ($category as $key => $value) {
            //获取当前主分类下最新四条商品信息
            $goods = Db::name('goods')->where('cid_one',$value['id'])->order('id desc')->limit(4)->cache(7200)->select();
            $category[$key]['list'] = $goods;
        }

        exit(json_encode(['code'=>200, 'msg'=>'首页数据获取成功', 'banner'=>$banner,'ad'=>$ad,'goods'=>$category]));
    }
}
