<?php
$pageTitle = 'Facebook Friends';
?>
<script>
    //$.cookie("test", 1);
    //$.removeCookie("test");
    //var cookieValue = $.cookie("test");
    
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '156669051033535',
          xfbml      : true,
          version    : 'v2.0'
        });
        $(document).trigger('fbload');
      };
      
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));

// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    if (response.status === 'connected') {
      console.log('Connected');
        FB.api('/me', function(response) {
              console.log(response);
            console.log('Good to see you, ' + response.name + '.');
          });
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      console.log('Please log into this app.');
      FB.login(function(response) {
        if (!response.authResponse) {
          console.log('User cancelled login or did not fully authorize.');
        }
      });
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      console.log('Please log into Facebook.');
      FB.login(function(response) {
        if (response.authResponse) {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
              console.log(response);
            console.log('Good to see you, ' + response.name + '.');
          });
        } else {
          console.log('User cancelled login or did not fully authorize.');
        }
      });
    }
  }
function login()
{
    FB.login(function(response) {
        if (response.authResponse) {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
              console.log(response);
            console.log('Good to see you, ' + response.name + '.');
          });
        } else {
          console.log('User cancelled login or did not fully authorize.');
        }
      }, {scope: 'public_profile,email,user_friends'});
}
    //MEANWHILE IN $(document).ready()
$(document).on(
    'fbload',  //  <---- HERE'S OUR CUSTOM EVENT BEING LISTENED FOR
    function(){
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
          });
    }
);

function getFriends()
{
    FB.api(
        "/me/friends",
        function (response) {
            console.log(response);
          if (response && !response.error) {
              
          }
        }
    );
}
    </script>
    <div id="getfriends"><input type="button" name="btnGetFriends" id="btnGetFriends" value="Get Facebook Friends" onClick="getFriends();" /></div>
    <div id="friendslist"></div>