// JavaScript Document
(function () {
    var mkhttpService = function ($http) {
        this.getHttp = function(urlReq, callback, cacheReq) {
            $http({method: 'GET', url: urlReq, cache: cacheReq}).
                then(function(response) {
                    console.log('success');
                    console.log(response);
                  callback(response);
                }, function(response) {
                    console.log('failure');
                    console.log(response);
                  callback(response);
              });
        };
    };
    mkhttpService.$inject = ['$http'];
    angular.module('dcomerce').service('mkhttpService', mkhttpService);
}());