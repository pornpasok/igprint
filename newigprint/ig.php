<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok'); 
error_reporting(E_ALL & ~E_NOTICE);

print_r($argv);
//exit;

$tag = $_GET['tag'];
$location = $_GET['location'];

if ($tag == "") {
	$tag = $argv[1];
}
else {
	$tag = $tag;
}

if ($location == "") {
	$location = $argv[2];
}
else {
	$location = $location;
}

echo "$tag : $location \n";

$frame_img = "frame_white_cp910.jpg";
//$frame_img = "frame_pink.jpg";
//$footer_img = "footer/".$tag.".jpg";
$footer_img = "footer/".$tag.".jpg";
#$font = 'Fonts/GeosansLight.ttf';
//$font = 'fonts/ThaiSansNeue-SemiBold.ttf';
//$font = 'Fonts/Kunlasatri.ttf';
//$font = 'Fonts/Circular.ttf';
$font = 'fonts/supermarket.ttf';

$org_img = "tag";
$user_profile = "user_profile";
$img_cover = "img_cover";

# Assign API
if ($location=="") {
	//$api = "https://api.instagram.com/v1/tags/".$tag."/media/recent?client_id=".$client;
	//$api = "https://api.instagram.com/v1/tags/".$tag."/media/recent?access_token=".$access_token;  
	$dir_tag = $org_img."/".$tag;
}
else {
	//$api = "https://api.instagram.com/v1/locations/".$location."/media/recent?client_id=".$client;
	//$api = "https://api.instagram.com/v1/locations/".$location."/media/recent?access_token=".$access_token;
	$dir_tag = $org_img."/".$location;
}

# Create Dir
$dir_tag_user = $dir_tag."/".$user_profile;
$dir_img_cover = $dir_tag."/".$img_cover;

if (is_dir($dir_tag)) {
	echo "Found Dir: $dir_tag \n";
}
else {
	mkdir($dir_tag, 0777);
	echo "Create Dir: $dir_tag Complete \n";
	
	# User Profile
	mkdir($dir_tag_user, 0777);
	echo "Create Dir: $dir_tag_user Complete \n";
	
	# User Profile
	mkdir($dir_img_cover, 0777);
	echo "Create Dir: $dir_img_cover Complete \n";
}


//curl -l https://www.instagram.com/explore/tags/japan/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'

# Count Images
$i = 0; 
$i = count(glob($dir_tag."/"."*.jpg"));
//$num_files = count(glob('$dir_tag/*.jpg'));
//echo "$i <br>\n";

$j=0;

$count_i = 0; // Total Images


while (1) {

	$sharedData = trim(shell_exec("curl --cookie cookies_ig.txt -l https://www.instagram.com/explore/tags/".$tag."/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'"));

	//print_r($sharedData);
	//exit;

	//$data = json_decode($sharedData);

	//print_r($data);

	//print_r($sharedData);
	//exit;

	// 2018-02-04
	// entry_data->TagPage->0->graphql->hashtag->edge_hashtag_to_media
	// count = total images
	// edges = images data [0..xxx]
	// node 
	// thumbnail_src = URL images [0..xxx] 0 = lastest

	$sharedData = json_decode($sharedData,1);
	//print_r($sharedData);

	$count = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['count'];

	echo "Count: $count\n";

	# Get and Gen Images
	for ($count_i=0; $count_i<$count; $count_i++) {

		// Images
		$thumbnail_src = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'][$count_i]['node']['thumbnail_src'];
		$src = explode("?", $thumbnail_src);
		$src = $src[0];

		// Taken Time
		$taken_at_timestamp = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'][$count_i]['node']['taken_at_timestamp'];
		$created_time = date('d.m.Y H:i', $taken_at_timestamp);

		// Caption Text
		$text = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'][$count_i]['node']['edge_media_to_caption']['edges'][0]['node']['text'];
		$caption_text = $text;

		$code = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'][$count_i]['node']['shortcode'];

		// UserID
		$user_id = $sharedData['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'][$count_i]['node']['owner']['id'];

	
		# Create Original IG Images
		$check_img = $dir_img_cover."/".md5($src).".jpg"; // Check 
		//echo "$i : $src\n";

		if (!file_exists($check_img)) {  // Not Found IG Images
			$i++;
			$j++;

			echo "$i : $src\n";
										
			# Name from $i
			$org_ig_name = $dir_tag."/".$i.".jpg";
			$org_ig_url = imagecreatefromstring(file_get_contents($src));
			imagejpeg($org_ig_url, $org_ig_name);
			echo "Not Found: $org_ig_name Create Complete \n";
											
			# Create Profile User
			$org_user_name = $dir_tag_user."/".$user_id.".jpg";
			
			if ($code !="") {
				// Get User Data
				# User Profile
				$user_sharedData = trim(shell_exec("curl -l https://www.instagram.com/p/".$code."/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'"));

				//print_r($user_sharedData);
				$user_data = json_decode($user_sharedData,1);

				$username = $user_data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['owner']['username'];
				$profile_pic_url = $user_data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['owner']['profile_pic_url'];
				$location_name = $user_data['entry_data']['PostPage'][0]['graphql']['shortcode_media']['location']['name'];
				echo "[$count_i]: $thumbnail_src : $text : $taken_at_timestamp : $code\n";
				echo "$username : $location_name : $profile_pic_url \n";

			}
									
			if (!file_exists($org_user_name)) {  // Not Found IG Images
				$org_user_url = imagecreatefromstring(file_get_contents($profile_pic_url));
				imagejpeg($org_user_url, $org_user_name);
				echo "Not Found: $org_user_name Create Complete \n";
			}
			else { // Found IG Images
				echo "Found: $org_user_name \n";
			}
											
			### Create Cover Images same IG ###
			# Create Instagram Frame
			$frame=imagecreatefromstring(file_get_contents($frame_img));                  // Clone new frame
			# Insert Instagram Images
			$realImageArray = imagecreatefromstring(file_get_contents($org_ig_name));
			
			imagecopymerge($frame, $realImageArray, 80, 180, 0, 0, 640, 640, 100); //Params =(background img, img upper, BG x, BG y, Upper X-Y-W-H, alpha)
									
			# Insert Avatar
			$avatar_img = imagecreatefromstring(file_get_contents($org_user_name)); 
			$resize_image = imagecreatetruecolor(60, 60);
			imagecopyresampled($resize_image, $avatar_img, 0, 0, 0, 0, 60, 60, 150, 150);
			$avatar_img = $resize_image;								 
			imagecopymerge($frame, $avatar_img, 80, 90, 0, 0, 60, 60, 100);
									
			# Insert Username
			$username_img = imagecreatetruecolor(390, 30);
			# Color
			$white = imagecolorallocate($username_img, 255, 255, 255);
			$pink = imagecolorallocate($username_img, 202, 100, 100);
			//$pink = imagecolorallocate($username_img, 255, 255, 255);
			$grey = imagecolorallocate($username_img, 128, 128, 128);
			$black = imagecolorallocate($username_img, 0, 0, 0);
			$blue = imagecolorallocate($username_img, 54, 35, 147);

			//imagefilledrectangle($username_img, 0, 0, 390, 30, $white);
			imagefilledrectangle($username_img, 0, 0, 390, 30, $white);
			//$text = $username." ".$full_name;
			$text = $username;
			//imagettftext($username_img, 14, 0, 11, 21, $white, $font, $text); // Add some shadow to the text. IMG - SIZE -ANGLE - X - Y - COLOR- FONT - CONTENT
			imagettftext($username_img, 22, 0, 11, 21, $black, $font, $text); // Add the text
			imagecopymerge($frame, $username_img, 150, 90, 0, 0, 390, 30, 100);
									
			# Insert Location
			$location_img=imagecreatetruecolor(580, 30);
			//imagefilledrectangle($location_img, 0, 0, 580, 30, $white);
			imagefilledrectangle($location_img, 0, 0, 580, 30, $white);
			$location=$location_name;
			//imagettftext($location_img, 22, 0, 11, 21, $blue, $font, $location); // Add the text
			imagettftext($location_img, 20, 0, 11, 23, $blue, $font, $location); // Add the text
			imagecopymerge($frame, $location_img, 150, 122, 0, 0, 580, 30, 100);
									
			# Insert Date Time
			$time_img=imagecreatetruecolor(300, 30);
			//imagefilledrectangle($time_img, 0, 0, 300, 30, $white);
			imagefilledrectangle($time_img, 0, 0, 300, 30, $white);
			$time=$created_time;
			imagettftext($time_img, 22, 0, 11, 21, $grey, $font, $time); // Add the text
			imagecopymerge($frame, $time_img, 520, 90, 0, 0, 300, 30, 100);

			# Insert Text
			// Match Emoticons
			$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
			$clean_text = preg_replace($regexEmoticons, '', $caption_text);

			// Match Miscellaneous Symbols and Pictographs
			$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
			$clean_text = preg_replace($regexSymbols, '', $clean_text);

			// Match Transport And Map Symbols
			$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
			$clean_text = preg_replace($regexTransport, '', $clean_text);

			// Match Miscellaneous Symbols
			$regexMisc = '/[\x{2600}-\x{26FF}]/u';
			$clean_text = preg_replace($regexMisc, '', $clean_text);

			// Match Dingbats
			$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
			$clean_text = preg_replace($regexDingbats, '', $clean_text);
										
			// Clean Text
			$caption_text = $clean_text;

			$text_img=imagecreatetruecolor(640, 30);
			//imagefilledrectangle($text_img, 0, 0, 612, 30, $white);
			imagefilledrectangle($text_img, 0, 0, 640, 35, $white);
			imagettftext($text_img, 20, 0, 11, 23, $pink, $font, $caption_text); // Add the text
			imagecopymerge($frame, $text_img, 80, 850, 0, 0, 640, 30, 100);
			
			# Insert Footer
			$footer_pictures=imagecreatefromjpeg($footer_img);
			imagecopymerge($frame, $footer_pictures, 0, 880, 0, 0, 800, 181, 100);

			# Create Instagram Images
			//imagejpeg($org_ig_url, $org_ig_name);
			imagejpeg($frame, $dir_img_cover."/".md5($src).".jpg");
			//imagejpeg($org_ig_url, $dir_img_cover."/".md5($src).".jpg");
			//echo "<img src=".$dir_img_cover."/".md5($src).".jpg".">\n";
			$img_print = "/Users/ton/Sites/igprint/newigprint/".$dir_img_cover."/".md5($src).".jpg";
										
			# Print
			echo "Print: $img_print \n";

			//exec("lp -d Canon_CP910_2 $img_print");
			//exec("lp -d Canon_CP910 $img_print");
			/*
											
			if(($i%2)==1) {
				exec("lp -d Canon_CP910 $img_print");
			}
			else {
				exec("lp -d Canon_CP910_2 $img_print");
			}
			*/
		}
		else { // Found IG Images
			echo "Found: $check_img \n";
		}
		// End if

	}
	// End for
	//print_r($src);
	


# Sleep API
echo date('h:i:s') . "\n";
sleep(60);


}
// End while

echo "<br> Complete: $j Images \n";
echo "Total: $i Images \n";

?>
