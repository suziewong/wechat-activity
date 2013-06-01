<?php
class ContentAction extends Action{
	/*
		添加内容
	*/
    public function submit()
    {
        //$this->display();
        /**
         * 微信扩展接口测试
         */
        	error_reporting(E_ERROR);
        	include("./wechat-php-sdk/wechatext.class.php");
        	
        	function logdebug($text){
        		file_put_contents('../data/log.txt',$text."\n",FILE_APPEND);		
        	};
        	
        	$options = array(
        		'account'=>'monkeysuzie@gmail.com',
        		'password'=>'33031992618',
        		'datapath'=>'./wechat-php-sdk/data/cookie_',
        			'debug'=>true,
        			'logcallback'=>'logdebug'
        	); 
        	$wechat = new Wechatext($options);
        	if ($wechat->checkValid()) {
        		// 获取用户信息
        		/*$data = $wechat->getInfo('1604175440');
        		var_dump($data);*/
        		// 获取最新一条消息
        		$Content = M('Content');

        		$lastid = $Content->order('lastid desc')->field("lastid")->find();
        		//$lastid
        		$topmsg = $wechat->getTopMsg();
        		var_dump($topmsg);
        		if($topmsg['id'] > $lastid)
        		{
        			$data = array();
        			$data['lastid'] 	= $topmsg['id'];
        			$data['nickname'] 	= $topmsg['nickName'];
        			$data['fakeid']		= $topmsg['fakeId'];
        			$data['datetime']   = $topmsg['dateTime'];
        			$data['content']	= $topmsg['content'];
        			$data['avatar']		= "https://mp.weixin.qq.com/cgi-bin/getheadimg?token=92252111&fakeid=".$topmsg['fakeId'].".jpg";
            		$result = $Content->add($data);
            	}

        		//$xx= "<img height='48' width='48' src='https://mp.weixin.qq.com/cgi-bin/getheadimg?token=92252111&fakeid=1604175440.jpg' data-fakeid='1604175440' class='avatar left'>";
        		//echo $xx;
        		// 主动回复消息
        		/*if ($topmsg && $topmsg['hasReply']==0)
        		$wechat->send($topmsg['fakeId'],'hi '.$topmsg['nickName'].',rev:'.$topmsg['content']);*/
        }
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
}
