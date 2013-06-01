<?php
class ApiAction extends Action {
    public function _initialize()
    {
      
      header("Access-Control-Allow-Origin:*");
      header("Content-type: application/json");
    }
    public function index(){
    	redirect(U('Index/index'));
	}
	//返回网站基础信息
	public function basic()
	{	
		$model = M("Setting");
		$setting = $model->getField('item_key,item_value');
		echo json_encode($setting);
	}

	//返回主播信息
	public function dj()
	{
		$Dj = M("Dj");
		$condition['id']=!empty($_GET['djid'])?$_GET['djid']:"";
		//var_dump($condition);
		if($condition['id'] == "")
		{
			$Dj = $Dj->select();
			for($i=0;$i<count($Dj);$i++)
			{
				
				$cond['djid'] = $Dj[$i]['id'];
				$Life = M("Life");
				$Life = $Life->where($cond)->select();
				for($j=0; $j<count($Life);$j++)
				{
					$Dj[$i]['lifePics'][$j] = $Life[$j]['lifeaddress'];
				}
				$Dj[$i]['headPic']= $Dj[$i]['headaddress'];
				$Dj[$i]['facePic']= $Dj[$i]['faceaddress'];
				$Dj[$i]['column']= $Dj[$i]['total'];
				$Dj[$i]['weibo']= $Dj[$i]['weibo-name'];
				$Dj[$i]['weiboUrl']= $Dj[$i]['weibo-url'];
				unset($Dj[$i]['headaddress']);
				unset($Dj[$i]['faceaddress']);
				unset($Dj[$i]['total']);
				unset($Dj[$i]['weibo-name']);
				unset($Dj[$i]['weibo-url']);
			}
		}
		else
		{
			$Dj = $Dj->where($condition)->select();
			$cond['djid'] = $condition['id'];
			$Life = M("Life");
			$Life = $Life->where($cond)->select();
			for($i=0; $i<count($Life);$i++)
			{
				$Dj[0]['lifePics'][$i] = $Life[$i]['lifeaddress'];
			}
			$Dj[0]['headPic']= $Dj[0]['headaddress'];
			$Dj[0]['facePic']= $Dj[0]['faceaddress'];
			$Dj[0]['column']= $Dj[0]['total'];
			$Dj[0]['weibo']= $Dj[0]['weibo-name'];
			$Dj[0]['weiboUrl']= $Dj[0]['weibo-url'];
			unset($Dj[0]['headaddress']);
			unset($Dj[0]['faceaddress']);
			unset($Dj[0]['total']);
			unset($Dj[0]['weibo-name']);
			unset($Dj[0]['weibo-url']);
		}
		echo json_encode($Dj);

	}
	//节目信息
	public function content()
	{
		$Content = M("Content");
		$contentid = isset($_GET['contentid'])?$_GET['contentid']:"";
		$condition['contentid']=$contentid;
		if($condition['contentid'] == "")
		{
			$classid = isset($_GET['classid'])?$_GET['classid']:2;
			$cond['classid']=$classid;
			$Content = $Content->where($cond)->order('contentid desc')->select();
			for($i=0; $i<count($Content);$i++)
			{
						
				$Content[$i]['title'] = $Content[$i]['contentname'];
				$Content[$i]['file'] = $Content[$i]['mp3address'];
				//$member = $Content[$i]['member'];
				//$member = $Content[$i]['member'];
				$member = explode(",", trim($Content[$i]['member']));
				unset($Content[$i]['contentname']);
				unset($Content[$i]['mp3address']);
				unset($Content[$i]['number']);
				unset($Content[$i]['member']);
				$Content[$i]['member'] = $member;	
			}

			
			
		}
		else
		{
			$Content = $Content->where($condition)->order('contentid desc')->select();
			$Content[0]['title'] = $Content[0]['contentname'];
			$Content[0]['file'] = $Content[0]['mp3address'];
			//$member = $Content[0]['member'];
			//$member = trim($Content[0]['member']);
			$member = explode(",", trim($Content[$i]['member']));
			unset($Content[0]['contentname']);
			unset($Content[0]['mp3address']);
			unset($Content[0]['number']);
			unset($Content[0]['member']);
			$Content[0]['member'] = $member;	
		}

		
		echo json_encode($Content);
	}
	//主播生活照
	public function get_life()
	{
		$Life = M("Life");
		$djid = isset($_GET['djid'])?$_GET['djid']:"";
		$condition['djid']=$djid;

		if($condition['djid'] == "")
		{
			$Life = $Life->select();
		}
		else
		{
			$Life = $Life->where($condition)->select();
		}
		echo json_encode($Life);
	}
	/*
		评论
	*/
	public function comment()
	{
		$Comment = M("Comment");
		if(isset($_POST['djid']))
		{
		    $data = array();
		    $data['djid'] = $_POST['djid'];
		    $data['comment'] = $_POST['comment'];
		    $data['posttime']=time();
		    $Comment = M('Comment');
		    $result = $Comment->add($data);
		
		    if($result)
		    {
		        echo "{'result':'success'}";
		    }
		    else
		    {
		        echo "{'result':'fail'}"; 
		    }
		}
		else
		{
			 echo "{'result':'fail'}"; 
		}
	}
	/*
		建议
	*/
	public function suggest()
	{
		
		if(isset($_POST['suggest']))
        {

            $data = array();
            $data['name'] = $_POST['name'];
            $data['email'] = $_POST['email'];
            $data['suggest'] = $_POST['suggest'];
            $data['posttime']=time();
           
            $Suggest = M('Suggest');
            $result = $Suggest->add($data);
        	
            if($result)
            {
                echo "{'result':'success'}";
            }
            else
            {
                echo "{'result':'fail'}"; 
            }
        }
        else
        {
        	  echo "{'result':'fail'}"; 
        }
	}
	/*
		直播
		输入
		输出
			1.是today or week
			2.详细列表
	*/
	public function live()
	{        
		//设置当前时间
        $Now = date('Y-m-d H:i:s');
        $Live = M("Live");

        //1.先判断是否有将要直播的节目
        $map['finish']  = array('gt',$Now);
        $Live = $Live->where($map)->order('liveid desc')->select();
        
        if($Live)
        {
        	for($j=0; $j<count($Live);$j++)
        	{
        		$Live[$j]['member'] = explode(",", $Live[$j]['member']);
        		
        		
        		$Live[$j]['title'] = $Live[$j]['livename']; 
        		unset($Live[$j]['livename']);
        	}
        	$data = array();
        	$data['type'] = 'today';
        	$data['list'] = $Live;
        	echo json_encode($data);
        }
        else
        {
        	$Live = M("Live");
        	$Live = $Live->order('liveid desc')->select();
        	for($j=0; $j<count($Live);$j++)
        	{
        		$Live[$j]['member'] = explode(",", $Live[$j]['member']);
        		$Live[$j]['title'] = $Live[$j]['livename']; 
        		unset($Live[$j]['livename']);

        	}
        	$data = array();
        	$data['type'] = 'week';
        	$data['list'] = $Live;
        	echo json_encode($data);
        }

	}


}
