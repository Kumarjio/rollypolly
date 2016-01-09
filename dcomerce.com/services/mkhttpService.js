// JavaScript Document
(function () {
    var mkhttpService = function ($http) {
        this.getHttp = function(urlReq, callback, callbackError, cacheReq) {
            $http({method: 'GET', url: urlReq, cache: cacheReq}).
                then(function(response) {
                  callback(response);
                }, function(response) {
                  callbackError(response);
              });
        };
    };
    mkhttpService.$inject = ['$http'];
    angular.module('dcomerce').service('mkhttpService', mkhttpService);
}());