angular.module('dcomerce.home', ['ngRoute', 'ngCookies'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/home', {
    templateUrl: 'home/home.html',
    controller: 'HomeCtrl'
  });
  $routeProvider.when('/', {
    templateUrl: 'home/deals.html',
    controller: 'HomeCtrl'
  });
  $routeProvider.when('/deals', {
    templateUrl: 'home/deals.html',
    controller: 'HomeCtrl'
  });
  $routeProvider.when('/post/deal', {
    templateUrl: 'home/post_deal.html',
    controller: 'PostDealCtrl'
  });
  $routeProvider.when('/user/login', {
    templateUrl: 'home/login.html',
    controller: 'LoginCtrl'
  });
}])


.controller('HomeCtrl', ['$scope', function($scope) {
    
}])

.controller('PostDealCtrl', ['$scope', '$log', function($scope, $log) {
    $log.debug('in postdeal controller');
}])

.controller('LoginCtrl', ['$scope', '$rootScope', '$log', '$cookies', function($scope, $rootScope, $log, $cookies) {
    $log.debug('in login controller');
    $scope.user = {};
    $scope.showUserDetails = false;
    var clientId = '437724595536-ig3t3oer4dks3q9k62c9v5gc69c0ma6o.apps.googleusercontent.com';
    var apiKey = 'AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw';
    var scopes = 'https://www.googleapis.com/auth/plus.me';
    $scope.handleClientLoad = function() {
        handleClientLoad();
    }
    // Use a button to handle authentication the first time.
    function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth,1);
    }
    function checkAuth() {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
      }
      function handleAuthResult(authResult) {
        var authorizeButton = document.getElementById('authorize-button');
        if (authResult && !authResult.error) {
          authorizeButton.style.visibility = 'hidden';
          makeApiCall();
        } else {
          authorizeButton.style.visibility = '';
          authorizeButton.onclick = handleAuthClick;
        }
      }
      function handleAuthClick(event) {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
        return false;
      }
      // Load the API and make an API call.  Display the results on the screen.
      function makeApiCall() {
        gapi.client.load('plus', 'v1', function() {
          var request = gapi.client.plus.people.get({
            'userId': 'me'
          });
          request.execute(function(resp) {
                $scope.$apply(function () {
                    $scope.showUserDetails = true;
                    $scope.user = resp;
                    var img = resp.image.url;
                    $scope.mainImage = img.replace("sz=50", "");
                    $scope.user.mainImage = $scope.mainImage;
                    $rootScope.userDetails = $scope.user;
                    $rootScope.isLoggedIn = true;
                    $cookies.putObject('user', $scope.user);
                });
          });
        });
      }
}])
;