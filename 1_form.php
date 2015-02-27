
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
	<title>NUBY 3차 프로모션 </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3.css">
	<link rel="stylesheet" media="screen and (max-width:720px)" href="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/promotion3_mobile.css" />
	<style type="text/css">
	label{}
	label img{}
	</style>
</head>
<body>
<form id="infomation" name="infomation" action="/app/event/feature_event/nuby_promotion_20140616/2_process.php" enctype="multipart/form-data" method="POST" />
	<fieldset>
	<div class="step1">
		<p><img src="q1_title.jpg" alt="우리 아니는 공주님인가요, 왕자님인가요?" style="width:100%;"/></p>
		<div class="step1_contents">
		<p class="p_image" style="overflow:hidden"><label for="girl"><img src="q1_contents_left.png" alt="" /></label>
			<label for="boy"><img src="q1_contents_right.png" alt="" /></label>
		</p>
		<p class="p_radio">
			<span style="margin-left:26.15384615384615%;">
			<input type="radio" name="sex" value="girl" id="girl">
			<input type="radio" name="sex" value="boy" id="boy" style="margin-left:38.23668639053254%;">
			</span>
		</p>
		</div>
		<p style="clear:both"><a href="#" class="sex"><img src="next_btn.jpg" alt="" style="width:100%;" /></a></p>
		<p><img src="step1_bar.jpg" alt="" style="width:100%;"/></p>
		
	</div>
	<div class="step2" style="display:none;">
		<p><img src="q2_title.jpg" alt="Q2. 우리 아이의 이름을 입력해주세요" style="width:100%;"/></p>
		<div class="step2_contents">
		<input type="text" name="name" id="name" maxlength="5" placeholder="이름을 입력해주세요.(최대5자)" >
		</div>
		<p style="clear:both"><a href="#" class="name"><img src="next_btn2.jpg" alt="" style="width:100%;"/></a></p>
		<p><img src="step2_bar.jpg" alt="" style="width:100%;"/></p>
	</div>
	<div class="step3" style="display:none;">
		<p><img src="q3_title.jpg" alt="Q3. 우리 아이의 사진을 업로드 해주세요" style="width:100%;"/></p>
		<div class="step3_contents">
		</div>
		<p class="file_upload">
			<a href="#">
				<input type="file" name="file" id="file" class="file" data-width-limit="490" data-height-limit="620" capture><img src="upload_btn.jpg" alt="" style="width:100%;"/>
			</a>
		</p>
		<p><img src="step3_bar.jpg" alt="" style="width:100%;"/></p>
	</div>
	</fieldset>
</form>
<script type="text/javascript">

/*======
Use document ready or window load events
For example:
With jQuery: $(function() { ...code here... })
Or window.onload = function() { ...code here ...}
Or document.addEventListener('DOMContentLoaded', function(){ ...code here... }, false)
=======*/
$(document).ready(function(){
	$('.sex').on('click', function(e){
		var checkbox = $("input[name='sex']:checked");
		if( checkbox.length > 0 ) {
		    $('.step1').hide();
		    $('.step2').fadeIn();
		    return false;
		} else {
		    alert('공주님인가요, 왕자님인가요?'); // error
		    return false;
		}
	    
    });
	$('.name').on('click', function(e){
		var obj = document.getElementsByName('name')[0];
		var inputname = obj.value.length;
		var chktext = /[ \{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\'\"\\\(\=]/gi;
		var result1 = true;
		var result2 = true;
		if(chktext.test(obj.value)){
			alert("특수문자는 입력하실 수 없습니다.");
			obj.value = "";
			obj.focus();
			result1 = false;
		}
		if(inputname>5 || inputname ==0){
			alert('이름을 입력해주세요.(입력가능한 이름의 길이는 1~5자 입니다.)');
			obj.focus();
		    result2 = false;
		}
		if(result1 && result2) {
			$('.step2').hide();
		    $('.step3').fadeIn();
			return false;
		}
		
	});
	$('.submit').on('click', function(e){
	});

	//파일업로드 시 파일 사이즈와 타입 체크
	$('.file').change(function(){
		imageSizeCheck($(this));
	});
	//엔터키 폼 전송 막기
	$("input:text").keydown(function(evt) {
        if (evt.keyCode == 13)
            return false;
    });
});


	function parentIframeResize()
	{
		var height = getParam('height');
		// This works as our parent's parent is on our domain..
		parent.resizeIframe(height);
	}

	// Helper function, parse param from request string
	function getParam( name )
	{
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if( results == null )
		  return "";
		else
		  return results[1];
	}
	/*
	 * uploadImageCheck 1.0
	 *
	 * ahn jeong woo, tendency
	 * Copyright 2013, MIT License
	 *
	 * @description
	 * obj는 input[type=file] 이어야 하고 ID가 부여되어 있어야 한다.
	 * 정해진 이미지 사이즈에 맞지 않거나 올바르지 않은 타입인지 검사한다.
	 * 정해진 이미지 사이즈는 해당 input[type=file]요소안에 data-width-limit와 data-height-limit로 정의해야한다.
	 * IE10미만에서는 작동하지 않는다.
	*/

	function imageSizeCheck(obj){
			
		var widthLimit, heightLimit, h, w, reader, image, title
		widthLimit = $(obj).attr('data-width-limit');
		heightLimit = $(obj).attr('data-height-limit');
		title = $(obj).attr('title');
		var result = 1;
		var ua = window.navigator.userAgent;
		if("" != $(obj).val()){
			if (ua.indexOf("MSIE") > -1) {//ie일때
				$('#infomation').submit();
			}else{
				imageFile = document.getElementById($(obj).attr('id')).files[0];
				reader = new FileReader();
				image  = new Image();
				reader.readAsDataURL(imageFile);

				reader.onload = function(_file) {

					image.src = _file.target.result;
					image.onload = function() {
						var w = this.width,
			   		h = this.height;
			   		//t = imageFile.type,n = imageFile.name,s = ~~(imageFile.size/1024) +'KB';
						if(w < widthLimit || h < heightLimit){
							alert("이미지 사이즈가 너무 작습니다. 더 큰 이미지를 업로드해주세요.");
							$(obj).val("");
							result = 0;
						}
						if( !(imageFile.type == "image/jpeg" || imageFile.type == "image/jpg") ){
							alert('jpg,jpeg 타입만 업로드 가능합니다. (업로드하신 파일타입: '+ imageFile.type+")");
							result = 0;
						}
						if(result == 1)	$('#infomation').submit();
			 		}
				  	image.onerror= function() {
				    	alert('적합하지 않은 파일타입니다.: '+ imageFile.type);
				    	result = 0;
					}
					
				};//end reader.onload
			}
		}//end if

	}//end function
</script>
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