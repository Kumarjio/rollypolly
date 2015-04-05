<?php
//http://mkgalaxy.com/api/oauth/token.php
//curl -u testclient:testpass http://mkgalaxy.com/api/oauth/token.php -d 'grant_type=authorization_code&code=012a4d472ef6e57878219ee3bbe0b2d77b70bdb3'


// include our OAuth2 Server object
require_once __DIR__.'/server.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();