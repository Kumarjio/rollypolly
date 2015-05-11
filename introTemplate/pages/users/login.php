<?php

require_once ROOTDIR.'/api/googleauth/src/Google_Client.php'; // include the required calss files for google login
require_once ROOTDIR.'/api/googleauth/src/contrib/Google_PlusService.php';
require_once ROOTDIR.'/api/googleauth/src/contrib/Google_Oauth2Service.php';
$client = null;

function logout()
{
    unset($_SESSION['access_token']);
    unset($_SESSION['gplusuer']);
    unset($_SESSION['user']);
    unset($_SESSION['settings']);
    header('Location: '.HTTPPATH . LOGINURL);
    exit;
}

function getCode()
{
    global $client;
    $client->authenticate(); // Authenticate
	$_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
	header('Location: '.HTTPPATH . LOGINURL);
    exit;
}

try {
    $client = new Google_Client();
    $client->setApplicationName("Google Authentication"); // Set your applicatio name
    $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me')); // set scope during user login
    $client->setClientId(CLIENTID); // paste the client id which you get from google API Console
    $client->setClientSecret(CLIENTSECRET); // set the client secret
    $client->setDeveloperKey(DEVELOPERKEY); // Developer key
    $url = HTTPPATH . LOGINURL;
    $client->setRedirectUri($url); // paste the redirect URI where you given in APi Console. You will get the Access Token here during login success
    $plus 		= new Google_PlusService($client);
    $oauth2 	= new Google_Oauth2Service($client); // Call the OAuth2 class for get email address
    //logout
    if(isset($_GET['logout'])) {
        logout();
    }
    //code logic
    if(isset($_GET['code'])) {
        getCode();
    }
    //access token
    if(isset($_SESSION['access_token'])) {
        $client->setAccessToken($_SESSION['access_token']);
    }
    //getaccesstoken
    if ($client->getAccessToken()) {
        $user 		= $oauth2->userinfo->get();
        $_SESSION['user'] = $user;
        //$plus = new apiPlusService($client);
        //$me 			= $plus->people->get('me');
        //pr($me);
    } else {
        $authUrl = $client->createAuthUrl();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <h3>Login</h3>

<?php

if (!empty($error)) {
  echo '<div class="error">'.$error.'</div>';
} else if(isset($authUrl)) {
	echo "<a class='login' href='$authUrl'><img src=\"/images/google-login-button-asif18.png\" alt=\"Google login\" title=\"login with google\" /></a>";
	} else {
		?>
<p><b>ID: </b><?php echo $_SESSION['user']['id']; ?><br>
<b>Name: </b><?php echo $_SESSION['user']['name']; ?><br>
<b>Gender: </b><?php echo $_SESSION['user']['gender']; ?><br>
<img src="<?php echo $_SESSION['user']['picture']; ?>" />
</p>
<p><a href="/users/login?logout=1">Logout</a></p>
		<?php
}

?>
<br >
<br >
<br >
<br >
<br >
    </div>
  </div>
</div>