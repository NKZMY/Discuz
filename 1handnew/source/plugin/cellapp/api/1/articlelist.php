<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forumindex.php 27451 2012-02-01 05:48:47Z monkey $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

if ($_GET['tpp']==''){ $_tpp=10;}
   else { $_tpp = $_GET['tpp'];} 
   	
if ($_GET['page']=='') { $_page=1;}
  else {$_page = $_GET['page'];}
  
  
$start = ($_page -1) * $_tpp + 5;
/*
  取文章列表,如果第一页，前5个置顶
  
*/
if ($_page==1){
  $sql = 'select a.aid,a.catid,a.uid,a.username,a.title,a.author,a.summary,idtype,id,dateline from '
        .DB::table('portal_article_title').' as a where a.catid='.$_GET['catid']
        .' order by dateline limit 0,5';
  $query = DB::QUERY($sql);
  $data = array();
  while($row = DB::fetch($query)) {
    $data_top[]=fetchrow($row);
    $len=count($data_top)-1;
    $data_top[$len]['isadv']=0;//广告标志置为0
  }
}

//测算是否存在下一页
  $sql = 'select count(1) as countall from '
        .DB::table('portal_article_title').' as a where a.catid='.$_GET['catid'];
  $query = DB::QUERY($sql);
  $row = DB::fetch($query);
  if ($row['countall']>$start+$_tpp){
	  $havenext=1;
  }else {
	  $havenext=0;
  }
  //读取数据      

  $sql = 'select a.aid,a.catid,a.uid,a.username,a.title,a.author,a.summary,idtype,id,dateline from '
        .DB::table('portal_article_title').' as a where a.catid='.$_GET['catid']
        .' order by dateline limit '.$start.','.$_tpp;
  $query = DB::QUERY($sql);
  $data = array();
  while($row = DB::fetch($query)) {
    $data[]=fetchrow($row);
    $len=count($data)-1;
    $data[$len]['isadv']=0;//广告标志置为0
    
  }

 $variable = array(
  	'data_top' => $data_top,
  	'data' => $data,
	'page' =>$_page,
	'tpp'  =>$_tpp,
	'catid' =>$_GET['catid'],
	'havenext' => $havenext,
	'message' => $message		);
 cellapp_core::result(cellapp_core::variable($variable));

/*


  填充一行数据


*/


function fetchrow($row){
  $data = cellapp_core::getvalues($row, array(
    	'aid', 'catid', 'uid', 'username', 
		'author', 'summary',
		'idtype','id','dateline'));
  //文章标题以文章区为准
  $titlegroup = $row['title'];
  $titles1 = explode("[",$titlegroup);
  $title = $titles1[0];
  $group = '';
  if ($titles1[1]<>''){
    $titles2 = explode("]",$titles1[1]);
    $group = $titles2[0];
  }
  //拆分栏目与标题
  $data['group'] = $group;
  $data['title'] = $title;
  
  $itemid = $row['id'];
  $itemtype= $row['idtype'];
  if ($itemtype=='tid'){
      //查询发帖用户id
      
	  //文章来源于论坛 读取数据
	  $sql1 = 'select authorid,views,sharetimes,favtimes,replies from '.DB::table('forum_thread')
	  .' where tid='.$itemid;
      $query1 = DB::QUERY($sql1);
	  $row1 = DB::fetch($query1);
	  $data['numview']=$row1['views'];
	  $data['numshare']=$row1['sharetimes'];
	  $data['numfavorite']=$row1['favtimes'];
	  $data['numcomment']=$row1['replies'];
	  $data['authoruid']=$row1['authorid'];

      $sql1 = 'select pid,message from '
        .DB::table('forum_post').' as a where first=1 and a.tid='.$itemid;
      $query1 = DB::QUERY($sql1);
	  $row1 = DB::fetch($query1);
      $message = $row1['message'];
      $pid = $row1['pid'];


	  //读取  赞 like 的次数
	  $sql1 = 'select numlike from '.DB::table('1hand_forum_post')
	  .' where pid='.$pid;
      $query1 = DB::QUERY($sql1);
	  $row1 = DB::fetch($query1);
	  if ($row1['numlike']==''){
 	    $data['numlike']=0;
	  } else {
	  $data['numlike']=$row1['numlike'];
	  }


	  //查询tableid，得到附件储存在哪个表里
	  $sql1 = 'select tableid from '.DB::table('forum_attachment')
	  .' where tid='.$itemid;
      $query1 = DB::QUERY($sql1);
	  $row1 = DB::fetch($query1);
	  //附件表
	  $tableid= $row1['tableid'];

      //得到文章
      preg_match_all('#\[attach\](.*?)\s*?\[/attach\]#si',$message,$matches);   
      //解析文章中图片  

      foreach($matches[1] as $match){
        $pics[] =  $match;
 	    //读取 图片
	    $sql1 = 'select attachment from '.DB::table('forum_attachment_'.$tableid)
	      .' where isimage=1 and aid='.$match;
        $query1 = DB::QUERY($sql1);
        
	    while ($row1 = DB::fetch($query1)){
     	  $data['pics'][]=$row1['attachment'];
     	  if ($data['pic'] ==''){
	     	  $data['pic']=$row1['attachment'];
     	  }
        }

        
      }      
	  $data['piccount']=count($pics);
   } else if ($itemtype==''){//无文章来源，说明就是文章区的文章
      

   }   
   return $data;
}


/*

  

*/	    
?>