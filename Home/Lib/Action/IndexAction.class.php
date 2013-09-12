<?php
	/*
		
	*/
class IndexAction extends Action {
	
    public function index(){
    	//var_dump($config);
    	//C('HOME_DEFAULT_THEME');
    	//$Content = M("Content");
		/*$page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

    	$ContentList = array();
        $Model = new Model();
        $Content = $Model->query("select id,username,title,link,anonymous,time from dc_content,dc_user where dc_user.userid = dc_content.userid order by time desc ");
		while (list($key, $val) = each($Content)) {
		    array_push($ContentList,$val);
		}
		import("ORG.Util.Page");// 导入分页类
		$count = count($ContentList);// 查询满足要求的总记录数
		$length = 20;
		$offset = $length * ($page - 1);
		$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
		//$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
		$Page->setConfig('prev','Older');
		$Page->setConfig('next','More');
		$Page->setConfig('theme',' %upPage%   %downPage%');
		$show = $Page->show();// 分页显示输出
		$this->assign("ContentList",$ContentList);
		$this->assign("offset",$offset);
		$this->assign("length",$length);
		$this->assign("page",$show);

    	//$this->display(C('HOME_DEFAULT_THEME').':index');
		*/
		$this->display();
		
	}
	public function about()
	{
		$this->display();
	}
	public function maktimes($date)
	{
		$time =  strtotime($date);
	    $t=time()-$time;
	     $f=array(
	       '31536000'=> '年',
	       '2592000' => '个月',
	       '604800'  => '星期',
	       '86400'   => '天',
	       '3600'    => '小时',
	       '60'      => '分钟',
	       '1'       => '秒'
	   );
	   foreach ($f as $k=>$v){        
	       if (0 !=$c=floor($t/(int)$k)){
	           return $c.$v.'前';
	       }
	   }
	 } 

}
