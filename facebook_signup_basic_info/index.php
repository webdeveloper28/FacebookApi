<?php

/*
 * Include libraries
 */

include './lib/Facebook/FacebookSession.php';
include './lib/Facebook/FacebookRequest.php';
include './lib/Facebook/FacebookResponse.php';
include './lib/Facebook/FacebookSDKException.php';
include './lib/Facebook/FacebookRequestException.php';
include './lib/Facebook/FacebookRedirectLoginHelper.php';
include './lib/Facebook/FacebookAuthorizationException.php';
include './lib/Facebook/GraphObject.php';
include './lib/Facebook/GraphUser.php';
include './lib/Facebook/GraphSessionInfo.php';
include './lib/Facebook/Entities/AccessToken.php';
include './lib/Facebook/HttpClients/FacebookCurl.php';
include './lib/Facebook/HttpClients/FacebookHttpable.php';
include './lib/Facebook/HttpClients/FacebookCurlHttpClient.php';


/*
 * use name spaces
 */

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookCurl;

/*
 * Process
 */

// Start the session
session_start();

if(isset($_REQUEST['logout'])){
    unset($_SESSION['fb_token']);
}

// Use appid, secretid and redirect url
$app_id         = 'xxxxxxxxxxxx';
$app_secret     = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$redirect_url   = 'http://kindredimacademy.com/facebook_signup/';

// Initialize the application, create and object for facebook to get facebook session
FacebookSession::setDefaultApplication($app_id, $app_secret);

$helper = new FacebookRedirectLoginHelper($redirect_url);
$sess   = $helper->getSessionFromRedirect();

if(isset($_SESSION['fb_token'])){
    $sess = new FacebookSession($_SESSION['fb_token']);
}

//If session exist display the name
if(isset($sess)){
    // store the token
    $_SESSION['fb_token'] = $sess->getAccessToken();
    // Create request object, excute and get response
    $request = new FacebookRequest($sess,'GET','/me?locale=en_US&fields=first_name,last_name,name,email,gender');
    
    //from response get graph object
    $response = $request->execute();
    
    $graph  = $response->getGraphObject(GraphUser::classname());
    
    $first_name   = $graph->getProperty('first_name');
    $last_name   = $graph->getProperty('last_name');
    $name   = $graph->getProperty('name');
    $gender   = $graph->getProperty('gender');
    $id     = $graph->getProperty('id');
    $image  = "http://graph.facebook.com/".$id."/picture?width=300";
    $email  = $graph->getProperty('email');
    
    print_r($graph);

    echo '<br/><br/>';
    
    echo "Facebook Id : $id<br/>";
    echo "FirstName: $first_name<br/>";
    echo "LastName: $last_name<br/>";
    echo "Gender: $gender<br/>";
    echo "Name: $name<br/>";
    echo "Email:$email<br/>";
    echo "<img src='$image' /><br/>";
    echo '<a href="http://xxxxxxxxx.com/facebook_signup/index.php?logout=true">Logout</a>';
}
else{
    //Style - 1
    echo '<a href="'.$helper->getLoginUrl(array('email')).'">Login with facebook</a>';
}
?>
