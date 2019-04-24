<?php 


namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;

class Pro extends Controller{


    public function index()
    {
    	return view('index');
    }

    public function students()
    {
    	$data = Db::name('user')->select();

    	if($data)
    	{
    		return json_encode(['status'=>'200','msg'=>'请求成功','data'=>$data]);
    	}
    	return json_encode(['status'=>'201','msg'=>'请求失败','data'=>'']);
    }

    public function once()
    {
    	$data = Db::name('user')->select();

        $sum = 0;//总权重
         
        for ($i=0; $i < count($data); $i++) { 
        	$sum += $data[$i]['weight'];
        }

        $pool = array();

	    foreach ($data as $v) {

	     for ($i = 0; $i <= $v['weight']; $i++) {
	            $pool[] = $v;
        }
        }

          $randNum = rand(1, $sum);

          return json_encode(['status'=>'200','msg'=>'请求成功','data'=>$pool[$randNum - 1]['user']]);
        

    }

    public function upwless()
    {
        $ins = Request::instance();

        $user = $ins->post('user');

        $data = Db::table('user')->where('user',$user)->find();

        $nweight = $data['weight']*0.5;
        
        $arr = ['names'=>$user,'status'=>"答对喽"];

        Db::table('history')->insert($arr);

        Db::table('user')->where('user',$user)->update(['weight'=>$nweight]);

        return json_encode(['status'=>'200','msg'=>'请求成功','data'=>"恭喜你回答正确"]);

    }

    public function upwadd(){
        $ins = Request::instance();

        $user = $ins->post('user');

        $data = Db::table('user')->where('user',$user)->find();

        $nweight = $data['weight']*1.5;

        $arr = ['names'=>$user,'status'=>"答错喽"];

        Db::table('history')->insert($arr);

        Db::table('user')->where('user',$user)->update(['weight'=>$nweight]);

        return json_encode(['status'=>'201','msg'=>'请求成功','data'=>"可惜了，回答错误"]);
    }

    public function his()
    {
        $data = Db::table('history')->order('id desc')->limit(10)->select();
        
        return view('his',['data'=>$data]);
    }







}


 ?>