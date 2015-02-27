<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../_config/config_nuby.php';
require_once _BASE_PATH_ . '/_config/nuby_frontend.php';

if ($_SESSION['user']['sn']) {
	echo 1;
	//ErrorMsg("이미 로그인되어 있습니다");
}
else {
	echo 0;
}