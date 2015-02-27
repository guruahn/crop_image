<?php
/**
 * Reply App
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
require_once _TDC_LIB_PATH_ . '/data.reply.php';

/*
*댓글 Reply 세팅
*/

$reply = new Reply(getURLOptions(),$db);
$reply->pageinfo['scale'] = 5;
$reply_list = $reply->getList($_POST['sn']);
?>

<div class="reply_list">
	<ul>
	<?
	if($reply_list)
	{
		$i=1;
		foreach($reply_list AS $row)
		{
			$reply->setReply($row);

			?>
			<div class="reply">
				<p class="thumbnail"><img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/<?=$reply->sn_attachment_file?>" width="100%"/></p>
				<div class="replay_text">
					<p class="nickname"><?=$reply->nick_members?></p>
					<p class="date"><?=$reply->date_add_f?></p>
					<p class="story"><?=nl2br(trim($reply->story))?></p>
				</div>
				
				<?
				//로그인 사용자의 글인 경우
				if( $reply->sn_members == $_SESSION['user']['sn'] )
				{
					?>	<div class="edit_text">
							<a href="#" title="수정" class="modify_reply" data-sn="<?=$reply->sn?>"><img src="/images/common/modify_reply_btn.jpg" alt="수정"/> </a>
							<a href="#" title="삭제" class="del_reply" data-sn="<?=$reply->sn?>"><img src="/images/common/del_reply_btn.jpg" alt="삭제"/></a>
							<?if($reply->sn_attachment_file){?>
							<div class="share">
								<span>
									<button class="kakao-link-btn" id="kakao-link-btn-<?=$i?>" data-href="" data-img="<?=$reply->sn_attachment_file?>">
							            <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/kakao_btn.png" />
							        </button>
						        </span>
						        <span>
						            <button class="kakao-story-link-btn" id="kakao-story-link-btn-<?=$i?>" data-href="" data-img="<?=$reply->sn_attachment_file?>">
						                <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/kas_btn.png" />
						            </button>
						        </span>
						        <span>
						            <a href="http://www.facebook.com/sharer/sharer.php?u=http://nuby.greaten.co.kr/app/event/event_view_promotion3.php?sn=646&result_img=<?=$result_img?>" data-img="<?=$reply->sn_attachment_file?>" data-short="" target="_blank" class="facebook-link-btn">
						                <img src="http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/facebook_btn.png" />
						            </a>
						        </span>
							</div>
							<?}?>
						</div>
					<?
				}
				//좋아요 기능 사용 여부(is_like)가 Y 인 경우
				if($_POST['is_like'] == 'Y')
				{
					?>
						<div class="like_box">
							<span><a href="#" class="like_add_reply" data-replysn="<?=$reply->sn?>">좋아요</a></span>
							<span><?=$reply->ct_like?></span>
						</div>
					<?
				}
				?>

			</div><!--//.reply-->
			<?
			$i++;
		}
	}
	?>
	</ul>
	<div class="paging">
		
		<?php
		if ($reply->pageinfo['total'] > 0) { 
			pagination_frontend_jquery($reply->pageinfo['total'],$reply->pageinfo['page'],$reply->pageinfo['scale'], 5);
		}
		?>
	</div>
</div>

<script>
	
	



    function executeKakaoTalkLink(obj)
    {
        Kakao.Link.createTalkLinkButton({
	        container: '#'+$(obj).attr('id'),
	        label: '[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.',
	        image: {
	            src: 'http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/'+$(obj).attr('data-img'),
	            width: '320',
	            height: '400'
	        },
	        webButton: {
	        text: '누비 클릭잇컵 이벤트',
	        url: $(obj).attr('data-href') // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
	        }
	    });
    }

    function executeKakaoStoryLink(obj)
    {
        kakao.link("story").send({   
            post : "[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이가 잡지모델로 만들어지는 재밌는 경험을 해보세요.\n"+$(obj).attr('data-href'),
            appid : "mnuby.greaten.co.kr",
            appver : "1.0",
            appname : "Nuby.co.kr",
            urlinfo : JSON.stringify({title:"[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.", desc:"[누비 클릭잇컵 런칭 기념 이벤트] 우리 아이를 잡지모델로 만들어보세요.", imageurl:['http://mnuby.greaten.co.kr/app/event/feature_event/nuby_promotion_20140616/images/'+$(obj).attr('data-img')], type:"article"})
        });
    }

    $(function(){
    	$('.kakao-story-link-btn').click(function(){
	    	executeKakaoStoryLink($(this));
	    });

		//페이스북 공유 링크 주소 줄이기
		var url = "";
		
		$('.facebook-link-btn').each(function(){
			//페이스북 링크 만들기
			var image = $(this).attr('data-img');

			image = image.replace("my_baby_is_famous_text_", "");
			image = image.replace(".jpg", "");
			url = "http://nuby.co.kr/app/event/event_view_promotion3.php?result_img="+image+"&sn=646";
			makeShortUrl(url, $(this), function(result, obj){

				var FB_shareUrl = "http://www.facebook.com/sharer/sharer.php?u="+result;
				$(obj).attr('href',FB_shareUrl); 
				$(obj).attr('data-short',result); 
				makeKakaoLink($(obj).parent().parent().find('.kakao-link-btn'), result);
			});
		});
		$('.kakao-link-btn').each(function(){
			
		});
    });
	function makeKakaoLink(obj, shortUrl){
		//카카오톡 링크 만들기
		var image = $(obj).attr('data-img');
		image = image.replace("my_baby_is_famous_text_", "");
		image = image.replace(".jpg", "");
		url = "http://nuby.co.kr/app/event/event_view_promotion3.php?result_img="+image+"&sn=646";
		shortUrl = $(obj).parent().parent().find('.facebook-link-btn').attr('data-short');
		$(obj).attr('data-href',shortUrl); 
		$(obj).parent().parent().find('.kakao-story-link-btn').attr('data-href',shortUrl); 
		executeKakaoTalkLink($(obj));
	}
    function makeShortUrl(url, obj, callback) {
    	var longUrl = url;
	    var bit = {
	        version: "2.0.1",
	        login: "o_3hgu0knjqb",
	        apiKey: "R_6bf2876c11a49ca562db614fc367983a",
	        longUrl: longUrl
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
					if( typeof callback == "function") callback(result, obj);
	            }
	        }
	    );
	}
</script>