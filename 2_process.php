<?php
/**
 * 2_process
 *
 * @author  Tendency
 * @author  Tendency Dev Team
 * @package event
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/../_config/config_nuby.php';
require_once '/data/tendency/_libraries/class.upload_0.30/class.upload.php';
$baby_name = $_REQUEST['name']; 
$sex = $_REQUEST['sex']; 
$isLogin = "N";
if(is_login()) $isLogin = "Y";
$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
                    '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
                    '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

if($_FILES){
    //파일사이즈 검사
    if ($_FILES['file']['size'] > 1024 * 1024 * 8 ) { 
        ErrorMsg("첨부파일은 최대 2메가까지 가능합니다.");
    }

    //파일업로드
    $foo = new Upload($_FILES['file']);

    $file_v_name = uploadImage($foo);

    //이미지 회전 체크
    correctImageOrientation($file_v_name);

    //이미지 사이즈 체크
    $src_image_size = getimagesize($file_v_name);
    $width = $src_image_size[0];
    $height = $src_image_size[1];
    //echo "width:".$width.", height:".$height;
    if($width < $height){
        $target_h = "560px";
        if( $isMobile ) $target_h = "400px";
        $target_w = "auto";
    }else{
        $target_h = "auto";
        $target_w = "100%";
    }
}

function is_login(){
    if ($_SESSION['user']['sn']) {
        return true;
        //ErrorMsg("이미 로그인되어 있습니다");
    }
    else {
        return false;
    }
}

/*
* correctImageOrientation
* @param 
* @return 
*/
function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
        $img = imagecreatefromjpeg($filename);
        $deg = 0;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename 
        imagejpeg($img, $filename, 95);
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}

/*
* Upload
* @param 
* @return 
*/
function uploadImage($foo)
{
    $A_extension = array("jpg", "jpeg");
    
    if ($foo->uploaded) {
        
        $mime = $foo->file_src_mime;
        $file_r_name = $foo->file_src_name;
        $file_size = $foo->file_src_size;           
        $file_ext = $foo->file_src_name_ext;

        if (!in_array(strtolower($file_ext), $A_extension) ) {
            ErrorMsg("이미지 파일만 첨부할 수 있습니다");
        }

        $save_filename = 'mybaby_is_famous_'.uniqueTimeStamp();
        $foo->file_new_name_body = $save_filename;
        $file_v_name = $foo->file_new_name_body .".". $file_ext;
        $uploadPath = '/data/tendency/mnuby_root/app/event/feature_event/nuby_promotion_20140616/';
        //echo $_FILES['file']['size'];
        $foo->image_resize       = false;
        $foo->Process($uploadPath);

        if ($foo->processed) 
        {
            return $file_v_name;            
        }
        else 
        {
            ErrorMsg('error : ' . $foo->error);
        }

    }   
    
}


function uniqueTimeStamp() {
  $milliseconds = microtime();
  $timestring = explode(" ", $milliseconds);
  $sg = $timestring[1];
  $mlsg = substr($timestring[0], 2, 4);
  $timestamp = $sg.$mlsg;
  return $timestamp;
}

/**
 * 에러 표시하고 Back
 */
function ErrorMsg($msg, $is_back = 1) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
    
    if ($is_back == 1) { 
        echo "
            <script type='text/javascript' charset='utf-8'>
            window.alert(\"$msg\");
            history.go(-1);
            </script>
        ";
        exit;
    }
    else {
        echo "
            <script type='text/javascript' charset='utf-8'>
            window.alert(\"$msg\");
            </script>
        ";
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>누비프로모션</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta http-equiv="Cache-Control" content="No-Cache" />
    <meta http-equiv="Expires" content="0" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" src="http://nuby.greaten.co.kr/public/script/jquery.bpopup.min.js"></script>
    <link rel="stylesheet" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3.css">
    <link rel="stylesheet" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/jquery.Jcrop.min.css" type="text/css">
    <link rel="stylesheet" media="screen and (max-width:720px)" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_mobile.css" />
    <style>
        body {font-size: 16px;overflow:hidden}
        
        #target { width:<?=$target_w?>!important;height:<?=$target_h?>!important;}
        .mask_img { position: absolute; z-index: 300;}
        #release { width: 40%;height: 100px;font-size: 2em;}
    </style>
</head>
<body>
    <script>

        "use stric"

        //크롭 좌표정보
        var coordJASONObject = {"x": "","y": "","x2": "","y2": "","w": "", "h":""}
        //이벤트 응모 정보
        var joinJASONObject = {"baby_name":"<?=$baby_name?>", "userid": "","upload_img": "<?=$file_v_name?>","crop_img": "","copy_img": "","result_img": "", "progress": 0, "sex":"<?=$sex?>", "isLogin": "<?=$isLogin?>"}

        $(document).ready(function(){
            $('#release').click(function()
            {//전송버튼 클릭
                loding_bpopup();
                //alert('canvas_w:'+$('.jcrop-holder').width()+', canvas_h:'+$('.jcrop-holder').height());
                //이미지 크롭후 마스크함수 콜백 
                //console.log(printr_json(coordJASONObject));
                $.ajax({
                    type: "POST",
                    url: "/app/event/feature_event/nuby_promotion_20140616/crop_image.php",
                    data: { x: coordJASONObject.x, y: coordJASONObject.y, x2: coordJASONObject.x2, y2: coordJASONObject.y2, w: coordJASONObject.w, h: coordJASONObject.h, canvas_w: $('.jcrop-holder').width(), canvas_h: $('.jcrop-holder').height(), upload_img: joinJASONObject.upload_img}
                }).success(function( data ) {
                    if(data == 0){
                        alert("이미지 자르기가 실패하였습니다. 관리자에게 문의하세요");
                    }else{
                        //console.log(printr_json(data));
                        /*$('#result').empty().append("<img src='images/"+data+"' />");*/
                        joinJASONObject.crop_img = data;
                        joinJASONObject.progress = 1;
                        copy_image();
                    }
                }).fail(function(response){

                });
            });

        });

        jQuery(function($){
            /*
            * 이미지 크롭핑
            */
            var jcrop_api;
            // In this example, since Jcrop may be attached or detached
            // at the whim of the user, I've wrapped the call into a function
            initJcrop();
            
        });
        var isMOBILE = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
        // The function is pretty simple
        function initJcrop()//{{{
        {
            
            var frame_w = 0;
            var frame_h = 0;
            if(!isMOBILE){
                frame_w = 200;
                frame_h = 250;
            } else{
                frame_w = 150;
                frame_h = 187.5;
            }
            // Invoke Jcrop in typical fashion
            $('#target').Jcrop({
                onChange:   showCoords,
                aspectRatio: 1/1.25,
                minSize: [ frame_w, frame_h ],
                setSelect: [ 60, 70, frame_w, frame_h]
                /*
                if(!isMOBILE) {
                    minSize: [ 300, 375 ]
                };
                else{
                    minSize: [ 200, 250 ]    
                };*/
                
            },function(){
                jcrop_api = this;
                jcrop_api.setOptions({ allowSelect: false });
                $('.jcrop-holder>div>div').prepend('<img class="mask_img" src="/app/event/feature_event/nuby_promotion_20140616/nuby_promotion_frame_ex.png" width="'+frame_w+'" height="'+frame_h+'"/>');
                //$('.jcrop-holder>div').maskable({maskSrc: '/testApi/img/mask/result/mask.png' });  
            });

        };
        
        function showCoords(c)
        {//좌표값 얻기
            //console.log(printr_json(c));
            $('.mask_img').width(c.w);
            $('.mask_img').height(c.h);
            coordJASONObject.x = c.x;
            coordJASONObject.y = c.y;
            coordJASONObject.x2 = c.x2;
            coordJASONObject.y2 = c.y2;
            coordJASONObject.w = c.w;
            coordJASONObject.h = c.h;
            //console.log(printr_json(coordJASONObject));
            //모바일이면 드래그할 손잡이 큼직하게
            if(isMOBILE) $('.jcrop-handle').css({"width":"42px", "height":"42px"});
        };


        function printr_json(obj)
        {
            //json형태의 데이터를 트리구조로 출력해준다.
            //사용예 : alert(printr_json(obj)) 
            return  JSON.stringify( obj, null, 11 );
        }   

        function imageSizeCheck(obj)
        {//실제 이미지 사이즈
        
            var widthLimit, heightLimit, h, w, reader, image, title

            if( "" != $(obj).val() ){

                imageFile = document.getElementById($(obj).attr('id')).files[0];
                reader = new FileReader();
                image  = new Image();
                reader.readAsDataURL(imageFile);

                reader.onload = function(_file) {

                    image.src = _file.target.result;
                    image.onerror= function() {
                        alert('파일타입이 이미지가 아닙니다. 첨부하신 파일타입: '+ imageFile.type);
                        return false;
                    }

                };//end reader.onload

            }//end if

        }//end function
        //팝업 오픈
        function popupOpen(){
            var popUrl = "";
            if(isMOBILE){
                popUrl = "http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/3_share.php"
            }else{
                popUrl = "http://nuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/3_share.php"
            }
            popUrl = popUrl+"?result_img="+joinJASONObject.result_img.substring(23,38)+"&sn=646";   //팝업창에 출력될 페이지 URL
            var popOption = "width=800, height=1000, resizable=no, scrollbars=no, status=no;";    //팝업창 옵션(optoin)
            /*popup = window.open(popUrl,"[이벤트]우리아이는 유명인 참여결과",popOption);
            $.load("url", function(){
                popup.location = target;
            });*/
            var a = document.createElement('a');
            a.setAttribute("href", popUrl);
            a.setAttribute("target", "_blank");

            window.top.location.replace(popUrl);
        }

        function copy_image()
        {//이미지 합성
            $.ajax({
                type: "POST",
                url: "/app/event/feature_event/nuby_promotion_20140616/copy_image.php",
                data: { crop_img: joinJASONObject.crop_img }
            }).success(function( data ) {
                if(data == 0){
                    alert("이미지 합성이 실패하였습니다. 관리자에게 문의하세요");
                }else{
                    /*$('#result').empty().append("<img src='images/"+data+"' />");*/
                    joinJASONObject.copy_img = data;
                    joinJASONObject.progress = 2;
                    text_image();
                }
            }).fail(function(response){

            });
        }

        function text_image()
        {//이미지에 텍스트 쓰기
            $.ajax({
                type: "POST",
                url: "/app/event/feature_event/nuby_promotion_20140616/text_image.php",
                data: { copy_img: joinJASONObject.copy_img, crop_img: joinJASONObject.crop_img, baby_name: joinJASONObject.baby_name, sex: joinJASONObject.sex, isLogin: joinJASONObject.isLogin}
            }).success(function( data ) {
                if(data == 0){
                    alert("이미지 합성이 실패하였습니다. 관리자에게 문의하세요");
                }else if(data == 1){
                    alert("이미지에 텍스트쓰기가 실패하였습니다. 다시 시도해보세요");
                    $("#loding_to_pop_up").bPopup().close();
                }else{
                    /*$('#result').empty().append("<img src='images/"+data+"' />");*/
                    joinJASONObject.result_img = data;
                    joinJASONObject.progress = 3;
                    setTimeout("popupOpen()",1000*3);
                }
                //alert(data);
            }).fail(function(response){

            });
        }

        // 로딩중 팝업
        function loding_bpopup()
        {
            $("#loding_to_pop_up").bPopup({
                modalColor: '#FFF',
                opacity : 1,
                closeClass:'b-close',
                modalClose: false,
                transitionClose: 'fadeIn',
                speed: 250,
                zIndex: 9000,
                position :['auto',0],
                //follow: [false, false],
                positionStyle : 'absolute',
                //modal: false
            });
        }
    </script>
    <p><img src="q4_title.jpg" alt="" style="width:100%;"/></p>
    <div class="step4_contents">
        <div id="targetBox">
            <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/<?=$file_v_name?>" id="target" alt="[Jcrop Example]" />
        </div>
    </div>
    <p style="clear:both"><a href="#" id="release"><img src="complete_btn.jpg" alt="완료" style="width:100%;" /></a></p>
    <p><img src="step4_bar.jpg" alt="" style="width:100%;"/></p>
    <!-- 로딩중 팝업 -->
    <div id="loding_to_pop_up" style="display:none;width: 100%;">
        <div class="popup_contents">
            <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/loding<?=rand(1, 3)?>.gif" style="width:100%;"/>
        </div>
    </div>
<!--구글추적코드-->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-51671118-1', 'greaten.co.kr');
ga('send', 'pageview');

</script>

   
</body>
</html>