<?php
class ContentAction extends Action{
	/*
		添加内容
	*/
    public function chat()
    {
        //$this->display();
        /**
         * 微信扩展接口测试
         */
        	//error_reporting(E_ERROR);
        	include("./wechat-php-sdk/wechatext.class.php");
        	
        	function logdebug($text){
        		file_put_contents('./wechat-php-sdk/data/log.txt',$text."\n",FILE_APPEND);		
        	};
        	
        	$options = array(
        		'account'=>'monkeysuzie@gmail.com',
        		'password'=>'33031992618',
        		'datapath'=>'./wechat-php-sdk/data/cookie_',
        			'debug'=>true,
        			'logcallback'=>'logdebug'
        	); 
        	$wechat = new Wechatext($options);
            $token= $wechat->token();
        	if ($wechat->checkValid()) {
        		// 获取用户信息
        		/*$data = $wechat->getInfo('1604175440');
        		var_dump($data);*/
        		// 获取最新一条消息
        		$Content = M('Content');

        		$arr = $Content->order('lastid desc')->field("lastid")->find();
               
        		//$lastid
        		$topmsg = $wechat->getTopMsg();
        		//var_dump($topmsg);
        		if($topmsg['id'] > $arr['lastid'])
        		{
        			$data = array();
        			$data['lastid'] 	= $topmsg['id'];
        			$data['nickname'] 	= $topmsg['nickName'];
        			$data['fakeid']		= $topmsg['fakeId'];
        			$data['datetime']   = $topmsg['dateTime'];
        			$data['content']	= $topmsg['content'];

                    //file_get_contents("");
        			//$data['avatar']		= "https://mp.weixin.qq.com/cgi-bin/getheadimg?token=".$token."&fakeid=".$topmsg['fakeId'].".jpg";
            		$data['avatar'] = "&fakeid=".$topmsg['fakeId'].".jpg";
                    $result = $Content->add($data);
            	}   
        		//$xx= "<img height='48' width='48' src='https://mp.weixin.qq.com/cgi-bin/getheadimg?token=92252111&fakeid=1604175440.jpg' data-fakeid='1604175440' class='avatar left'>";
        		//echo $xx;
        		// 主动回复消息
        		//if ($topmsg && $topmsg['hasReply']==0)
        		//$wechat->send($topmsg['fakeId'],'hi '.$topmsg['nickName'].',rev:'.$topmsg['content']);
        }

        
        $ContentList = array();
        $Content = M("Content");
        $page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

        $Content= $Content->order('lastid desc')->select();
  //  $Model = new Model();
#     $Content = $Model->query("select contentid,contentname,classname,member,number,mp3address from feel_content,feel_class
#where feel_content.classid = feel_class.classid");
        while (list($key, $val) = each($Content)) {
            array_push($ContentList,$val);
        }       
        import("ORG.Util.Page");// 导入分页类
        $count = count($ContentList);// 查询满足要求的总记录数
        $length = 10;
        $offset = $length * ($page - 1);
        $Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
        $Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
        $show = $Page->show();// 分页显示输出
        $this->assign("token",$token);
        $this->assign("ContentList",$ContentList);
        $this->assign("offset",$offset);
        $this->assign("length",$length);
        $this->assign("page",$show);
        $this->display();
        
    }

	public function add()
	{
		include "./wechat-php-sdk/wechat.class.php";
		$options = array(
				'token'=>'tokenaccesskey' //填写你设定的key
			);
		$weObj = new Wechat($options);
		$weObj->valid();
		$type = $weObj->getRev()->getRevType();
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					$weObj->text("hello, I'm wechat")->reply();
					exit;
					break;
			case Wechat::MSGTYPE_EVENT:
					break;
			case Wechat::MSGTYPE_IMAGE:
					break;
			default:
					$weObj->text("help info")->reply();
		}
	}

    public function submit()
    {
        
        $ContentList = array();
        //$Content = M("Content");
        $page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

        $location = "中国浙江省%";
        $Model = new Model();
        $sql = "select nickname,avatar,datetime from wc_content where content like '".$location."' order by datetime desc ";
        $Content = $Model->query($sql);
        //$Content= $Content->order('datetime desc')->select();
        //var_dump($Content);
  //  $Model = new Model();
#     $Content = $Model->query("select contentid,contentname,classname,member,number,mp3address from feel_content,feel_class
#where feel_content.classid = feel_class.classid");
        while (list($key, $val) = each($Content)) {
            array_push($ContentList,$val);
        }       
        import("ORG.Util.Page");// 导入分页类
        $count = count($ContentList);// 查询满足要求的总记录数
        $length = 50;
        $offset = $length * ($page - 1);
        $Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
        $Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
        $show = $Page->show();// 分页显示输出
        $this->assign("ContentList",$ContentList);
        $this->assign("offset",$offset);
        $this->assign("length",$length);
        $this->assign("token",$token);
        $this->assign("page",$show);
        $this->display();
    }

    public function cj()
    {
        
        $this->display();
    }
}
