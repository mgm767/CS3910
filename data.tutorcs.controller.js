/* Controller where we get the data on da tutor stuffs.*/

(function () {
    'use strict';
    
    
    // the tutorcs part comes from the name of the app we created in cs.module.js
    var myApp = angular.module("cs_project", []);
    
    // datais used in the html file when defining the ng-controller attribute
    myApp.controller("dataControl", function($scope, $http, $window) {

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
                            $scope.users = response.data.value.users;
                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };

        
         $scope.sessions = [];
        $scope.getSessions = function () {
            $http.get('tutor_scheduled.php')
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.status == 'error') {
                            alert('error: ' + response.data.message.sessions);
                        } else {
                            console.log(response.data.value.sessions);
                            $scope.sessions = response.data.value.sessions;
                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };        
        
        
        $scope.available_sessions_student = [];
        $scope.getAvailableSessions_student = function () {
            $http.get('getAvailableSessions_student.php')
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.status == 'error') {
                            alert('error: ' + response.data.message.sessions);
                        } else {
                            console.log(response.data.value.sessions);
                            $scope.sessions = response.data.value.sessions;
                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };
        
        $scope.scheduled_sessions = [];
        $scope.getScheduledSessions = function () {
            $http.get('getScheduledSessions.php')
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.status == 'error') {
                            alert('error: ' + response.data.message.sessions);
                        } else {
                            console.log(response.data.value.sessions);
                            $scope.sessions = response.data.value.sessions;
                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };

        //function to send new session info to web api to add it to the database
        $scope.newSession = function(sessionDetails) {
            var sessionupload = angular.copy(sessionDetails);
            $http.post("tutor_available.php", sessionupload)
            .then(function(response) {
                if (response.status == 200) {
                if (response.data.status == 'error') {
                    alert('error: ' + response.data.message);
                } else {
                    //successful - send user back to homepage
                    $window.location.href = "index_tutor.html";
                }
                } else {
                alert('unexpected error');
                } 
            });
        };

        // Fetch all available sessions for display
        $scope.availableSessions = [];
        $scope.getAvailableSessions = function() {
            $http.get('getAvailableSessions.php')
                .then(function(response) {
                    if (response.status === 200) {
                        if (response.data.status === 'error') {
                            alert('Error: ' + response.data.message);
                        } else {
                            console.log(response.data.value.sessions);
                            $scope.availableSessions = response.data.value.sessions;
                        }
                    } else {
                        alert('Something went wrong. Please try again');
                    }
                });
        };

        $scope.signUpForSession = function(sessionId) {
            console.log('Siging up..');
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
        
        $scope.updateUser = function(editUser, originalUser) {
            // In case the admin has chosed to change the hawk_id (our primary key), keep the old one so we know which row to change
            editUser.original_hawk_id = originalUser.hawk_id;

			$http.post('editUser.php', editUser)
				.then(function(response) {
					if (response.status === 200) {
						if (response.data.status === 'error') {
							alert('Error: ' + response.data.message);
						} else {
							$scope.setUserEditMode(false, originalUser);
							$window.location.reload();
						}
					} else {
						alert('Something went wrong. Please try again');
					}
				});
		};
        

        $scope.deleteUser = function(firstName, lastName, hawk_id) {
			if (confirm("Are you sure you want to delete " + firstName + ' ' + lastName + "?")) {	
				$http.post('deleteUser.php', {"hawk_id": hawk_id})
					.then(function(response) {
						if (response.status === 200) {
							if (response.data.status === 'error') {
								alert('Error: ' + response.data.message);
							} else {
								$window.location.reload();
							}
						} else {
							alert('Something went wrong. Please try again');
						}
					});
			}
        };
        
        $scope.deleteSessionTutor = function(session_id, slot) {
			if (confirm("Are you sure you want to delete session number " + session_id + ' on ' + slot + "?")) {	
				$http.post('deleteSessionTutor.php', {"session_id": session_id})
					.then(function(response) {
						if (response.status === 200) {
							if (response.data.status === 'error') {
								alert('Error: ' + response.data.message);
							} else {
								$window.location.reload();
							}
						} else {
							alert('Something went wrong. Please try again');
						}
					});
			}
        };
        //create a new account
        $scope.newAccount = function () {
            $http.post('admin_add_account.php')
            .then(function(response) {
                if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message.users);
                    } else {
                        console.log(response.data.value.users);
                        $scope.users = response.data.value.users;
                    }
                } else {
                        alert('unexpected error');
                    }
                });
        };
        
        
        $scope.facultyCourses = [];
        $scope.getFacultyCourses = function () {
            $http.get('getFacultyCourses.php')
            .then(function(response) {
                if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        console.log(response.data.value.courses);
                        $scope.courses = response.data.value.courses;
                    }
                } else {
                        alert('unexpected error');
                    }
                });
        };
        $scope.studentCourses = [];
        $scope.getStudentCourses = function () {
            $http.get('getStudentCourses.php')
            .then(function(response) {
                if (response.status == 200) {
                    if (response.data.status == 'error') {
                        alert('error: ' + response.data.message);
                    } else {
                        console.log(response.data.value.courses);
                        $scope.courses = response.data.value.courses;
                    }
                } else {
                        alert('unexpected error');
                    }
                });
        };
        
        $scope.studentUsers = [];
        $scope.getStudentAccounts = function () {
            $http.get('getStudentAccounts.php')
                .then(function (response) {
                    if (response.status == 200) {
                        if (response.data.status == 'error') {
                            alert('error: ' + response.data.message.users);
                        } else {
                            console.log(response.data.value.users);
                            $scope.users = response.data.value.users;
                        }
                    } else {
                        alert('unexpected error');
                    }
                });
        };
        
    });

})();