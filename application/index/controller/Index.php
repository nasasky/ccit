<?php
namespace app\index\controller;
use app\index\controller\Base;
use Db;
class Index extends Base
{
    public function index()
    {
    	//未加载前最大的新闻数量
    	$limit = 10;
    	//获取今日新闻
    	$data = Db::table('news')->order("time desc")->limit($limit)->select();
    	//传递到前端
    	$this->assign('newestData',$data);
    	//获取总数量，计算可更新次数
    	$newsCount = Db::table("news")->count();
    	$page = floor($newsCount / $limit)-1;
    	$this->assign('newsPage',$page);
    	//渲染
        return $this->fetch();
    }

    //流加载新闻
    public function loadNews()
    {
    	$curPage = input("post.curPage");
    	$low = $curPage * 10;
    	$data = Db::table('news')->order("time desc")->limit($low,10)->select();
    	//返回最新数据
    	exit(json_encode($data));
    }

    //新闻详情
    public function newsDetail()
    {
        //点击阅读，增加阅读量
        $save['read'] = Db::table('news')->where('id='.input('get.id'))->value('read')+1;
        Db::table('news')->where('id='.input('get.id'))->update($save);
        //前端数据返回
        $data = Db::table('news')->where('id='.input('get.id'))->find();
        $this->assign('data',$data);
    	return $this->fetch();
    }
}
