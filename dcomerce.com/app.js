'use strict';

// Declare app level module which depends on views, and components
angular.module('dcomerce', [
  'ngRoute',
  'dcomerce.home',
  'ngCookies'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.otherwise({redirectTo: '/deals'});
}])
.controller('AppCtrl', ['$scope', 'mkhttpService', '$rootScope', '$log', '$cookies', function($scope, mkhttpService, $rootScope, $log, $cookies) {
    $rootScope.isLoggedIn = false;
    $rootScope.userDetails = $cookies.getObject('user');
    if (typeof($rootScope.userDetails) !== 'undefined' && $rootScope.userDetails) {
        $rootScope.isLoggedIn = true;
    }
    $log.debug('user details');
    $log.warn($rootScope.userDetails);
    mkhttpService.getHttp('json/config.php', configPass, configFail, true);
    function configPass(response) {
        var data = response.data;
        $log.debug('in pass');
        $log.debug(response.data);
        $scope.countyName = data.county.properties.name;
        $scope.categories = data.categories;
        $log.debug('categories');
        $log.debug($scope.categories);
    }
    function configFail(response) {
        $log.error('in fail');
        $log.error(response.data);
    }
}]);