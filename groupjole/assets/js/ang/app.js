// JavaScript Document
var app = angular.module("GroupJole", []);

app.controller("createCtrl", ['$scope', function($scope) {
}]);

//remove space
app.filter('spaceless',function() {
    return function(input) {
        if (input) {
            input = input.replace(/[^a-zA-Z]/g, '-'); 
            input = input.toLowerCase();
            return input;   
        }
    }
});