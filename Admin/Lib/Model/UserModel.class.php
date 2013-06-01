<?php
class UserModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('username','require','username必须'),
        );
    // 定义自动完成
    protected $_auto    =   array(
        array('create_time','time',1,'function'),
        );
}