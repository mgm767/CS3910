/* Controller where we get the data on da movies.*/

(function () {
    'use strict';
    
    
    // the movie part comes from the name of the app we created in movie.module.js
    //var myApp = angular.module("cs_project");
    
    // dataControl is used in the html file when defining the ng-controller attribute
    //myApp.controller("dataControl", function($scope, $http, $window) {
    
       // $http.get('getmovies.php')
       //     .then(function(response) {
         //       $scope.data = response.data.value;
         //   }
            
          //      );
       
        
        //Code for search bar
        $scope.query = {};
        $scope.queryBy = "$";
        
        $scope.menuHighlight = 0;
        
 
        
        // function to send new account information to web api to add it to the database
        $scope.login = function(accountDetails) {
          var accountupload = angular.copy(accountDetails);
          
          $http.post("login.php", accountupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "index.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };
        
        
        // function to log the user out
        $scope.logout = function() {
          $http.post("logout.php")
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // send user back to home page
                        $window.location.href = "index.html";
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };             
        
        // function to check if user is logged in
        $scope.checkifloggedin = function() {
          $http.post("isloggedin.php")
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        // successful
                        // set $scope.isloggedin based on whether the user is logged in or not
                        $scope.isloggedin = response.data.loggedin;
                    }
               } else {
                    alert('unexpected error');
               }
            });                        
        };       

        
    });
    
    
} )();