<?php


//����һ������
/*
	
	���tidΪ0��������ǻ��������tid��Ϊ0��������Ƿ���
	
	post�������ӵĲ���
	����֤�û��治���ڣ���ȥ��֤��̳ID�治����
	Ȼ�����ݴ洢�����ݿ��У���صı��У�forum_groupuser, forum_forum, forum_post, forum_thread
	
	����discuz��̳��˵���κ�һ��thread������һ��post������ֻ�������post��������ԡ�
	
	����forum_thread��˵��
	����һ�����ӣ��״α������������У�fid, author, authorid, subject, dateline, lastpost, lastposter, subfid
	����subfidΪһ������
	
	�ӿͻ��˽��ܵ�������fid, author, authorid, subject, (�޸�)
	ʣ�µ�ֵĬ��Ϊ0
	��һ�η�����datelineĬ�ϵ���lastpost��authorĬ�ϵ���lastposter
	�������ӣ�
	
*/

//�ظ�һ������
/*
	���tidΪ0��������ǻ��������tid��Ϊ0��������Ƿ���
	
	���ڻ�����˵����Ҫfid, tid, first, author, authorid, subject, dateline, message, userip, position
	ʣ�µĲ�������Ĭ��������
	���пͻ��˴�����fid, tid, message, 
	ʣ�µĲ�����php������
	
*/


if(!defined('IN_MOBILE_API')) {

	exit('Access Denied');

}


		
		$fid = $_POST['fid'];
		//echo $fid;
		$authorid = $_G['uid'];// $_G['uid']
		//$authorid = $_POST['uid'];
		//ȷ���Ƿ�������У��fid�ǲ���Ϊ��
		$sql = "select * from `pre_forum_forum` where fid='$fid'";
		$result = DB::QUERY($sql);
		if(!empty($result)){
			
			//У��authorid�ǲ��Ǵ�����member����
			$sql = "select * from `pre_common_member` where uid='$authorid'";
			$query = DB::QUERY($sql);
			$result = DB::fetch($query);
			//echo $result;
			
			if(!empty($result)){
			
				//У���Ƿ������ǻ���			
				$tid = $_POST['tid'];   //$tidΪ0��˵���Ƿ�����$tid��Ϊ0��˵���ǻ���
				
				if($tid == '0'){				
					
					//��������
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
						// �õ��ղŲ������ݵ�ID��Ȼ��洢��pre_forum_thread��
						// ��Ϊtid�ڱ������������ģ����ԶԱ���tid�ֶ���max�����Ϳ��Եõ��ղŲ������ݵ�id
						
						$resultOfThread = DB::QUERY("select max(tid) as recentTid from pre_forum_thread");
						$data = DB::fetch($resultOfThread);
						$recentTid = $data['recentTid'];
						
						//�õ��ղŲ����tid����pre_forum_post�н��в���������
						
						$sql = "insert into pre_forum_post (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment, rate, ratetimes, status, tags, comment, replycredit, position) values ('$fid', '$recentTid', '1', '$author', '$authorid','$subject', '$dateline', '$message', '$userip', '0', '0', '1', '0', '-1', '-1', '0', '0', '0', '0', '0' , '0','0', '0' ,'1')";
						
						DB::QUERY($sql);
						
						//��pre_forum_forum�и��������ݣ���Ҫ���µ�������threads��posts��todayposts��yesterdayposts, lastpost
						
						//����fid && first = 1����threads
						$resultNumberOfThread = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' and first = 1");
						$data = DB::fetch($resultNumberOfThread);
						$numberOfThread = $data['number'];
						
												
						//����fid ����post����Ϊthread��post��������post
						$resultNumberOfPost = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' ");
						$data = DB::fetch($resultNumberOfPost);
						$numberOfPost = $data['number'];
						
						
						
						//����lastpost=tid+subject+dateline+author
						$lastpost = $tid . '	' . $subject . '	' . $dateline . '	' . $author;
						
						$sql = "update pre_forum_forum  set threads='$numberOfThread' , posts='$numberOfPost',
						 lastpost='$lastpost' where fid='$fid'";
						 
						 DB::QUERY($sql);
						 
						 //������̳�д洢һ�ݣ�subfid��������һ�����飬��Ҫ���������̳�д洢�������ǰ���+�����ָ��
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
					
					//��������
					
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
					
					
					
					//����tid����pre_forum_thread�е�lastpost, lastposter
					
					$sql = "update pre_forum_thread set lastpost = '$lastpost', lastposter = '$lastposter' where tid = '$tid'";
					DB::QUERY($sql);
					//����Ϣ�洢��pre_forum_post�У����Ҹ���֮ǰ�ļ�¼����postion
					
					//�������ǰposition��Ȼ����insert ������ʱ��
					
					$resultOfPosition = DB::QUERY("select max(position) as recentPosition from pre_forum_post where tid = '$tid'");
					$data = DB::fetch($resultOfPosition);
					$recentPosition = $data['recentPosition']+1;
					
					$sql = "insert into pre_forum_post (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment, rate, ratetimes, status, tags, comment, replycredit, position) values ('$fid', '$tid', '1', '$author', '$authorid','$subject', '$dateline', '$message', '$userip', '0', '0', '1', '0', '-1', '-1', '0', '0', '0', '0', '0' , '0','0', '0' ,
					'$recentPosition')";
						
					DB::QUERY($sql);
					
					//��ɶ�pre_forum_post��insert�����󣬼�����ɶ�pre_1hand_post_ext�Ĳ���
					//���ȵõ�pre_forum_post���ɵ�
					$resultPid = DB::QUERY("select max(pid) as recentPid from pre_forum_post");
					$data = DB::fetch($resultPid);
					$recentPid = $data['recentPid'];
					
					//��ɶ�pre_1hand_post_ext����
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
					
					
					//����pre_forum_forum��posts
					
					//����fid ����post����Ϊthread��post��������post
					$resultNumberOfPost = DB::QUERY("SELECT count(*) as number FROM `pre_forum_post` where fid ='$fid' ");
					$data = DB::fetch($resultNumberOfPost);
					$numberOfPost = $data['number'];
					
					DB::QUERY("update pre_forum_forum set posts = '$numberOfPost' where fid = '$fid'");
				
				
				}
				
				
				
			}

			
		}
	









?>