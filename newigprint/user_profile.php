<?php

// Hashtag curl -l https://www.instagram.com/explore/tags/".$tag."/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'

// User Profile curl -l https://www.instagram.com/p/BND0jOHA_MO/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'


$user_sharedData = trim(shell_exec("curl -l https://www.instagram.com/p/BMtgD6FD70p/ |awk -F'window._sharedData = ' '{print $2}' | awk -F';</script>' '{print $1}'"));

//print_r($sharedData);
$user_data = json_decode($user_sharedData,1);
//print_r($user_data);
//exit;

$username = $user_data['entry_data']['PostPage'][0]['media']['owner']['username'];
$profile_pic_url = $user_data['entry_data']['PostPage'][0]['media']['owner']['profile_pic_url'];

$location = $user_data['entry_data']['PostPage'][0]['media']['location']['name'];

echo "$username : $location : $profile_pic_url \n";
	

?>