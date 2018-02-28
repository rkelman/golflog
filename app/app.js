angular.module(‘authApp’, [‘ui.router’, ‘satellizer’])
.config( function ($stateProvider, $urlRouterProvider) {
  $stateProvider
    .state(‘login’, {
    url: ‘login’,
    templateUrl: ‘templates/login.tpl.html’,
    controller: ‘LoginCtrl as login’
    })
    .state(‘register’, {
      url: ‘register’,
      templateUrl: ‘templates/register.tpl.html’,
      controller: ‘RegisterCtrl as register’
    })
    .state(‘dashboard’, {
      url: ‘dashboard’,
      templateUrl: ‘templates/dashboard.tpl.html’
    });
   $urlRouterProvider.otherwise(‘/login’);
})
