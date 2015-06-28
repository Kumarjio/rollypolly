// JavaScript Document
var app = angular.module("RealMoneyMaking", []);


app.controller("step1Ctrl", ['$scope', function($scope) {
	console.log($scope);
												 
}]);
/*
//remove space
app.filter('spaceless',function() {
    return function(input) {
        if (input) {
            input = input.replace(/[^a-zA-Z]/g, '-'); 
            input = input.toLowerCase();
            return input;   
        }
    }
});*/