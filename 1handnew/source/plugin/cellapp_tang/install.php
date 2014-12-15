<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: install.php 8889 2010-04-23 07:48:22Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_hand1user;
CREATE TABLE pre_hand1user (
  `uid` mediumint(8) unsigned NOT NULL,
  `lastlogin` datetime NOT NULL ,
  `continuelogin` mediumint(8) NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) TYPE=MyISAM;

EOF;

runquery($sql);

$finish = TRUE;

?>