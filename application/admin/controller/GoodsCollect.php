<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class GoodsCollect extends Common
{
    public function index() {
        return view();
    }

    public function getDataTables() {
        //获取请求过来的数据
        $getParam = request()->param();

        $draw = $getParam['draw'];

        //排序
        $orderSql = $getParam['order'][0]['dir'];

        //自定义查询参数
        $extra_search = $getParam['extra_search'];

        //获取表名
        $tablename = request()->controller();
        // 总记录数
        $recordsTotal = Db::name($tablename)->count();
        //过滤条件后的总记录数
        $search = $getParam['search']['value'];
        $recordsFiltered = strlen($search) ?  Db::name($tablename)->where($extra_search,'like','%'.$search.'%')->count() : $recordsTotal;

        //分页
        $start = $getParam['start']; //起始下标
        $length = $getParam['length']; //每页显示记录数

        //根据开始下标计算出当前页
        $page = intval($start/$length) + 1;
        $config = ['page'=>$page, 'list_rows'=>$length];
        $list = Db::name($tablename)->order($orderSql)->paginate(null,false,$config);
        $lists = [];
        $uploads_tem = config('view_replace_str.__UPLOADS__').'/';
        if(!empty($list)){
            foreach ($list as $key => $value) {
                $lists[$key] = $value;
                $lists[$key]['nickname'] = Db::name('user')->where('id', $value['uid'])->value('nickname');
                //获取商品信息
                $goods = Db::name('goods')->where('id', $value['gid'])->find();
                $lists[$key]['name'] = $goods['name'];
                $lists[$key]['img'] = "<img src='".$uploads_tem.$goods['img']."' alt='".$goods['name']."' width='36'>";
                $lists[$key]['price'] = $goods['price'];
            }
        }

        $data = array(
            "draw"=>$draw,
            "recordsTotal"=>$recordsTotal, //数据总数
            "recordsFiltered"=>$recordsFiltered, //过滤之后的记录总数
            "data"=>$lists
        );

        echo json_encode($data);
    }
}
