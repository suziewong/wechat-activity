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
		$Content = $Content->group('fakeid')->select();
		echo json_encode($Content);
	}

}
