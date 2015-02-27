<?php
/** ---------------------------------------------------------------------------------------------------
 * Avent Korea 2010 Renewal
 *
 * @package			NINE PIXEL
 * @author			Notte
 * @link			http://www.avent.co.kr
 * @lastmodified    2010.11.01
 * --------------------------------------------------------------------------------------------------*/

/*
| ---------------------------------------------------------------------------------------------------
| PAGE SETTINGS
| ---------------------------------------------------------------------------------------------------
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/../_config/config_nuby.php';
require_once _BASE_PATH_ . '/_config/nuby_frontend.php';
require_once _TDC_LIB_PATH_ . '/data.reply.php';
	
must_login();

$reply = new Reply(getURLOptions(),$db);
	
if ($_POST['do'] == 'reply_add_proc') { 

	if ($_SESSION['user']['sn'] > 0) { 

		$sql_data = array(
			'section'		=> "{$_POST['section']}",	
			'sn_board'		=> "{$_POST['sn_board']}",
			'id_members'	=> "{$_SESSION['user']['id']}",
			'sn_members'	=> "{$_SESSION['user']['sn']}",
			'name_members'	=> "{$_SESSION['user']['name']}",		
			'nick_members'	=> "{$_SESSION['user']['nickname']}",					
			'date_add' 		=> _TODAYTIME_,
			'story' 		=> "{$_POST['story']}",
			'ip_address' 	=> "{$_SERVER['REMOTE_ADDR']}",
			'sn_attachment_file' 	=> "{$_POST['picture']}",
		);
		//print_r($sql_data);
		$db->insert($reply->pageinfo['tableName'], $sql_data)->execute();
		echo "1";
	}
}
else if ($_POST['do'] == 'reply_update') { 
	$reply->update($_POST['sn'], $_POST['story']);
	echo "1";
}
else if ($_POST['do'] == 'reply_delete') { 
	$result = 0;
	if ($_SESSION['user']['sn'] > 0) { 
		$result= $_POST['sn_board'];
		$is_duplicate = $reply->duplicateCheck($_POST['sn_board'], $_POST['section']);
		if ($is_duplicate) { 
			$reply->delete($_POST['sn']);
			$result = 1;
		}
		echo $result;
	}
}

else if ($_POST['do'] == 'article_delete') { 

	if ($_SESSION[user][sn] > 0) { 
		$sql_where 			= "sn = '$_POST[sn]' AND sn_members = '{$_SESSION['user']['sn']}'";	
		$ct				 	= $db->count('NP_common_board')->where($sql_where, false)->fetch_element('total');
				
		if ($ct > 0) { 
			$db->delete('NP_common_board', $sql_data)->where($sql_where, false)->execute();
		}

	}
}

else if ($_POST['do'] == 'like') { 

	if ($_SESSION[user][sn] > 0) { 
	
		$sql_where 			= "sn_reply = '$_POST[sn_reply]' AND sn_members = '{$_SESSION['user']['sn']}'";	
		$ct				 	= $db->count('NP_common_like')->where($sql_where, false)->fetch_element('total');

		if ($ct > 0) { 
			echo "dup";
		}
		else {
			
			$sql_data = array(
				'sn_reply'		=> "{$_POST[sn_reply]}",
				'sn_members'	=> "{$_SESSION[user][sn]}",
				'date_add' 		=> _TODAYTIME_,
				'ip_address' 	=> "{$_SERVER['REMOTE_ADDR']}",
			);
			$db->insert('NP_common_like', $sql_data)->execute();
			$value['last_insert_id'] = $db->last_insert_id();		


			$sql_where 			= "sn_reply = '$_POST[sn_reply]'";	
			$ct_new			 	= $db->count('NP_common_like')->where($sql_where, false)->fetch_element('total');

			$db->sql("UPDATE NP_common_reply SET ct_like = '$ct_new' WHERE sn='{$_POST[sn_reply]}'")->execute();
						
			echo "$ct_new";
			
		}
		
	}
}

