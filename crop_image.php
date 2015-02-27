<?php
/*
*	1. 사용자의 이미지를 
*	사용자가 지정한 위치와 사이즈로 크롭한다.
*/
$upload_img = $_REQUEST['upload_img'];

correctImageOrientation($upload_img);

$dst_x = 0;   //기준점 X좌표 
$dst_y = 0;   //기준점 Y좌표
$src_x = $_REQUEST['x']; //자르기 시작할 점의 X좌표
$src_y = $_REQUEST['y']; //자르기 시작할 점의 Y좌표
$dst_w = 640;//$_REQUEST['w']; //최종 이미지의 가로크기
$dst_h = 800; //최종 이미지의 세로크기
$src_w = $_REQUEST['x2']-$src_x; // 자를 영역의 가로크기
$src_h = $_REQUEST['y2']-$src_y; // 자를 영역의 세로크기
$canvas_w = $_REQUEST['canvas_w']; 
$canvas_h = $_REQUEST['canvas_h']; 
//echo 'dst_x:'.$dst_x.', dst_y:'.$dst_y.', src_x:'.$src_x.', src_y:'.$src_y.', dst_w:'.$dst_w.', dst_h:'.$dst_h.', src_w:'.$src_w.', src_h:'.$src_h.', canvas_w:'.$canvas_w,', canvas_h:'.$canvas_h;

//이미지 파일의 실제 사이즈
$src_image_size = getimagesize($upload_img);
$real_width = $src_image_size[0];
$real_height = $src_image_size[1];

//크롭 화면의 캔버스크기와 실제 파일 사이즈가 다를경우 비율계산하여 정확하게 자르기위해 설정
if($canvas_w != $real_width){
	$ratio_w = $real_width/$canvas_w;
	$ratio_h = $real_height/$canvas_h;
	$src_x = $src_x*$ratio_w;
	$src_y = $src_y*$ratio_h;
	$src_w = $src_w*$ratio_w;
	$src_h = $src_h*$ratio_h;
}
//echo '<br />'.$real_width.'*'.$real_height;
//echo '<br />dst_x:'.$dst_x.', dst_y:'.$dst_y.', src_x:'.$src_x.', src_y:'.$src_y.', dst_w:'.$dst_w.', dst_h:'.$dst_h.', src_w:'.$src_w.', src_h:'.$src_h.', canvas_w:'.$canvas_w,', canvas_h:'.$canvas_h;
// 실제이미지와 Merge될 이미지 만든다. 그림을 예를 들면 캔버스라고 생각하면 된다.
$dst_image = imagecreatetruecolor($dst_w,$dst_h);

// 실제이미지
$src_image = imagecreatefromjpeg($upload_img);

//print_r('ratio_w:'.$ratio_w);print_r(' ratio_h:'.$ratio_h);print_r(' dst_x:'.$dst_x);print_r(' dst_y:'.$dst_y);print_r(' src_x:'.$src_x);print_r(' src_y:'.$src_y);print_r(' dst_w:'.$dst_w);print_r(' dst_h:'.$dst_h);print_r(' src_w:'.$src_w);print_r(' src_h:'.$src_h);

// 실제이미지를 지정된 사이즈로 잘라 캔버스에($dst_image)에 넣는다.
$result = imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

// Saving 
$date_time = date("Y-m-d_H:i:s");
$file_name = "my_baby_is_famous_crop_".$date_time.'.jpg';
//$dst_image를 이미지 파일로 저장한다.
imagejpeg($dst_image, "images/".$file_name);
//임시 파일 삭제
imagedestroy( $dst_image );
imagedestroy( $src_image );

// 저장이 실패할 경우(파일존재 검사) 0을 리턴하고 성공한 경우 파일이름을 리턴한다.
if(is_file('images/'.$file_name)==true && $result){
	echo $file_name;
}else{
	echo 0;
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