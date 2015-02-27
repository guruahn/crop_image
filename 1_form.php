
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
	<title>Image Crop </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/app.css">


</head>
<body>
<div id="wrapper" class="small-12 medium-9 small-centered large-centered columns">
    <div class="off-canvas-wrap" data-offcanvas>
        <div class="inner-wrap">
            <nav class="tab-bar">
                <section class="left-small">
                    <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
                </section>

                <section class="middle tab-bar-section">
                    <h1 class="title">Image Crop</h1>
                </section>

                <section class="right-small">
                    <a class="right-off-canvas-toggle menu-icon" href="#"><span></span></a>
                </section>
            </nav>

            <aside class="left-off-canvas-menu">
                <ul class="off-canvas-list">
                    <li><label>Foundation</label></li>
                    <li><a href="#">The Psychohistorians</a></li>
                    <li><a href="#">...</a></li>
                </ul>
            </aside>

            <section class="main-section">
                <form id="infomation" name="infomation" action="2_process.php" enctype="multipart/form-data" method="POST" />
                    <fieldset>

                        <div class="step2">
                            <p>이미지에 넣을 텍스트</p>
                            <div class="step2_contents">
                                <input type="text" name="text" id="text" maxlength="5" placeholder="이미지에 넣을 텍스트를 입력해주세요.(최대5자)" >
                            </div>
                            <p style="clear:both"><a href="#" class="text"></a></p>
                        </div>
                        <div class="step3">
                            <p>사진업로드</p>
                            <div class="step3_contents">
                            </div>
                            <p class="file_upload">
                                <a href="#">
                                    <input type="file" name="file" id="file" class="file" data-width-limit="490" data-height-limit="620" capture>
                                </a>
                            </p>
                        </div>
                    </fieldset>
                </form>
            </section>

            <a class="exit-off-canvas"></a>
        </div>


    </div>

</div>


<script src="js/vendor/modernizr.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/foundation.min.js"></script>
<script type="text/javascript">

/*======
Use document ready or window load events
For example:
With jQuery: $(function() { ...code here... })
Or window.onload = function() { ...code here ...}
Or document.addEventListener('DOMContentLoaded', function(){ ...code here... }, false)
=======*/
$(document).foundation();
$(document).ready(function(){

	//파일업로드 시 파일 사이즈와 타입 체크
	$('.file').change(function(){
		imageSizeCheck($(this));
	});

});

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

</body>
</html>