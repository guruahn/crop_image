<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../_config/config_nuby.php';
require_once _BASE_PATH_ . '/_config/nuby_frontend.php';
require_once _TDC_LIB_PATH_ . '/data.event.php';
$event = new Event(getURLOptions(),$db);
$event->setMenu( array('menu'=>'NUBY Club', 'submenu'=>'NUBY 이벤트', 'section'=> 'event') );
$row = $event->getEvent("646");
$event->setEvent($row);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>누비프로모션</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta http-equiv="Cache-Control" content="No-Cache" />
    <meta http-equiv="Expires" content="0" />
    <meta property="og:title" content="우리아이는 유명인 이벤트(테스트)">
    <meta property="og:site_name" content="우리아이는 유명인 이벤트(테스트):이벤트:누비">
    <meta property="og:image" content="http://nuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_copy_2014-06-03_11:49:08.jpg">
    <meta property="og:description" content="누비 이벤트 '우리아이는 유명인 이벤트(테스트)', 기간 2014-06-02 ~ 2014-06-24">
    <meta property="og:url" content="http://nuby.greaten.co.kr/app/event/event_view.php?sn=644&amp;page=1&amp;q=&amp;flag=">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link rel="stylesheet" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3.css">
    

</head>
<body>
	<div id="wrapper" style="width:100%">
		<div class="promotion3_main">
			<img style="width:100%;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_main1.jpg" />
            <a class="go_login" href="#"><img style="width:100%;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_main1_2.jpg" /></a>
            <img style="width:100%;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_main1_3.jpg" />
			<div class="event_btn" style="overflow:hidden;width:100%;" >
				<a class="go_app" href="1_form.php"><img style="float:left;width:50%!important;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_main2_1.jpg"></a>
				<a href="http://www.facebook.com/sharer/sharer.php?u=http://nuby.greaten.co.kr/app/event/event_view.php?sn=646&page=1&q=&flag=" target="_blank"><img style="float:left;width:50%!important;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_main2_2.jpg"></a>
			</div>
		</div>
	</div>
	<!-- // wrapper -->
	
	<script type="text/javascript" src="http://nuby.greaten.co.kr/public/script/jquery.bpopup.min.js"></script>
	<script type="text/javascript" src="http://nuby.greaten.co.kr/public/script/functions.js" charset="utf-8"></script>
    <script>
    $(function() {
    	
    	$('.go_app').click(function(){
    		//window.top.location.href = "http://nuby.greaten.co.kr/app/event/event_view.php?sn=644&page=1&q=&flag="; 
    	});
    	$('.go_login').click(function(){
    		//모바일이 아니면 PC용으로 보내기
            var isMOBILE = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
            if(isMOBILE) {
                checkLogin();
            } else{
                window.top.location.href = "http://nuby.co.kr/app/member/login.php?nextURL=http://nuby.greaten.co.kr/app/event/event_view.php?sn=646"
            }
    	});
    	
    });
	//로그인 체크
    function checkLogin() {
        var url = "http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/_login_check.php";
        $.ajax({
            url: url,
            type: "POST"
        }).success(function(data) {
            if(data == "0") {
                window.top.location.href = "http://mnuby.greaten.co.kr/app/member/login.php?nextURL=http://nuby.greaten.co.kr/app/event/event_view.php?sn=646";
            }else if(data == "1"){
                alert("이미 로그인 하셨습니다.");
                return false;
            }
        }).fail(function(response){
            
        });
    }
    </script>