<?php
require('lib/config.php');
$cookieData		= explode('|', file_get_contents('./data/'.$cookieFile));
$cookie 		= $cookieData[0]; // Cookie Instagram
$useragent 		= $cookieData[1]; // Useragent Instagram
$loop			= true;
//feed/user/{$userId}/story/
echo "
 _____ _                   _                       
/  ___| |                 | |                      
\ `--.| |_ ___  _ __ _   _| |     ___   ___  _ __  
 `--. \ __/ _ \| '__| | | | |    / _ \ / _ \| '_ \ 
/\__/ / || (_) | |  | |_| | |___| (_) | (_) | |_) |
\____/ \__\___/|_|   \__, \_____/\___/ \___/| .__/ 
                      __/ |                 | |    
                     |___/                  |_|    \n";
echo "[-] ============ Auto Views Story ============ [-]\n";
echo "[-] =========== Made by @theaxe.id =========== [-]\n\n";
echo "[?] Input your API Key : ";
$apikey 	= trim(fgets(STDIN, 1024));
$check_Api	= basic_cURL($apihost.'/api/v3/checkUser?apikey='.$apikey);
$check_Api	= json_decode($check_Api[1], true);
if($check_Api['status'] == 'ok'){
	//Get Account
	$get_Acc 	= basic_cURL($apihost.'/api/v3/listInstagram?apikey='.$apikey);
	$get_Acc 	= json_decode($get_Acc[1], true);
	if($get_Acc['status'] == 'error'){ echo "\nUndefined error: ".$get_Acc['message']."\n"; exit(); }
	if($get_Acc['data'] == null){ echo "\n[~] Please add instagram account before you run this tools\n"; echo "[!] Exit application...\n"; exit(); }
	$count_Acc	= count($get_Acc['data']);
	echo "\n[~] Get ".$count_Acc." instagram account\n";
	for($i=0; $i < $count_Acc; $i++){ echo "[".$i."] @".$get_Acc['data'][$i]['username']."\n"; }
	echo "[?] Input account number : ";
	$acc_Get 	= trim(fgets(STDIN, 1024));
	if(!is_numeric($acc_Get)){ echo "\n[!] Invalid input\n"; echo "[!] Exit application...\n"; exit(); }
	if($get_Acc['data'][$acc_Get] == null){ echo "\n[!] Invalid number account"; echo "[!] Exit application...\n"; exit(); }
	//Get Setting
	$get_Set 	= basic_cURL($apihost.'/api/v3/listStoryloop?apikey='.$apikey);
	$get_Set 	= json_decode($get_Set[1], true);
	if($get_Set['status'] == 'error'){ echo "\nUndefined error: ".$get_Set['message']."\n"; exit(); }
	if($get_Set['data'] == null){ echo "\n[~] Please add storyloop setting before you run this tools\n"; echo "[!] Exit application...\n"; exit(); }
	$count_Set	= count($get_Set['data']);
	echo "\n[~] Get ".$count_Set." setting preset\n";
	for($i=0; $i < $count_Set; $i++){ echo "[".$i."] ".$get_Set['data'][$i]['name']."\n"; }
	echo "[?] Input setting number : ";
	$set_Get 	= trim(fgets(STDIN, 1024));
	if(!is_numeric($set_Get)){ echo "\n[!] Invalid input\n"; echo "[!] Exit application...\n"; exit(); }
	if($get_Set['data'][$set_Get] == null){ echo "\n[!] Invalid number account\n"; echo "[!] Exit application...\n"; exit(); }
	//Show Account & Setting
	$cookie 	= $get_Acc['data'][$acc_Get]['cookies']; 	// Cookie Instagram
	$useragent 	= $get_Acc['data'][$acc_Get]['useragent'];	// Useragent Instagram
	$target 	= $get_Set['data'][$set_Get]['target'];
	$target 	= json_decode($target, true);
	$answer 	= $get_Set['data'][$set_Get]['answer'];
	$answer 	= json_decode($answer, true);
	$sleep_1	= $get_Set['data'][$set_Get]['sleep_1'];
	$sleep_2	= $get_Set['data'][$set_Get]['sleep_2'];
	$jumlah 	= $get_Set['data'][$set_Get]['target_count'];
	$getakun	= proccess(1, $useragent, 'accounts/current_user/', $cookie);
	$getakun	= json_decode($getakun[1], true);
	if($getakun['status'] == 'ok'){
		//LOSS
		$getakunV2	= proccess(1, $useragent, 'users/'.$getakun['user']['pk'].'/info', $cookie);
		$getakunV2	= json_decode($getakunV2[1], true);
		echo "\n[~] Login as @".$getakun['user']['username']." \n";
		echo "[~] [Media : ".$getakunV2['user']['media_count']."] [Follower : ".$getakunV2['user']['follower_count']."] [Following : ".$getakunV2['user']['following_count']."]\n";
		echo "[~] Please wait 5 second for loading script\n";
		echo "[~] "; for($x = 0; $x <= 4; $x++){ echo "========"; sleep(1); } echo "\n\n";
		do {
			foreach($target as $target){
				//Files
				$dailyFile	= date('d-m-Y').'-'.$getakun['user']['pk'].'.txt';
				$reactFile	= 'storyData-'.$getakun['user']['pk'].'.txt';
				//Daily react count
				$todays		= file_get_contents('./data/daily/'.$dailyFile);
				$today		= explode("\n", str_replace("\r", "", $todays));
				$today		= array($today)[0];
				//Proxy
				//$proxy		= file_get_contents('https://veonpanel.com/api/panel/proxy?key=MEMEF');
				//$proxy		= json_decode($proxy, true);
				//$prox['ip']			= $proxy['data']['proxy'];
				$prox['ip']			= 0;
				$prox['user']		= 0;
				$prox['is_socks5']	= 0;
				//
				echo "[~] Get followers of ".$target."\n";
				//echo "[~] Proxy ".$prox['ip']."\n";
				$targetid	= json_decode(request(1, $useragent, 'users/'.$target.'/usernameinfo/', $cookie, 0, array(), $prox['ip'], $prox['user'], $prox['is_socks5'])[1], 1)['user']['pk'];
				$gettarget	= proccess(1, $useragent, 'users/'.$targetid.'/info', $cookie, 0, array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
				$gettarget	= json_decode($gettarget[1], true);
				echo "[~] [Media : ".$gettarget['user']['media_count']."] [Follower : ".$gettarget['user']['follower_count']."] [Following : ".$gettarget['user']['following_count']."]\n";
				if(!is_numeric($jumlah)){
					$limit = 1;
				} elseif ($jumlah > ($gettarget['user']['follower_count'] - 1)){
					$limit = $gettarget['user']['follower_count'] - 1;
				} else {
					$limit = $jumlah - 1;
				}
				$next      	= false;
				$next_id    = 0;
				$listids	= array();
				do {
					if($next == true){ $parameters = '?max_id='.$next_id.''; } else { $parameters = ''; }
					$req        = proccess(1, $useragent, 'friendships/'.$targetid.'/followers/'.$parameters, $cookie, 0, array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
					$req        = json_decode($req[1], true);
					if($req['status'] !== 'ok'){
						echo "[!] User not found or your ip blocked maybe\n";
						echo "[!] Exit application...\n";
						var_dump($req);
						exit();
					}
					for($i = 0; $i < count($req['users']); $i++):
						if($req['users'][$i]['is_private'] == false):
							if($req['users'][$i]['latest_reel_media']):
								if(count($listids) <= $limit):
									$listids[count($listids)] = $req['users'][$i]['pk'];
								endif;
							endif;
						endif;
					endfor;
					if($req['next_max_id']){ $next = true; $next_id	= $req['next_max_id']; } else { $next = false; $next_id = '0'; }
				} while(count($listids) <= $limit);
				echo "[~] ".count($listids)." followers of ".$target." collected\n";
				$reels		= array();
				$reels_suc	= array();
				for($i = 0; $i < count($listids); $i++):
					$getstory   = proccess(1, $useragent, 'feed/user/'.$listids[$i].'/story/', $cookie, 0, array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
					$getstory   = json_decode($getstory[1], true);
					foreach($getstory['reel']['items'] as $storyitem):
						$reels[count($reels)]	= $storyitem['id']."_".$getstory['reel']['user']['pk'];
						$stories['id']			= $storyitem['id'];
						$stories['reels']		= $storyitem['id']."_".$getstory['reel']['user']['pk'];
						$stories['reel']		= $storyitem['taken_at'].'_'.time();
						if(strpos(file_get_contents('./data/'.$reactFile), $stories['reels']) == false){
							$hook       = '{"live_vods_skipped": {}, "nuxes_skipped": {}, "nuxes": {}, "reels": {"'.$stories['reels'].'": ["'.$stories['reel'].'"]}, "live_vods": {}, "reel_media_skipped": {}}';
							$viewstory  = proccess_v2(1, $useragent, 'media/seen/?reel=1&live_vod=0', $cookie, hook(''.$hook.''), array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
							$viewstory  = json_decode($viewstory[1], true);
							if($storyitem['story_polls']){
								$stories['pool_id']	= $storyitem['story_polls'][0]['poll_sticker']['poll_id'];
								$react_1	  		= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['pool_id'].'/story_poll_vote/', $cookie, hook('{"radio_type": "none", "vote": "'.rand(0,1).'"}'), array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
								$react_1			= json_decode($react_1[1], true);
								if($react_1['status'] == 'ok'){
									echo "[~] ".date('d-m-Y H:i:s')." - Success polling for ".$stories['id']."\n";
								}
								//echo "[Stories Polls True : ".$stories['pool_id']." : ".$react_1[1]."] ";
							}
							if($storyitem['story_questions']){
								$stories['question_id']	= $storyitem['story_questions'][0]['question_sticker']['question_id'];
						        $textAnswer 			= $answer[array_rand($answer)];
								$react_2	  			= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['question_id'].'/story_question_response/', $cookie, hook('{"response": "'.$textAnswer.'", "type": "text"}'), array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
								$react_2				= json_decode($react_2[1], true);
								if($react_2['status'] == 'ok'){
									echo "[~] ".date('d-m-Y H:i:s')." - Question answer for ".$stories['id']." : ".$textAnswer." \n";
								}
								//echo "[Stories Question True : ".$stories['question_id']." : ".$react_2[1]."] ";
							}
							if($storyitem['story_countdowns']){
								$stories['countdown_id']	= $storyitem['story_countdowns'][0]['countdown_sticker']['countdown_id'];
								$react_3	  				= proccess(1, $useragent, 'media/'.$stories['countdown_id'].'/follow_story_countdown/', $cookie, 0, array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
								$react_3					= json_decode($react_3[1], true);
								//echo "[Stories Countdown True : ".$stories['countdown_id']." : ".$react_3[1]."] ";
							}
							if($storyitem['story_sliders']){
								$stories['slider_id']	= $storyitem['story_sliders'][0]['slider_sticker']['slider_id'];
								$react_4	  			= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['slider_id'].'/story_slider_vote/', $cookie, hook('{"radio_type": "wifi-none", "vote": "1"}'), array(), $prox['ip'], $prox['user'], $prox['is_socks5']);
								$react_4				= json_decode($react_4[1], true);
								if($react_2['status'] == 'ok'){
									echo "[~] ".date('d-m-Y H:i:s')." - Success sent slider for ".$stories['id']."\n";
								}
								//echo "[Stories Slider True : ".$stories['slider_id']." : ".$react_4[1]."] ";
							}
							if($storyitem['story_quizs']){
								$stories['quiz_id']	= $storyitem['story_quizs'][0]['quiz_sticker']['quiz_id'];
								//$react_5	  		= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['quiz_id'].'/story_poll_vote/', $cookie, hook('{"radio_type": "none", "vote": "'.rand(0,3).'"}'));
								//echo "[Stories Quiz True : ".$stories['quiz_id']." : ".$react_5[1]."] ";
							}
							if($viewstory['status'] == 'ok'){
								$reels_suc[count($reels_suc)] = $storyitem['id']."_".$getstory['reel']['user']['pk'];
								echo "[~] ".date('d-m-Y H:i:s')." - Seen stories ".$stories['id']." \n";
								saveData('./data/'.$reactFile, $stories['reels']);
								saveData('./data/daily/'.$dailyFile, $stories['reels']);
							}
							sleep($sleep_1);
						}
					endforeach;
					echo "[~] ".date('d-m-Y H:i:s')." - Sleep for ".$sleep_2." second to bypass instagram limit\n"; sleep($sleep_2);
				endfor;
				echo "[~] ".count($reels)." story from ".$target." collected\n";
				echo "[~] ".count($reels_suc)." story from ".$target." marked as seen\n";
				echo "[~] ".count($today)." story reacted today\n";
				echo "[~] ".date('d-m-Y H:i:s')." - Sleep for 30 second to bypass instagram limit\n";
				echo "[~] "; for($x = 0; $x <= 4; $x++){ echo "========"; sleep(6); } echo "\n\n";
			}
			if(count($today) > '1900'){
				echo "[~] ".count($today)." story reacted today\n";
				echo "[~] Limit instagram api 2000 seen/day\n";
				echo "[~] Sleep for 20 hours to bypass instagram limit\n";
				sleep(72000);
				echo "[~] End sleep...\n\n";
			}
		} while($loop == true);
	} else {
		echo "[!] Error : ".json_encode($getakun)."\n";
	}
} else {
	echo "\n[!] Undefined error: ".$check_Api['message']."\n";
	echo "[!] Exit application...\n";
}
?>