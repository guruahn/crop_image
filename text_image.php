<?php

/*
*	3. 타임지가 씌워진 이미지에
*	광고 문구를 쓴다.
*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/event/feature_event/nuby_promotion_20140616/_array_text.php';

$copy_img = $_REQUEST['copy_img'];
$crop_img = $_REQUEST['crop_img'];
$baby_name = $_REQUEST['baby_name'];
$sex = $_REQUEST['sex'];
$isLogin = $_REQUEST['isLogin'];
// 고정 사이즈의 캔버스 이미지 생성
$dst_image = imagecreatetruecolor(640,800);

// 원본 이미지 생성
$src_image = imagecreatefromjpeg('images/'.$copy_img);

// 캔버스에 원본이미지 덮어씌움
$result1 = imagecopyresampled( $dst_image, $src_image, 0, 0, 0, 0, 640, 800, 640, 800 );

$nLeft= 45; // 문자열 시작 위치의 x 값
$nTop = 600; // 문자열 시작 위치의 y값 
$nFontSize =22;// 글씨 폰트  사이즈
$nAngle = 0; // 글씨가 나올 각도
$color = imagecolorallocate($dst_image, 128, 128, 128); // 글씨 색상
$white = imagecolorallocate($dst_image, 255, 255, 255);
$black = imagecolorallocate($dst_image, 0, 0, 0);
$font = './NANUMGOTHICBOLD.TTF'; // 폰트 파일의 위치


/*
글자 위치 세팅
*/
$randomText = $textArray[rand(1, count($textArray))];
krsort($randomText['left']);
//왼쪽 글 영역 쓰기

$i = 0;
foreach ($randomText['left'] as $key => $value) {
	$sStr = $value;
	$sStr = str_replace("ㅇㅇㅇㅇㅇ", $baby_name, $sStr);

	fontSettingAsType($randomText['type'], count($randomText['left']), $i+1);//스타일 세팅
	//echo "<br />";
    //echo "type:".$randomText['type'].", font-size:".$nFontSize.", top:".$nTop.", text:".$sStr;
	$bbox  = imagettfbbox($nFontSize, $nAngle, $font, $sStr);// 문자열의 사이즈를 가져온다
	//글씨에 보더 넣기
	//echo $value;
	imagettftextSp($dst_image, $nFontSize , $nAngle, $nLeft+1, $nTop, $white , $font, $sStr, 1);
	imagettftextSp($dst_image, $nFontSize , $nAngle, $nLeft, $nTop+1, $white , $font, $sStr, 1);
	imagettftextSp($dst_image, $nFontSize , $nAngle, $nLeft-1, $nTop, $white , $font, $sStr, 1);
	imagettftextSp($dst_image, $nFontSize , $nAngle, $nLeft, $nTop-1, $white , $font, $sStr, 1);
	//글씨쓰기
	$result2 = imagettftextSp($dst_image, $nFontSize , $nAngle, $nLeft, $nTop, $black , $font, $sStr, 1); // 이미지에 글씨를 그림
	$i++;
}

function imagettftextSp($image, $size, $angle, $x, $y, $color, $font, $text, $spacing)
{        
    $temp_x = $x;
    preg_match_all('/./u',$text,$letters); 
	//print_r($letters[0]); 
    for ($j = 0; $j < count($letters[0]); $j++)
    {
        $bbox = imagettftext($image, $size, $angle, $temp_x, $y, $color, $font, $letters[0][$j]);
		$temp_x += $spacing + ($bbox[2] - $bbox[0]);
    }
}

$nFontSize = 23;
$nTop = 740;
krsort($randomText['bottom']);
//아래쪽 글 영역 쓰기
foreach ($randomText['bottom'] as $key => $value) {
	$nTop = $nTop-(38);
	//printr(10*(int)$key);
	//echo "<br />";
	$sStr = $value;
	$sStr = str_replace("ㅇㅇㅇㅇㅇ", $baby_name, $sStr);
	//echo "<br />";
	//echo "<br />";
	$bbox  = imagettfbbox($nFontSize, $nAngle, $font, $sStr);// 문자열의 사이즈를 가져온다
	//글씨에 보더 넣기
	imagettftext($dst_image, $nFontSize , $nAngle, $nLeft+1, $nTop, $black , $font, $sStr);
	imagettftext($dst_image, $nFontSize , $nAngle, $nLeft, $nTop+1, $black , $font, $sStr);
	imagettftext($dst_image, $nFontSize , $nAngle, $nLeft-1, $nTop, $black , $font, $sStr);
	imagettftext($dst_image, $nFontSize , $nAngle, $nLeft, $nTop-1, $black , $font, $sStr);
	//글씨쓰기
	$result2 = imagettftext($dst_image, $nFontSize , $nAngle, $nLeft, $nTop, $white , $font, $sStr); // 이미지에 글씨를 그림
}

 
//print_r('nLeft:'.$nLeft);print_r(' nTop:'.$nTop);print_r(' nFontSize:'.$nFontSize);print_r(' nAngle:'.$nAngle);print_r(' color:'.$color);print_r(' font:'.$font);
// 이미지에 글자 쓰기


header( 'Content-type: image/jpeg' );

// 이미지 파일로 저장
$date_time = date("Ymd");
if($sex == "girl"){
	$sex = "0";
}else{
	$sex = "1";
}
if($isLogin == "Y"){
	$isLogin = "0";
}else{
	$isLogin = "1";
}
$file_name = "my_baby_is_famous_text_".$sex.$isLogin.rand(10000, 99999).$date_time.'.jpg';
imagejpeg( $dst_image, "images/".$file_name );
//임시 파일 삭제
imagedestroy( $src );
imagedestroy( $dst );

if($result1 == false){// 원본이미지 가져오기 실패
	echo 0;
}elseif($result2  == false){// 글자 쓰기 실패
	echo 1;
}else{// 모두 성공
	unlink('images/'.$copy_img);
	if($crop_img) unlink('images/'.$crop_img);
	echo $file_name;
}






/**
* 변수의 구성요소를 리턴받는다.
*/
function getPrintr($var, $title = null)
{
    $dump = '';
    $dump .=  '<div align="left">';
    $dump .=  '<pre style="background-color:#000; color:#00ff00; padding:5px; font-size:14px;">';
    if( $title )
    {
        $dump .=  "<strong style='color:#fff'>{$title} :</strong> \n";
    }
    $dump .= print_r($var, TRUE);
    $dump .=  '</pre>';
    $dump .=  '</div>';
    $dump .=  '<br />';
    return $dump;
}

/**
 * 변수의 구성요소를 출력한다.
 */
function printr($var, $title = null)
{
    $dump = getPrintr($var, $title);
    echo $dump;
}

/**
 * 변수의 구성요소를 출력하고 멈춘다.
 */
function printr2($var, $title = null)
{
    printr($var, $title);
    exit;
}

/**
 * 타입별 폰트 설정 변경
 */
function fontSettingAsType($type, $leftTotal, $no)
{
	global $nFontSize;
	global $nTop;
    if($type == "A"){
    	if( $no == $leftTotal ) {
			$nFontSize = 40;
			$nTop = $nTop-(50);
		}else{
			$nFontSize = 28;
			$nTop = $nTop-(41);
		}
    }else if($type == "B"){
    	if( $no == $leftTotal || $no == ($leftTotal-1) ) {
			$nFontSize = 40;
			$nTop = $nTop-(57);
		}else{
			$nFontSize = 28;
			$nTop = $nTop-(41);
		}
    }else{
    	$nFontSize = 40;
		$nTop = $nTop-(50);
    }

}
?>