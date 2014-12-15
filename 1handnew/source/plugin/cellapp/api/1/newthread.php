<?php


//创建一个帖子
/*
	
	如果tid为0，则表明是回帖；如果tid不为0，则表明是发帖
	
	post过来帖子的参数
	先验证用户存不存在，再去验证论坛ID存不存在
	然后将数据存储在数据库中，相关的表有：forum_groupuser, forum_forum, forum_post, forum_thread
	
	对于discuz论坛来说，任何一个thread都当做一个post来处理，只不过这个post特殊的属性。
	
	对于forum_thread来说：
	对于一个帖子，首次必须插入的数据有：fid, author, authorid, subject, dateline, lastpost, lastposter, subfid
	其中subfid为一个数组
	
	从客户端接受的数据有fid, author, authorid, subject, (修改)
	剩下的值默认为0
	第一次发帖后，dateline默认等于lastpost，author默认等于lastposter
	测试链接：
	
*/

//回复一个帖子
/*
	如果tid为0，则表明是回帖；如果tid不为0，则表明是发帖
	
	对于回帖来说，需要fid, tid, first, author, authorid, subject, dateline, message, userip, position
	剩下的参数按照默认来处理
	其中客户端传过来fid, tid, message, 
	剩下的参数由php来计算
	
*/


if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}


		
		$fid = $_POST['fid'];
		//echo $fid;
		$authorid = $_G['uid'];// $_G['uid']
		//$authorid = $_POST['uid'];
		//确认是发帖，先校验fid是不是为空
		$sql = "select * from `pre_forum_forum` where fid='$fid'";
		$result = DB::QUERY($sql);
		if(!empty($result)){
			
			//校验authorid是不是存在于member表中
			$sql = "select * from `pre_common_member` where uid='$authorid'";
			$query = DB::QUERY($sql);
			$result = DB::fetch($query);
			//echo $result;
			
			if(!empty($result)){
			
				//校验是发帖还是回帖			
				$tid = $_POST['tid'];   //$tid为0则说明是发帖，$tid不为0则说明是回帖
				
				if($tid == '0'){				
					
					//发帖处理
					$fid = $_POST['fid'];
					$author = $result['username'];
					
					$authorid = $_G['uid'];
					//$authorid = $_POST['uid'];
					$subject = $_POST['subject'];
					
					$dateline =  date("U");
					$lastpost = $dateline;
					$lastposter = $author;
					
					$message = $_POST['message'];
					
					$userip = $_SERVER['REMOTE_ADDR'];
					
					$sql = "insert into pre_forum_thread(fid, author, authorid, subject, dateline, lastpost, lastposter) values('$fid', '$author', '$authorid', '$subject', '$dateline', '$lastpost', '$lastposter') ";
										
					if(DB::QUERY($sql)){
						// 得到刚才插入数据的ID，然后存储在pre_forum_thread中
						// 因为tid在表中是自增长的，所以对表中tid字段做max操作就可以得到刚才插入数据的id
						
						$resultOfThread = DB::QUERY("select max(tid) as recentTid from pre_forum_thread");
						$data = DB::fetch($resultOfThread);
						$recentTid = $data['recentTid'];
						
						//得到刚才插入的tid，在pre_forum_post中进行插入新数据
						
						$sql = "insert into pre_forum_post (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment, rate, ratetimes, status, tags, comment, replycredit, position) values ('$fid', '$recentTid', '1', '$author', '$authorid','$subject', '$dateline', '$message', '$userip', '0', '0', '1', '0', '-1', '-1', '0', '0', '0', '0', '0' , '0','0', '0' ,'1')";
						
						DB::QUERY($sql);
						
						//在pre_forum_forum中更新新数据，需要更新的数据有threads，posts，todayposts，yesterdayposts, lastpost
						
						//按照fid && first = 1计算threads
						$resultNumberOfThread = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' and first = 1");
						$data = DB::fetch($resultNumberOfThread);
						$numberOfThread = $data['number'];
						
												
						//按照fid 计算post，因为thread和post都算作是post
						$resultNumberOfPost = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' ");
						$data = DB::fetch($resultNumberOfPost);
						$numberOfPost = $data['number'];
						
						
						
						//计算lastpost=tid+subject+dateline+author
						$lastpost = $tid . '	' . $subject . '	' . $dateline . '	' . $author;
						
						$sql = "update pre_forum_forum  set threads='$numberOfThread' , posts='$numberOfPost',
						 lastpost='$lastpost' where fid='$fid'";
						 
						 DB::QUERY($sql);
						 
						 //在子论坛中存储一份，subfid的类型是一个数组，需要多次在子论坛中存储，数组是按‘+’来分割的
						$subfidString = $_POST['subfid'];
						
						$subfidArray = explode(" ", $subfidString);
						
						$subject = '['. $recentTid .']'. $subject;
						
						foreach($subfidArray as $subfidValue)
						{
							
							$sql = "insert into pre_forum_thread(fid, author, authorid, subject, dateline, lastpost, lastposter) values('$subfidValue', '$author', '$authorid', '$subject', '$dateline', '$lastpost', '$lastposter') ";	
							DB::QUERY($sql);
						}
						
					}
					
					
					
				}
				else {
					
					//回帖处理
					
					$fid = $_POST['fid'];
					$author = $result['username'];
					
					$authorid = $_G['uid'];
					//$authorid = $_POST['uid'];
					$subject = $_POST['subject'];
					echo 'subject'.$subject;
					$dateline =  date("U");
					$lastpost = $dateline;
					$lastposter = $author;
					
					$message = $_POST['message'];
					echo 'message'.$message;
					$userip = $_SERVER['REMOTE_ADDR'];
					
					
					
					//按照tid更新pre_forum_thread中的lastpost, lastposter
					
					$sql = "update pre_forum_thread set lastpost = '$lastpost', lastposter = '$lastposter' where tid = '$tid'";
					DB::QUERY($sql);
					//将信息存储在pre_forum_post中，并且根据之前的记录设置postion
					
					//计算出当前position，然后做insert 操作的时候
					
					$resultOfPosition = DB::QUERY("select max(position) as recentPosition from pre_forum_post where tid = '$tid'");
					$data = DB::fetch($resultOfPosition);
					$recentPosition = $data['recentPosition']+1;
					
					$sql = "insert into pre_forum_post (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment, rate, ratetimes, status, tags, comment, replycredit, position) values ('$fid', '$tid', '1', '$author', '$authorid','$subject', '$dateline', '$message', '$userip', '0', '0', '1', '0', '-1', '-1', '0', '0', '0', '0', '0' , '0','0', '0' ,
					'$recentPosition')";
						
					DB::QUERY($sql);
					
					//完成对pre_forum_post的insert操作后，继续完成对pre_1hand_post_ext的插入
					//首先得到pre_forum_post生成的
					$resultPid = DB::QUERY("select max(pid) as recentPid from pre_forum_post");
					$data = DB::fetch($resultPid);
					$recentPid = $data['recentPid'];
					
					//完成对pre_1hand_post_ext插入
					$reply_pid = $_POST['reply_pid'];
					$have_pic = $_POST['have_pic'];
					$pic = $_POST['pic'];
					$have_location = $_POST['have_location'];
					$latitude = $_POST['latitude'];
					$longtitute = $_POST['longtitute'];
					$locationname = $_POST['locationname'];
					$fromm = $_POST['fromm'];
					$uid = $authorid ;
					$click = $_POST['click'];
					$position = $_POST['position'];
					$commentnum = $_POST['commentnum'];
					$favtimes = $_POST['favtimes'];
					$sharetimes = $_POST['sharetimes'];
					
					$sql = "insert into pre_1hand_post_ext (pid, reply_pid, have_pic, pic, have_location, latitude, longtitute, locationname, fromm, uid, click, position, commentnum, favtimes, sharetimes) values ('$recentPid', '$reply_pid',
					'$have_pic', '$pic', '$have_location', '$latitude', '$longtitute', '$locationname', '$fromm', '$uid', '$click', 
					'$position', '$commentnum', '$favtimes', '$sharetimes')";
					
					DB::QUERY($sql);
					
					
					//更新pre_forum_forum中posts
					
					//按照fid 计算post，因为thread和post都算作是post
					$resultNumberOfPost = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' ");
					$data = DB::fetch($resultNumberOfPost);
					$numberOfPost = $data['number'];
					
					DB::QUERY("update pre_forum_forum set posts = '$numberOfPost' where fid = '$fid'");
				
				
				}
				
				
				
			}

			
		}
	









?>