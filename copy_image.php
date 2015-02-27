<?php
/*
*	2. 크롭한 사용자의 이미지위에 
*	타임지 표지 틀을 씌운다.
*/
$crop_img = $_REQUEST['crop_img'];

//캔버스 이미지를 만든다.
$dst = imagecreatefromjpeg( 'images/'.$crop_img );
//캔버스에 덮어씌울 이미지를 만든다.
$src = imagecreatefrompng( 'nuby_promotion_frame'.rand(1, 3).'.png' );

/*imagealphablending($dst, false);
imagesavealpha($dst, true);

imagealphablending($src, true);*/
//캔버스에 이미지를 덮어씌운다.
$result = imagecopyresampled( $dst, $src, 0, 0, 0, 0, 640, 800, 640, 800 );

header( 'Content-type: image/jpeg' );
//합쳐진 이미지를 파일로 저장한다.
$date_time = date("Y-m-d_H:i:s");
$file_name = "my_baby_is_famous_copy_".$date_time.'.jpg';
imagejpeg( $dst, "images/".$file_name );
//임시 파일 삭제
imagedestroy( $src );
imagedestroy( $dst );

// 저장이 실패할 경우(파일존재 검사) 0을 리턴하고 성공한 경우 파일이름을 리턴한다.
if(is_file('images/'.$file_name)==true && $result){
	echo $file_name;
}else{
	echo 0;
}
?>