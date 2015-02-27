<?php
/**
 * event App
 *
 * @author  Tendency
 * @author  Tendency Dev Team
 * @package App/event
 */

/*
| ---------------------------------------------------------------------------------------------------
| PAGE SETTINGS
| ---------------------------------------------------------------------------------------------------
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/../_config/config_nuby.php';
require_once _BASE_PATH_ . '/_config/nuby_frontend.php';
require_once _BASE_PATH_ . '/_config/nuby_menu_frontend.php';

require_once _TDC_LIB_PATH_ . '/data.event.php';
require_once _TDC_LIB_PATH_ . '/data.attachment.php';
require_once _TDC_LIB_PATH_ . '/data.reply.php';

$event = new Event(getURLOptions(),$db);
$event->setMenu( array('menu'=>'NUBY Club', 'submenu'=>'NUBY 이벤트', 'section'=> 'event') );
$sn	= checked_sn($_GET['sn']);
$row = $event->getEvent($sn);
$event->setEvent($row);

for ($i=1; $i<=3; $i++) { 
	$attachment = new Attachment($db);//첨부파일 관련 모델 호출
	$attachment->setAttachmentInfo($row['sn_attachment_file'.$i]);
	if ($row['sn_attachment_file'.$i]) {
		$file_url[] = '/media/'.$event->pageinfo['mediaDirectory'].'/'.$attachment->file_v_name;
	}
}
$dup_check = 0;
if($event->cat == "4" OR $event->cat == "5"){//댓글, 페이스북 이벤트인 경우
	$dup_check = 1;
}

//페이스북 공유 시 적용될 썸네일을 위한 코드
$file_url[0] = "http://nuby.greaten.co.kr".$file_url[0];
$sn_reply = $_GET['sn_reply'];
$result_img = $_GET['result_img'];
if($sn_reply){
	$reply = new Reply(getURLOptions(), $db);
	$row_reply = $reply->getReply($sn_reply);
	//printr($row_reply);
	$file_url[0] = "http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/".$row_reply['sn_attachment_file'];
}else if($result_img){
	$file_url[0] = "http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_text_".$result_img.".jpg";
}
$title = $event->SUBJECT;
$site_name = $event->SUBJECT.":이벤트:누비";
$url = $_SERVER['REQUEST_URI'];
$image = $file_url[0];
$end_date = $event->ENDDATE;
if($event->cat == '5')	$end_date = '';
$description = "누비 이벤트 '".$event->SUBJECT."', 기간 ".$event->STARTDATE." ~ ".$end_date;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta property="og:title" content="우리 아이를 잡지표지모델로 데뷔시켜보세요.">
	<meta property="og:site_name" content="<?=$site_name?>">
	<meta property="og:url" content="<?=_SITE_URL_.$url?>">
	<meta property="og:image" content="<?=$image?>">
	<meta property="og:description" content="누비 딸깍안심컵 런칭 기념 이벤트. 우리 아이가 잡지 모델로 만들어지는 재밌는 경험을 함께 해보세요.">
	<title>NUBY에 오신걸 환영합니다</title>

	<link rel="stylesheet" type="text/css" href="/public/css/common.css" />
	<link rel="stylesheet" type="text/css" href="/public/css/event.css" />
	<link rel="stylesheet" type="text/css" href="/public/css/idangerous.swiper.css"> 
	<link rel="stylesheet" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3.css">
	<link rel="stylesheet" media="screen and (max-width:720px)" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_mobile.css" />
	<style type="text/css">
	/*댓글팝업*/
	#reply_to_pop_up {background-color: #fff;width:100%;}
	#reply_to_pop_up .popup_contents{padding:4.236111111111111%; border: 15px solid #f1f1f1;border-top: 40px solid #f1f1f1;height: 87%;}
	#reply_to_pop_up .b-close{position: absolute;top: 1%;right:5%;width:19.30555555555556%;}
	#reply_to_pop_up #replySubmit{position: absolute;top: 1%;right:25%;width:19.30555555555556%;}
	#reply_to_pop_up .b-close img, #reply_to_pop_up #replySubmit img{ width: 100%;}
	#reply_to_pop_up #replyForm{width: 100%;height: 100%}
	#reply_to_pop_up #replyForm{width: 100%;height: 100%}
	#reply_to_pop_up #story{width: 100%;height: 80%; border:0;padding-top: 15px;}
	</style>
	<script type="text/javascript" src="/public/script/jquery-1.10.1.min.js"></script>
	<script src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/kakao.link.js"></script>
	<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
</head>


<body class="<?=$app?> page-event_view">
	<header>
		<div class="top_header">
			<h1>누비로고<a href="/"><img src="/images/common/logo.jpg" alt="nuby"></a></h1>
			<div id="top_nav">
				<ul class="text_nav">
					<?
					if(!is_login()){
					?>
						<li class="login"><a href="/app/member/login.php"><img src="/images/common/login.jpg" alt="login" /></a></li>
				        <li class="join"><a href="/app/member/join.php"><img src="/images/common/join.jpg" alt="join" /></a></li>
			        <?}else{?>
			        	<li class="logout"><a href="/app/member/logout.php"><img src="/images/common/logout.jpg" alt="logout" /></a></li>
			        <?}?>
			        			</ul>
				<ul class="img_nav" >
		            <li class="blog"><a href="http://blog.naver.com/mysonreve" target="_blank"><img src="/images/common/blog.jpg" alt="blog"></a></li>
		            <li class="facebook"><a href="https://www.facebook.com/greaten.kr" target="_blank"><img src="/images/common/facebook.jpg" alt="facebook"></a></li>
		            <li class="greaten"><a href="http://www.greaten.co.kr/app/shop/category.html?cat=0624" target="_blank"><img src="/images/common/shopping.jpg" alt="greaten"></a></li>
		        </ul>
	        </div>
		</div>
		<nav id="depth1" role="navigation">

			<!-- gnb -->
			<ul>
				<li class="company"><a href="/app/company/index.php" class=""><img src="/images/common/depth1_1.jpg" alt="About NUBY"></a></li><li class="shop"><a href="/app/shop/index.php" class=""><img src="/images/common/depth1_2.jpg" alt="PRODUCT"></a></li><li class="event"><a href="/app/event/index.php" class="on"><img src="/images/common/depth1_3.jpg" alt="NUBY CLUB"></a></li><li class="store"><a href="/app/store/index.php" class=""><img src="/images/common/depth1_4.jpg" alt="STORE"></a></li><li class="customer"><a href="/app/customer/index.php" class=""><img src="/images/common/depth1_5.jpg" alt="C/S Center"></a></li>		</ul>
			<!-- //gnb -->
		</nav>
		<nav id="depth2" role="navigation" style="display: none;">
			<a class="arrow-left" href="#"><img src="/images/common/menu_prev_on.jpg" alt="이전메뉴"></a><ul><div class="swiper-container"><div class="swiper-wrapper" style="width: 1389.2916666666667px; height: 40px;"><div class="swiper-slide" style="width: 347.3229166666667px; height: 40px;"><div class="title"><li><a href="/app/event/event.php"><img src="/images/app/event/depth2_1_off.jpg" alt=""></a></li></div></div><div class="swiper-slide" style="width: 347.3229166666667px; height: 40px;"><div class="title"><li><a href="/app/event/notice.php"><img src="/images/app/event/depth2_2_off.jpg" alt=""></a></li></div></div><div class="swiper-slide" style="width: 347.3229166666667px; height: 40px;"><div class="title"><li><a href="/app/event/nuby_mammy.php"><img src="/images/app/event/depth2_3_off.jpg" alt=""></a></li></div></div><div class="swiper-slide" style="width: 347.3229166666667px; height: 40px;"><div class="title"><li><a href="/app/event/review.php"><img src="/images/app/event/depth2_4_on.jpg" alt=""></a></li></div></div></div></div></ul><a class="arrow-right" href="#"><img src="/images/common/menu_next_on.jpg" alt="다음메뉴"></a>
		</nav>
	</header>

<!--#wrapper-->
<div id="wrapper">
	
	<!--콘텐츠 -->
	<div id="contents_wrap">
		<div class="contents">
			<!--.view -->
				<div class="view">
					<div class="view_header">
						<h4><?=$event->SUBJECT?></h4>
						<p class="date"><span><?=substr($event->CREDATE, 0, 10)?></span></p>
					</div>
					<div class="view_content">
						
						<div id="share_wrapper">
							<div id="result">
								<img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_text_<?=$result_img?>.jpg" />
							</div>
							<div class="go_product">
								<a href="http://www.greaten.co.kr/app/event/special_view.html?sn=654" target="_blank">
									<img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_go_product_btn.png" />
								</a>
							</div>
							<div id="share">
								<span>
									<button id="refresh" >
										<img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_go_firstpage_btn.png" />
									</button>
								</span>
								<span>
									<button id="download" >
										<img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_images_save_btn.png" />
									</button>
								</span>
								<span style="clear:both">
									<a href="http://www.facebook.com/sharer/sharer.php?u=http://nuby.greaten.co.kr/app/event/event_view_promotion3.php?sn=646&result_img=<?=$result_img?>" target="_blank" id="facebook-link-btn">
						                <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_facebook_btn.png" />
						            </a>
									<button id="kakao-link-btn" data-href="">
							            <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_kakao_btn.png" />
							        </button>
							        <button id="kakao-story-link-btn" data-href="">
						                <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_kas_btn.png" />
						            </button>
						        </span>
						        <p class="mreply2">
									<button id="reply2" <?=(is_login())?"data-login='Y'":"data-login='N'"?>>
										<img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/m_reply_btn.png" />
									</button>
								</p>

							</div>
						</div>
					</div>
					<div class="view_navi">
						<?if($event->sn != "646"){?>
						<p><a href="/app/event/event.php?page=<?=$event->pageinfo['page']?>&q=<?=$event->pageinfo['q']?>&flag=<?=$event->pageinfo['flag']?>"><img src="/images/common/go_list_btn.jpg" alt="목록으로"></a></p>
						<?}?>
					</div>
					<?if($event->cat != "4"){?>
					<div class="add_reply">
						<form id="replyForm" name="replyForm" action="" method="POST" />
							<input type="hidden" name="sn_board" value="<?=$event->sn?>" />
							<input type="hidden" name="section" value="<?=$event->pageinfo['section']?>" />	
							<input type="hidden" name="do" value="reply_add_proc" />
							<input type="hidden" name="dup_check" value="<?=$dup_check?>" />
							<p class="comment"><span><strong>Comment</strong><br />(0/250 Byte)</span></p>
							<textarea class="text" id="story" name="story"></textarea>
							<button id="replySubmit" <?if(!is_login()) echo "class='go_login'"?>>등록</button>
						</form>
					</div>
					<?}?>
					<?if($event->cat != "4" || $event->sn == "646"){?>
					<div id="reply"></div>
					<?}?>

				</div>
				<!--//.view -->
		</div>
	</div>
	<!-- //콘텐츠 -->
</div>
<!--//#wrapper-->
<!-- 댓글 팝업 -->
	<div id="reply_to_pop_up" style="display:none;">
	    <a class="b-close close_top"><img src="http://mnuby.greaten.co.kr/images/common/modify_reply_cancle.jpg" alt="취소" /></a>
	    <a class="" id="replySubmit" href="#"><img src="http://mnuby.greaten.co.kr/images/common/modify_reply_ok.jpg" alt="확인" /></a>
	    <div class="popup_contents">
	    	<p class="comment_popup"><strong>Comment </strong> (최대 250 byte)</p>
	    	<form id="replyForm" name="replyForm" action="" method="POST" />
				<input type="hidden" name="sn_board" value="646">
				<input type="hidden" name="section" value="event">	
				<input type="hidden" name="do" value="reply_add_proc">
				<input type="hidden" name="picture" value="my_baby_is_famous_text_<?=$result_img?>.jpg">
	    		<textarea class="text" id="story" name="story"></textarea>
	    	</form>
	    </div>
	</div>
	<!-- //댓글 팝업 -->

	<!-- 이미지 팝업 -->
	<div id="image_to_pop_up" style="display:none;">
		<img class="b-close" style="position:absolute;top:1%;right:1%;width:8%;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/thumb_close1.png">
	    <div class="popup_contents" style="width:80%;margin-left:10%;margin-top:1%">
			<img style="width:100%;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_text_<?=$result_img?>.jpg" />	    	
	    </div>
	    <img style="width:85%;margin-left:7.5%;margin-top:10px;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/save_text.png">
	</div>
<!-- 댓글 수정팝업 -->
<div id="modify_to_pop_up" style="display:none;">
    <a class="b-close close_top"><img src="/images/app/event/modify_popup_close.png" alt="닫기"></a>
    <div class="popup_contents">
    	<p>하단의 <strong>확인</strong>을 누르시면 댓글 수정이 완료됩니다.</p>
    	<p class="comment_popup"><strong>Comment </strong> (0/250 byte)</p>
    	<form id="replyEditForm" name="replyEditForm" action="" method="POST" />
			<input type="hidden" name="do" value="reply_update" />
			<input type="hidden" name="sn" value="" />
    		<textarea class="text" id="story_popup" name="story"></textarea>
    	</form>
    	<div class="modify_btn">
    		<a class="modify_ok" href="#"><img src="/images/app/event/modify_ok_btn.png" alt="확인" /></a>
    		<a class="b-close" href=""><img src="/images/app/event/modify_cancel_btn.png" alt="취소" /></a>
    	</div>
    </div>
</div>
<!-- //댓글 수정팝업 -->
<!-- 댓글 썸네일팝업 -->
<div id="thumbnail_to_pop_up" style="display:none;">
    <div class="popup_contents" style="width:100%;">
    	
    </div>
    <a class="b-close"><img style="width:50%;margin:0 auto;margin-top:10px;" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/thumb_close.png"></a>
</div>
<!-- //댓글 썸네일팝업 -->
<!--댓글 삭제 폼-->
<form id="replyDeleteForm" name="replyDeleteForm" action="" method="POST" />
	<input type="hidden" name="sn" value="" />
	<input type="hidden" name="sn_board" value="<?=$event->sn?>" />
	<input type="hidden" name="do" value="reply_delete" />
	<input type="hidden" name="section" value="<?=$event->pageinfo['section']?>" />	
</form>
<!--//댓글 삭제 폼-->

<script>
	
Kakao.init('412a2a0db9c3c6c0883e3147c1138bda');

    // 카카오톡 링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
    function createTalkButton(){
    	//alert($('#kakao-link-btn').attr('data-href'));
    	Kakao.Link.createTalkLinkButton({
	        container: '#kakao-link-btn',
	        label: '[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.',
	        image: {
	            src: 'http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_text_<?=$result_img?>.jpg',
	            width: '640',
	            height: '800'
	        },
	        webButton: {
	        text: '누비 클릭잇컵 이벤트',
	        url: $('#kakao-link-btn').attr('data-href') // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
	        }
	    });
    }
    
   

    function executeKakaoStoryLink2(link)
    {
        kakao.link("story").send({   
            post : "[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이가 잡지모델로 만들어지는 재밌는 경험을 해보세요.\n"+link,
            appid : "Nuby.co.kr",
            appver : "1.0",
            appname : "Nuby.co.kr",
            urlinfo : JSON.stringify({title:"[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.", desc:"[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.", imageurl:["http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/my_baby_is_famous_text_<?=$result_img?>.jpg"], type:"article"})
        });
    }

	$(function() {
		var section = '<?=$event->pageinfo['section']?>';
		var sn =  <?=$event->sn?>;
		var is_like = '<?=$event->is_like?>';
		var reply_url = "_load_reply.php";
		<?if($event->sn == "646"){?>
			reply_url = "/app/event/feature_event/nuby_promotion_20140616/_load_reply.php";
		<?}?>
		go_page(reply_url, section, sn, 10, is_like, 1);
		$('#reply').on('click','.page', function(event){
			var page = $(this).attr('data-page');
			go_page(reply_url, section, sn, 10, is_like, page);
			event.preventDefault()
		});
		// 처음으로
		$('#refresh').click(function(){
			window.location.href="http://mnuby.greaten.co.kr/app/event/event_view.php?sn=646";
		});
		//다운받기

		$('#download').click(function(){
			event.preventDefault();//prevent the normal click action from occuring
    		image_bpopup();
		});

		//댓글달기 팝업
		$('#reply2').click(function(){
			//alert($(this).attr('data-login'));
			if( $(this).attr('data-login') == "N" ){
				if(confirm("댓글작성은 로그인 후 가능합니다.\n지금 로그인 하시겠습니까?\n(이미지를 먼저 저장하시고 로그인 해주세요.)")){
					window.location = "/app/member/login.php";
				}
			}else{
				reply_bpopup();	
			}
		});

		//댓글 입력
		$('#replySubmit').click(function(){
			submitReply("replyForm");
			return false;
		});
		
		
		//댓글 팝업
		$("#reply").on("click",".modify_reply", function(){
			var reply = $(this).parent().parent().find('.story').text();
			$("#replyEditForm textarea[name=story]").val(reply);
			$('#replyEditForm input[name=sn]').val($(this).attr('data-sn'));
			modify_bpopup();
			return false;
		});
		//댓글 수정
		$('.modify_ok').click(function(){
			submitReply2("replyEditForm");
			return false;
		});
		//댓글 삭제
		$("#reply").on("click",".del_reply", function(){
			if (confirm("정말로 삭제하시겠습니까?")){
				$('#replyDeleteForm input[name=sn]').val($(this).attr('data-sn'));
				submitReply2("replyDeleteForm");
			}else{
				return false;
			}
			return false;
		});
		//코멘트 길이 표시하기
		$("#story").keyup(function(){
			textLimitInsert($(this), 250);
		});
		$("#story_popup").keyup(function(){
			textLimitEdit($(this), 250);
		});
		
		//댓글 썸네일 팝업
		$("#reply").on("click",".thumbnail", function(){
			thumbnail_bpopup($(this));
			return false;
		});
		$('#kakao-story-link-btn').click(function(){
	        /*Kakao.Auth.getStatus(function(statusObj) {
	            if (statusObj.status == "not_connected") {
	              loginWithKakao(executeKakaoStoryLink);
	            } else {
	                executeKakaoStoryLink();          
	            }
	        });*/
			var link = $(this).attr('data-href');
	    	executeKakaoStoryLink2(link);
	    });
	    //모바일이 아니면 카톡,카스버튼 감추기
		/*if(!isMOBILE){
			$('#kakao-story-link-btn').hide();
			$('#kakao-link-btn').hide();
		}*/
		//페이스북 공유 링크 주소 줄이기
		var url = "http://nuby.co.kr/app/event/event_view_promotion3.php?sn=646&result_img=<?=$result_img?>";
		makeShortUrl2(url, function(result){
			var FB_shareUrl = "http://www.facebook.com/sharer/sharer.php?u="+result;
			$('#facebook-link-btn').attr('href',FB_shareUrl); 
			$('#kakao-link-btn').attr('data-href',result); 
			$('#kakao-story-link-btn').attr('data-href',result); 
			//alert(result);
			createTalkButton();
		});
	});
	var isMOBILE = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
	//댓글 입력
	function submitReply(form) {
		$.ajax({
			url: "_story_tools.php",
			type: "POST", 
			data: $("#"+form).serialize(),
			dataType: "html"
		}).success(function(data) {
			console.log(data);
			if(data == "1"){
				alert("댓글이 등록되었습니다.");
				$("#reply_to_pop_up").bPopup().close();
				window.location.reload(); //페이지를 리로드
			}
		}).fail(function(response){
			alert("업로드가 실패하였습니다.");
		});
	}
	//댓글 입력
	function submitReply2(form) {
		$.ajax({
			url: "_story_tools.php",
			type: "POST", 
			data: $("#"+form).serialize(),
			dataType: "html"
		}).success(function(data) {
			if(data == "0") {
				alert("이미 참여하셨습니다");
			}else if(data == "1"){
				document.location.reload();
			}else{
				alert(data);
			}
		}).fail(function(response){
			alert("업로드가 실패하였습니다.");
		});
	}
	// 댓글 등록 팝업
	function reply_bpopup()
	{
	    $("#reply_to_pop_up").bPopup({
	        closeClass:'b-close',
	        modalClose: false,
	        transitionClose: 'fadeIn',
	        speed: 250,
	        zIndex: 9000,
	        position :['auto',100],
	        //follow: [false, false],
	        positionStyle : 'absolute',
	        onClose: function(){
	        
	    	}
	    });
	}
	
	// 이미지 등록 팝업
	function image_bpopup()
	{
	    $("#image_to_pop_up").bPopup({
	        closeClass:'b-close',
	        //modalClose: false,
	        transitionClose: 'fadeIn',
	        speed: 250,
	        zIndex: 9000,
	        position :['auto',0],
	        //follow: [false, false],
	        positionStyle : 'absolute'
	    });
	}
	function go_page(url, section, sn, total, is_like, page)
	{
		$.ajax({
			url: url,
			type: "POST", 
			data: {section : section, sn : sn, total: total, is_like: is_like, page: page },
			dataType: "html"
		}).success(function(data) {
			//console.log(data);
			$("#reply").html(data);
		}).fail(function(response){

		});
	}
	// 댓글 수정 팝업
	function modify_bpopup()
	{
	    $("#modify_to_pop_up").bPopup({
	        closeClass:'b-close',
	        modalClose: true,
	        transitionClose: 'fadeIn',
	        speed: 250,
	        zIndex: 9000,
	        position :['auto',100],
	        //follow: [false, false],
	        positionStyle : 'absolute'
	    });
	}
	// 댓글  썸네일 팝업
	function thumbnail_bpopup($obj)
	{

		$("#thumbnail_to_pop_up .popup_contents").empty();
		var url = $obj.find('img').attr('src');
	    $("#thumbnail_to_pop_up").bPopup({
	        closeClass:'b-close',
	        modalClose: true,
	        transitionClose: 'fadeIn',
	        speed: 250,
	        zIndex: 9000,
	        position :['auto',50],
	        //follow: [false, false],
	        positionStyle : 'absolute',
	        onOpen : function(){
	        	$("#thumbnail_to_pop_up .popup_contents").append('<img src="'+url+'" />');
	        }
	    });
	}

	//텍스트 입력 제한(댓글입력)
	function textLimitInsert(obj, maxlen) {
		var text = obj.val();
		var text_length = stringByteSize(text);
		if (text_length > maxlen + 1){
			alert('입력가능한 최대글자를 초과하였습니다.');
			$(obj).val(stringCutByteSize(text, maxlen));
		}else{
			$('.comment').html("<span><strong>Comment</strong><br />("+text_length+"/250 Byte)</span>")
		}		
	}

	//텍스트 입력 제한(댓글수정)
	function textLimitEdit(obj, maxlen) {
		var text = obj.val();
		var text_length = stringByteSize(text);
		if (text_length > maxlen + 1){
			alert('입력가능한 최대글자를 초과하였습니다.');
			$(obj).val(stringCutByteSize(text, maxlen));
		}else{
			$('.comment_popup').html("<span><strong>Comment</strong>("+text_length+"/250 Byte)</span>")
		}		
	}
	function makeShortUrl2(url, callback) {
	    var bit = {
	        version: "2.0.1",
	        login: "o_3hgu0knjqb",
	        apiKey: "R_6bf2876c11a49ca562db614fc367983a",
	        longUrl: url
	    }; 
	    var apiCallUrl= "http://api.bit.ly/shorten?"
	            + "version=" + bit.version
	            + "&login= " + bit.login
	            + "&apiKey=" + bit.apiKey
	            + "&callback=?" // 이부분이 있어야 crossdomain때문에 일어나는 권한 문제를 해결할 수 있다.
	            + "&longUrl=" + encodeURIComponent(bit.longUrl);
	    
	    var result;
	    jQuery.getJSON(
	        apiCallUrl, 
	        function(data){
	            if ( data.statusCode == "OK" ) {
	                result = data.results[url].shortUrl; 
					if( typeof callback == "function") callback(result);
	            }
	        }
	    );
	}
</script>


<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_views/footer.php";
?>