/* Controller where we get the data on da tutor stuffs.*/

(function () {
    'use strict';
    
    
    // the tutorcs part comes from the name of the app we created in cs.module.js
    var myApp = angular.module("cs_project", []);
    
    // datais used in the html file when defining the ng-controller attribute
    myApp.controller("dataControl", function($scope, $http, $window) {
    
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
          console.log(accountDetails);
          
          $http.post("login.php", accountupload)
            .then(function (response) {
               if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        var role = response.data.authorizedRole;
                        // successful
                        // send user to proper home page
                        switch (role) {
                            case 'student': $window.location.href = "index_student.html";
                                break;
                            case 'tutor': $window.location.href = "index_tutor.html";
                                break;
                            case 'professor': $window.location.href = "index_faculty.html";
                                break;
                            case 'administrator': $window.location.href = "index_admin.html";
                                break;
                        }
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
        
        $scope.users = [];
        $scope.getAccounts = function () {
            $http.get('getAccounts.php')
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.status == 'error') {
                            alert('error: ' + response.data.message.users);
                        } else {
                            console.log(response.data.value);
                            $scope.users = response.data.value.users;

                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };

        $scope.setUserEditMode = function(editing, user) {
			if (editing) {
			 // Make a copy that we can change without losing the copy from the DB itself
			 $scope.editUser = angular.copy(user);
			 user.editMode = true;
			} else {
				$scope.editUser = null;
				user.editMode = false;
			}
		};

        $scope.deleteUser = function(firstName, lastName, id) {
			if (confirm("Are you sure you want to delete " + firstName + ' ' + lastName + "?")) {	
				$http.post('deleteUser.php', {"id": id})
					.then(function(response) {
						if (response.status === 200) {
							if (response.data.status === 'error') {
								alert('Error: ' + response.data.message);
							} else {
								location.reload();
							}
						} else {
							alert('Something went wrong. Please try again');
						}
					});
			}
		};

        
    });
    
    
} )();