CREATE TABLE `wc_user`(
  `userid` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(50) NOT NULL COMMENT '管理员名称',
  `userpassword` varchar(32) NOT NULL COMMENT '管理员密码',
  `userpower` int(1) unsigned NOT NULL COMMENT '管理员权限',
  `loginip` varchar(30) DEFAULT NULL COMMENT '登录IP',
  `lastlogintime` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  `logincount` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='管理员表';



CREATE TABLE `wc_content` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `lastid` int NOT NULL COMMENT 'lastid',
  `nickname`varchar(70) COMMENT '昵称',
  `fakeid` int NOT NULL COMMENT 'fakeid',
  `datetime` int NOT NULL COMMENT '时间',
  `content` varchar(500) COMMENT '内容',
  `avatar` varchar(200) NOT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='内容表';



CREATE TABLE `wc_here` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `lastid` int NOT NULL COMMENT 'lastid',
  `nickname`varchar(70) COMMENT '昵称',
  `fakeid` int NOT NULL COMMENT 'fakeid',
  `datetime` int NOT NULL COMMENT '时间',
  `content` varchar(500) COMMENT '内容',
  `avatar` varchar(200) NOT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='内容表';