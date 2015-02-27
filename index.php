<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>누비프로모션</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta http-equiv="Cache-Control" content="No-Cache" />
    <meta http-equiv="Expires" content="0" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="/app/event/feature_event/nuby_promotion_20140616/jquery.Jcrop.min.js"></script>
    <link rel="stylesheet" href="/app/event/feature_event/nuby_promotion_20140616/jquery.Jcrop.min.css" type="text/css">
    <style>
    body {font-size: 16px;}
        #target { height: 800px;}
        .mask_img { position: absolute; z-index: 300;}
        #release { width: 40%;height: 100px;font-size: 2em;}
    </style>

    <style type="text/css" media="screen and (max-width:720px)">
     .jcrop-handle{width: 22px !important; height: 22px !important;}
    </style>
</head>
<body>
    <script>

        "use stric"

        //크롭 좌표정보
        var coordJASONObject = {"x": "","y": "","x2": "","y2": "","w": "", "h":""}
        //이벤트 응모 정보
        var joinJASONObject = {"userid": "","crop_img": "","restult_img": "", "progress": 0}

        $(document).ready(function(){
            $('#release').click(function()
            {//전송버튼 클릭
                
                //이미지 크롭후 마스크함수 콜백 
                //console.log(printr_json(coordJASONObject));
                $.ajax({
                    type: "POST",
                    url: "/app/event/feature_event/nuby_promotion_20140616/crop_image.php",
                    data: { x: coordJASONObject.x, y: coordJASONObject.y, x2: coordJASONObject.x2, y2: coordJASONObject.y2, w: coordJASONObject.w, h: coordJASONObject.h, canvas_w: $('#targetBox>img').width(), canvas_h: $('#targetBox>img').height()}
                }).success(function( data ) {
                    if(data == 0){
                        alert("이미지 자르기가 실패하였습니다. 관리자에게 문의하세요");
                    }else{
                        $('#result').empty().append("<img src='images/"+data+"' />");
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

            // In this example, since Jcrop may be attached or detached
            // at the whim of the user, I've wrapped the call into a function
            initJcrop();

            
        });

        // The function is pretty simple
        function initJcrop()//{{{
        {
            // Invoke Jcrop in typical fashion
            $('#target').Jcrop({
                onChange:   showCoords,
                aspectRatio: 1/1.25,
                minSize: [ 300, 375 ]
            },function(){

                $('.jcrop-holder>div>div').prepend('<img class="mask_img" src="/app/event/feature_event/nuby_promotion_20140616/TimesMagazineMan.png" />');
                //$('.jcrop-holder>div').maskable({maskSrc: '/testApi/img/mask/result/mask.png' });  
            });

        };
        
        function showCoords(c)
        {
            //console.log(printr_json(c));
            $('.mask_img').width(c.w);
            $('.mask_img').height(c.h);
            coordJASONObject.x = c.x;
            coordJASONObject.y = c.y;
            coordJASONObject.x2 = c.x2;
            coordJASONObject.y2 = c.y2;
            coordJASONObject.w = c.w;
            coordJASONObject.h = c.h;
            var isMOBILE = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
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
                    $('#result2').empty().append("<img src='images/"+data+"' />");
                    joinJASONObject.restult_img = data;
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
                data: { restult_img: joinJASONObject.restult_img }
            }).success(function( data ) {
                if(data == 0){
                    alert("이미지 합성이 실패하였습니다. 관리자에게 문의하세요");
                }else if(data == 1){
                    alert("이미지에 텍스트쓰기가 실패하였습니다. 관리자에게 문의하세요");
                }else{
                    $('#result3').empty().append("<img src='images/"+data+"' />");
                    joinJASONObject.restult_img = data;
                    joinJASONObject.progress = 3;
                }
                console.log(data);
            }).fail(function(response){

            });
        }
    </script>
    
    <div id="targetBox">
        <img src="/app/event/feature_event/nuby_promotion_20140616/crying-baby.jpg" id="target" alt="[Jcrop Example]" />
    </div>
    <div style="margin: 1em auto">
        <span class="requiresjcrop">
            <button id="release" >만들기</button>
        </span>
    </div>
    <div id="result"></div>
    <div id="result2"></div>
    <div id="result3"></div>
    <?php 
       /* echo '<pre>'; 
        print_r(gd_info());
        echo '</pre>'; */
    ?>
</body>
</html>