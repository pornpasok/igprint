<?php

$instagram = array(
    'client_id' =>       '3ba410783dbb4dc792080fce05121318',
    'client_secret' =>  'a74829cd6dcb4cfdbe880fc06121b380',
    'website_url' =>    'http://localhost',
    'redirect_uri' =>   'http://localhost',
    'access_token' =>   'YOUR_ACCESS_TOKEN'
);

if (isset($_REQUEST['token'])){
    // get access token
    $checkin_url = "https://api.instagram.com/oauth/access_token";
    $parameters = array(
        'client_id' => $instagram['client_id'],
        'client_secret' => $instagram['client_secret'],
        'grant_type' => 'authorization_code',
        'redirect_uri' =>  $instagram['redirect_uri'],
        'token' => $_REQUEST['token']
    );
    $curl = curl_init($checkin_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    print($response);
} else {
    header("Location:https://api.instagram.com/oauth/authorize/?client_id=" . $instagram['client_id'] ."&response_type=code&redirect_uri=" . $instagram['redirect_uri'] . "&response_type=token");
}
?>
