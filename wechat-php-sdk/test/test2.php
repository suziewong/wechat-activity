<?php
/**
 * 微信扩展接口测试
 */
	error_reporting(E_ERROR);
	include("../wechatext.class.php");
	
	function logdebug($text){
		file_put_contents('../data/log.txt',$text."\n",FILE_APPEND);		
	};
	
	$options = array(
		'account'=>'monkeysuzie@gmail.com',
		'password'=>'33031992618',
		'datapath'=>'../data/cookie_',
			'debug'=>true,
			'logcallback'=>'logdebug'
	); 
	$wechat = new Wechatext($options);
	if ($wechat->checkValid()) {
		// 获取用户信息
		$data = $wechat->getInfo('1604175440');
		var_dump($data);
		// 获取最新一条消息
		$topmsg = $wechat->getTopMsg();
		var_dump($topmsg);

		$xx= "<img height='48' width='48' src='https://mp.weixin.qq.com/cgi-bin/getheadimg?token=92252111&fakeid=1604175440.jpg' data-fakeid='1604175440' class='avatar left'>";
		echo $xx;
		// 主动回复消息
		if ($topmsg && $topmsg['hasReply']==0)
		$wechat->send($topmsg['fakeId'],'hi '.$topmsg['nickName'].',rev:'.$topmsg['content']);
}