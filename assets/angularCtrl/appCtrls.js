var app = angular.module('salesapp', ['toastr', 'ngFileUpload', 'angularUtils.directives.dirPagination', 'ngMap', 'isteven-multi-select', 'ngProgress', 'AngularChart']);

app.factory('Excel',function($window){
    
    var uri='data:application/vnd.ms-excel;base64,',
        template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
        format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
    return {
        tableToExcel:function(tableId,worksheetName){
        var table=$(tableId),
        ctx={worksheet:worksheetName,table:table.html()},
        href=uri+base64(format(template,ctx));
            return href;
        }
    };
});

//**************** Angular Toast directive ******************//
    app.config(function(toastrConfig) {
        angular.extend(toastrConfig, {
            allowHtml: true,
            closeButton: true,
            closeHtml: '<button>&times;</button>',
            extendedTimeOut: 1500,
            iconClasses: {
                error: 'toast-error',
                info: 'toast-info',
                success: 'toast-success',
                warning: 'toast-warning'
            },  
            messageClass: 'toast-message',
            onHidden: null,
            onShown: null,
            onTap: null,
            progressBar: false,
            tapToDismiss: true,
            templates: {
                toast: 'directives/toast/toast.html',
                progressbar: 'directives/progressbar/progressbar.html'
            },
            timeOut: 1500,
            titleClass: 'toast-title',
            toastClass: 'toast'
        });
    });


    app.filter('dateFormat1', function($filter){        
        return function(input){
            if(input == null){ return ""; }
            var _date = $filter('date')(new Date(input), 'yyyy-MM-dd');
            return _date.toUpperCase();
        };        
    });


//************************ Signup Controller *********************//
app.controller('signupCtrl', function($scope, $http, toastr){

  $scope.submitSignup = function(){

    if($scope.userPassword == $scope.userPasswordC){

      var data = $.param({
        userTypeId: 1,
        userTypeName: 'superadmin',
        userName: $scope.userName,
        userEmail: $scope.userEmail,
        userPassword: $scope.userPassword
      });

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }

      $http.post(main_url+'signup/user_signup',data,config)
          .then(function(response){

              if(response.data.status == "success"){
                
                toastr.success(response.data.message, 'Success!');
                window.location = main_url+"login";

              }else if(response.data.status == "failed"){
                toastr.error(response.data.message, 'Warning!');
              }else{
                toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
              }

          });


    }else{

      toastr.error("Password doesn't Match", 'Warning!');
      
    }


  }

});


//************************ Login Controller *********************//
app.controller('loginCtrl', function($scope, $http, toastr) {

  $scope.submitLogin = function(){

    var data = $.param({
      userName: $scope.userName,
      userPassword: $scope.userPassword
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'login/user_login',data,config)
    .then(function(response){

      if(response.data.status == "success"){

        toastr.success(response.data.message,'Success!');

        if(response.data.userTypeName=="appuser"){
          window.location = main_url+"user/";
        }else{
          window.location = main_url;
        }

        

        }else if(response.data.status == "failed"){
          toastr.error(response.data.message, 'Warning!');
        }else{
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }


    });

  }

});


//************************ Dashboard Controller *********************//
app.controller('dashboardCtrl', function($scope, $http, toastr){

    var map;
    var image         =   'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
    var markerBall    =   main_url+'assets/markericon/marker_ball.png';
  
    function initialize(){
        var latlng      =   new google.maps.LatLng(22.5726,88.3639);
        var myOptions   =   {
            zoom        : 4,
            center      : latlng,
            panControl  : true,
            zoomControl : true,
            scaleControl: true,
            mapTypeId   : google.maps.MapTypeId.ROADMAP
        }
    
        map = new google.maps.Map(document.getElementById("map"), myOptions);

        infowindow = new google.maps.InfoWindow();
        /*
        var service = new google.maps.places.PlacesService(map);
        service.nearbySearch({
          location: latlng,
          radius: 500,
          type: ['restaurant']
        }, callback);*/
        /*
        var serviceCompany = new google.maps.places.PlacesService(map);
        serviceCompany.nearbySearch({
          location: latlng,
          radius: 500,
          type: ['company']
        }, callbackCompany);*/
        getAllActiveUsers();
    }

    /*
    function callback(results, status){
        if (status === google.maps.places.PlacesServiceStatus.OK){
            for (var i = 0; i < results.length; i++) {
                createMarker(results[i]);
            }
        }
    }

    function createMarker(place) {
        var placeLoc    =   place.geometry.location;
        var marker      =   new google.maps.Marker({
        map: map,
        position: place.geometry.location,
        icon: image
    });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<b>Restaurant Name: </b>'+place.name);
            infowindow.open(map, this);
        });
    }*/


    function callbackCompany(results, status){
        if(status === google.maps.places.PlacesServiceStatus.OK){
            for (var i = 0; i < results.length; i++) {
                createMarkerCompany(results[i]);
            }
        }
    }

    function createMarkerCompany(place){
        var placeLoc = place.geometry.location;
        var marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
        });

        google.maps.event.addListener(marker, 'click', function(){
            infowindow.setContent('<b>Company Name: </b>'+place.name);
            infowindow.open(map, this);
        });
    }

    function getAllActiveUsers(){
        $http.get(main_url+'dashboard/get_all_active_app_users')
            .then(function(response){
            $scope.users = response.data;
            for(var i = 0; i < $scope.users.length; i++) {
                createUserMarker($scope.users[i]);
            }
        });
    }


    //function createUserMarker(user){
    createUserMarker = user => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat:parseFloat(user.usrCurrentLat), lng:parseFloat(user.usrCurrentLong)},
            icon: markerBall
        });

        google.maps.event.addListener(marker, 'click', function(){
            if(user.usrLastMeetingId == '0'){
            //Show App user location Name
            var usrlatlng   =   new google.maps.LatLng(user.usrCurrentLat, user.usrCurrentLong);
            var userAddress =   '';
            var geocoder = new google.maps.Geocoder();  
                geocoder.geocode({
                    'latLng': usrlatlng
                },
                function (results, status){
                    if(status == google.maps.GeocoderStatus.OK){
                        if(results[0]){
                            //alert(results[0].formatted_address);
                            localStorage.setItem("userAddress", results[0].formatted_address);
                        }
                        else{
                            //alert('No results found');
                            localStorage.setItem("userAddress", "No location found");
                        }
                    }
                    else{
                        //alert('Geocoder failed due to: ' + status);
                    }
                });
                userAddress = localStorage.getItem("userAddress");

                infowindow.setContent(
                    '<b>User Name: </b>'+user.usrUserName+' ['+user.usrParentName+']'+
                    '<br><b>User Location : </b> '+userAddress+
                    '<br><b>Not Visited Any Meeting Yet</b>'+
                    '<br><br><a href="'+main_url+'dashboard/route?id='+user.usrId+'"><b>View Route</b></a>'
                );
            }
            else{
            //Show App user location Name
                var usrlatlng   =   new google.maps.LatLng(user.usrCurrentLat, user.usrCurrentLong);
                var userAddress =   '';
                var geocoder = new google.maps.Geocoder();  
                geocoder.geocode({
                    'latLng': usrlatlng
                },
                    function (results, status){
                        if(status == google.maps.GeocoderStatus.OK){
                            if(results[0]){
                                localStorage.setItem("userAddress", results[0].formatted_address);
                            }
                            else{
                            //alert('No results found');
                                localStorage.setItem("userAddress", "No location found");
                            }
                    }
                    else{
                        //alert('Geocoder failed due to: ' + status);
                    }
                });
                userAddress = localStorage.getItem("userAddress");
                infowindow.setContent(
                    '<b>User Name: </b>'+user.usrUserName+' ['+user.usrParentName+']'+
                    '<br><b>Last Customer Name: </b>'+user.usrLastMeetingCustomerName+
                    '<br><b>User Location : </b>'+userAddress+
                    '<br><b>Last Meeting Name: </b>'+user.usrLastMeetingName+
                    '<br><b>Last Meeting Date: </b>'+user.usrLastMeetingDate+
                    '<br><b>Last Meeting Time: </b>'+user.usrLastMeetingTime+
                    '<br><br><a href="'+main_url+'dashboard/route?id='+user.usrId+'"><b>View Route</b></a>'
                );
            }
            $(this).dblclick();
            infowindow.open(map, this);
        });
    }
    initialize();
});


    //************************ Route Controller *********************//
    app.controller('routeCtrl', ($scope, $http, toastr) => {

        getUrlParameter = sParam => {
            var sPageURL        =   decodeURIComponent(window.location.search.substring(1)),
                sURLVariables   =   sPageURL.split('&'),
                sParameterName,
                i;

            for(i = 0; i < sURLVariables.length; i++){
                sParameterName = sURLVariables[i].split('=');
                if(sParameterName[0] === sParam){
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }

        var map;
        var markerPinGreen      =   main_url+'assets/markericon/marker_pin_green.png';
        var markerPinRed        =   main_url+'assets/markericon/marker_pin_red.png';
        var markerFlagRed       =   main_url+'assets/markericon/marker_flag_red.png';
        var markerBallPinGreen  =   main_url+'assets/markericon/marker_ballpin_green.png';
        var markerBallPinRed    =   main_url+'assets/markericon/marker_ballpin_red.png';
        var markerFlagPinGreen  =   main_url+'assets/markericon/marker_flagpin_green.png';
        var markerFlagPinBlue   =   main_url+'assets/markericon/marker_flagpin_blue.png';

        var iconMarkerPinGreen = {
            url: main_url+'assets/markericon/marker_pin_green.png', // url
            scaledSize: new google.maps.Size(32, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };

        var iconMarkerLogin = {
            url: main_url+'assets/markericon/marker_login.png', // url
            scaledSize: new google.maps.Size(50, 50) // scaled size
        };

        var iconMarkerLogout = {
            url: main_url+'assets/markericon/marker_logout.png', // url
            scaledSize: new google.maps.Size(50, 50) // scaled size
        };

        var iconMarkerStartTrip = {
            url: main_url+'assets/markericon/marker_start_trip.png', // url
            scaledSize: new google.maps.Size(32, 32) // scaled size
        };

        var iconMarkerStopTrip = {
            url: main_url+'assets/markericon/marker_stop_trip.png', // url
            scaledSize: new google.maps.Size(48, 48) // scaled size
        };

        var iconMarkerVisited = {
            url: main_url+'assets/markericon/marker_visited.png', // url
            scaledSize: new google.maps.Size(24, 24) // scaled size
        };

        var symbolOne = {
            path: 'M -2,0 0,-2 2,0 0,2 z',
            strokeColor: '#F00',
            fillColor: '#F00',
            fillOpacity: 1
        };
    initialize = () => {
        var latlng = new google.maps.LatLng(22.5726,88.3639);
        var myOptions = {
            zoom: 9,
            center: latlng,
            panControl: true,
            zoomControl: true,
            scaleControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        infowindow = new google.maps.InfoWindow();

    /*
        var flightPlanCoordinates2 = [
{
            "lat": 22.5684765,
            "lng": 88.43143209999999
          },
{
                "lat": 22.5730292,
                "lng": 88.4211497
              },
{
                "lat": 22.557945,
                "lng": 88.4123719
              },
{
                "lat": 22.5499009,
                "lng": 88.402502
              },
{
                "lat": 22.5428193,
                "lng": 88.39864969999999
              },
{
                "lat": 22.5399428,
                "lng": 88.370285
              },
{
                "lat": 22.5406299,
                "lng": 88.36842999999999
              },
{
                "lat": 22.5411113,
                "lng": 88.3657477
              },
{
                "lat": 22.5416933,
                "lng": 88.3629188
              },
{
                "lat": 22.5416343,
                "lng": 88.3627863
              },
{
                "lat": 22.5408931,
                "lng": 88.3413412
              },
{
                "lat": 22.5404191,
                "lng": 88.33937540000001
              },
{
                "lat": 22.5504836,
                "lng": 88.34039399999999
              },
{
                "lat": 22.5554416,
                "lng": 88.34430189999999
              },
{
                "lat": 22.5552428,
                "lng": 88.34996550000001
              },
{
                "lat": 22.5594853,
                "lng": 88.3504865
              },
{
                "lat": 22.5626757,
                "lng": 88.35087279999999
              },

        ];


        var flightPath2 = new google.maps.Polyline({
          path: flightPlanCoordinates2,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 0.5,
          strokeWeight: 5,
          icons: [{
                icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, strokeColor: '#87ceeb', strokeOpacity: '1', strokeWeight: '1', fillColor: '#000000', fillOpacity:'1', scaledSize: new google.maps.Size(18, 18),},
                offset: '100%'
            }]
        });

        flightPath2.setMap(map);


    for (var i = 0, n = flightPlanCoordinates2.length; i < n; i++) {

        var coordinates = new Array();
        for (var j = i; j < i+2 && j < n; j++) {
            coordinates[j-i] = flightPlanCoordinates2[j];
        }

        var polyline = new google.maps.Polyline({
            path: coordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.5,
            strokeWeight: 5,
            icons: [{
                icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, strokeColor: '#87ceeb', strokeOpacity: '1', strokeWeight: '1', fillColor: '#000000', fillOpacity:'1', scaledSize: new google.maps.Size(18, 18),},
                offset: '100%'
            }]
        });
        polyline.setMap(map);
        

    }




                    var flightPlanCoordinates3 = [
{
                "lat": 22.5730292,
                "lng": 88.4211497
              },
{
                "lat": 22.557945,
                "lng": 88.4123719
              },
{
                "lat": 22.5499009,
                "lng": 88.402502
              },


        ];


        var flightPath3 = new google.maps.Polyline({
          path: flightPlanCoordinates3,
          geodesic: true,
          strokeColor: '#0000FF',
          strokeOpacity: 0.5,
          strokeWeight: 5
        });

        flightPath3.setMap(map);


                    var flightPlanCoordinates4 = [
{
                "lat": 22.5428193,
                "lng": 88.39864969999999
              },
{
                "lat": 22.5399428,
                "lng": 88.370285
              },
{
                "lat": 22.5406299,
                "lng": 88.36842999999999
              },


        ];


        var flightPath4 = new google.maps.Polyline({
          path: flightPlanCoordinates4,
          geodesic: true,
          strokeColor: '#0000FF',
          strokeOpacity: 0.5,
          strokeWeight: 5
        });

        flightPath4.setMap(map);
        



      function createMarker(place) {
        var marker = new google.maps.Marker({
          map: map,
          position: place
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Place: </b>'+place.lat+' , '+place.lng);
          infowindow.open(map, this);
        });
      }


      createLoginMarker(flightPlanCoordinates2[0]);
      function createLoginMarker(place) {
        var marker = new google.maps.Marker({
          map: map,
          position: place,
          icon: markerPinGreen
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Login </b>'+
            '<br><b>Login Date: </b>2017-03-04'+
            '<br><b>Login Time: </b>12:00'
            );
          infowindow.open(map, this);
        });
      }


      createLogoutMarker(flightPlanCoordinates2[parseInt(flightPlanCoordinates2.length-1)]);
      function createLogoutMarker(place) {
        var marker = new google.maps.Marker({
          map: map,
          position: place,
          icon: markerPinRed
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Logout </b>'+
            '<br><b>Logout Date: </b>2017-03-04'+
            '<br><b>Logout Time: </b>20:00'
            );
          infowindow.open(map, this);
        });
      }


      createTripStartMarker(flightPlanCoordinates3[0], '12:00', '1');
      createTripStartMarker(flightPlanCoordinates4[0], '15:00', '2');
      function createTripStartMarker(place, time, id) {
        var marker = new google.maps.Marker({
          map: map,
          position: place,
          icon: markerBallPinGreen
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Trip Start </b>'+
            '<br><b>Trip ID: </b>'+id+
            '<br><b>Start Date: </b>2017-03-04'+
            '<br><b>Start Time: </b>'+time
            );
          infowindow.open(map, this);
        });
      }


      createTripStopMarker(flightPlanCoordinates3[parseInt(flightPlanCoordinates3.length-1)], '14:30', '1');
      createTripStopMarker(flightPlanCoordinates4[parseInt(flightPlanCoordinates4.length-1)], '18:00', '2');
      function createTripStopMarker(place, time, id) {
        var marker = new google.maps.Marker({
          map: map,
          position: place,
          icon: markerBallPinRed
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Trip Stop </b>'+
            '<br><b>Trip ID: </b>'+id+
            '<br><b>Stop Date: </b>2017-03-04'+
            '<br><b>Stop Time: </b>'+time
            );
          infowindow.open(map, this);
        });
      }


      createVisitedMarker(flightPlanCoordinates3[parseInt(flightPlanCoordinates3.length-1)], '14:00', 'Customer One');
      createVisitedMarker(flightPlanCoordinates4[parseInt(flightPlanCoordinates4.length-1)], '17:00', 'Customer Two');
      function createVisitedMarker(place, time, id) {
        var marker = new google.maps.Marker({
          map: map,
          position: place,
          icon: markerFlagPinBlue
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent('<b>Visited </b>'+
            '<br><b>Customer Name: </b>'+id+
            '<br><b>Visited Date: </b>2017-03-04'+
            '<br><b>Visited Time: </b>'+time
            );
          infowindow.open(map, this);
        });
      }*/
    }
    initialize();

    getLoginRecords = () => {
        var data = $.param({
            userId: getUrlParameter('id')
        });
        console.log(data);
        $http.get(main_url+'dashboard/get_user_login_records?'+data)
        .then(function(response){
        $scope.loginRecords = response.data;
            for(var i=0; i<$scope.loginRecords.length; i++){
                addLoginMarker($scope.loginRecords[i]);
                if(!($scope.loginRecords[i].lgnrLogoutLat == "") || !($scope.loginRecords[i].lgnrLogoutLat == null)){
                    addLogoutMarker($scope.loginRecords[i]);
                }
                var dataLoginLocations = $.param({
                    userLoginRecordId: $scope.loginRecords[i].lgnrId
                });

            $http.get(main_url+'dashboard/get_user_login_record_locations?'+dataLoginLocations)
                .then(function(response){

                $scope.loginLocations = response.data;
                    for(var i = 0, n = $scope.loginLocations.length; i < n; i++){
                        var coordinates = new Array();
                        for (var j = i; j < i+2 && j < n; j++) {
                            coordinates[j-i] = $scope.loginLocations[j];
                        }

                        var polyline = new google.maps.Polyline({
                            path: coordinates,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.5,
                            strokeWeight: 5,
                            icons: [{
                                icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, strokeColor: '#87ceeb', strokeOpacity: '1', strokeWeight: '1', fillColor: '#000000', fillOpacity:'1', scaledSize: new google.maps.Size(18, 18),},
                                offset: '100%'
                            }]
                        });
                        polyline.setMap(map);
                    }
                    /*
                    var loginPath = new google.maps.Polyline({
                        path: $scope.loginLocations,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.5,
                        strokeWeight: 5
                    });
                    loginPath.setMap(map);*/
                });


            var dataTrip = $.param({
                userLoginRecordId: $scope.loginRecords[i].lgnrId
            });

            $http.get(main_url+'dashboard/get_user_trip_records?'+dataTrip)
                .then(function(response){

                $scope.tripRecords = response.data;

                    for(var i=0; i<$scope.tripRecords.length; i++){
                        addTripStartMarker($scope.tripRecords[i]);
                        
                        if(!($scope.tripRecords[i].trpEndLat == "") || !($scope.tripRecords[i].trpEndLat == null)){
                            addTripStopMarker($scope.tripRecords[i]);
                        }

                        var dataTripLocations = $.param({
                            userTripRecordId: $scope.tripRecords[i].trpId
                        });

                        $http.get(main_url+'dashboard/get_user_trip_record_locations?'+dataTripLocations)
                            .then(function(response){

                            $scope.tripLocations = response.data;

                            var tripPath = new google.maps.Polyline({
                                path: $scope.tripLocations,
                                geodesic: true,
                                strokeColor: '#0000FF',
                                strokeOpacity: 1,
                                strokeWeight: 2
                            });
                            tripPath.setMap(map);
                            getVisited();
                        });
                    }
                });
            }
        });
    }
    getLoginRecords();

    getVisited = () => {
        var data = $.param({
          userId: getUrlParameter('id')
        });
        $http.get(main_url+'dashboard/get_user_visited?'+data)
        .then(function(response){
            $scope.visiteds = response.data;
            for(var i=0; i<$scope.visiteds.length; i++){
                addVisitedMarker($scope.visiteds[i]);
            }
        });
    }
    
    addLoginMarker = item => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(item.lgnrLoginLat),lng: parseFloat(item.lgnrLoginLong)},
            icon: iconMarkerLogin
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(
                '<!--<b>Login </b>-->'+
                '<br><b>Login Date: </b>'+item.lgnrLoginDate+
                '<br><b>Login Time: </b>'+item.lgnrLoginTime+
                '<br><br><a href="'+main_url+'dashboard"><b>Go Back</b></a>'
            );
            infowindow.open(map, this);
        });
    }
    
    addTripStartMarker = item => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(item.trpStartLat),lng: parseFloat(item.trpStartLong)},
            icon: iconMarkerStartTrip
        });
        google.maps.event.addListener(marker, 'click', function(){
            infowindow.setContent('<b>Trip Start </b>'+
                '<br><b>Trip ID: </b>'+item.trpId+
                '<br><b>Trip Start Date: </b>'+item.trpStartDate+
                '<br><b>Trip Start Time: </b>'+item.trpStartTime
            );
            infowindow.open(map, this);
        });
    }
    
    addLogoutMarker = item => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(item.lgnrLogoutLat),lng: parseFloat(item.lgnrLogoutLong)},
            icon: iconMarkerLogout
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<b>Logout </b>'+
                '<br><b>Logout Date: </b>'+item.lgnrLogoutDate+
                '<br><b>Logout Time: </b>'+item.lgnrLogoutTime
            );
            infowindow.open(map, this);
        });
    }
  
    addTripStopMarker = item => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(item.trpEndLat),lng: parseFloat(item.trpEndLong)},
            icon: iconMarkerStopTrip
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<b>Trip Stop </b>'+
                '<br><b>Trip ID: </b>'+item.trpId+
                '<br><b>Trip Start Date: </b>'+item.trpEndDate+
                '<br><b>Trip Start Time: </b>'+item.trpEndTime
              );
            infowindow.open(map, this);
        });
    }
    
    addVisitedMarker = item => {
        var marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(item.mtnVisitedLat),lng: parseFloat(item.mtnVisitedLong)},
            icon: iconMarkerVisited
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<b>Visited </b>'+
                '<br><b>Customer Name: </b>'+item.mtnVisitedCustomerName+
                '<br><b>Visited Date: </b>'+item.mtnVisitedDate+
                '<br><b>Visited Time: </b>'+item.mtnVisitedTime
            );
            infowindow.open(map, this);
        });
    }
});


//************************ User Controller *********************//
    app.controller('userCtrl', function($scope, $http, toastr, Excel,$timeout){

        $scope.dataloading = true;

        $scope.viewUserDetails = function(item){
            if(item.ausrKycdoc != '' && item.ausrKycdoc != null){
                $scope.salesmankyc  =   main_url + 'uploads/kyc/salesperson/' + item.ausrKycdoc;
            }
            else{
                $scope.salesmankyc  =   '';
            }
          
            $scope.activeUser = {
                ausrId          : item.ausrId,
                ausrFirstName   : item.ausrFirstName,
                ausrLastName    : item.ausrLastName,
                ausrContactNo   : item.ausrContactNo,
                ausrUserEmail   : item.ausrUserEmail,
                ausrUserName    : item.ausrUserName,
                ausrDesignation : item.ausrDesignation,
                ausrPassword    : item.ausrPassword,
                ausrKycFile     : $scope.salesmankyc
            };
        };

        $scope.myStatus = function(item){

            if(item.ausrStatusName == "active"){
                $scope.changeStatusName =   "deactive";
                $scope.changeStatus     =   0;
            }
            else{
                $scope.changeStatusName = "active";
                $scope.changeStatus     =   1;
            }

            let data = $.param({
                ausrId: item.ausrId,
                ausrStatusName: $scope.changeStatusName,
                ausrStatus: $scope.changeStatus
            });

            let config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }

            $http.post(main_url+'usermanagement/change_status',data,config)
                .then(function(response){

                if(response.data.status == "success"){
                    toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
                    getAllUsers();
                }
                else if(response.data.status == "failed"){
                    toastr.error(response.data.message, 'Warning!');
                }
                else{
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }

        $scope.userLogout = function(item){
            let data = $.param({
                ausrId: item.ausrId
            });

            let config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }

            $http.post(main_url+'usermanagement/user_app_logout',data,config)
                .then(function(response){
                if(response.data.status == "success"){
                    toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
                    getAllUsers();
                }
                else if(response.data.status == "failed"){
                    toastr.error(response.data.message, 'Warning!');
                }
                else{
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }

        $scope.whatClassIsIt = function(someValue){
            if(someValue=="active"){
                return "label label-success";
            }
            else{
                return "label label-danger";
            }
        };

        $scope.sort = function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        }

        $scope.refreshTable = function(){
            getAllUsers();
            toastr.success("Table Refreshed",'Success!');
        }

        getAllUsers = () => {
            $http.get(main_url+'usermanagement/get_all_app_users')
                .then(function(response){
                $scope.appUsers     = response.data;
                $scope.dataloading  = false;
            });
        }

        getAllAdmins = () => {
            $http.get(main_url+'usermanagement/get_all_admins')
                .then(function(response){
                  $scope.admins = response.data;
            });
        }
        
        getAllUsers();
        getAllAdmins();

        $scope.updateCreatedBy = function(){

            var data = $.param({
                userId: $scope.activeUser.ausrId,
                userParentId: $scope.adminParent.adminId,
                userParentPath: $scope.adminParent.adminParentPath
            });

            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }


            $http.post(main_url+'usermanagement/update_created_by',data,config)
                .then(function(response){

                if(response.data.status == "success"){
                    toastr.success(response.data.message,'Success!');
                    $('.modal-change-created').modal('hide');
                    getAllUsers();
                }
                else if(response.data.status == "failed"){
                    toastr.error(response.data.message, 'Warning!');
                }
                else{
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }

        $scope.goToEdit       = function(item){
            window.location   = main_url+"usermanagement/edit?id="+item.ausrId;
        }

        $scope.exportToExcel =   function(tableId){
            var exportHref   =   Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href = exportHref;},100);
        }
    });


//************************ Add User Controller *********************//
app.controller('addUserCtrl', function($scope, Upload, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();  

  $scope.cancelAddUser = function(){
    window.location = main_url+"usermanagement";
  }

  $scope.submitAddUser = function(file){

    if($scope.userPassword == $scope.userPasswordC){

      $scope.progressbar.start();

      /*var data = $.param({
        userFirstName: $scope.userFirstName,
        userLastName: $scope.userLastName,
        userContactNo: $scope.userContactNo,
        userEmail: $scope.userEmail,
        userName: $scope.userName,
        userDesignation: $scope.userDesignation,
        userAddress: $scope.userAddress,
        userCountryId: $scope.userCountry.countryId,
        userCountry: $scope.userCountry.countryName,
        userStateId: $scope.userState.stateId,
        userState: $scope.userState.stateName,
        userCityId: $scope.userCity.cityId,
        userCity: $scope.userCity.cityName,
        userPassword: $scope.userPassword,
        userPasswordC: $scope.userPasswordC
      });

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }


      $http.post(main_url+'usermanagement/add_user',data,config)
      .then(function(response){

        if(response.data.status == "success"){

          $scope.progressbar.complete();


          toastr.success(response.data.message,'Success!');
          window.location = main_url+"usermanagement";

        }else if(response.data.status == "failed"){
          $scope.progressbar.complete();
          toastr.error(response.data.message, 'Warning!');
        }else{
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }


      });*/
        
        // WITH ATTACHMENT
        file.upload = Upload.upload({
            method  :   "POST",
            url     :   main_url+'usermanagement/add_user',
            data    :   {
                userFirstName   : $scope.userFirstName,
                userLastName    : $scope.userLastName,
                userContactNo   : $scope.userContactNo,
                userEmail       : $scope.userEmail,
                userName        : $scope.userName,
                userDesignation : $scope.userDesignation,
                userAddress     : $scope.userAddress,
                userCountryId   : $scope.userCountry.countryId,
                userCountry     : $scope.userCountry.countryName,
                userStateId     : $scope.userState.stateId,
                userState       : $scope.userState.stateName,
                userCityId      : $scope.userCity.cityId,
                userCity        : $scope.userCity.cityName,
                userPassword    : $scope.userPassword,
                userPasswordC   : $scope.userPasswordC,
                file            : file
            },
        });
        file.upload.then(function (response){            
            if(response.data.status == "success"){
                $scope.progressbar.complete();
                toastr.success(response.data.message,'Success!');
                window.location = main_url+"usermanagement";
            }else if(response.data.status == "failed"){
              $scope.progressbar.complete();
              toastr.warning(response.data.message, 'Warning!');
            }else{
              $scope.progressbar.complete();
              toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
            }
        });

    }else{

      toastr.error("Password doesn't Match", 'Warning!');

    }
  }

  $scope.getCities = function(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.userState.stateId)
        .then(function(response){

          $scope.cities = response.data;

        });

  }

  $scope.getStates = function(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.userCountry.countryId)
        .then(function(response){

          $scope.states = response.data;

        });

  }

  function getAllCountries(){

        $http.get(main_url+'customermanagement/get_all_countries')
        .then(function(response){

          $scope.countries = response.data;

          $scope.userCountry = $scope.countries[100];

          $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.userCountry.countryId)
          .then(function(response){

            $scope.states = response.data;

            $scope.userState = $scope.states[40];

                $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.userState.stateId)
                .then(function(response){

                  $scope.cities = response.data;

                  $scope.userCity = $scope.cities[271];

                });

          });

        });
  }
    
getAllCountries();

});


//************************ Edit User Controller *********************//
app.controller('editUserCtrl', function($scope, Upload, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getUserDetail();

  $scope.updateUser = function(file){

    if($scope.userPassword == $scope.userPasswordC){

        $scope.progressbar.start();
        
        if(file){
            file.upload = Upload.upload({
                method  :   "POST",
                url     :   main_url+'usermanagement/update_user',
                data    :   {
                    userId              : $scope.appUser.ausrId,
                    userFirstName       : $scope.userFirstName,
                    userLastName        : $scope.userLastName,
                    userContactNo       : $scope.userContactNo,
                    userEmail           : $scope.userEmail,
                    userName            : $scope.userName,
                    userDesignation     : $scope.userDesignation,
                    userAddress         : $scope.userAddress,
                    userCountryId       : $scope.userCountry.countryId,
                    userCountry         : $scope.userCountry.countryName,
                    userStateId         : $scope.userState.stateId,
                    userState           : $scope.userState.stateName,
                    userCityId          : $scope.userCity.cityId,
                    userCity            : $scope.userCity.cityName,
                    userPassword        : $scope.userPassword,
                    userPasswordC       : $scope.userPasswordC,
                    file                : file
                },
            });
            file.upload.then(function (response){
                if(response.data.status == "success"){

                  $scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  window.location = main_url+"usermanagement";

                }else if(response.data.status == "failed"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
            
        }
        else{
          var data = $.param({
                userId              : $scope.appUser.ausrId,
                userFirstName       : $scope.userFirstName,
                userLastName        : $scope.userLastName,
                userContactNo       : $scope.userContactNo,
                userEmail           : $scope.userEmail,
                userName            : $scope.userName,
                userDesignation     : $scope.userDesignation,
                userAddress         : $scope.userAddress,
                userCountryId       : $scope.userCountry.countryId,
                userCountry         : $scope.userCountry.countryName,
                userStateId         : $scope.userState.stateId,
                userState           : $scope.userState.stateName,
                userCityId          : $scope.userCity.cityId,
                userCity            : $scope.userCity.cityName,
                userPassword        : $scope.userPassword,
                userPasswordC       : $scope.userPasswordC
          });

          var config = {
              headers : {
                  'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
              }
          }


          $http.post(main_url+'usermanagement/update_user',data,config)
          .then(function(response){
                if(response.data.status == "success"){

                  $scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  window.location = main_url+"usermanagement";

                }else if(response.data.status == "failed"){
                  $scope.progressbar.complete();
                  toastr.error(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
          });
        }        
        
    }else{

        toastr.error("Password doesn't Match", 'Warning!');

    }

  }  

  $scope.cancelEditUser = function(){
    window.location = main_url+"usermanagement";
  }

  $scope.getCities = function(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.userState.stateId)
        .then(function(response){

          $scope.cities = response.data;

        });

  }

  $scope.getStates = function(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.userCountry.countryId)
        .then(function(response){

          $scope.states = response.data;

        });

  }

  function getAllCountries(){

    $http.get(main_url+'customermanagement/get_all_countries')
        .then(function(response){

          $scope.countries = response.data;
          $scope.userCountry = $scope.countries[parseInt($scope.userCountryId)-1];

        });
  }

  function getAllStatesById(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.userCountryId)
        .then(function(response){

          $scope.states = response.data;

          for(var i=0; i<$scope.states.length; i++){
            if($scope.states[i].stateId == $scope.userStateId){
              $scope.userState = $scope.states[i];
            }
          }
          

        });
  }

  function getAllCitiesById(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.userStateId)
        .then(function(response){

          $scope.cities = response.data;

          for(var i=0; i<$scope.cities.length; i++){
            if($scope.cities[i].cityId == $scope.userCityId){
              $scope.userCity = $scope.cities[i];
            }
          }

        });
  }
    
  function getUserDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'usermanagement/get_user_detail?id='+$scope.id)
        .then(function(response){

          $scope.appUser            = response.data;
          $scope.userFirstName      = $scope.appUser.ausrFirstName;
          $scope.userLastName       = $scope.appUser.ausrLastName;
          $scope.userContactNo      = parseInt($scope.appUser.ausrContactNo);
          $scope.userEmail          = $scope.appUser.ausrUserEmail;
          $scope.userName           = $scope.appUser.ausrUserName;
          $scope.userDesignation    = $scope.appUser.ausrDesignation;
          $scope.userAddress        = $scope.appUser.ausrAddress;
          $scope.userCountryId      = $scope.appUser.ausrCountryId;
          $scope.userCountry        = $scope.appUser.ausrCountry;
          $scope.userStateId        = $scope.appUser.ausrStateId;
          $scope.userState          = $scope.appUser.ausrState;
          $scope.userCityId         = $scope.appUser.ausrCityId;
          $scope.userCity           = $scope.appUser.ausrCity;
          $scope.userPassword       = $scope.appUser.ausrPassword;
          $scope.userPasswordC      = $scope.appUser.ausrPassword;
          $scope.userKyc            = $scope.appUser.ausrKycFile;


          getAllCountries();
          getAllStatesById();
          getAllCitiesById();
          //getAllCustomerTypes();

        });
  }

});


//************************ Admin Controller *********************//
app.controller('adminCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;

  getAllUsers();

  $scope.refreshTable = function(){
    getAllUsers();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.viewAdminDetails = function(item){

    $scope.activeAdmin =
      {
        wusrId: item.wusrId,
        wusrFirstName: item.wusrFirstName,
        wusrLastName: item.wusrLastName,
        wusrUnitNames: item.wusrUnitNames,
        wusrUserName: item.wusrUserName,
        wusrPassword: item.wusrPassword

      };

  };

 $scope.myStatus = function(item){

    if(item.wusrStatusName == "active"){
      $scope.changeStatusName = "deactive";
      $scope.changeStatus = 0;
    }else{
      $scope.changeStatusName = "active";
      $scope.changeStatus = 1;
    }

    var data = $.param({
      wusrId: item.wusrId,
      wusrStatusName: $scope.changeStatusName,
      wusrStatus: $scope.changeStatus
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'adminmanagement/change_status',data,config)
    .then(function(response){

      if(response.data.status == "success"){
        toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
        getAllUsers();
      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }else{
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }


    });

  }

  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.whatClassIsIt= function(someValue){
      if(someValue=="active"){
        return "label label-success";
      }else{
        return "label label-danger";
      }
    };

  function getAllUsers(){

    $http.get(main_url+'adminmanagement/get_all_web_users')
        .then(function(response){

          $scope.webUsers = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToEdit = function(item){
    window.location = main_url+"adminmanagement/edit?id="+item.wusrId;
  }

});


//************************ Add Admin Controller *********************//
app.controller('addAdminCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  $scope.userVenueAddPermission = false;
  $scope.userVenueEditPermission = false;

  var image_url = main_url+'assets/dist/img/buildingicon.png';
/*
$scope.modernBrowsers = [
    { icon: "<img src="+image_url+" />",               name: "Opera",              maker: "(Opera Software)",        ticked: true  },
    { icon: "<img src="+image_url+" />",   name: "Internet Explorer",  maker: "(Microsoft)",             ticked: false },
    { icon: "<img src="+image_url+" />",        name: "Firefox",            maker: "(Mozilla Foundation)",    ticked: true  },
    { icon: "<img src="+image_url+" />",      name: "Safari",             maker: "(Apple)",                 ticked: false },
    { icon: "<img src="+image_url+" />",              name: "Chrome",             maker: "(Google)",                ticked: true  }
];*/

  getAllUnits();

  $scope.cancelAddAdmin = function(){
    window.location = main_url+"adminmanagement";
  }

  $scope.submitAddAdmin = function(){

    if($scope.userPassword == $scope.userPasswordC){

      if($scope.outputUnits.length <= 0){
        toastr.error("Please Select Unit", 'Warning!');
      }else{

        $scope.progressbar.start();

        var data = $.param({
          userFirstName: $scope.userFirstName,
          userLastName: $scope.userLastName,
          userEmail: $scope.userEmail,
          userName: $scope.userName,
          userPassword: $scope.userPassword,
          userPasswordC: $scope.userPasswordC,
          userVenueAddPermission: $scope.userVenueAddPermission,
          userVenueEditPermission: $scope.userVenueEditPermission,
          userUnits: $scope.outputUnits
        });

        //alert(data);


        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

  
        $http.post(main_url+'adminmanagement/add_admin',data,config)
        .then(function(response){

          if(response.data.status == "success"){

            $scope.progressbar.complete();
            toastr.success(response.data.message,'Success!');
            window.location = main_url+"adminmanagement";

          }else if(response.data.status == "failed"){
            $scope.progressbar.complete();
            toastr.error(response.data.message, 'Warning!');
          }else{
            $scope.progressbar.complete();
            toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
          }


        },function(reject){
          // error handler 
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
        });

      }



    }else{
      toastr.error("Password doesn't Match", 'Warning!');
    }


  }


  function getAllUnits(){

    $http.get(main_url+'adminmanagement/get_all_active_units')
        .then(function(response){

          $scope.inputUnits = response.data;

        });
  }

});


//************************ Edit Admin Controller *********************//
app.controller('editAdminCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getAdminDetail();
  getAllEditUnits();

  $scope.updateAdmin = function(){

    if($scope.userPassword == $scope.userPasswordC){

      if($scope.outputEditUnits.length <= 0){
        toastr.error("Please Select Unit", 'Warning!');
      }else{

        $scope.progressbar.start();

        var data = $.param({
          userId: $scope.appUser.wusrId,
          userFirstName: $scope.userFirstName,
          userLastName: $scope.userLastName,
          userEmail: $scope.userEmail,
          userName: $scope.userName,
          userPassword: $scope.userPassword,
          userPasswordC: $scope.userPasswordC,
          userVenueAddPermission: $scope.userVenueAddPermission,
          userVenueEditPermission: $scope.userVenueEditPermission,
          userUnits: $scope.outputEditUnits
        });

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }


        $http.post(main_url+'adminmanagement/update_admin',data,config)
        .then(function(response){

          if(response.data.status == "success"){

            $scope.progressbar.complete();
            toastr.success(response.data.message,'Success!');
            window.location = main_url+"adminmanagement";

          }else if(response.data.status == "failed"){
            $scope.progressbar.complete();
            toastr.error(response.data.message, 'Warning!');
          }else{
            $scope.progressbar.complete();
            toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
          }


        },function(reject){
          // error handler 
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
        });


      }



    }else{

      toastr.error("Password doesn't Match", 'Warning!');

    }

  }


  function getAdminDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'adminmanagement/get_admin_detail?id='+$scope.id)
        .then(function(response){

          $scope.appUser = response.data;
          $scope.userFirstName = $scope.appUser.wusrFirstName;
          $scope.userLastName = $scope.appUser.wusrLastName;
          $scope.userEmail = $scope.appUser.wusrUserEmail;
          $scope.userName = $scope.appUser.wusrUserName;
          $scope.userPassword = $scope.appUser.wusrPassword;
          $scope.userPasswordC = $scope.appUser.wusrPassword;
          if($scope.appUser.wusrVenueAddPermission == 'true'){
            $scope.userVenueAddPermission = true;
          }else{
            $scope.userVenueAddPermission = false;
          }

          if($scope.appUser.wusrVenueEditPermission == 'true'){
            $scope.userVenueEditPermission = true;
          }else{
            $scope.userVenueEditPermission = false;
          }

        });
  }

  function getAllEditUnits(){

     $scope.id = getUrlParameter('id');

    $http.get(main_url+'adminmanagement/get_all_active_edit_units?id='+$scope.id)
        .then(function(response){

          $scope.inputEditUnits = response.data;

        });
  }

  $scope.cancelEditAdmin = function(){
    window.location = main_url+"adminmanagement";
  }


});


//************************ Customer Controller *********************//
app.controller('customerCtrl', function($scope, $http, toastr, Excel,$timeout) {

  $scope.dataloading = true;

  getAllCustomers();


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.refreshTable = function(){
    getAllCustomers();
    toastr.success("Table Refreshed",'Success!');
  }

  function getAllCustomers(){

    $http.get(main_url+'customermanagement/get_all_customers')
        .then(function(response){

          $scope.customers = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToEdit = function(item){
    window.location = main_url+"customermanagement/edit?id="+item.cusId;
  }

  $scope.addMeeting = function(item){
    window.location = main_url+"meetingmanagement/add?id="+item.cusId;
  }

  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

});


//************************ Add Customer Controller *********************//
app.controller('addCustomerCtrl', function($scope, $http, toastr, ngProgressFactory) {


  $scope.progressbar = ngProgressFactory.createInstance();

  getAllAdminUsers();
  getAllCountries();
  getAllCustomerTypes();
  getAllGenders();
  getAllIndustryTypes();
  getAllCompanies();
  getAllDepartments();
  getAllDesignations();
  getAllAreas();
  

  $scope.movies = ["Lord of the Rings",
        "Drive",
        "Science of Sleep",
        "Back to the Future",
        "Oldboy"];

  $('#cusDOB, #cusDOA').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    maxDate : new Date()
  });



  $scope.getCities = function(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.cusState.stateId)
        .then(function(response){

          $scope.cities = response.data;

        });

  }

  $scope.getStates = function(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.cusCountry.countryId)
        .then(function(response){

          $scope.states = response.data;

        });

  }


  function getAllCountries(){

    $http.get(main_url+'customermanagement/get_all_countries')
        .then(function(response){

          $scope.countries = response.data;

          $scope.cusCountry = $scope.countries[100];

          $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.cusCountry.countryId)
          .then(function(response){

            $scope.states = response.data;

            $scope.cusState = $scope.states[40];

                $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.cusState.stateId)
                .then(function(response){

                  $scope.cities = response.data;

                  $scope.cusCity = $scope.cities[271];

                });

          });


        });
  }


  function getAllAdminUsers(){

    $http.get(main_url+'customermanagement/get_all_admin_users')
        .then(function(response){

          $scope.adminusers = response.data;
          $scope.cusManage = $scope.adminusers[0];

        });
  }


  function getAllCustomerTypes(){

    $http.get(main_url+'customermanagement/get_all_customer_types')
        .then(function(response){

          $scope.customerTypes = response.data;

        });
  }

  function getAllIndustryTypes(){

    $http.get(main_url+'customermanagement/get_all_industry_types')
        .then(function(response){

          $scope.industryTypes = response.data;

        });
  }


  function getAllGenders(){

    $http.get(main_url+'customermanagement/get_all_genders')
        .then(function(response){

          $scope.genders = response.data;
          $scope.cusGender = $scope.genders[0];

        });
  }

  function getAllCompanies(){

    $http.get(main_url+'customermanagement/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;

          $( "#cmptags" ).autocomplete({
            source: $scope.companies
          });

        });
  }

  function getAllDepartments(){

    $http.get(main_url+'customermanagement/get_all_departments')
        .then(function(response){

          $scope.departments = response.data;

          $( "#depttags" ).autocomplete({
            source: $scope.departments
          });

        });
  }


  function getAllDesignations(){

    $http.get(main_url+'customermanagement/get_all_designations')
        .then(function(response){

          $scope.designations = response.data;

          $( "#desgtags" ).autocomplete({
            source: $scope.designations
          });

        });
  }


  function getAllAreas(){

    $http.get(main_url+'customermanagement/get_all_areas')
        .then(function(response){

          $scope.areas = response.data;

          $( "#areatags" ).autocomplete({
            source: $scope.areas
          });

        });
  }







  $scope.submitAddCustomer = function(){

    $scope.progressbar.start();

    $scope.cusDOB = $('#cusDOB').val();
    $scope.cusDOA = $('#cusDOA').val();

    var data = $.param({
      cusFirstName: $scope.cusFirstName,
      cusLastName: $scope.cusLastName,
      cusGenderId: $scope.cusGender.gndId,
      cusGender: $scope.cusGender.gndName,
      cusDOB: $scope.cusDOB,
      cusDOA: $scope.cusDOA,
      cusCustomerTypeId: $scope.cusCustomerType.custId,
      cusCustomerType: $scope.cusCustomerType.custName,
      cusIndustryTypeId: $scope.cusIndustryType.indtId,
      cusIndustryType: $scope.cusIndustryType.indtName,
      cusCompanyName: $scope.cusCompanyName,
      cusDepartment: $scope.cusDepartment,
      cusDesignation: $scope.cusDesignation,
      cusAddress: $scope.cusAddress,
      cusAddress2: $scope.cusAddress2,
      cusLandmark: $scope.cusLandmark,
      cusArea: $scope.cusArea,
      cusCountryId: $scope.cusCountry.countryId,
      cusCountry: $scope.cusCountry.countryName,
      cusStateId: $scope.cusState.stateId,
      cusState: $scope.cusState.stateName,
      cusCityId: $scope.cusCity.cityId,
      cusCity: $scope.cusCity.cityName,
      cusPinCode: $scope.cusPinCode,
      cusEmail: $scope.cusEmail,
      cusMobileNo: $scope.cusMobileNo,
      cusAlternateNo: $scope.cusAlternateNo,
      cusManageId: $scope.cusManage.usrId
    });

   // alert(data);

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }


    $http.post(main_url+'customermanagement/add_customer',data,config)
    .then(function(response){

      if(response.data.status == "success"){

        $scope.progressbar.complete();
        toastr.success(response.data.message,'Success!');
        window.location = main_url+"customermanagement";

      }else if(response.data.status == "failed"){
        $scope.progressbar.complete();
        toastr.error(response.data.message, 'Warning!');
      }else{
        $scope.progressbar.complete();
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }


    },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });


  }

  $scope.cancelAddCustomer = function(){
    window.location = main_url+"customermanagement";
  }


});


//************************ Edit Customer Controller *********************//
app.controller('editCustomerCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  $('#cusDOB, #cusDOA').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    maxDate : new Date()
  });

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getCustomerDetail();
  getAllCompanies();
  getAllDepartments();
  getAllDesignations();
  getAllAreas();

  $scope.submitUpdateCustomer = function(){

    $scope.progressbar.start();

    $scope.cusDOB = $('#cusDOB').val();
    $scope.cusDOA = $('#cusDOA').val();

    var data = $.param({
      cusId: $scope.customer.cusId,
      cusCode: $scope.cusCode,
      cusFirstName: $scope.cusFirstName,
      cusLastName: $scope.cusLastName,
      cusGender: $scope.cusGender.gndName,
      cusGenderId: $scope.cusGender.gndId,
      cusDOB: $scope.cusDOB,
      cusDOA: $scope.cusDOA,
      cusCompanyName: $scope.cusCompanyName,
      cusDepartment: $scope.cusDepartment,
      cusDesignation: $scope.cusDesignation,
      cusAddress: $scope.cusAddress,
      cusAddress2: $scope.cusAddress2,
      cusLandmark: $scope.cusLandmark,
      cusArea: $scope.cusArea,
      cusCountryId: $scope.cusCountry.countryId,
      cusCountry: $scope.cusCountry.countryName,
      cusStateId: $scope.cusState.stateId,
      cusState: $scope.cusState.stateName,
      cusCityId: $scope.cusCity.cityId,
      cusCity: $scope.cusCity.cityName,
      cusPinCode: $scope.cusPinCode,
      cusEmail: $scope.cusEmail,
      cusMobileNo: $scope.cusMobileNo,
      cusAlternateNo: $scope.cusAlternateNo,
      cusCustomerType: $scope.cusCustomerType.custName,
      cusCustomerTypeId: $scope.cusCustomerType.custId,
      cusIndustryType: $scope.cusIndustryType.indtName,
      cusIndustryTypeId: $scope.cusIndustryType.indtId,
      cusManageId: $scope.cusManage.usrId
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }


    $http.post(main_url+'customermanagement/update_customer',data,config)
    .then(function(response){

      if(response.data.status == "success"){

        $scope.progressbar.complete();


        toastr.success(response.data.message,'Success!');
        window.location = main_url+"customermanagement";

      }else if(response.data.status == "failed"){

        $scope.progressbar.complete();
        toastr.error(response.data.message, 'Warning!');

      }else{
        $scope.progressbar.complete();
        toastr.error('Something problem in Internet, Please try Again', 'Warning!');
      }


    },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });


  }


  function getCustomerDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'customermanagement/get_customer_detail?id='+$scope.id)
        .then(function(response){

          $scope.customer = response.data;
          $scope.cusId = $scope.customer.cusId;
          $scope.cusCode = $scope.customer.cusCode;
          $scope.cusFirstName = $scope.customer.cusFirstName;
          $scope.cusLastName = $scope.customer.cusLastName;
          //$scope.cusGender = $scope.customer.cusGender;
          $scope.cusGenderId = $scope.customer.cusGenderId;
          $scope.cusDOB = $scope.customer.cusDOB;
          $scope.cusDOA = $scope.customer.cusDOA;
          $scope.cusCompanyName = $scope.customer.cusCompanyName;
          $scope.cusDepartment = $scope.customer.cusDepartment;
          $scope.cusDesignation = $scope.customer.cusDesignation;
          $scope.cusAddress = $scope.customer.cusAddress;
          $scope.cusAddress2 = $scope.customer.cusAddress2;
          $scope.cusLandmark = $scope.customer.cusLandmark;
          $scope.cusArea = $scope.customer.cusArea;
          $scope.cusCountryId = $scope.customer.cusCountryId;
          $scope.cusCountry = $scope.customer.cusCountry;
          $scope.cusStateId = $scope.customer.cusStateId;
          $scope.cusState = $scope.customer.cusState;
          $scope.cusCityId = $scope.customer.cusCityId;
          $scope.cusCity = $scope.customer.cusCity;
          $scope.cusPinCode = parseInt($scope.customer.cusPinCode);
          $scope.cusEmail = $scope.customer.cusEmail;
          $scope.cusMobileNo = parseInt($scope.customer.cusMobileNo);
          $scope.cusAlternateNo = parseInt($scope.customer.cusAlternateNo);
          //$scope.cusCustomerType = $scope.customer.cusCustomerType;
          $scope.cusCustomerTypeId = $scope.customer.cusCustomerTypeId;
          //$scope.cusIndustryType = $scope.customer.cusIndustryType;
          $scope.cusIndustryTypeId = $scope.customer.cusIndustryTypeId;

          $scope.cusManageId = $scope.customer.cusManageId;

          getAllAdminUsers();
          getAllGenders();
          getAllCountries();
          getAllStatesById();
          getAllCitiesById();
          getAllCustomerTypes();
          getAllIndustryTypes();

        });
  }

  $scope.cancelEditCustomer = function(){
    window.location = main_url+"customermanagement";
  }


  $scope.getCities = function(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.cusState.stateId)
        .then(function(response){

          $scope.cities = response.data;

        });

  }

  $scope.getStates = function(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.cusCountry.countryId)
        .then(function(response){

          $scope.states = response.data;

        });

  }


  function getAllAdminUsers(){

    $http.get(main_url+'customermanagement/get_all_admin_users')
        .then(function(response){

          $scope.adminusers = response.data;

          for(var i=0; i<$scope.adminusers.length; i++){
            if($scope.adminusers[i].usrId == $scope.cusManageId){
              $scope.cusManage = $scope.adminusers[i];
              break;
            }
          }

          

        });
  }


  function getAllCountries(){

    $http.get(main_url+'customermanagement/get_all_countries')
        .then(function(response){

          $scope.countries = response.data;
          $scope.cusCountry = $scope.countries[parseInt($scope.cusCountryId)-1];

        });
  }


  function getAllStatesById(){

    $http.get(main_url+'customermanagement/get_states_by_id?id='+$scope.cusCountryId)
        .then(function(response){

          $scope.states = response.data;

          for(var i=0; i<$scope.states.length; i++){
            if($scope.states[i].stateId == $scope.cusStateId){
              $scope.cusState = $scope.states[i];
              break;
            }
          }
          

        });
  }


  function getAllCitiesById(){

    $http.get(main_url+'customermanagement/get_cities_by_id?id='+$scope.cusStateId)
        .then(function(response){

          $scope.cities = response.data;

          for(var i=0; i<$scope.cities.length; i++){
            if($scope.cities[i].cityId == $scope.cusCityId){
              $scope.cusCity = $scope.cities[i];
              break;
            }
          }

        });
  }


  function getAllCustomerTypes(){

    $http.get(main_url+'customermanagement/get_all_customer_types')
        .then(function(response){

          $scope.customerTypes = response.data;

          for(var i=0; i<$scope.customerTypes.length; i++){
            if($scope.customerTypes[i].custId == $scope.cusCustomerTypeId){
              $scope.cusCustomerType = $scope.customerTypes[i];
              break;
            }
          }

        });
  }


  function getAllIndustryTypes(){

    $http.get(main_url+'customermanagement/get_all_industry_types')
        .then(function(response){

          $scope.industryTypes = response.data;

          for(var i=0; i<$scope.industryTypes.length; i++){
            if($scope.industryTypes[i].indtId == $scope.cusIndustryTypeId){
              $scope.cusIndustryType = $scope.industryTypes[i];
              break;
            }
          }

        });
  }


  function getAllGenders(){

    $http.get(main_url+'customermanagement/get_all_genders')
        .then(function(response){

          $scope.genders = response.data;

          for(var i=0; i<$scope.genders.length; i++){
            if($scope.genders[i].gndId == $scope.cusGenderId){
              $scope.cusGender = $scope.genders[i];
              break;
            }

          }

        });
  }


  function getAllCompanies(){

    $http.get(main_url+'customermanagement/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;

          $( "#cmptags" ).autocomplete({
            source: $scope.companies
          });

        });
  }

  function getAllDepartments(){

    $http.get(main_url+'customermanagement/get_all_departments')
        .then(function(response){

          $scope.departments = response.data;

          $( "#depttags" ).autocomplete({
            source: $scope.departments
          });

        });
  }


  function getAllDesignations(){

    $http.get(main_url+'customermanagement/get_all_designations')
        .then(function(response){

          $scope.designations = response.data;

          $( "#desgtags" ).autocomplete({
            source: $scope.designations
          });

        });
  }


  function getAllAreas(){

    $http.get(main_url+'customermanagement/get_all_areas')
        .then(function(response){

          $scope.areas = response.data;

          $( "#areatags" ).autocomplete({
            source: $scope.areas
          });

        });
  }


});


//************************ Add Multiple Customer Controller *********************//
app.controller('addMultipleCustomerCtrl', function($scope, $http, toastr, ngProgressFactory, $timeout) {

  $scope.progressbar = ngProgressFactory.createInstance();

  function getCarData() {
    return [

    ];
  }
  
  var
    example1 = document.getElementById('example1'),
    hot1;
  /*
  hot1 = new Handsontable(example1, {
    data: getCarData(),
    colHeaders: ['Car', 'Year', 'Chassis color', 'Bumper color'],
    columns: [
      {
        type: 'autocomplete',
        source: ['BMW', 'Chrysler', 'Nissan', 'Suzuki', 'Toyota', 'Volvo'],
        strict: false
      },
      {type: 'numeric'},
      {
        type: 'autocomplete',
        source: ['yellow', 'red', 'orange and another color', 'green', 'blue', 'gray', 'black', 'white', 'purple', 'lime', 'olive', 'cyan'],
        strict: false,
        visibleRows: 4
      },
      {
        type: 'autocomplete',
        source: ['yellow', 'red', 'orange and another color', 'green', 'blue', 'gray', 'black', 'white', 'purple', 'lime', 'olive', 'cyan'],
        strict: false,
        trimDropdown: false
      }
    ]
  });*/

    getAllGenderSources();
    getAllCustomerTypeSources();
    getAllIndustryTypeSources();
    getAllCountriesSources();
    getAllStatesSources();
    getAllCitiesSources();


    var data1 = [];
    var mGenders = [];
    var mCustomerTypes = [];
    var mIndustryTypes = [];
    var mCountries = [];
    var mStates = [];
    var Cities = [];

    hot1 = new Handsontable(example1, {
    data: data1,
    width: '100%',
    height: 300,
    colWidths: [
      90, //First Name
      90, //Last Name
      90, //Gender
      90, //DOB
      90, //Anniversary
      150, //Customer Type
      150, //Industry Type
      110, //Company Name
      100, //Department
      100, //Designation
      100, //Email
      110, //Mobile No
      110, //Alternate No
      90, //Country
      90, //State
      90, //City
      150, //Address
      100, //Landmark
      100, //Area
      90  //Pincode
    ],
    undo: true,
    rowHeaders: true,
    colHeaders: true,
    manualColumnResize: true,
    manualRowResize: true,
    contextMenu: ['row_above', 'row_below', 'remove_row'],
    minRows: 1,
    colHeaders: [
    'First Name', 
    'Last Name', 
    'Gender', 
    'DOB', 
    'Anniversary', 
    'Customer Type', 
    'Industry Type', 
    'Company Name', 
    'Department', 
    'Designation',
    'Email',
    'Mobile No',
    'Alternate No',
    'Country',
    'State',
    'City',
    'Address',
    'Landmark',
    'Area',
    'Pincode'
    ],
    columns: [
      //First Name
      {
        type: 'text'
      },

      //Last Name
      {
        type: 'text'
      },

      //Gender
      {
        type: 'dropdown',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_gender_sources')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //DOB
      {
        type: 'date',
        dateFormat: 'YYYY-MM-DD'
      },

      //Anniversary
      {
        type: 'date',
        dateFormat: 'YYYY-MM-DD'
      },

      //Customer Type
      {
        type: 'dropdown',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_customer_type_sources')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Industry Type
      {
        type: 'dropdown',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_industry_type_sources')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Company Name
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_companies')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Department
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_departments')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Designation
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_designations')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Email
      {
        type: 'text'
      },

      //Mobile No
      {
        type: 'text'
      },

      //Alternate No
      {
        type: 'text'
      },

      //Country
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_countries_sources')
                    .then(function(response){
                    process(response.data);
                  });
                },
        strict: true
      },

      //State
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_states_sources')
                    .then(function(response){
                    process(response.data);
                  });
                },
        strict: true

      },

      //City
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_cities_sources')
                    .then(function(response){
                    process(response.data);
                  });
                },
        strict: true

      },

      //Address
      {
        type: 'text'
      },

      //Landmark
      {
        type: 'text'
      },

      //Area
      {
        type: 'autocomplete',
        source: function (query, process) {
                  $http.get(main_url+'customermanagement/get_all_areas')
                    .then(function(response){
                    process(response.data);
                  });
                }
      },

      //Pincode
      {
        type: 'text'
      }

    ]
  });

  // You can pass options by attributes..
/*
  $scope.db = {
    items:   {
    "id": 1,
    "name": {
      "first": "John",
      "last": "Schmidt"
    },
    "address": "45024 France",
    "price": 760.41,
    "isActive": "Yes",
    "product": {
      "description": "Fried Potatoes1",
      "options": [
        {
          "description": "Fried Potatoes1",
          "image": "//a248.e.akamai.net/assets.github.com/images/icons/emoji/fries.png"
        },
        {
          "description": "Fried Onions2",
          "image": "//a248.e.akamai.net/assets.github.com/images/icons/emoji/fries.png"
        }
      ]
    }
  }
  };
*/
/*
  $scope.db = {
    items: {}      };*/

  $scope.submitAddMultipleCustomer = function(){
    $scope.validationStatus = true;
    for(var i=0; i<data1.length; i++){

      var dFirstName = data1[i][0];
      var dLastName = data1[i][1];
      var dGender = data1[i][2];
      var dDOB = data1[i][3];
      var dDOA = data1[i][4];
      var dCustomerType = data1[i][5];
      var dIndustryType = data1[i][6];
      var dCompanyName = data1[i][7];
      var dDepartment = data1[i][8];
      var dDesignation = data1[i][9];
      var dEmail = data1[i][10];
      var dMobileNo = data1[i][11];
      var dAlternateNo = data1[i][12];
      var dCountry = data1[i][13];
      var dState = data1[i][14];
      var dCity = data1[i][15];
      var dAddress = data1[i][16];
      var dLandmark = data1[i][17];
      var dArea = data1[i][18];
      var dPincode = data1[i][19];
      var deMessage;

      if(dFirstName == null || dFirstName == ""){
        deMessage = "First Name is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dLastName == null || dLastName == ""){
        deMessage = "Last Name is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dGender == null || dGender == ""){
        deMessage = "Gender is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mGenders.indexOf(dGender)<0){
        deMessage = "Gender Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dCustomerType == null || dCustomerType == ""){
        deMessage = "Customer Type is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mCustomerTypes.indexOf(dCustomerType)<0){
        deMessage = "Customer Type Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dIndustryType == null || dIndustryType == ""){
        deMessage = "Industry Type is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mIndustryTypes.indexOf(dIndustryType)<0){
        deMessage = "Industry Type Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dCompanyName == null || dCompanyName == ""){
        deMessage = "Company Name is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dEmail == null || dEmail == ""){
        deMessage = "Email is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dMobileNo == null || dMobileNo == ""){
        deMessage = "Mobile No is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dCountry == null || dCountry == ""){
        deMessage = "Country is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mContries.indexOf(dCountry)<0){
        deMessage = "Country Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dState == null || dState == ""){
        deMessage = "State is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mStates.indexOf(dState)<0){
        deMessage = "State Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dCity == null || dCity == ""){
        deMessage = "City is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(mCities.indexOf(dCity)<0){
        deMessage = "City Selected Wrong in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dAddress == null || dAddress == ""){
        deMessage = "Address is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dArea == null || dArea == ""){
        deMessage = "Area is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }else if(dPincode == null || dPincode == ""){
        deMessage = "Pincode is blank in Row: "+(i+1);
        toastr.error(deMessage, 'Warning!');
        $scope.validationStatus = false;
        break;
      }/*else{
        
        var data = $.param({
          cusFirstName: dFirstName,
          cusLastName: dLastName,
          cusGender: dGender,
          cusDOB: dDOB,
          cusDOA: dDOA,
          cusCustomerType: dCustomerType,
          cusIndustryType: dIndustryType,
          cusCompanyName: dCompanyName,
          cusDepartment: dDepartment,
          cusDesignation: dDesignation,
          cusAddress: dAddress,
          cusLandmark: dLandmark,
          cusArea: dArea,
          cusCountry: dCountry,
          cusState: dState,
          cusCity: dCity,
          cusPinCode: dPincode,
          cusEmail: dEmail,
          cusMobileNo: dMobileNo,
          cusAlternateNo: dAlternateNo
        });


        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }


        $http.post(main_url+'customermanagement/add_multiple_customer',data,config)
        .then(function(response){

          if(response.data.status == "success"){

            deMessage = response.data.message+' of Row: '+i;
            toastr.success(deMessage,'Success!');

            data1.splice(i,1);


          }else{
            deMessage = response.data.message+' of Row: '+i;
            toastr.error(deMessage, 'Warning!');
          }


        },function(reject){
          // error handler 
          toastr.error('Something problem in internet, Please try Again', 'Warning!');    
        });

      }*/

    }

    if($scope.validationStatus == true){

      var data2 = data1;
      $scope.progressbar.start();

      

      for(var i=0; i<data2.length; i++){

        var dFirstName = data2[i][0];
        var dLastName = data2[i][1];
        var dGender = data2[i][2];
        var dDOB = data2[i][3];
        var dDOA = data2[i][4];
        var dCustomerType = data2[i][5];
        var dIndustryType = data2[i][6];
        var dCompanyName = data2[i][7];
        var dDepartment = data2[i][8];
        var dDesignation = data2[i][9];
        var dEmail = data2[i][10];
        var dMobileNo = data2[i][11];
        var dAlternateNo = data2[i][12];
        var dCountry = data2[i][13];
        var dState = data2[i][14];
        var dCity = data2[i][15];
        var dAddress = data2[i][16];
        var dLandmark = data2[i][17];
        var dArea = data2[i][18];
        var dPincode = data2[i][19];
        var deMessage;

        var data = $.param({
          cusFirstName: dFirstName,
          cusLastName: dLastName,
          cusGender: dGender,
          cusDOB: dDOB,
          cusDOA: dDOA,
          cusCustomerType: dCustomerType,
          cusIndustryType: dIndustryType,
          cusCompanyName: dCompanyName,
          cusDepartment: dDepartment,
          cusDesignation: dDesignation,
          cusAddress: dAddress,
          cusLandmark: dLandmark,
          cusArea: dArea,
          cusCountry: dCountry,
          cusState: dState,
          cusCity: dCity,
          cusPinCode: dPincode,
          cusEmail: dEmail,
          cusMobileNo: dMobileNo,
          cusAlternateNo: dAlternateNo
        });

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }


        $http.post(main_url+'customermanagement/add_multiple_customer',data,config)
        .then(function(response){

          if(response.data.status == "success"){

            deMessage = response.data.message+' of Row: '+i;
            toastr.success(deMessage,'Success!');

            data1.splice(i,1);

          }else if(response.data.status == "failed"){
            deMessage = response.data.message+' of Row: '+i;
            toastr.error(deMessage, 'Warning!');
          }else{
            toastr.error('Something problem in Internet, Please try Again', 'Warning!');
          }


        },function(reject){
          // error handler 
          toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
        });

      }


      if(data1.length == 1){
        data1.length = 0;
      }
      hot1.loadData(data1);
      $scope.progressbar.complete();

    }

  }


  function getAllGenderSources(){

    $http.get(main_url+'customermanagement/get_all_gender_sources')
        .then(function(response){

          mGenders = response.data;

        });
  }

  function getAllCustomerTypeSources(){

    $http.get(main_url+'customermanagement/get_all_customer_type_sources')
        .then(function(response){

          mCustomerTypes = response.data;

        });
  }

  function getAllIndustryTypeSources(){

    $http.get(main_url+'customermanagement/get_all_industry_type_sources')
        .then(function(response){

          mIndustryTypes = response.data;

        });
  }

  function getAllCountriesSources(){

    $http.get(main_url+'customermanagement/get_all_countries_sources')
        .then(function(response){

          mContries = response.data;

        });
  }

  function getAllStatesSources(){

    $http.get(main_url+'customermanagement/get_all_states_sources')
        .then(function(response){

          mStates = response.data;

        });
  }

  function getAllCitiesSources(){

    $http.get(main_url+'customermanagement/get_all_cities_sources')
        .then(function(response){

          mCities = response.data;

        });
  }

});


//************************ Meeting Controller *********************//
app.controller('meetingCtrl', function($scope, $http, toastr, Excel,$timeout) {

  $scope.dataloading = true;

  getAllMeetings();

  $scope.whatClassIsIt= function(someValue){
    if(someValue=="yes"){
      return "label label-success";
    }else{
      return "label label-danger";
    }
  };


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.refreshTable = function(){
    getAllMeetings();
    toastr.success("Table Refreshed",'Success!');
  }


  $scope.viewMeetingUpdates = function(item){

    $scope.activeMeeting =
      {
        mtnId: item.mtnId,
        mtnName: item.mtnName,
        mtnCustomerId: item.mtnCustomerId,
        mtnCustomerName: item.mtnCustomerName,
        mtnUserId: item.mtnUserId,
        mtnUserName: item.mtnUserName,
        mtnDate: item.mtnDate,
        mtnTime: item.mtnTime,
        mtnVisited: item.mtnVisited,
        mtnVisitedDate: item.mtnVisitedDate,
        mtnVisitedTime: item.mtnVisitedTime,
        mtnVisitedLat: item.mtnVisitedLat,
        mtnVisitedLong: item.mtnVisitedLong,
        mtnVisitedMessage: item.mtnVisitedMessage,
        mtnRemarks: item.mtnRemarks,
        mtnRemarksDate: item.mtnRemarksDate,
        mtnRemarksTime: item.mtnRemarksTime,
        mtnRemarksLat: item.mtnRemarksLat,
        mtnRemarksLong: item.mtnRemarksLong,
        mtnRemarksMessage: item.mtnRemarksMessage,
        mtnSignature: item.mtnSignature,
        mtnSignatureDate: item.mtnSignatureDate,
        mtnSignatureTime: item.mtnSignatureTime,
        mtnSignatureLat: item.mtnSignatureLat,
        mtnSignatureLong: item.mtnSignatureLong,
        mtnSignatureImage: item.mtnSignatureImage,
        mtnPicture: item.mtnPicture,
        mtnPictureDate: item.mtnPictureDate,
        mtnPictureTime: item.mtnPictureTime,
        mtnPictureLat: item.mtnPictureLat,
        mtnPictureLong: item.mtnPictureLong,
        mtnPictureImage: item.mtnPictureImage,
        mtnNextVisit: item.mtnNextVisit,
        mtnCompleted: item.mtnCompleted,
        mtnParentName: item.mtnParentName
      };

  };




  function getAllMeetings(){

    $http.get(main_url+'meetingmanagement/get_all_meetings')
        .then(function(response){

          $scope.meetings = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToEdit = function(item){
    window.location = main_url+"meetingmanagement/edit?id="+item.mtnId;
  }


});


//************************ Signup Controller *********************//
app.controller('addMeetingCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  getCustomerDetail();
  getAllUsers();
  getAllAdmins();
  getAllMeetingTypes();

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  $('#mtnDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    minDate : new Date()
  });

  $('#mtnTime').bootstrapMaterialDatePicker
  ({
    date: false,
    shortTime: false,
    format: 'HH:mm'
  });


  $scope.submitAddMeeting =  function(){

    $scope.mtnDate = $('#mtnDate').val();
    $scope.mtnTime = $('#mtnTime').val();

    if($scope.mtnDate == null || $scope.mtnDate == ""){
      toastr.error("Please Enter Meeting Date",'Warning!');
    }else if($scope.mtnTime == null || $scope.mtnTime == ""){
      toastr.error("Please Enter Meeting Time",'Warning!');
    }else{

      var data = $.param({
        mtnCustomerId: $scope.cusId,
        mtnUserId: $scope.mtnUserId.ausrId,
        mtnName: $scope.mtnName,
        mtnDate: $scope.mtnDate,
        mtnTime: $scope.mtnTime,
        mtnMeetingTypeId: $scope.mtnMeetingType.mtntId,
        mtnMeetingType: $scope.mtnMeetingType.mtntName,
        mtnTelecallerId: $scope.mtnTelecallerId.usrId,
        mtnComments: $scope.mtnComments
      });

      //alert(data);

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }

      $scope.progressbar.start();

      $http.post(main_url+'meetingmanagement/add_meeting',data,config)
      .then(function(response){

        if(response.data.status == "success"){
          $scope.progressbar.complete();
          toastr.success(response.data.message,'Success!');
          window.location = main_url+"customermanagement";
        }else if(response.data.status == "failed"){
          $scope.progressbar.complete();
          toastr.error(response.data.message, 'Warning!');
        }else{
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }


      });

    }

  }


  function getAllUsers(){

    $http.get(main_url+'usermanagement/get_all_app_users')
        .then(function(response){
          $scope.appUsers = response.data;

        });
  }


  function getAllAdmins(){

    $http.get(main_url+'meetingmanagement/get_all_admin_users')
        .then(function(response){
          $scope.adminUsers = response.data;

          $scope.mtnTelecallerId = $scope.adminUsers[0];

        });
  }




  function getAllMeetingTypes(){

    $http.get(main_url+'meetingmanagement/get_all_meeting_types')
        .then(function(response){
          $scope.meeting_types = response.data;

        });
  }


  function getCustomerDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'customermanagement/get_customer_detail?id='+$scope.id)
        .then(function(response){

          $scope.customer = response.data;
          $scope.cusId = $scope.customer.cusId;
          $scope.cusCode = $scope.customer.cusCode;
          $scope.cusFirstName = $scope.customer.cusFirstName;
          $scope.cusLastName = $scope.customer.cusLastName;

        });
  }

  $scope.cancelAddMeeting = function(){
    window.location = main_url+"customermanagement";
  }

});


//************************ Edit Meeting Controller *********************//
app.controller('editMeetingCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  $('#mtnDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    minDate : new Date()
  });

  $('#mtnTime').bootstrapMaterialDatePicker
  ({
    date: false,
    shortTime: false,
    format: 'HH:mm'
  });


  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getMeetingDetail();

  $scope.submitUpdateMeeting = function(){



    $scope.mtnDate = $('#mtnDate').val();
    $scope.mtnTime = $('#mtnTime').val();

    if($scope.mtnDate == null || $scope.mtnDate == ""){
      toastr.error("Please Enter Meeting Date",'Warning!');
    }else if($scope.mtnTime == null || $scope.mtnTime == ""){
      toastr.error("Please Enter Meeting Time",'Warning!');
    }else{


      var data = $.param({
        mtnId: $scope.mtnId,
        mtnUserId: $scope.mtnUserId.ausrId,
        mtnName: $scope.mtnName,
        mtnDate: $scope.mtnDate,
        mtnTime: $scope.mtnTime,
        mtnMeetingType: $scope.mtnMeetingType.mtntName,
        mtnMeetingTypeId: $scope.mtnMeetingType.mtntId,
        mtnTelecallerId: $scope.mtnTelecallerId.usrId,
        mtnComments: $scope.mtnComments
      });

      //alert(data);

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }


      $scope.progressbar.start();

      $http.post(main_url+'meetingmanagement/update_meeting',data,config)
      .then(function(response){

        if(response.data.status == "success"){

          $scope.progressbar.complete();
          toastr.success(response.data.message,'Success!');
          window.location = main_url+"meetingmanagement";

        }else if(response.data.status == "failed"){
          $scope.progressbar.complete();
          toastr.error(response.data.message, 'Warning!');
        }else{
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }


      });

    }

  }


  function getMeetingDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'meetingmanagement/get_meeting_detail?id='+$scope.id)
        .then(function(response){

          $scope.meeting = response.data;
          $scope.mtnId = $scope.meeting.mtnId;
          $scope.cusFirstName = $scope.meeting.mtnCustomerFirstName;
          $scope.cusLastName = $scope.meeting.mtnCustomerLastName;
          $scope.mtnName = $scope.meeting.mtnName;
          $scope.mtnDate = $scope.meeting.mtnDate;
          $scope.mtnTime = $scope.meeting.mtnTime;
          $scope.mtnMeetingTypeId = $scope.meeting.mtnMeetingTypeId;
          $scope.mtnUserId = $scope.meeting.mtnUserId;

          $scope.mtnTelecallerId = $scope.meeting.mtnTelecallerId;
          $scope.mtnComments = $scope.meeting.mtnComments;

          $("#mtnDate").val($scope.mtnDate);
          $("#mtnTime").val($scope.mtnTime);

          getAllUsers();
          getAllAdmins();
          getAllMeetingTypes();

        });
  }

  $scope.cancelEditMeeting = function(){
    window.location = main_url+"meetingmanagement";
  }


  function getAllUsers(){

    $http.get(main_url+'usermanagement/get_all_app_users')
        .then(function(response){
          $scope.appUsers = response.data;

          for(var i=0; i<$scope.appUsers.length; i++){
            if($scope.appUsers[i].ausrId == $scope.mtnUserId){
              $scope.mtnUserId = $scope.appUsers[i];
            }
          }

        });
  }


  function getAllAdmins(){

    $http.get(main_url+'meetingmanagement/get_all_admin_users')
        .then(function(response){
          $scope.adminUsers = response.data;

          for(var i=0; i<$scope.adminUsers.length; i++){
            if($scope.adminUsers[i].usrId == $scope.mtnTelecallerId){
              $scope.mtnTelecallerId = $scope.adminUsers[i];
            }
          }


        });
  }


  function getAllMeetingTypes(){

    $http.get(main_url+'meetingmanagement/get_all_meeting_types')
        .then(function(response){
          $scope.meeting_types = response.data;

          for(var i=0; i<$scope.meeting_types.length; i++){
            if($scope.meeting_types[i].mtntId == $scope.mtnMeetingTypeId){
              $scope.mtnMeetingType = $scope.meeting_types[i];
            }
          }

        });
  }


});


//************************ Setting Controller *********************//
app.controller('settingCtrl', function($scope, $http, toastr) {


  $scope.submitChangePassword = function(){

    if($scope.userNewPassword == $scope.userNewPasswordC){


      var data = $.param({
        userCurrentPassword: $scope.userCurrentPassword,
        userNewPassword: $scope.userNewPassword,
        userNewPasswordC: $scope.userNewPasswordC
      });

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }


      $http.post(main_url+'setting/update_password',data,config)
      .then(function(response){

        if(response.data.status == "success"){


          toastr.success(response.data.message,'Success!');
          

        }else if(response.data.status == "failed"){
          toastr.error(response.data.message, 'Warning!');
        }else{
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }


      });

    }else{

      toastr.error("Password Confirm doesn't Match", 'Warning!');

    }

  }




});


//************************ Customer Info Controller *********************//
app.controller('customerinfoCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;


  getAllCustomers();


  $scope.refreshTable = function(){
    getAllCustomers();
    toastr.success("Table Refreshed",'Success!');
  }


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }


  function getAllCustomers(){

    $http.get(main_url+'customerinfo/get_all_customers')
        .then(function(response){

          $scope.customers = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToDetail = function(item){
    window.location = main_url+"customerinfo/detail?id="+item.cusId;
  }


});


//************************ Customer Info Detail Controller *********************//
app.controller('customerinfodetailCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;


  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

    $scope.whatClassIsIt= function(someValue){
    if(someValue=="yes"){
      return "label label-success";
    }else{
      return "label label-danger";
    }
  };


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  getCustomerDetail();


  function getCustomerDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'customerinfo/get_customer_detail?id='+$scope.id)
        .then(function(response){

          $scope.customer = response.data;

          getCustomerMeetings();

        });
  }


  function getCustomerMeetings(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'customerinfo/get_all_customer_meetings?id='+$scope.id)
        .then(function(response){

          $scope.meetings = response.data;
          $scope.dataloading = false;

        });
  }


  $scope.viewMeetingUpdates = function(item){

    $scope.activeMeeting =
      {
        mtnId: item.mtnId,
        mtnName: item.mtnName,
        mtnCustomerId: item.mtnCustomerId,
        mtnCustomerName: item.mtnCustomerName,
        mtnUserId: item.mtnUserId,
        mtnUserName: item.mtnUserName,
        mtnDate: item.mtnDate,
        mtnTime: item.mtnTime,
        mtnVisited: item.mtnVisited,
        mtnVisitedDate: item.mtnVisitedDate,
        mtnVisitedTime: item.mtnVisitedTime,
        mtnVisitedLat: item.mtnVisitedLat,
        mtnVisitedLong: item.mtnVisitedLong,
        mtnVisitedMessage: item.mtnVisitedMessage,
        mtnRemarks: item.mtnRemarks,
        mtnRemarksDate: item.mtnRemarksDate,
        mtnRemarksTime: item.mtnRemarksTime,
        mtnRemarksLat: item.mtnRemarksLat,
        mtnRemarksLong: item.mtnRemarksLong,
        mtnRemarksMessage: item.mtnRemarksMessage,
        mtnSignature: item.mtnSignature,
        mtnSignatureDate: item.mtnSignatureDate,
        mtnSignatureTime: item.mtnSignatureTime,
        mtnSignatureLat: item.mtnSignatureLat,
        mtnSignatureLong: item.mtnSignatureLong,
        mtnSignatureImage: item.mtnSignatureImage,
        mtnPicture: item.mtnPicture,
        mtnPictureDate: item.mtnPictureDate,
        mtnPictureTime: item.mtnPictureTime,
        mtnPictureLat: item.mtnPictureLat,
        mtnPictureLong: item.mtnPictureLong,
        mtnPictureImage: item.mtnPictureImage,
        mtnNextVisit: item.mtnNextVisit,
        mtnCompleted: item.mtnCompleted,
        mtnParentName: item.mtnParentName
      };

  };



});


//************************ Expense Controller *********************//
app.controller('expenseCtrl', function($scope, $http, toastr) {

  var viewer = ImageViewer();

  $scope.imageDisplay = false;

  $scope.dataloading = true;
  

  getAllExpenses();

  //$('.pannable-image').ImageViewer();

  $scope.viewImageViewer = function(item){
    /*
    var viewer = ImageViewer();
    viewer.show(item, item);*/
/*
    $('#expense-image-container').ImageViewer();
    var viewer = $('#expense-container').data();*/

    $("#expense-image-container").show();
    $("#expense-image-view").hide();

    $('#expense-image-container').ImageViewer();

    //viewer = ImageViewer('#expense-image-container');

    //viewer.refresh();

    //viewer.show(item, item);
/*
    var viewer = ImageViewer('#expense-image-container', {
    maxZoom : 400
    });*/
    


  }


  $scope.whatClassIsIt= function(someValue){
    if(someValue=="yes" || someValue=="paid"){
      return "label label-success";
    }else if(someValue=="pending"){
      return "label label-warning";
    }else{
      return "label label-danger";
    }
  };




  $scope.refreshTable = function(){
    getAllExpenses();
    toastr.success("Table Refreshed",'Success!');
  }


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.viewExpense = function(item){

    $("#expense-image-container").hide();
    $("#expense-image-view").show();



    $scope.activeExpense =
      {
        expId: item.expId,
        expTitle: item.expTitle,
        expAmount: item.expAmount,
        expMeetingName: item.expMeetingName,
        expMeetingCustomerName: item.expMeetingCustomerName,
        expDescription: item.expDescription,
        expImageStatus: item.expImageStatus,
        expImageAvailable: item.expImageAvailable,
        expIsMeetingAssociated: item.expIsMeetingAssociated,
        expImage: item.expImage
      };

    if(item.expImageStatus == "true"){
      $scope.imageDisplay = false;
    }else{
      $scope.imageDisplay = true;
    }

    if(item.expIsMeetingAssociated == "yes"){
      $scope.meetingNameDisplay = false;
      $scope.customerNameDisplay = false;
    }else{
      $scope.meetingNameDisplay = true;
      $scope.customerNameDisplay = true;
    }

    

  };




  $scope.submitPaid = function(item){

    var data = $.param({
      expId: item.expId,
      expPaymentStatus: 'paid'
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'expensemanagement/change_payment_status',data,config)
    .then(function(response){

      if(response.data.status == "success"){
        toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
        getAllExpenses();
      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }


    });

  }


  $scope.submitUnpaid = function(item){

    var data = $.param({
      expId: item.expId,
      expPaymentStatus: 'unpaid'
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'expensemanagement/change_payment_status',data,config)
    .then(function(response){

      if(response.data.status == "success"){
        toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
        getAllExpenses();
      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }else{
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }


    });

  }


  function getAllExpenses(){

    $http.get(main_url+'expensemanagement/get_all_expenses')
        .then(function(response){

          $scope.expenses = response.data;
          $scope.dataloading = false;

        });
  }

});


//************************ Customers Report Controller *********************//
app.controller('customersreportCtrl', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();


  $('#dob, #doa').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    maxDate: new Date()
  });

  getAllAdmins();
  getAllCustomerTypes();
  getAllIndustryTypes();
  getAllCompanies();
  getAllAreas();



  function getAllAdmins(){

    $http.get(main_url+'customersreport/get_all_admins')
        .then(function(response){

          $scope.admins = response.data;
          $scope.selectedAdmin = $scope.admins[0];
          $scope.getAdminUsers();

        });
  }

  $scope.getAdminUsers = function(){

    $http.get(main_url+'customersreport/get_all_admin_users?id='+$scope.selectedAdmin.adminId)
        .then(function(response){

          $scope.users = response.data;
          $scope.selectedUser = $scope.users[0];

        });    

  }


  function getAllCustomerTypes(){

    $http.get(main_url+'customersreport/get_all_customer_types')
        .then(function(response){

          $scope.customertypes = response.data;
          $scope.selectedCustomerType = $scope.customertypes[0];

        });
  }


  function getAllIndustryTypes(){

    $http.get(main_url+'customersreport/get_all_industry_types')
        .then(function(response){

          $scope.industrytypes = response.data;
          $scope.selectedIndustryType = $scope.industrytypes[0];

        });
  }


  function getAllCompanies(){

    $http.get(main_url+'customersreport/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;
          $scope.selectedCompany = $scope.companies[0];

        });
  }


  function getAllAreas(){

    $http.get(main_url+'customersreport/get_all_areas')
        .then(function(response){

          $scope.areas = response.data;
          $scope.selectedArea = $scope.areas[0];

        });
  }


  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }



  $scope.getReports = function(){

    $scope.dob = $('#dob').val();
    $scope.doa = $('#doa').val();

      var data = $.param({
        adminId: $scope.selectedAdmin.adminId,
        adminName: $scope.selectedAdmin.adminName,
        adminParentPath: $scope.selectedAdmin.adminParentPath,
        userId: $scope.selectedUser.usrId,
        userName: $scope.selectedUser.usrName,
        customerTypeId: $scope.selectedCustomerType.custId,
        customerTypeName: $scope.selectedCustomerType.custName,
        industryTypeId: $scope.selectedIndustryType.indtId,
        industryTypeName: $scope.selectedIndustryType.indtName,
        companyId: $scope.selectedCompany.cmpId,
        companyName: $scope.selectedCompany.cmpName,
        areaId: $scope.selectedArea.areaId,
        areaName: $scope.selectedArea.areaName,
        dob: $scope.dob,
        doa: $scope.doa
      });

      $scope.progressbar.start();


      $http.get(main_url+'customersreport/get_customer_records?'+data)
      .then(function(response){

            $scope.progressbar.complete();

            $scope.customers = response.data;

            //$scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

      },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });



  }


  $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});


//************************ Meeting Report Controller *********************//
app.controller('meetingreportCtrl', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory) {


  $scope.progressbar = ngProgressFactory.createInstance();


  $scope.datetypes = [
    {"dttId":"1","dttValue":"today","dttName":"Today"},
    {"dttId":"2","dttValue":"tomorrow","dttName":"Tomorrow"},
    {"dttId":"3","dttValue":"coming7days","dttName":"Coming 7 Days"},
    {"dttId":"4","dttValue":"last7days","dttName":"Last 7 Days"},
    {"dttId":"5","dttValue":"last30days","dttName":"Last 30 Days"},
    {"dttId":"6","dttValue":"lifetime","dttName":"Life Time"},
    {"dttId":"7","dttValue":"daterange","dttName":"Custom Date Range"}
  ];
  


  $('#fromDate, #toDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true
  });

  getAllAdmins();
  getAllCustomerTypes();
  getAllIndustryTypes();
  getAllCompanies();


  $scope.getDateType = function(){
    if($scope.selectedDateType.dttValue == "daterange"){

      $scope.fromDate = false;
      $scope.toDate = false;

    }else{

      $scope.fromDate = true;
      $scope.toDate = true;
      $("#fromDate").val('');
      $("#toDate").val('');

    }

  }

  $scope.selectedDateType = $scope.datetypes[0];
  $scope.getDateType();



  function getAllAdmins(){

    $http.get(main_url+'meetingreport/get_all_admins')
        .then(function(response){

          $scope.admins = response.data;
          $scope.selectedAdmin = $scope.admins[0];
          $scope.getAdminUsers();

        });
  }

  $scope.getAdminUsers = function(){


    $http.get(main_url+'meetingreport/get_all_admin_users?id='+$scope.selectedAdmin.adminId)
        .then(function(response){

          $scope.users = response.data;
          $scope.selectedUser = $scope.users[0];

        });    

  }


  function getAllCustomerTypes(){

    $http.get(main_url+'meetingreport/get_all_customer_types')
        .then(function(response){

          $scope.customertypes = response.data;
          $scope.selectedCustomerType = $scope.customertypes[0];

        });
  }


  function getAllIndustryTypes(){

    $http.get(main_url+'meetingreport/get_all_industry_types')
        .then(function(response){

          $scope.industrytypes = response.data;
          $scope.selectedIndustryType = $scope.industrytypes[0];

        });
  }


  function getAllCompanies(){

    $http.get(main_url+'meetingreport/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;
          $scope.selectedCompany = $scope.companies[0];

        });
  }



  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }



  $scope.getReports = function(){

    $scope.fromDate = $('#fromDate').val();
    $scope.toDate = $('#toDate').val();
    $scope.dob = $('#dob').val();
    $scope.doa = $('#doa').val();

    var startDate = new Date($scope.fromDate);
    var endDate = new Date($scope.toDate);

    if($scope.fromDate!="" && $scope.toDate!="" && endDate < startDate){
      toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
    }else{

      var data = $.param({
        dateTypeId: $scope.selectedDateType.dttId,
        dateTypeValue: $scope.selectedDateType.dttValue,
        dateTypeName: $scope.selectedDateType.dttName,
        fromDate: $scope.fromDate,
        toDate: $scope.toDate,
        adminId: $scope.selectedAdmin.adminId,
        adminName: $scope.selectedAdmin.adminName,
        adminParentPath: $scope.selectedAdmin.adminParentPath,
        userId: $scope.selectedUser.usrId,
        userName: $scope.selectedUser.usrName,
        customerTypeId: $scope.selectedCustomerType.custId,
        customerTypeName: $scope.selectedCustomerType.custName,
        industryTypeId: $scope.selectedIndustryType.indtId,
        industryTypeName: $scope.selectedIndustryType.indtName,
        companyId: $scope.selectedCompany.cmpId,
        companyName: $scope.selectedCompany.cmpName
      });


      $scope.progressbar.start();


      $http.get(main_url+'meetingreport/get_meeting_records_by_date?'+data)
      .then(function(response){

            $scope.progressbar.complete();

            $scope.meetings = response.data;

            $scope.getDateType();

            //$scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

      },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });

    }

  }


  $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});


//************************ Order Report Controller *********************//
app.controller('orderreportCtrl', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory) {


  $scope.progressbar = ngProgressFactory.createInstance();


  $('#fromDate, #toDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true
  });

  getAllAdmins();
  getAllCustomerTypes();
  getAllIndustryTypes();
  getAllCompanies();
  getAllOrderStatusTypes();

  function getAllAdmins(){

    $http.get(main_url+'orderreport/get_all_admins')
        .then(function(response){

          $scope.admins = response.data;
          $scope.selectedAdmin = $scope.admins[0];
          $scope.getAdminUsers();

        });
  }

  $scope.getAdminUsers = function(){


    $http.get(main_url+'orderreport/get_all_admin_users?id='+$scope.selectedAdmin.adminId)
        .then(function(response){

          $scope.users = response.data;
          $scope.selectedUser = $scope.users[0];

        });    

  }


  function getAllCustomerTypes(){

    $http.get(main_url+'orderreport/get_all_customer_types')
        .then(function(response){

          $scope.customertypes = response.data;
          $scope.selectedCustomerType = $scope.customertypes[0];

        });
  }


  function getAllIndustryTypes(){

    $http.get(main_url+'orderreport/get_all_industry_types')
        .then(function(response){

          $scope.industrytypes = response.data;
          $scope.selectedIndustryType = $scope.industrytypes[0];

        });
  }


  function getAllCompanies(){

    $http.get(main_url+'orderreport/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;
          $scope.selectedCompany = $scope.companies[0];

        });
  }

  function getAllOrderStatusTypes(){

    $http.get(main_url+'orderreport/get_all_order_status_types')
        .then(function(response){

          $scope.statustypes = response.data;
          $scope.selectedStatus = $scope.statustypes[0];

        });
  }


  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }


  $scope.getReports = function(){

    $scope.fromDate = $('#fromDate').val();
    $scope.toDate = $('#toDate').val();

    var startDate = new Date($scope.fromDate);
    var endDate = new Date($scope.toDate);

    if($scope.fromDate == null || $scope.fromDate == ""){
        toastr.error("Please Select From Date",'Warning!');
    }else if($scope.toDate == null || $scope.toDate == ""){
        toastr.error("Please Select To Date",'Warning!');
    }else if(endDate < startDate){
      toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
    }else{

      var data = $.param({
        fromDate: $scope.fromDate,
        toDate: $scope.toDate,
        orderStatusId: $scope.selectedStatus.ostId,
        orderStatus: $scope.selectedStatus.ostName,
        adminId: $scope.selectedAdmin.adminId,
        adminName: $scope.selectedAdmin.adminName,
        userId: $scope.selectedUser.usrId,
        userName: $scope.selectedUser.usrName,
        customerTypeId: $scope.selectedCustomerType.custId,
        customerTypeName: $scope.selectedCustomerType.custName,
        industryTypeId: $scope.selectedIndustryType.indtId,
        industryTypeName: $scope.selectedIndustryType.indtName,
        companyId: $scope.selectedCompany.cmpId,
        companyName: $scope.selectedCompany.cmpName
      });

      $scope.progressbar.start();

      $http.get(main_url+'orderreport/get_order_records_by_date?'+data)
      .then(function(response){

            $scope.progressbar.complete();

            $scope.orders = response.data;

            $scope.orderTotalAmount = 0;

            $scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

            if($scope.orders.length <= 0){
              $scope.orderTotalAmount = 0;
            }

            for(var i=0; i<$scope.orders.length; i++){

              $scope.orderTotalAmount = $scope.orderTotalAmount + parseFloat($scope.orders[i].ordAmount);

            }

      },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });

    }
  }

  $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});


//************************ Login Report Controller *********************//
    app.controller('loginreportCtrl', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory) {
    
        getAllUsers = () => {
            $http.get(main_url+'loginreport/get_all_users')
            .then(function(response){
                $scope.appUsers     =   response.data;
            });
        }

        getAllUsers();

        $('#fromDate, #toDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true,
            maxDate: new Date()
        });

        $scope.sort = function(keyname){
            $scope.sortKey = keyname;  
            $scope.reverse = !$scope.reverse; 
        }

        $scope.getReports = function(){
        
            $scope.fromDate =   $('#fromDate').val();
            $scope.toDate   =   $('#toDate').val();

            var startDate   =   new Date($scope.fromDate);
            var endDate     =   new Date($scope.toDate);
          
            $scope.dataloading = true;

            if($scope.fromDate == null || $scope.fromDate == ""){
                toastr.error("Please Select From Date",'Warning!');
            }
            else if($scope.toDate == null || $scope.toDate == ""){
                toastr.error("Please Select To Date",'Warning!');
            }
            else if(endDate < startDate){
                toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
            }
            else{

                var data = $.param({
                    fromDate: $scope.fromDate,
                    toDate: $scope.toDate
                });

                $http.get(main_url+'loginreport/get_login_records_by_date?'+data)
                .then(function(response){
                    $scope.logins           =   response.data;
                    $scope.dataloading      =   false;
                    $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
                });
            }
        }

        $scope.exportToPDF = function(printSectionId){
            html2canvas(document.getElementById(printSectionId),{
                onrendered: function (canvas){
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500,
                        }]
                    };
                  pdfMake.createPdf(docDefinition).download("report.pdf");
                }
            });
        }

        $scope.exportToExcel=function(tableId){
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100);
        }

        $scope.printToReport = function(printSectionId){
            var innerContents = document.getElementById(printSectionId).innerHTML;
            var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
            popupWinindow.document.open();
            popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
            popupWinindow.document.close();
        }
    });


//************************ Trip Report Controller *********************//
app.controller('tripreportCtrl', function($scope, $http, toastr, Excel, $timeout) {

  getAllUsers();
  $scope.grandTotalDistance = 0;


  function getAllUsers(){

    $http.get(main_url+'tripreport/get_all_users')
        .then(function(response){

          $scope.appUsers = response.data;

        });
  }


  $('#fromDate, #toDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    maxDate: new Date()
  });


  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }


  $scope.getReports = function(){

    $scope.fromDate = $('#fromDate').val();
    $scope.toDate = $('#toDate').val();

    var startDate = new Date($scope.fromDate);
    var endDate = new Date($scope.toDate);

    if($scope.fromDate == null || $scope.fromDate == ""){
        toastr.error("Please Select From Date",'Warning!');
    }else if($scope.toDate == null || $scope.toDate == ""){
        toastr.error("Please Select To Date",'Warning!');
    }else if($scope.selectedUser == null || $scope.selectedUser == ""){
        toastr.error("Please Select User",'Warning!');
    }else if(endDate < startDate){
      toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
    }else{

      var data = $.param({
        fromDate: $scope.fromDate,
        toDate: $scope.toDate,
        userId: $scope.selectedUser.usrId
      });

      $http.get(main_url+'tripreport/get_trip_records_by_date?'+data)
      .then(function(response){

            $scope.trips = response.data;

            $scope.grandTotalDistance = 0;

            $scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

            if($scope.trips.length <= 0){
              $scope.grandTotalDistance = 0;
            }

            for(var i=0; i<$scope.trips.length; i++){

              $scope.grandTotalDistance = $scope.grandTotalDistance + parseFloat($scope.trips[i].trpDistance);

            }

      });

    }

  }

  $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});


//************************ Expense Report Controller *********************//
app.controller('expensereportCtrl', function($scope, $http, toastr, Excel, $timeout) {

  getAllUsers();
  $scope.expenseTotalAmount = 0;

  $scope.paymenttypes = [
    {"statusId":"1","statusValue":"all","statusName":"All"},
    {"statusId":"2","statusValue":"pending","statusName":"Pending"},
    {"statusId":"3","statusValue":"paid","statusName":"Paid"},
    {"statusId":"4","statusValue":"unpaid","statusName":"Unpaid"},
  ];


  function getAllUsers(){

    $http.get(main_url+'tripreport/get_all_users')
        .then(function(response){

          $scope.appUsers = response.data;

        });
  }


  $('#fromDate, #toDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    maxDate: new Date()
  });


  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }


  $scope.getReports = function(){

    $scope.fromDate = $('#fromDate').val();
    $scope.toDate = $('#toDate').val();

    var startDate = new Date($scope.fromDate);
    var endDate = new Date($scope.toDate);

    if($scope.fromDate == null || $scope.fromDate == ""){
        toastr.error("Please Select From Date",'Warning!');
    }else if($scope.toDate == null || $scope.toDate == ""){
        toastr.error("Please Select To Date",'Warning!');
    }else if($scope.selectedUser == null || $scope.selectedUser == ""){
        toastr.error("Please Select User",'Warning!');
    }else if($scope.selectedPaymentStatus == null || $scope.selectedPaymentStatus == ""){
        toastr.error("Please Select Payment Status",'Warning!');
    }else if(endDate < startDate){
      toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
    }else{

      var data = $.param({
        fromDate: $scope.fromDate,
        toDate: $scope.toDate,
        userId: $scope.selectedUser.usrId,
        paymentStatus: $scope.selectedPaymentStatus.statusValue
      });

      

      $http.get(main_url+'expensereport/get_expense_records_by_date?'+data)
      .then(function(response){

            $scope.expenses = response.data;

            $scope.expenseTotalAmount = 0;

            $scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

            if($scope.expenses.length <= 0){
              $scope.expenseTotalAmount = 0;
            }

            for(var i=0; i<$scope.expenses.length; i++){

              $scope.expenseTotalAmount = $scope.expenseTotalAmount + parseFloat($scope.expenses[i].expAmount);

            }

      });

    }

  }

  $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});


//************************ Leave Controller *********************//
app.controller('leaveCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;

  getAllPendingLeaveRequest();
  getAllAcceptedLeaveRequest();
  getAllRejectedLeaveRequest();

    $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }

  $scope.refreshPendingTable = function(){
    getAllPendingLeaveRequest();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.refreshAcceptedTable = function(){
    getAllAcceptedLeaveRequest();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.refreshRejectedTable = function(){
    getAllRejectedLeaveRequest();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.viewPendingRequest = function(item){

    $scope.activeRequest =
      {
        plreqId: item.plreqId,
        plreqUserName: item.plreqUserName,
        plreqSubject: item.plreqSubject,
        plreqDescription: item.plreqDescription,
        plreqStatus: item.plreqStatus
      };

  }

  $scope.viewAcceptedRequest = function(item){

    $scope.activeAcceptedRequest =
      {
        alreqId: item.alreqId,
        alreqUserName: item.alreqUserName,
        alreqSubject: item.alreqSubject,
        alreqDescription: item.alreqDescription,
        alreqStatus: item.alreqStatus
      };

  }

  $scope.viewRejectedRequest = function(item){

    $scope.activeRejectedRequest =
      {
        rlreqId: item.rlreqId,
        rlreqUserName: item.rlreqUserName,
        rlreqSubject: item.rlreqSubject,
        rlreqDescription: item.rlreqDescription,
        rlreqStatus: item.rlreqStatus
      };

  }

  $scope.acceptPendingRequest = function(item){

    $scope.acceptActiveRequest =
      {
        plreqId: item.plreqId,
        plreqUserName: item.plreqUserName,
        plreqSubject: item.plreqSubject,
        plreqDescription: item.plreqDescription,
        plreqStatus: item.plreqStatus
      };

  }

  $scope.updateAcceptRequest = function(){

    var data = $.param({
      lreqId: $scope.acceptActiveRequest.plreqId,
      lreqStatus: 'accepted',
      lreqStatusId: 2,
      lreqStatusMessage: $scope.acceptMessage
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }


    $http.post(main_url+'leavemanagement/update_accept_pending_leave_request',data,config)
    .then(function(response){

      if(response.data.status == "success"){


        toastr.success(response.data.message,'Success!');
        $('.modal-update-accept-request').modal('hide');
        getAllPendingLeaveRequest();

      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }else{
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }

    });

  }


  $scope.rejectPendingRequest = function(item){

    $scope.rejectActiveRequest =
      {
        plreqId: item.plreqId,
        plreqUserName: item.plreqUserName,
        plreqSubject: item.plreqSubject,
        plreqDescription: item.plreqDescription,
        plreqStatus: item.plreqStatus
      };

  }

  $scope.updateRejectRequest = function(){

    var data = $.param({
      lreqId: $scope.rejectActiveRequest.plreqId,
      lreqStatus: 'rejected',
      lreqStatusId: 3,
      lreqStatusMessage: $scope.rejectMessage
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }


    $http.post(main_url+'leavemanagement/update_reject_pending_leave_request',data,config)
    .then(function(response){

      if(response.data.status == "success"){


        toastr.success(response.data.message,'Success!');
        $('.modal-update-reject-request').modal('hide');
        getAllPendingLeaveRequest();

      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }else{
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }

    });

  }

  function getAllPendingLeaveRequest(){

    $http.get(main_url+'leavemanagement/get_all_pending_leave_request')
        .then(function(response){

          $scope.plrequests = response.data;
          $scope.dataloading = false;

        });
  }


  function getAllAcceptedLeaveRequest(){

    $http.get(main_url+'leavemanagement/get_all_accepted_leave_request')
        .then(function(response){

          $scope.alrequests = response.data;

        });
  }


  function getAllRejectedLeaveRequest(){

    $http.get(main_url+'leavemanagement/get_all_rejected_leave_request')
        .then(function(response){

          $scope.rlrequests = response.data;

        });
  }



});


//************************ Meeting Re-assignment Controller *********************//
app.controller('meetingreassignmentCtrl', function($scope, $http, toastr, Excel,$timeout) {

  $scope.selectedmeeting = [];

  $scope.dataloading = true;

  getAllMeetings();
  getAllUsers();

  $scope.whatClassIsIt= function(someValue){
    if(someValue=="yes"){
      return "label label-success";
    }else{
      return "label label-danger";
    }
  };


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.refreshTable = function(){
    getAllMeetings();
    toastr.success("Table Refreshed",'Success!');
  }


  $scope.submitSelectedMeeting = function(){
    var selected_meeting_no = 0;
    for(var i=0; i<$scope.meetings.length; i++){
      if($scope.meetings[i].isChecked == true){

        selected_meeting_no = selected_meeting_no + 1;

        var data = $.param({
          mtnId: $scope.meetings[i].mtnId,
          mtnUserId: $scope.mtnUserId.ausrId
        });

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }


        $http.post(main_url+'meetingreassignment/update_meeting',data,config)
        .then(function(response){


        });
        
      }

    }
    if(selected_meeting_no == 0){
      toastr.error("Please Select Meeting",'Warning!');
    }else{
      toastr.success("Meeting Re-Assigned Successfully",'Success!');
      getAllMeetings();
    }
    $('.modal-meeting-updates').modal('hide');
  }


  function getAllMeetings(){

    $http.get(main_url+'meetingreassignment/get_all_meetings')
        .then(function(response){

          $scope.meetings = response.data;
          $scope.dataloading = false;

        });
  }

  function getAllUsers(){

    $http.get(main_url+'usermanagement/get_all_app_users')
        .then(function(response){
          $scope.appUsers = response.data;

        });
  }

  $scope.goToEdit = function(item){
    window.location = main_url+"meetingmanagement/edit?id="+item.mtnId;
  }


});


//************************ Order Controller *********************//
app.controller('orderCtrl', function($scope, $http, toastr) {

  getAllOrders();
  $scope.dataloading = true;

  $scope.whatClassIsIt= function(someValue){
      if(someValue=="Confirmed"){
        return "label label-success";
      }else if(someValue=="Cancelled" || someValue=="Lost"){
        return "label label-danger"
      }else{
        return "label label-warning";
      }
    };


  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.refreshTable = function(){
    getAllOrders();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.viewOrderDetails = function(item){

    $scope.activeOrder =
      {
        ordId: item.ordId,
        ordName: item.ordName,
        ordUnitId: item.ordUnitId,
        ordUnit: item.ordUnit,
        ordVenueId: item.ordVenueId,
        ordVenue: item.ordVenue,
        ordQuantity: item.ordQuantity,
        ordAmount: item.ordAmount,
        ordDescription: item.ordDescription,
        ordForDate: item.ordForDate,
        ordDate: item.ordDate,
        ordTime: item.ordTime,
        ordParentName: item.ordParentName,
        ordMeetingName: item.ordMeetingName,
        ordCustomerName: item.ordCustomerName,
        ordCustomerCompanyName: item.ordCustomerCompanyName,
        ordCustomerAddress: item.ordCustomerAddress
      };

  }


  function getAllOrders(){

    $http.get(main_url+'ordermanagement/get_all_orders')
        .then(function(response){
          $scope.orders = response.data;
          $scope.dataloading = false;

        });
  }


  $scope.goToEdit = function(item){
    window.location = main_url+"ordermanagement/edit?id="+item.ordId;
  }



});


//************************ Edit Order Controller *********************//
app.controller('editorderCtrl', function($scope, $http, toastr) {

  $('#ordForDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true,
    minDate : new Date()
  });


  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getOrderDetail();

  $scope.updateOrder = function(){

    $scope.ordForDate = $('#ordForDate').val();


    if($scope.ordForDate == null || $scope.ordForDate == ""){
      toastr.error("Please Enter Order Date",'Warning!');
    }else{


      var data = $.param({
        ordId: $scope.ordId,
        ordName: $scope.ordName,
        ordUnitId: $scope.ordUnitSelect.untId,
        ordUnit: $scope.ordUnitSelect.untName,
        ordVenueId: $scope.ordVenueSelect.venId,
        ordVenue: $scope.ordVenueSelect.venShortName,
        ordQuantity: $scope.ordQuantity,
        ordAmount: $scope.ordAmount,
        ordForDate: $scope.ordForDate,
        ordDescription: $scope.ordDescription,
        ordStatusId: $scope.ordStatusSelect.ostId,
        ordStatus: $scope.ordStatusSelect.ostName
      });

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }


      $http.post(main_url+'ordermanagement/update_order',data,config)
      .then(function(response){

        if(response.data.status == "success"){

          toastr.success(response.data.message,'Success!');
          window.location = main_url+"ordermanagement";

        }else if(response.data.status == "failed"){
          toastr.error(response.data.message, 'Warning!');
        }


      });



    }


  }


  function getOrderDetail(){

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'ordermanagement/get_order_detail?id='+$scope.id)
        .then(function(response){

          $scope.order = response.data;
          $scope.ordId = $scope.order.ordId;
          $scope.ordName = $scope.order.ordName;
          $scope.ordUnitId = $scope.order.ordUnitId;
          $scope.ordUnit = $scope.order.ordUnit;
          $scope.ordVenueId = $scope.order.ordVenueId;
          $scope.ordVenue = $scope.order.ordVenue;
          $scope.ordQuantity = parseInt($scope.order.ordQuantity);
          $scope.ordAmount = parseInt($scope.order.ordAmount);
          $scope.ordForDate = $scope.order.ordForDate;
          $scope.ordDescription = $scope.order.ordDescription;
          $scope.ordParentId = $scope.order.ordParentId;
          $scope.ordStatusId = $scope.order.ordStatusId;

          $('#ordForDate').val($scope.ordForDate);

          getAllUnits();
          getAllOrderStatusTypes();

        });
  }

  function getAllUnits(){

    var data = $.param({
      ordParentId: $scope.ordParentId
    });

    $http.get(main_url+'ordermanagement/get_all_user_units?'+data)
        .then(function(response){

          $scope.units = response.data;

          for(var i=0; i<$scope.units.length; i++){
            if($scope.units[i].untId == $scope.ordUnitId){
              $scope.ordUnitSelect = $scope.units[i];
              $scope.getVenues();
              break;
            }
          }

        });
  }

  $scope.getVenues =  function(){

    $http.get(main_url+'ordermanagement/get_all_venues_by_unit?id='+$scope.ordUnitSelect.untId)
        .then(function(response){

          $scope.venues = response.data;

          for(var i=0; i<$scope.venues.length; i++){
            if($scope.venues[i].venId == $scope.ordVenueId){
              $scope.ordVenueSelect = $scope.venues[i];
              break;
            }
          }

        });
  }

  function getAllOrderStatusTypes(){

    $http.get(main_url+'ordermanagement/get_all_order_status_types')
        .then(function(response){

          $scope.statustypes = response.data;

          for(var i=0; i<$scope.statustypes.length; i++){
            if($scope.statustypes[i].ostId == $scope.ordStatusId){
              $scope.ordStatusSelect = $scope.statustypes[i];
              break;
            }
          }

        });
  }

  $scope.cancelEditOrder = function(){
    window.location = main_url+"ordermanagement";
  }




});


  //************************ Route History Controller *********************//
    
    app.controller('routehistoryCtrl', function($scope, $http, toastr){

        getAllUsers = () => {
            $http.get(main_url+'tripreport/get_all_users')
                .then(function(response){
                $scope.appUsers = response.data;
            });
        }

        getAllUsers();

        var today       = new Date()
        var priorDate   = new Date().setDate(today.getDate()-39)

        $('#selectDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true,
            maxDate: new Date(),
            minDate: new Date(priorDate)
        });

        $scope.submitRoute = function(){
            $scope.selectDate  =  $('#selectDate').val();

            var date1       = new Date($scope.selectDate);
            var date2       = new Date();
            var timeDiff    = Math.abs(date2.getTime() - date1.getTime());
            var diffDays    = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

            if($scope.selectDate == null || $scope.selectDate == ""){
                toastr.error("Please Select Date",'Warning!');
            }
            else if($scope.selectedUser == null || $scope.selectedUser == ""){
                toastr.error("Please Select User",'Warning!');
            }
            else if(diffDays > 40){
                toastr.error("Only 40 days Old Route Record is Available",'Sorry!');
            }
            else{
              getLoginRecords();
            }
        }

        var map;
        var markerPinGreen      =       main_url+'assets/markericon/marker_pin_green.png';
        var markerPinRed        =       main_url+'assets/markericon/marker_pin_red.png';
        var markerFlagRed       =       main_url+'assets/markericon/marker_flag_red.png';
        var markerBallPinGreen  =       main_url+'assets/markericon/marker_ballpin_green.png';
        var markerBallPinRed    =       main_url+'assets/markericon/marker_ballpin_red.png';
        var markerFlagPinGreen  =       main_url+'assets/markericon/marker_flagpin_green.png';
        var markerFlagPinBlue   =       main_url+'assets/markericon/marker_flagpin_blue.png';

        var iconMarkerPinGreen = {
            url: main_url+'assets/markericon/marker_pin_green.png', // url
            scaledSize: new google.maps.Size(32, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };

        var iconMarkerLogin = {
            url: main_url+'assets/markericon/marker_login.png', // url
            scaledSize: new google.maps.Size(50, 50) // scaled size
        };

        var iconMarkerLogout = {
            url: main_url+'assets/markericon/marker_logout.png', // url
            scaledSize: new google.maps.Size(50, 50) // scaled size
        };

        var iconMarkerStartTrip = {
            url: main_url+'assets/markericon/marker_start_trip.png', // url
            scaledSize: new google.maps.Size(32, 32) // scaled size
        };

        var iconMarkerStopTrip = {
            url: main_url+'assets/markericon/marker_stop_trip.png', // url
            scaledSize: new google.maps.Size(48, 48) // scaled size
        };

        var iconMarkerVisited = {
            url: main_url+'assets/markericon/marker_visited.png', // url
            scaledSize: new google.maps.Size(24, 24) // scaled size
        };

        var symbolOne = {
            path: 'M -2,0 0,-2 2,0 0,2 z',
            strokeColor: '#F00',
            fillColor: '#F00',
            fillOpacity: 1
        };

        initialize = () => {
            var latlng = new google.maps.LatLng(22.5726,88.3639);
            var myOptions = {
                zoom: 8,
                center: latlng,
                panControl: true,
                zoomControl: true,
                scaleControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map         =   new google.maps.Map(document.getElementById("map"), myOptions);
            infowindow  =   new google.maps.InfoWindow();
        }
        initialize();

        getLoginRecords = () => {
            initialize();

            $scope.selectDate= $('#selectDate').val();

            var data = $.param({
                userId: $scope.selectedUser.usrId,
                date:   $scope.selectDate
            });

            $http.get(main_url+'routehistory/get_user_login_records?'+data)
            .then(function(response){
                $scope.loginRecords = response.data;
                if($scope.loginRecords.length > 0){
                    for(var i=0; i<$scope.loginRecords.length; i++){
                        addLoginMarker($scope.loginRecords[i]);
                        if(!($scope.loginRecords[i].lgnrLogoutLat == "") || !($scope.loginRecords[i].lgnrLogoutLat == null)){
                            addLogoutMarker($scope.loginRecords[i]);
                        }
                        //login locations
                        var dataLoginLocations = $.param({
                            userLoginRecordId: $scope.loginRecords[i].lgnrId,
                            userLoginDate: $scope.selectDate
                        });
                        $http.get(main_url+'routehistory/get_user_login_record_locations?'+dataLoginLocations)
                        .then(function(response){
                            $scope.loginLocations = response.data;
                                for(var i = 0, n = $scope.loginLocations.length; i < n; i++){
                                    var coordinates = new Array();

                                    for(var j = i; j < i+2 && j < n; j++) {
                                        coordinates[j-i] = $scope.loginLocations[j];
                                    }

                                    var polyline = new google.maps.Polyline({
                                        path: coordinates,
                                        geodesic: true,
                                        strokeColor: '#FF0000',
                                        strokeOpacity: 0.5,
                                        strokeWeight: 5,
                                        icons: [{
                                            icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, strokeColor: '#87ceeb', strokeOpacity: '1', strokeWeight: '1', fillColor: '#000000', fillOpacity:'1', scaledSize: new google.maps.Size(18, 18),},
                                            offset: '100%'
                                        }]
                                    });
                                    polyline.setMap(map);
                                }
                                /*
                                var loginPath = new google.maps.Polyline({
                                  path: $scope.loginLocations,
                                  geodesic: true,
                                  strokeColor: '#FF0000',
                                  strokeOpacity: 0.5,
                                  strokeWeight: 5
                                });
                                loginPath.setMap(map);
                                */
                        });

                        var dataTrip = $.param({
                            userLoginRecordId: $scope.loginRecords[i].lgnrId
                        });

                        $http.get(main_url+'routehistory/get_user_trip_records?'+dataTrip)
                        .then(function(response){

                            $scope.tripRecords = response.data;

                            for(var i=0; i<$scope.tripRecords.length; i++){
                                addTripStartMarker($scope.tripRecords[i]);
                                if(!($scope.tripRecords[i].trpEndLat == "") || !($scope.tripRecords[i].trpEndLat == null)){
                                    addTripStopMarker($scope.tripRecords[i]);
                                }
                                var dataTripLocations = $.param({
                                    userTripRecordId: $scope.tripRecords[i].trpId
                                });

                                $http.get(main_url+'routehistory/get_user_trip_record_locations?'+dataTripLocations)
                                .then(function(response){
                                    $scope.tripLocations = response.data;
                                    var tripPath = new google.maps.Polyline({
                                        path: $scope.tripLocations,
                                        geodesic: true,
                                        strokeColor: '#0000FF',
                                        strokeOpacity: 1,
                                        strokeWeight: 2
                                    });
                                    tripPath.setMap(map);
                                    getVisited();
                                });
                            }
                        });
                    }
                }
                else{
                    toastr.error("Route Record not Available, Please Try Again",'Sorry!');
                }
            });
        }    
        //getLoginRecords();

        getVisited = () => {
            $scope.selectDate   =   $('#selectDate').val();

            var data = $.param({
                userId: $scope.selectedUser.usrId,
                date: $scope.selectDate
            });

            $http.get(main_url+'routehistory/get_user_visited?'+data)
            .then(function(response){
                $scope.visiteds = response.data;
                for(var i=0; i<$scope.visiteds.length; i++){
                    addVisitedMarker($scope.visiteds[i]);
                }
            });
        }

        addLoginMarker = item => {
            var marker = new google.maps.Marker({
                map: map,
                position: {lat: parseFloat(item.lgnrLoginLat),lng: parseFloat(item.lgnrLoginLong)},
                icon: iconMarkerLogin
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<b>Login </b>'+
                    '<br><b>Login Date: </b>'+item.lgnrLoginDate+
                    '<br><b>Login Time: </b>'+item.lgnrLoginTime
                );
                infowindow.open(map, this);
            });
        }

        addTripStartMarker = item => {
            var marker = new google.maps.Marker({
                map: map,
                position: {lat: parseFloat(item.trpStartLat),lng: parseFloat(item.trpStartLong)},
                icon: iconMarkerStartTrip
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<b>Trip Start </b>'+
                    '<br><b>Trip ID: </b>'+item.trpId+
                    '<br><b>Trip Start Date: </b>'+item.trpStartDate+
                    '<br><b>Trip Start Time: </b>'+item.trpStartTime
                );
                infowindow.open(map, this);
            });
        }

        addLogoutMarker = item => {
            var marker = new google.maps.Marker({
                map: map,
                position: {lat: parseFloat(item.lgnrLogoutLat),lng: parseFloat(item.lgnrLogoutLong)},
                icon: iconMarkerLogout
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<b>Logout </b>'+
                    '<br><b>Logout Date: </b>'+item.lgnrLogoutDate+
                    '<br><b>Logout Time: </b>'+item.lgnrLogoutTime
                );
                infowindow.open(map, this);
            });
        }

        addTripStopMarker = item => {
            var marker = new google.maps.Marker({
                map: map,
                position: {lat: parseFloat(item.trpEndLat),lng: parseFloat(item.trpEndLong)},
                icon: iconMarkerStopTrip
            });

            google.maps.event.addListener(marker, 'click', function(){
                infowindow.setContent('<b>Trip Stop </b>'+
                    '<br><b>Trip ID: </b>'+item.trpId+
                    '<br><b>Trip Start Date: </b>'+item.trpEndDate+
                    '<br><b>Trip Start Time: </b>'+item.trpEndTime
                );
                infowindow.open(map, this);
            });
        }

        addVisitedMarker = item => {
            var marker = new google.maps.Marker({
                map: map,
                position: {lat: parseFloat(item.mtnVisitedLat),lng: parseFloat(item.mtnVisitedLong)},
                icon: iconMarkerVisited
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<b>Visited </b>'+
                    '<br><b>Customer Name: </b>'+item.mtnVisitedCustomerName+
                    '<br><b>Visited Date: </b>'+item.mtnVisitedDate+
                    '<br><b>Visited Time: </b>'+item.mtnVisitedTime
                );
                infowindow.open(map, this);
            });
        }
    });

  //************************ Route History Controller *********************//


  //************************ Route Road History Controller *********************//

    app.controller('routehistoryRoadCtrl', function($scope, $http, toastr){

          getAllUsers = () => {
            $http.get(main_url + 'tripreport/get_all_users')
              .then(function (response){
                $scope.appUsers = response.data;
              });
          }

          getAllUsers();

          var today     = new Date()
          var priorDate = new Date().setDate(today.getDate() - 39)

          $('#selectDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true,
            maxDate: new Date(),
            minDate: new Date(priorDate)
          });

          $scope.submitRoute = function(){
            $scope.selectDate = $('#selectDate').val();

            var date1     = new Date($scope.selectDate);
            var date2     = new Date();
            var timeDiff  = Math.abs(date2.getTime() - date1.getTime());
            var diffDays  = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if($scope.selectDate == null || $scope.selectDate == ""){
              toastr.error("Please Select Date", 'Warning!');
            }
            else if($scope.selectedUser == null || $scope.selectedUser == ""){
              toastr.error("Please Select User", 'Warning!');
            }
            else if(diffDays > 40){
              toastr.error("Only 40 days Old Route Record is Available", 'Sorry!');
            }
            else{
              getLoginRecords();
            }
          }

          $scope.reloadPage = function(){
            location.reload(true);
          }

          var map;
          var directionsService;
          var directionsRenderer;

          initialize = () => {

            directionsService   = new google.maps.DirectionsService();
            directionsRenderer  = new google.maps.DirectionsRenderer();

            map = new google.maps.Map(document.getElementById("map"), {
              zoom: 8,
              center: { lat: 22.5726, lng: 88.3639 },
            });
            directionsRenderer.setMap(map);
          }
          initialize();

          calculateAndDisplayRoute = (start, end) => {

            directionsService = new google.maps.DirectionsService();

            var rendererOptions = {
              map: map
            }
            directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);

            directionsService.route({
              origin: {
                query: start.lat + ',' + start.lng,
              },
              destination: {
                query: end.lat + ',' + end.lng,
              },
              travelMode: google.maps.TravelMode.WALKING,
            },
              (response, status) => {
                if(status === "ZERO_RESULTS"){
                    return false;
                }
                if(status === "OK"){
                    directionsRenderer.setDirections(response);
                    directionsRenderer.setOptions({
                    // suppressMarkers: true
                    preserveViewport:true
                    /*
                     * ,
                     * preserveViewport:true
                     */
                    });
                  directionsRenderer.setMap(map);
                }
                else {
                  window.alert("Directions request failed due to " + status);
                }
              });

            //directionsRenderer.setMap(map);
          }

          getLoginRecords = () => {

            $scope.selectDate = $('#selectDate').val();

            var data = $.param({
              userId: $scope.selectedUser.usrId,
              date: $scope.selectDate
            });

            $http.get(main_url + 'routehistory/get_user_login_record_locations_dir_api?' + data)
              .then(function (response){
                if(response.data.status == 'failure'){
                  toastr.error(response.data.message);
                  return false;
                }
                else{
                  toastr.success('Please wait for 5mins to generate the footmap');
                }
                
                var ll      = 0;
                var timeout = 0;
                var startLat, endLat;
                var totalCount  =   response.data.length;

                $.each(response.data, function(valueLatLong){
                  timeout += 2000;

                  setTimeout(function(){

                    if (ll <= response.data.length - 1){

                      if (ll == 0) {
                        startLat = response.data[ll];
                      }
                      //endLat = response.data[ll + 1];
                      endLat = response.data[ll + 1];

                      calculateAndDisplayRoute(startLat, endLat);
                      if(totalCount - 1 == ll){
                        $scope.dataloading = false;
                      }
                      else{
                        $scope.dataloading = true;
                      }
                      /*if (ll == 0) {
                        var marker = new google.maps.Marker({
                          map: map,
                          position: startLat,
                          icon: iconMarkerLogin,

                        });
                      }*/
                      // if (ll == 10 - 2) {
                      /*if (ll == response.data.length - 2) {
                        var marker2 = new google.maps.Marker({
                          map: map,
                          position: endLat,
                          icon: iconMarkerLogout,
                        });

                      }*/
                      startLat = endLat;
                    }
                    ll++
                  }, timeout);
                });
              });
          }
    });

  //************************ Route Road History Controller *********************//


//************************ Unit Controller *********************//
app.controller('unitCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;

  getAllUnits();

  $scope.refreshTable = function(){
    getAllUnits();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.viewUnitDetails = function(item) {
    $scope.activeUnit = {
      untId: item.untId,
      untCode: item.untCode,
      untName: item.untName,
      untDescription: item.untDescription
    };
  };

 $scope.myStatus = function(item){

    if(item.untStatus == "active") {
      $scope.changeStatus = "deactive";
      $scope.changeStatusId = 0;
    }else{
      $scope.changeStatus = "active";
      $scope.changeStatusId = 1;
    }

    var data = $.param({
      untId: item.untId,
      untStatus: $scope.changeStatus,
      untStatusId: $scope.changeStatusId
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'unitmanagement/change_status',data,config)
    .then(function(response){

      if(response.data.status == "success"){
        toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
        getAllUnits();
      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }


    });

  }

  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse; 
  }

  $scope.whatClassIsIt= function(someValue){
      if(someValue=="active"){
        return "label label-success";
      }else{
        return "label label-danger";
      }
    };

  function getAllUnits(){

    $http.get(main_url+'unitmanagement/get_all_units')
        .then(function(response){

          $scope.units = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToEdit = function(item) {
    window.location = main_url+"unitmanagement/edit?id="+item.untId;
  }

});


//************************ Add Unit/Department Controller *********************//
app.controller('addUnitCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  $scope.cancelAddUnit = function(){
    window.location = main_url+"unitmanagement";
  }

  $scope.submitAddUnit = function(){

      $scope.progressbar.start();

      var data = $.param({
        untName: $scope.untName,
        untDescription: $scope.untDescription
      });

      var config = {
          headers : {
              'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
          }
      }


      $http.post(main_url+'unitmanagement/add_unit',data,config)
      .then(function(response) {

        console.log(response);

        if(response.data.status == "success"){

          $scope.progressbar.complete();
          toastr.success(response.data.message,'Success!');
          window.location = main_url+"unitmanagement";

        }else if(response.data.status == "failed"){
          $scope.progressbar.complete();
          toastr.error(response.data.message, 'Warning!');
        }else{
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }

      });

  }

});


//************************ Edit Unit Controller *********************//
app.controller('editUnitCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getUnitDetail();

  $scope.submitUpdateUnit = function() {

    $scope.progressbar.start()

    var data = $.param({
      untId: $scope.untId,
      untName: $scope.untName,
      untDescription: $scope.untDescription
    });

    var config = {
      headers : {
        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
      }
    }

    $http.post(main_url+'unitmanagement/update_unit',data,config)
    .then(function(response) {
      if(response.data.status == "success") {
        $scope.progressbar.complete();
        toastr.success(response.data.message,'Success!');
        window.location = main_url+"unitmanagement";
      }else if(response.data.status == "failed"){
        $scope.progressbar.complete();
        toastr.error(response.data.message, 'Warning!');
      }
    });

  }


  function getUnitDetail() {

    $scope.id = getUrlParameter('id');

    $http.get(main_url+'unitmanagement/get_unit_detail?id='+$scope.id)
        .then(function(response) {
          $scope.untId = response.data.untId;
          $scope.untCode = response.data.untCode;
          $scope.untName = response.data.untName;
          $scope.untDescription = response.data.untDescription;
        });
  }

  $scope.cancelEditUnit = function(){
    window.location = main_url+"unitmanagement";
  }


});


//************************ Venue List Controller *********************//
app.controller('venueCtrl', function($scope, $http, toastr) {

  $scope.dataloading = true;

  getAllVenues();

  $scope.refreshTable = function(){
    getAllVenues();
    toastr.success("Table Refreshed",'Success!');
  }

  $scope.viewVenueDetails = function(item) {
    $scope.activeVenue = {
      venId: item.venId,
      venUnitId: item.venUnitId,
      venUnitName: item.venUnitName,
      venCode: item.venCode,
      venShortName: item.venShortName,
      venFullName: item.venFullName,
      venDescription: item.venDescription
    };
  };

 $scope.myStatus = function(item) {
    if(item.venStatus == "active") {
      $scope.changeStatus = "deactive";
      $scope.changeStatusId = 0;
    }else{
      $scope.changeStatus = "active";
      $scope.changeStatusId = 1;
    }

    var data = $.param({
      venId: item.venId,
      venStatus: $scope.changeStatus,
      venStatusId: $scope.changeStatusId
    });

    var config = {
        headers : {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
        }
    }

    $http.post(main_url+'venuemanagement/change_status',data,config)
    .then(function(response){

      if(response.data.status == "success"){
        toastr.success(response.data.message,'Success!',{ timeOut: 800, extendedTimeOut: 800 });
        getAllVenues();
      }else if(response.data.status == "failed"){
        toastr.error(response.data.message, 'Warning!');
      }else{
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }


    });

  }

  $scope.sort = function(keyname){
    $scope.sortKey = keyname; 
    $scope.reverse = !$scope.reverse;
  }

  $scope.whatClassIsIt= function(someValue){
      if(someValue=="active"){
        return "label label-success";
      }else{
        return "label label-danger";
      }
    };

  function getAllVenues(){

    $http.get(main_url+'venuemanagement/get_all_venues')
        .then(function(response){
          $scope.venues = response.data;
          $scope.dataloading = false;

        });
  }

  $scope.goToEdit = function(item) {
    window.location = main_url+"venuemanagement/edit?id="+item.venId;
  }

});


//************************ Add Venue Controller *********************//
app.controller('addVenueCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  getAllUnits();

  $scope.cancelAddVenue = function() {
    window.location = main_url+"venuemanagement";
  }

  $scope.submitAddVenue = function() {

    $scope.progressbar.start();

    var data = $.param({
      venUnitId: $scope.venUnitId.untId,
      venUnitName: $scope.venUnitId.untName,
      venShortName: $scope.venShortName,
      venFullName: $scope.venFullName,
      venDescription: $scope.venDescription
    });

    var config = {
      headers : {
        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
      }
    }


    $http.post(main_url+'venuemanagement/add_venue',data,config)
    .then(function(response) {
      if(response.data.status == "success"){
        $scope.progressbar.complete();
        toastr.success(response.data.message,'Success!');
        window.location = main_url+"venuemanagement";
      }else if(response.data.status == "failed"){
        $scope.progressbar.complete();
        toastr.error(response.data.message, 'Warning!');
      }else{
        $scope.progressbar.complete();
        toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
      }
    });
  }

  function getAllUnits() {
    $http.get(main_url+'unitmanagement/get_all_units')
        .then(function(response) {
          $scope.units = response.data;
        });
  }

});


//************************ Edit Venue Controller *********************//
app.controller('editVenueCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  }

  getVenueDetail();

  $scope.submitUpdateVenue = function() {

    $scope.progressbar.start();

    var data = $.param({
      venId: $scope.venId,
      venUnitId: $scope.venUnitId.untId,
      venUnitName: $scope.venUnitId.untName,
      venShortName: $scope.venShortName,
      venFullName: $scope.venFullName,
      venDescription: $scope.venDescription
    });

    var config = {
      headers : {
        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
      }
    }


      $http.post(main_url+'venuemanagement/update_venue',data,config)
      .then(function(response){

        if(response.data.status == "success"){
          $scope.progressbar.complete();
          toastr.success(response.data.message,'Success!');
          window.location = main_url+"venuemanagement";
        }else if(response.data.status == "failed"){
          $scope.progressbar.complete();
          toastr.error(response.data.message, 'Warning!');
        }else{
          $scope.progressbar.complete();
          toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
        }
      });
  }

  function getVenueDetail() {
    $scope.id = getUrlParameter('id');
    $http.get(main_url+'venuemanagement/get_venue_detail?id='+$scope.id)
        .then(function(response) {
          $scope.venId = response.data.venId;
          $scope.venUnitId = response.data.venUnitId;
          $scope.venCode = response.data.venCode;
          $scope.venShortName = response.data.venShortName;
          $scope.venFullName = response.data.venFullName;
          $scope.venDescription = response.data.venDescription;
          $scope.venStatusId = response.data.venStatusId;
          $scope.venStatus = response.data.venStatus;
          getAllUnits();
        });
  }

  $scope.cancelEditVenue = function(){
    window.location = main_url+"venuemanagement";
  }

  function getAllUnits() {
    $http.get(main_url+'unitmanagement/get_all_units')
        .then(function(response) {
          $scope.units = response.data;

          for(var i=0; i<$scope.units.length; i++){
            if($scope.units[i].untId == $scope.venUnitId){
              $scope.venUnitId = $scope.units[i];
            }
          }
        });
  }

});


//************************ Meeting Report Controller *********************//
app.controller('usermeetingreportCtrl', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory) {


  $scope.progressbar = ngProgressFactory.createInstance();


  $scope.datetypes = [
    {"dttId":"1","dttValue":"today","dttName":"Today"},
    {"dttId":"2","dttValue":"tomorrow","dttName":"Tomorrow"},
    {"dttId":"3","dttValue":"coming7days","dttName":"Coming 7 Days"},
    {"dttId":"4","dttValue":"last7days","dttName":"Last 7 Days"},
    {"dttId":"5","dttValue":"last30days","dttName":"Last 30 Days"},
    {"dttId":"6","dttValue":"lifetime","dttName":"Life Time"},
    {"dttId":"7","dttValue":"daterange","dttName":"Custom Date Range"},
  ];

  
  $('#fromDate, #toDate').bootstrapMaterialDatePicker
  ({
    time: false,
    clearButton: true
  });

  getAllCustomerTypes();
  getAllIndustryTypes();
  getAllCompanies();


  $scope.getDateType = function(){
    if($scope.selectedDateType.dttValue == "daterange"){

      $scope.fromDate = false;
      $scope.toDate = false;

    }else{

      $scope.fromDate = true;
      $scope.toDate = true;
      $("#fromDate").val('');
      $("#toDate").val('');

    }

  }

  $scope.selectedDateType = $scope.datetypes[0];
  $scope.getDateType();


  function getAllCustomerTypes(){

    $http.get(main_url+'user/get_all_customer_types')
        .then(function(response){

          $scope.customertypes = response.data;
          $scope.selectedCustomerType = $scope.customertypes[0];

        });
  }


  function getAllIndustryTypes(){

    $http.get(main_url+'user/get_all_industry_types')
        .then(function(response){

          $scope.industrytypes = response.data;
          $scope.selectedIndustryType = $scope.industrytypes[0];

        });
  }


  function getAllCompanies(){

    $http.get(main_url+'user/get_all_companies')
        .then(function(response){

          $scope.companies = response.data;
          $scope.selectedCompany = $scope.companies[0];

        });
  }



  $scope.sort = function(keyname){
    $scope.sortKey = keyname;  
    $scope.reverse = !$scope.reverse; 
  }



  $scope.getReports = function(){

    $scope.fromDate = $('#fromDate').val();
    $scope.toDate = $('#toDate').val();
    $scope.dob = $('#dob').val();
    $scope.doa = $('#doa').val();

    var startDate = new Date($scope.fromDate);
    var endDate = new Date($scope.toDate);

    if($scope.fromDate!="" && $scope.toDate!="" && endDate < startDate){
      toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
    }else{

      var data = $.param({
        dateTypeId: $scope.selectedDateType.dttId,
        dateTypeValue: $scope.selectedDateType.dttValue,
        dateTypeName: $scope.selectedDateType.dttName,
        fromDate: $scope.fromDate,
        toDate: $scope.toDate,
        customerTypeId: $scope.selectedCustomerType.custId,
        customerTypeName: $scope.selectedCustomerType.custName,
        industryTypeId: $scope.selectedIndustryType.indtId,
        industryTypeName: $scope.selectedIndustryType.indtName,
        companyId: $scope.selectedCompany.cmpId,
        companyName: $scope.selectedCompany.cmpName
      });


      $scope.progressbar.start();


      $http.get(main_url+'user/get_meeting_records_by_date?'+data)
      .then(function(response){

            $scope.progressbar.complete();

            $scope.meetings = response.data;

            $scope.getDateType();

            //$scope.reportDateInfo = "( "+$scope.fromDate+" to "+$scope.toDate+" )";

      },function(reject){
      // error handler 
      $scope.progressbar.complete();
      toastr.error('Something problem in Internet, Please try Again', 'Warning!');    
    });

    }

  }


  $scope.exportToPDF = function(printSectionId){
    document.getElementById(printSectionId).parentNode.style.overflow = 'visible';
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
        document.getElementById(printSectionId).parentNode.style.overflow = 'hidden';
          //var data = canvas.toDataURL();
          var data = canvas.toDataURL();
            window.open(data, "toDataURL() image", "width=800, height=800");
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }

        $scope.exportAction = function () {


          var TableColumn = ["Code", "Meeting Name", "Meeting Date", "Meeting Time", "Type", "Customer Name", "Visited", "Completed", "Assigned User"];

          var TableData = new Array();

          $('#tableToExport tr').each(function(row, tr){
              TableData[row]={
                  0 : $(tr).find('td:eq(0)').text(),
                  1 : $(tr).find('td:eq(1)').text(),
                  2 : $(tr).find('td:eq(2)').text(),
                  3 : $(tr).find('td:eq(3)').text(),
                  4 : $(tr).find('td:eq(4)').text(),
                  5 : $(tr).find('td:eq(5)').text(),
                  6 : $(tr).find('td:eq(6)').text(),
                  7 : $(tr).find('td:eq(7)').text(),
                  8 : $(tr).find('td:eq(8)').text(),
                  9 : $(tr).find('td:eq(9)').text()
              }
          }); 

          // Only pt supported (not mm or in)
          var doc = new jsPDF('p', 'pt');
          doc.autoTable(TableColumn, TableData,{
            styles: {overflow: 'linebreak', columnWidth: 55, fontSize: 8},
            columnStyles: {text: {columnWidth: 'auto'}},
            addPageContent: function(data) {
              doc.text("Sales App Meeting Report", 40, 30);
            }
          });
          doc.save('Report.pdf');


      }


  $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

  $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }



});

//************************ Performance Controller *********************//
app.controller('performanceCtrl', function($scope, $http, toastr, ngProgressFactory) {

  $scope.progressbar = ngProgressFactory.createInstance();

  var dt = new Date()
  $scope.currentYear = dt.getFullYear();

  getAllYears();
  getAllUsers();


  function getAllYears(){

    $http.get(main_url+'performance/get_all_years')
        .then(function(response){

          $scope.years = response.data;

          for(var i=0; i<$scope.years.length; i++){
            if($scope.years[i].yearName==$scope.currentYear){
              $scope.selectedYear = $scope.years[i];
              break;
            }
          }

        });
  }


  function getAllUsers(){

    $http.get(main_url+'performance/get_all_users')
        .then(function(response){

          $scope.inputUsers = response.data;

        });
  }


  $scope.getPerformanceData = function(){

      if($scope.orderNo=="" || $scope.orderNo==null){
        toastr.error("Please Enter Order/Month Target", 'Warning!');
      }else if($scope.orderAmount=="" || $scope.orderAmount==null){
        toastr.error("Please Enter Order Amount/Month Target", 'Warning!');
      }else if($scope.projectionAmount=="" || $scope.projectionAmount==null){
        toastr.error("Please Enter Projection Amount/Month Target", 'Warning!');
      }else if($scope.collectionAmount=="" || $scope.collectionAmount==null){
        toastr.error("Please Enter Collection Amount/Month Target", 'Warning!');
      }else if($scope.outputUsers.length <= 0){
        toastr.error("Please Select User", 'Warning!');
      }else{

        var data = $.param({
          yearId    : $scope.selectedYear.yearId,
          yearName  : $scope.selectedYear.yearName,
          users     : $scope.outputUsers
        });

        $scope.progressbar.start();

        $http.get(main_url+'performance/get_performance_record?'+data)
        .then(function(response){

         $scope.progressbar.complete();
         $scope.performances = response.data;
         
         var chartOrderTitle       =   "Order Performance Analysis ("+$scope.selectedYear.yearName+")";
         var chartOrderAmountTitle =   "Order Amount Performance Analysis ("+$scope.selectedYear.yearName+")";
         var chartProjectionTitle  =   "Projection Performance Analysis ("+$scope.selectedYear.yearName+")";
         var chartTitle            =   "Collection Performance Analysis ("+$scope.selectedYear.yearName+")";

         $scope.avgVisit = 2;

          var chart = new Highcharts.chart('container', {
            title: {
                text: chartTitle
            },
            xAxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            series: [{
                type: 'spline',
                name: 'Target',
                data: [$scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount, 
                        $scope.collectionAmount
                      ],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[$scope.performances.length],
                    fillColor: 'white'
                }
            }]
        });

          var order_chart = new Highcharts.chart('order_container', {
            title: {
                text: chartOrderTitle
            },
            xAxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            series: [{
                type: 'spline',
                name: 'Target',
                data: [$scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo, 
                        $scope.orderNo],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[$scope.performances.length],
                    fillColor: 'white'
                }
            }]
        });

          var order_amount_chart = new Highcharts.chart('order_amount_container', {
            title: {
                text: chartOrderAmountTitle
            },
            xAxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            series: [{
                type: 'spline',
                name: 'Target',
                data: [$scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount, 
                        $scope.orderAmount],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[$scope.performances.length],
                    fillColor: 'white'
                }
            }]
          });
            
          var projection_amount_chart = new Highcharts.chart('projection_amount_container', {
            title: {
                text: chartProjectionTitle
            },
            xAxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            series: [{
                type: 'spline',
                name: 'Target',
                data: [$scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount, 
                        $scope.projectionAmount],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[$scope.performances.length],
                    fillColor: 'white'
                }
            }]
          });
            
            for(var i=0; i<$scope.performances.length; i++){
                chart.addSeries({
                    name: $scope.performances[i].userName,
                    data: [$scope.performances[i].collect_amount_JAN, 
                            $scope.performances[i].collect_amount_FEB, 
                            $scope.performances[i].collect_amount_MAR, 
                            $scope.performances[i].collect_amount_APR, 
                            $scope.performances[i].collect_amount_MAY, 
                            $scope.performances[i].collect_amount_JUN, 
                            $scope.performances[i].collect_amount_JUL, 
                            $scope.performances[i].collect_amount_AUG, 
                            $scope.performances[i].collect_amount_SEP, 
                            $scope.performances[i].collect_amount_OCT, 
                            $scope.performances[i].collect_amount_NOV, 
                            $scope.performances[i].collect_amount_DEC
                    ]}, true);

                order_chart.addSeries({
                    name: $scope.performances[i].userName,
                    data: [$scope.performances[i].order_JAN, 
                            $scope.performances[i].order_FEB, 
                            $scope.performances[i].order_MAR, 
                            $scope.performances[i].order_APR, 
                            $scope.performances[i].order_MAY, 
                            $scope.performances[i].order_JUN, 
                            $scope.performances[i].order_JUL, 
                            $scope.performances[i].order_AUG, 
                            $scope.performances[i].order_SEP, 
                            $scope.performances[i].order_OCT, 
                            $scope.performances[i].order_NOV, 
                            $scope.performances[i].order_DEC
                    ]}, true);

                order_amount_chart.addSeries({
                    name: $scope.performances[i].userName,
                    data: [$scope.performances[i].order_amount_JAN, 
                            $scope.performances[i].order_amount_FEB, 
                            $scope.performances[i].order_amount_MAR, 
                            $scope.performances[i].order_amount_APR, 
                            $scope.performances[i].order_amount_MAY, 
                            $scope.performances[i].order_amount_JUN, 
                            $scope.performances[i].order_amount_JUL, 
                            $scope.performances[i].order_amount_AUG, 
                            $scope.performances[i].order_amount_SEP, 
                            $scope.performances[i].order_amount_OCT, 
                            $scope.performances[i].order_amount_NOV, 
                            $scope.performances[i].order_amount_DEC
                    ]}, true);
                
                projection_amount_chart.addSeries({
                    name: $scope.performances[i].userName,
                    data: [$scope.performances[i].project_amount_JAN, 
                            $scope.performances[i].project_amount_FEB, 
                            $scope.performances[i].project_amount_MAR, 
                            $scope.performances[i].project_amount_APR, 
                            $scope.performances[i].project_amount_MAY, 
                            $scope.performances[i].project_amount_JUN, 
                            $scope.performances[i].project_amount_JUL, 
                            $scope.performances[i].project_amount_AUG, 
                            $scope.performances[i].project_amount_SEP, 
                            $scope.performances[i].project_amount_OCT, 
                            $scope.performances[i].project_amount_NOV, 
                            $scope.performances[i].project_amount_DEC
                    ]}, true);
            };

            chart.redraw();
            order_chart.redraw();
            order_amount_chart.redraw();
            projection_amount_chart.redraw();
        });
      }
    }  
});




//************************************** Soumyajeet Modules **************************************//

//************************ Item Controller *********************//

app.controller('itemCtrl', function($scope, $http, toastr, ngProgressFactory){

    $scope.dataloading  =   true;    
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    function getAllItems(){

        $http.get(main_url+'itemmanagement/allitems')
            .then(function(response){
            $scope.appItems         =   response.data;
            $scope.dataloading      =   false;

        });
    }
    
    getAllItems();
    
    $scope.sort     =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    }

    $scope.refreshTable     =   function(){
        getAllItems();
        toastr.success("Table Refreshed",'Success!');
    }
    
    $scope.viewItemDetails  =   function(item){
        
        if(item.status == 0)
        {
            $scope.modalStatus  =   'Out Of Stock';
        }else{
            $scope.modalStatus  =   'Available';
        }

        $scope.activeItem =
        {
            itemName        :   item.item_name,
            itemPackage     :   item.package_name,
            itemSize        :   item.package_size_name,
            itemPrice       :   item.package_item_price,
            itemStatus      :   $scope.modalStatus,
            itemCreatedDate :   item.created_at
        };

    };
    
    $scope.goToEdit         =   function(item){
        window.location = main_url+"itemmanagement/edit?id="+item.pckg_id;
    }
    
    $scope.badgeClass       =   function(badge){
      if(badge == 1){
        return "label label-success";
      }else{
        return "label label-danger";
      }
    };
    
    $scope.changeStatus     =   function(item){
        if(item.status == "1"){
          $scope.switchStatus = 0;
        }else{
          $scope.switchStatus = 1;
        }
        
        var data = $.param({
          pckg_id       : item.pckg_id,
          status        : $scope.switchStatus
        });
        
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
          
        $http.post(main_url+'itemmanagement/update_status',data,config)
        .then(function(response){
            if(response.data.status == "success"){
              $scope.progressbar.complete();
              getAllItems();
              //toastr.success(response.data.message,'Success!');
              if(item.status == 1){
                toastr.error('Item Switch to Out of stock','Success!');
              }else{
                toastr.success('Item Switch to Available','Success!');  
              }
            }else if(response.data.status == "failed"){
              $scope.progressbar.complete();
              toastr.warning(response.data.message, 'Warning!');
            }else{
              $scope.progressbar.complete();
              toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
            }
        });
    };

});

//************************ Item Controller *********************//

//************************ Add Item Controller *********************//

    app.controller('addItemCtrl', function($scope, $http, toastr, ngProgressFactory){
        
        $scope.progressbar = ngProgressFactory.createInstance();
        
        getAllPackageMode = () => {
            $http.get(main_url+'itemmanagement/packagemodecollection')
                .then(function(response){
                $scope.packageModes = response.data;
            });
        }
        
        getAllPackageSize = () => {
            $http.get(main_url+'itemmanagement/packagesizecollection')
                .then(function(response){
                $scope.packageSizes = response.data;
            });
        }    
        
        getAllPackageSize();
        getAllPackageMode();
        
        $scope.cancelEditItem = function(){
            window.location = main_url+"itemmanagement";
        }
        
        $scope.addItem = function(){

            $scope.progressbar.start();

            var data = $.param({
                item_name           :   $scope.itemName,
                mode_of_package     :   $scope.itemPackageMode,
                package_size        :   $scope.itemPackageSize,
                package_item_price  :   $scope.itemPrice,
                retail_item_price   :   $scope.retailitemPrice,
            });

            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'itemmanagement/additem',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    toastr.success(response.data.message,'Success!');
                    $scope.addForm.$setPristine();
                    setTimeout(function(){ window.location = main_url+"itemmanagement"; }, 2000);
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }
    });

//************************ Add Item Controller *********************//

//************************ Edit Item Controller *********************//

    app.controller('editItemCtrl', function($scope, $http, toastr, ngProgressFactory){
        
        $scope.progressbar = ngProgressFactory.createInstance();
        
        getUrlParameter = sParam => {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

            for(i = 0; i < sURLVariables.length; i++){
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }
        
        getAllPackageMode = () => {
            $http.get(main_url+'itemmanagement/packagemodecollection')
                .then(function(response){
                $scope.packageModes = response.data;
            });
        }
        
        getAllPackageSize = () => {
            $http.get(main_url+'itemmanagement/packagesizecollection')
                .then(function(response){
                $scope.packageSizes = response.data;
            });
        }
        
        getItemDetail = () => {
            $scope.id = getUrlParameter('id');
            
            $http.get(main_url+'itemmanagement/get_item_detail?id='+$scope.id)
                .then(function(response){

                $scope.appItem            =   response.data;
                $scope.itemId             =   $scope.appItem.pckg_id;
                $scope.itemName           =   $scope.appItem.item_name;
                $scope.itemPrice          =   $scope.appItem.package_item_price;
                $scope.itemRetailerPrice  =   $scope.appItem.retail_item_price;
                $scope.itemPackageMode    =   $scope.appItem.mode_of_package;
                $scope.itemPackageSize    =   $scope.appItem.package_size;

            });
        }
        
        getItemDetail();
        getAllPackageSize();
        getAllPackageMode();
        
        $scope.cancelEditItem = function(){
            window.location = main_url+"itemmanagement";
        }
        
        $scope.updateItem = function(){

            $scope.progressbar.start();

            var data = $.param({
                id                  : $scope.itemId,
                item_name           : $scope.itemName,
                mode_of_package     : $scope.itemPackageMode,
                package_size        : $scope.itemPackageSize,
                package_item_price  : $scope.itemPrice,
                retail_item_price   : $scope.itemRetailerPrice
            });

            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'itemmanagement/update_item',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    toastr.success(response.data.message,'Success!');
                    $scope.addForm.$setPristine();;
                    setTimeout(function(){ window.location = main_url+"itemmanagement"; }, 2000);
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }
    });

//************************ Edit Item Controller *********************//

//************************ Add Distributors Controller *********************//

    app.controller('adddistributorCtrl', function($scope, Upload, $http, toastr, ngProgressFactory){
        
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
        
        $('#dobDate').bootstrapMaterialDatePicker
        ({
            time: false,
            clearButton: true,
            maxDate: new Date()
        });
        
        function getAllDistricts(){

            $http.get(main_url+'distributors/alldistricts')
                .then(function(response){
                $scope.appdistricts     =   response.data;
                $scope.dataloading      =   false;

            });
        }

        function getAllTraderType(){
          $http.get(main_url+'distributors/traderslist')
              .then(function(response){
              $scope.apptradersType   =   response.data;
              $scope.dataloading      =   false;
          });
        }
        
        getAllDistricts();
        getAllTraderType();
        
        $scope.sort             =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        }

        $scope.refreshTable     =   function(){
            getAllDistributors();
            toastr.success("Table Refreshed",'Success!');
        }
        
        //Reset Add form
        function reset(){
            $scope.distributorName      =   '';
            $scope.district             =   '';
            $scope.distributorType      =   '';
            $scope.distributorPhone     =   '';
        };
        
        $scope.cancelAddDistributors = function(){
            window.location = main_url+"distributors";
        }
        
        $scope.addDistributor = function(file){
            
            $scope.progressbar.start();
            $scope.distributorDob    =   $('#dobDate').val();
            //console.log($scope.distributorcredit);
            // WITH ATTACHMENT
            file.upload = Upload.upload({
                method  :   "POST",
                url     :   main_url+'distributors/add_distributor',
                data    :   {
                    distributorName     : $scope.distributorName,
                    district            : $scope.district,
                    distributorType     : $scope.distributorType,
                    distributorPhone    : $scope.distributorPhone,
                    distributorEmail    : $scope.distributorEmail,
                    distributorAddress  : $scope.distributorAddress,
                    distributorGstNo    : $scope.distributorGstNo,
                    creditLimit         : $scope.distributorcredit,
                    distributorDob      : $scope.distributorDob,
                    distributordiscount : $scope.distributordiscount,
                    file                : file
                },
            });
            file.upload.then(function (response){
                if(response.data.status == "success"){
                  $scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  reset();
                  setTimeout(function(){ window.location = main_url+"distributors"; }, 2000);
                }
                else if(response.data.status == "failed"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }
                else{
                  $scope.progressbar.complete();
                  toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
            
            // WITHOUT ATTACHMENT
            /*var data = $.param({
                distributorName     : $scope.distributorName,
                district            : $scope.district,
                distributorType     : $scope.distributorType,
                distributorPhone    : $scope.distributorPhone,
                distributorAddress  : $scope.distributorAddress,
                distributorGstNo    : $scope.distributorGstNo,
                //distributorattach   : $scope.attachment
            });
            //console.log(data);
            
            var config = {
                  headers : {
                      'Content-Type'    :   'application/x-www-form-urlencoded;charset=utf-8;'
                  }
              }
            
            $http.post(main_url+'distributors/add_distributor',data,config)
            .then(function(response){
                //console.log(response);
                
                if(response.data.status == "success"){

                  $scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  reset();
                  setTimeout(function(){ window.location = main_url+"distributors"; }, 2000);              

                }else if(response.data.status == "failed"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });*/
            
            
        }
        
    });

//************************ Add Distributors Controller *********************//

//************************ List Distributors Controller *********************//

    app.controller('distributorCtrl', function($scope, $http, toastr, ngProgressFactory){
        
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
        
        getAllDistributors = () => {

            $http.get(main_url+'distributors/list_all_distributors')
                .then(function(response){
                $scope.appdistributors      =   response.data;
                $scope.dataloading          =   false;

            });
        }
        
        getAllDistributors();
        
        $scope.sort             =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        };

        $scope.refreshTable     =   function(){
            getAllDistributors();
            toastr.success("Table Refreshed",'Success!');
        };
        
        $scope.viewDistributorDetails  =   function(distributor){
            
            if(distributor.status == 0){
                $scope.modalStatus  =   'Inactive';
            }
            else{
                $scope.modalStatus  =   'Active';
            }
            
            if(distributor.distributorKycFile != ''){
                $scope.distributorattachment  =   main_url + 'uploads/kyc/distributor/' + distributor.distributorKycFile;
            }
            else{
                $scope.distributorattachment  =   '';
            }
            
            $scope.activeDistributor = {
                distributorName         :   distributor.distributorName,
                distributorType         :   distributor.distributorType,
                district                :   distributor.districtName,
                distributorPhone        :   distributor.distributorPhone,
                distributorEmail        :   distributor.distributorEmail,
                distributorStatus       :   $scope.modalStatus,
                distributorAddress      :   distributor.distributorAddress,
                distributorGstNo        :   distributor.distributorGstNo,
                distributorDob          :   distributor.distributorDob,
                creditLimit             :   distributor.creditLimit,
                distributorattachment   :   $scope.distributorattachment,     
                distributorCreated_at   :   distributor.created_at
            };
        };
        
        $scope.goToEdit         =   function(distributor){
            window.location = main_url+"distributors/edit?id="+distributor.distributorId;
        };

        $scope.addsales         =   function(distributor){
            window.location = main_url+"distributors/addsales?id="+distributor.distributorId;
        };
        
        $scope.changeStatus     =   function(distributor){
            
            if(distributor.status == "1"){
              $scope.switchStatus = 0;
            }
            else{
              $scope.switchStatus = 1;
            }
            
            var data = $.param({
                distributorId     : distributor.distributorId,
                status            : $scope.switchStatus
            });
            
            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
              
            $http.post(main_url+'distributors/update_status',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    getAllDistributors();
                    //toastr.success(response.data.message,'Success!');
                    if(distributor.status == 1){
                        toastr.error('Distributor Deactivated Successfully','Success!');
                    }
                    else{
                        toastr.success('Distributor Activated Successfully','Success!');  
                    }
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        };
        
        $scope.badgeClass       =   function(badge){
            if(badge == 1){
                return "label label-success";
            }
            else{
                return "label label-danger";
            }
        };    
    });

//************************ List Distributors Controller *********************//

//************************ Edit Distributors Controller *********************//

    app.controller('editDistributor', function($scope, Upload, $http, toastr, ngProgressFactory){
        
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
        
        $('#dobDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true,
            maxDate: new Date()
        });
        
        getUrlParameter = (sParam) => {
            var sPageURL    =   decodeURIComponent(window.location.search.substring(1)),
            sURLVariables   =   sPageURL.split('&'),
            sParameterName,
            i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }
        
        getAllDistricts = () => {
            $http.get(main_url+'distributors/alldistricts')
                .then(function(response){
                $scope.appdistricts     =   response.data;
                $scope.dataloading      =   false;

            });
        }

        getAllTraderType = () => {
            $http.get(main_url+'distributors/traderslist')
                .then(function(response){
                $scope.apptradersType   =   response.data;
                $scope.dataloading      =   false;
            });
        }
        
        getAllDistricts();
        getAllTraderType();
        
        getDistributorDetail = () => {
            $scope.id = getUrlParameter('id');
            
            $http.get(main_url+'distributors/get_distributor_detail?id='+$scope.id)
                .then(function(response){

                $scope.appDistributor               =   response.data;
                $scope.distributorId                =   $scope.appDistributor.distributorId;
                $scope.distributorName              =   $scope.appDistributor.distributorName;
                $scope.distributordistrict          =   $scope.appDistributor.district;
                $scope.distributorType              =   $scope.appDistributor.distributorType;
                $scope.distributorPhone             =   $scope.appDistributor.distributorPhone;
                $scope.distributorEmail             =   $scope.appDistributor.distributorEmail;
                $scope.distributorAddress           =   $scope.appDistributor.distributorAddress;
                $scope.distributorGstNo             =   $scope.appDistributor.distributorGstNo;
                $scope.distributorDob               =   $scope.appDistributor.distributorDob;
                $scope.distributorcredit            =   $scope.appDistributor.creditLimit;
                $scope.distributorKyc               =   $scope.appDistributor.distributorKyc;
                $scope.distributordiscount          =   $scope.appDistributor.discount;

            });
        }
        
        getDistributorDetail();
        
        $scope.cancelEditDistributors = function(){
            window.location = main_url+"distributors";
        }
        
        $scope.updateDistributor = function(file){

            $scope.progressbar.start();
            $scope.distributorDob    =   $('#dobDate').val();
            
            if(file){
                // WITH ATTACHMENT
                file.upload = Upload.upload({
                    method  :   "POST",
                    url     :   main_url+'distributors/update_distributor',
                    data    :   {
                        distributorId       : $scope.distributorId,
                        distributorName     : $scope.distributorName,
                        district            : $scope.distributordistrict,
                        distributorType     : $scope.distributorType,
                        distributorPhone    : $scope.distributorPhone,
                        distributorAddress  : $scope.distributorAddress,
                        distributorGstNo    : $scope.distributorGstNo,
                        distributorDob      : $scope.distributorDob,
                        creditLimit         : $scope.distributorcredit,
                        discount            : $scope.distributordiscount,
                        file                : file
                    },
                });
                file.upload.then(function (response){
                    if(response.data.status == "success"){

                      $scope.progressbar.complete();
                      toastr.success(response.data.message,'Success!');
                      setTimeout(function(){ window.location = main_url+"distributors"; }, 2000);

                    }else if(response.data.status == "failed"){
                      $scope.progressbar.complete();
                      toastr.warning(response.data.message, 'Warning!');
                    }else{
                      $scope.progressbar.complete();
                      toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                    }
                });
         }
         else{
             // WITHOUT ATTACHMENT
             var data = $.param({
                distributorId       : $scope.distributorId,
                distributorName     : $scope.distributorName,
                district            : $scope.distributordistrict,
                distributorType     : $scope.distributorType,
                distributorPhone    : $scope.distributorPhone,
                distributorEmail    : $scope.distributorEmail,
                distributorAddress  : $scope.distributorAddress,
                distributorGstNo    : $scope.distributorGstNo,
                creditLimit         : $scope.distributorcredit,
                distributorDob      : $scope.distributorDob,
                discount            : $scope.distributordiscount
              });

              var config = {
                  headers : {
                      'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                  }
              }
              
              $http.post(main_url+'distributors/update_distributor',data,config)
              .then(function(response){
                if(response.data.status == "success"){

                  $scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  $scope.addForm.$setPristine();
                  setTimeout(function(){ window.location = main_url+"distributors"; }, 2000);              

                }else if(response.data.status == "failed"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }

              });
            }
        }
    });

//************************ Edit Distributors Controller *********************//

//************************ Rochak Orders Controller *********************//

    app.controller('allOrderslist', function($scope, $http, toastr, ngProgressFactory){
        
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
        
        getAllRochakOrders  =   () => {
            $http.get(main_url+'rochakorders/get_all_orders')
                .then(function(response){
                $scope.approchakorders      =   response.data;
                $scope.dataloading          =   false;
            });
        }

        $scope.badgeClass   =   function(badge){
            if(badge == 1){
                return "label label-success";
            }
            else{
                return "label label-danger";
            }
        };

        $scope.changeStatus =   function(order){
        
            if(order.status == "1"){
                $scope.switchStatus = 0;
            }
            else{
                $scope.switchStatus = 1;
            }
            
            var data = $.param({
              order_id  : order.id,
              status    : $scope.switchStatus
            });

            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
          
            $http.post(main_url+'rochakorders/update_status',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    getAllRochakOrders();
                    if(order.status == 1){
                        toastr.error('Order Not Delivered','Warning!');
                    }
                    else{
                        toastr.success('Order Delivered','Success!');  
                    }
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }
        
        getAllRochakOrders();
        
        $scope.sort             =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        };

        $scope.refreshTable     =   function(){
            getAllRochakOrders();
            toastr.success("Table Refreshed",'Success!');
        };
        
        $scope.getOrderDetails  =   function getOrderDetails(id){
            
            var data = $.param({
                id  :   id
            });

            var config  =   {
                headers :   {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
              
            $http.post(main_url+'rochakorders/get_single_order',data,config)
              .then(function(response){
                if(response.data.status == "success"){
                    $scope.rochakorderslist      =   response.data.orderdata;
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }

        $scope.getInvoiceDetails = function getInvoiceDetails(id){
            
            var data = $.param({
                id  :   id
            });

            var config  =   {
                headers :   {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
              
            $http.post(main_url+'rochakorders/fetch_order_invoice',data,config)
              .then(function(response){
                if(response.data.status == "success"){
                    $('.modal-invoice-details').modal('show');
                    $scope.glaucusinvoicedata      =   response.data.invoicedata;
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    $('.modal-invoice-details').modal('hide');
                    //toastr.error('Something problem in Internet, Please try Again', 'Warning!');
                    toastr.error('No Invoice Data Found!', 'Warning!'); 
                }
            });
        }

        $scope.addInvoice   =   function(credit){
            window.location = main_url+"rochakorders/addinvoice?id="+credit.id;
        }
    });

//************************ Rochak Orders Controller *********************//

//************************ Assign Sales to Distributor Controller *********************//

    app.controller('assigndistributorsTosales', function($scope, $http, toastr, ngProgressFactory){
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();

        $scope.cancelEditDistributors = function(){
            window.location = main_url+"distributors";
        }

        getUrlParameter = sParam => {
            var sPageURL    =   decodeURIComponent(window.location.search.substring(1)),
            sURLVariables   =   sPageURL.split('&'),
            sParameterName,
            i;
            for(i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }

        fetchAllSales = () => {
            $http.get(main_url+'distributors/fetch_sales_persons?dist='+getUrlParameter('id'))
                .then(function(response){
                $scope.appsalesperson      =   response.data;
                $scope.dataloading          =   false;

            });
        }
        fetchAllSales();

        $scope.assigns2d = function(){

            $scope.progressbar.start();

            var data = $.param({
                distributor_id      :   getUrlParameter('id'),
                sales_users         :   $scope.addsalesperson,
            });
            
            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'distributors/add_sales',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    toastr.success(response.data.message,'Success!');
                    $scope.addForm.$setPristine();
                    setTimeout(function(){ window.location = main_url+"distributors"; }, 2000);
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }
    });

//************************ Assign Sales to Distributor Controller *********************//

//************************ Glaucus Invoice Controller *********************//

    app.controller('addInvoiceCtrl', function($scope, $http, toastr, ngProgressFactory){
        
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();

        getUrlParameter = (sParam) => {
            var sPageURL    =   decodeURIComponent(window.location.search.substring(1)),
            sURLVariables   =   sPageURL.split('&'),
            sParameterName,
            i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        }

        $('#ivdate').bootstrapMaterialDatePicker({
            time        : false,
            clearButton : true
        });

        $scope.cancelInvoiceAdd = function(){
            window.location     =   main_url+"rochakorders";
        }

        $scope.addInvoice = function(){

            $scope.progressbar.start();

            $scope.invoiceDate  =   $('#ivdate').val();

            //setTimeout(function(){ $scope.progressbar.reset(); }, 6000);

            var data = $.param({
                order_id        :   getUrlParameter('id'),
                invoice_ins_id  :   $scope.invoiceID,
                invoice_date    :   $scope.invoiceDate,
                invoice_amount  :   $scope.invoiceAmount,
            });
            
            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'rochakorders/insert_invoice_data',data,config)
            .then(function(response){
                if(response.data.status == "success"){
                    $scope.progressbar.complete();
                    toastr.success(response.data.message,'Success!');
                    $scope.addForm.$setPristine();
                    setTimeout(function(){ document.getElementById("ivoadd").reset(); }, 2000);
                    //setTimeout(function(){ window.location = main_url+"itemmanagement"; }, 2000);
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }

    });

//************************ Glaucus Invoice Controller *********************//

//************************ Rochak Credit Controller *********************//

app.controller('creditCtrl', function($scope, $http, toastr, ngProgressFactory){

    $scope.dataloading  =   true;    
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    function getCreditList(){

        $http.get(main_url+'CreditHistory/get_credit_history_web')
            .then(function(response){
            $scope.appCredits       =   response.data;
            $scope.dataloading      =   false;

        });
    }
    
    getCreditList();
    
    $scope.sort     =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    }

    $scope.refreshTable     =   function(){
        getCreditList();
        toastr.success("Table Refreshed",'Success!');
    }
    
    $scope.viewCreditDetails  =   function(credit){
        
        $scope.activeCredit =
        {
            creditId        :   credit.id,
            orderId         :   credit.orderId,
            distributor     :   credit.distributor,
            salesperson     :   credit.firstName+' '+credit.lastName,
            credit          :   credit.credit,
            settled         :   credit.settled,
            outstanding     :   credit.outstanding,
            creditDate      :   credit.creditDate,
            lastPayment     :   credit.lastPaymentDate
        };

    };
    
    $scope.goToEdit         =   function(credit){
        window.location = main_url+"itemmanagement/edit?id="+credit.id;
    }

});

//************************ Rochak Credit Controller *********************//

//************************ Payment Projection Controller *********************//

app.controller('paymentProjectionlist', function($scope, $http, toastr, ngProgressFactory){
    
    $scope.dataloading  =   true;
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    function getPaymentProjections(){

        $http.get(main_url+'ProjectionHistory/get_projection_history_web')
            .then(function(response){
            $scope.appProjectionHistory =   response.data.projectiondata;
            $scope.dataloading          =   false;

        });
    }
    
    getPaymentProjections();
    
    $scope.sort             =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    };

    $scope.refreshTable     =   function(){
        getPaymentProjections();
        toastr.success("Table Refreshed",'Success!');
    };
    
    $scope.getProjectionDetails  = function getProjectionDetails(id){
        
          var data = $.param({
            masterProjectionId  :   id
          });

          var config = {
              headers : {
                  'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
              }
          }
          
          $http.post(main_url+'ProjectionHistory/get_single_projection_web',data,config)
          .then(function(response){
            if(response.data.status == "success"){
              $scope.projectionlist      =   response.data.projectiondata;
            }else if(response.data.status == "failed"){
              $scope.progressbar.complete();
              toastr.warning(response.data.message, 'Warning!');
            }else{
              $scope.progressbar.complete();
              toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
            }
          });
    }    
});

//************************ Payment Projection Controller *********************//

//************************ Glaucus Order Report Controller *********************//

    app.controller('rochakordersreport', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory){
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
        
        /*-------------------Time Period Filteration-------------------*/
        $scope.timePeriod = true;
        $scope.datePeriod = true;
        
        $scope.dateChecker      =   function(){
            $scope.time         =   angular.copy(null);
            $scope.datePeriod   =   false;
            $scope.timePeriod   =   true;
        }
        
        $scope.timeChecker      =   function(){
            $scope.fromDate     =   angular.copy('');
            $scope.toDate       =   angular.copy('');
            $scope.timePeriod   =   false;
            $scope.datePeriod   =   true;
        }
        /*-------------------Time Period Filteration-------------------*/

        $('#fromDate, #toDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true
        });
        
        getAllDistributors = () => {
            $http.get(main_url+'orderreport/distributor_list')
                .then(function(response){
                $scope.appdistributors      =   response.data;
                $scope.dataloading          =   false;
            });
        }
        
        getAllAppusers = () => {
            $http.get(main_url+'orderreport/app_user_list')
                .then(function(response){
                $scope.appusers         =   response.data;
                $scope.dataloading      =   false;

            });
        }
        
        getAllItems = () => {
            $http.get(main_url+'itemmanagement/allitems')
                .then(function(response){
                $scope.appItems         =   response.data;
                $scope.dataloading      =   false;
            });
        }    
        
        getAllDistributors();
        getAllAppusers();
        getAllItems();
        
        $scope.sort             =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        };
        $scope.refreshTable     =   function(){
            getAllDistributors();
            toastr.success("Table Refreshed",'Success!');
        };
        
        $scope.generateReports = function(){
            $scope.fromDate =   $('#fromDate').val();
            $scope.toDate   =   $('#toDate').val();

            var startDate   =   new Date($scope.fromDate);
            var endDate     =   new Date($scope.toDate);
            if($scope.fromDate && $scope.toDate){
                $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
            }
            else{
                $scope.reportDateInfo   =   "";
            }
            /*if($scope.fromDate == null || $scope.fromDate == ""){
                toastr.error("Please Select From Date",'Warning!');
            }else if($scope.toDate == null || $scope.toDate == ""){
                toastr.error("Please Select To Date",'Warning!');
            }else if(endDate < startDate){
              toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
            }else{*/
            if($scope.fromDate && $scope.toDate){
                if(endDate < startDate){
                    toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
                    return false;
                }
            }
            if($scope.fromDate){
                if($scope.toDate == null || $scope.toDate == ""){
                    toastr.error("Please Select To Date",'Warning!');
                    return false;
                }
            }
                var data = $.param({
                    dateFrom        : $scope.fromDate,
                    dateTo          : $scope.toDate,
                    salesUserId     : $scope.user,
                    distributorid   : $scope.distributor,
                    cartItemId      : $scope.item,
                    timevalue       : $scope.time
                });

                var config = {
                    headers : {
                      'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                    }
                }
                
                $http.post(main_url+'orderreport/get_order_filter_web_by_combinations',data,config)
                .then(function(response){
                    if(response.data.status == "success"){
                      $scope.progressbar.complete();
                      $scope.orders = response.data.message;
                      $scope.orderTotalAmount   =   0;
                      for(var i=0; i<$scope.orders.length; i++){
                        $scope.orderTotalAmount = $scope.orderTotalAmount + parseFloat($scope.orders[i].totalPrice);
                      }
                      /*$scope.progressbar.complete();
                      toastr.success(response.data.message,'Success!');
                      $scope.orders = response.data.message;*/
                    }else if(response.data.status == "failure"){
                      $scope.progressbar.complete();
                      toastr.warning(response.data.message, 'Warning!');
                    }else{
                      $scope.progressbar.complete();
                      toastr.error('Please Select any filter option', 'Warning!'); 
                    }

                });
            //}
        }
        
        $scope.exportToPDF = function(printSectionId){
        html2canvas(document.getElementById(printSectionId), {
                onrendered: function (canvas){
                  var data = canvas.toDataURL();
                  var docDefinition = {
                        content: [{
                            image: data,
                            width: 500,
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("report.pdf");
                }
            });
        }

        $scope.exportToExcel=function(tableId){
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100);
        }

        $scope.printToReport = function(printSectionId) {
            var innerContents = document.getElementById(printSectionId).innerHTML;
            var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
            popupWinindow.document.open();
            popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
            popupWinindow.document.close();
        }
    });

//************************ Glaucus Order Report Controller *********************//

//************************ Rochak Projection Report Controller *********************//

app.controller('rochakprojectionreport', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory){
    $scope.dataloading  =   true;
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    /*-------------------Time Period Filteration-------------------*/
    $scope.timePeriod = true;
    $scope.datePeriod = true;
    
    $scope.dateChecker      =   function(){
        $scope.time         =   angular.copy(null);
        $scope.datePeriod   =   false;
        $scope.timePeriod   =   true;
    }
    
    $scope.timeChecker      =   function(){
        $scope.fromDate     =   angular.copy('');
        $scope.toDate       =   angular.copy('');
        $scope.timePeriod   =   false;
        $scope.datePeriod   =   true;
    }
    /*-------------------Time Period Filteration-------------------*/

    $('#fromDate, #toDate').bootstrapMaterialDatePicker({
        time: false,
        clearButton: true
    });
    
    function getAllDistributors(){

        $http.get(main_url+'orderreport/distributor_list')
            .then(function(response){
            $scope.appdistributors      =   response.data;
            $scope.dataloading          =   false;
        });
    }
    
    function getAllAppusers(){

        $http.get(main_url+'orderreport/app_user_list')
            .then(function(response){
            $scope.appusers         =   response.data;
            $scope.dataloading      =   false;

        });
    }  
    
    getAllDistributors();
    getAllAppusers();
    
    $scope.sort             =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    };
    $scope.refreshTable     =   function(){
        getAllDistributors();
        toastr.success("Table Refreshed",'Success!');
    };
    
    $scope.generateReports = function(){
        $scope.fromDate =   $('#fromDate').val();
        $scope.toDate   =   $('#toDate').val();

        var startDate   =   new Date($scope.fromDate);
        var endDate     =   new Date($scope.toDate);
        if($scope.fromDate && $scope.toDate){
            $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
        }
        else{
            $scope.reportDateInfo   =   "";
        }
        /*if($scope.fromDate == null || $scope.fromDate == ""){
            toastr.error("Please Select From Date",'Warning!');
        }else if($scope.toDate == null || $scope.toDate == ""){
            toastr.error("Please Select To Date",'Warning!');
        }else if(endDate < startDate){
          toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
        }else{*/
        if($scope.fromDate && $scope.toDate){
            if(endDate < startDate){
                toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
                return false;
            }
        }
        if($scope.fromDate){
            if($scope.toDate == null || $scope.toDate == ""){
                toastr.error("Please Select To Date",'Warning!');
                return false;
            }
        }
            var data = $.param({
                dateFrom        : $scope.fromDate,
                dateTo          : $scope.toDate,
                salesUserId     : $scope.user,
                distributorid   : $scope.distributor,
                timevalue       : $scope.time
            });

            var config = {
                headers : {
                  'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'projectionreport/get_projection_filter_web_by_combinations',data,config)
              .then(function(response){
                if(response.data.status == "success"){
                  $scope.progressbar.complete();
                  $scope.projections    =   response.data.message;
                  $scope.projectionTotalAmount   =   0;
                  for(var i=0; i<$scope.projections.length; i++){
                    $scope.projectionTotalAmount = $scope.projectionTotalAmount + parseFloat($scope.projections[i].projectionAmount);
                  }
                  /*$scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  $scope.orders = response.data.message;*/
                }else if(response.data.status == "failure"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Please Select any filter option', 'Warning!'); 
                }

              });
        //}
    }
    
    $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }

    $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

    $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }
});

//************************ Rochak Projection Report Controller *********************//

//************************ Rochak Collection Report Controller *********************//

app.controller('rochakcollectionreport', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory){
    $scope.dataloading  =   true;
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    /*-------------------Time Period Filteration-------------------*/
    $scope.timePeriod = true;
    $scope.datePeriod = true;
    
    $scope.dateChecker      =   function(){
        $scope.time         =   angular.copy(null);
        $scope.datePeriod   =   false;
        $scope.timePeriod   =   true;
    }
    
    $scope.timeChecker      =   function(){
        $scope.fromDate     =   angular.copy('');
        $scope.toDate       =   angular.copy('');
        $scope.timePeriod   =   false;
        $scope.datePeriod   =   true;
    }
    /*-------------------Time Period Filteration-------------------*/

    $('#fromDate, #toDate').bootstrapMaterialDatePicker({
        time: false,
        clearButton: true
    });
    
    function getAllDistributors(){

        $http.get(main_url+'orderreport/distributor_list')
            .then(function(response){
            $scope.appdistributors      =   response.data;
            $scope.dataloading          =   false;
        });
    }
    
    function getAllAppusers(){

        $http.get(main_url+'orderreport/app_user_list')
            .then(function(response){
            $scope.appusers         =   response.data;
            $scope.dataloading      =   false;

        });
    }  
    
    getAllDistributors();
    getAllAppusers();
    
    $scope.sort             =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    };
    $scope.refreshTable     =   function(){
        getAllDistributors();
        toastr.success("Table Refreshed",'Success!');
    };
    
    $scope.generateReports = function(){
        $scope.fromDate =   $('#fromDate').val();
        $scope.toDate   =   $('#toDate').val();

        var startDate   =   new Date($scope.fromDate);
        var endDate     =   new Date($scope.toDate);
        if($scope.fromDate && $scope.toDate){
            $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
        }
        else{
            $scope.reportDateInfo   =   "";
        }
        /*if($scope.fromDate == null || $scope.fromDate == ""){
            toastr.error("Please Select From Date",'Warning!');
        }else if($scope.toDate == null || $scope.toDate == ""){
            toastr.error("Please Select To Date",'Warning!');
        }else if(endDate < startDate){
          toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
        }else{*/
        if($scope.fromDate && $scope.toDate){
            if(endDate < startDate){
                toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
                return false;
            }
        }
        if($scope.fromDate){
            if($scope.toDate == null || $scope.toDate == ""){
                toastr.error("Please Select To Date",'Warning!');
                return false;
            }
        }
            var data = $.param({
                dateFrom        : $scope.fromDate,
                dateTo          : $scope.toDate,
                salesUserId     : $scope.user,
                distributorid   : $scope.distributor,
                timevalue       : $scope.time
            });

            var config = {
                headers : {
                  'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'collectionreport/get_collection_filter_web_by_combinations',data,config)
              .then(function(response){
                if(response.data.status == "success"){
                  $scope.progressbar.complete();
                  $scope.collections    =   response.data.message;
                  $scope.collectionTotalAmount   =   0;
                  for(var i=0; i<$scope.collections.length; i++){
                    $scope.collectionTotalAmount = $scope.collectionTotalAmount + parseFloat($scope.collections[i].collectionAmount);
                  }
                  /*$scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  $scope.orders = response.data.message;*/
                }else if(response.data.status == "failure"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Please Select any filter option', 'Warning!'); 
                }

              });
        //}
    }
    
    $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }

    $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

    $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }
});

//************************ Rochak Collection Report Controller *********************//

//************************ Rochak Credit Report Controller *********************//

app.controller('rochakcreditreport', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory){
    $scope.dataloading  =   true;
    $scope.progressbar  =   ngProgressFactory.createInstance();
    
    /*-------------------Time Period Filteration-------------------*/
    $scope.timePeriod = true;
    $scope.datePeriod = true;
    
    $scope.dateChecker      =   function(){
        $scope.time         =   angular.copy(null);
        $scope.datePeriod   =   false;
        $scope.timePeriod   =   true;
    }
    
    $scope.timeChecker      =   function(){
        $scope.fromDate     =   angular.copy('');
        $scope.toDate       =   angular.copy('');
        $scope.timePeriod   =   false;
        $scope.datePeriod   =   true;
    }
    /*-------------------Time Period Filteration-------------------*/

    $('#fromDate, #toDate').bootstrapMaterialDatePicker({
        time: false,
        clearButton: true
    });
    
    function getAllDistributors(){

        $http.get(main_url+'orderreport/distributor_list')
            .then(function(response){
            $scope.appdistributors      =   response.data;
            $scope.dataloading          =   false;
        });
    }
    
    function getAllAppusers(){

        $http.get(main_url+'orderreport/app_user_list')
            .then(function(response){
            $scope.appusers         =   response.data;
            $scope.dataloading      =   false;

        });
    }  
    
    getAllDistributors();
    getAllAppusers();
    
    $scope.sort             =   function(keyname){
        $scope.sortKey  =   keyname; 
        $scope.reverse  =   !$scope.reverse; 
    };
    $scope.refreshTable     =   function(){
        getAllDistributors();
        toastr.success("Table Refreshed",'Success!');
    };
    
    $scope.generateReports = function(){
        $scope.fromDate =   $('#fromDate').val();
        $scope.toDate   =   $('#toDate').val();

        var startDate   =   new Date($scope.fromDate);
        var endDate     =   new Date($scope.toDate);
        if($scope.fromDate && $scope.toDate){
            $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
        }
        else{
            $scope.reportDateInfo   =   "";
        }
        /*if($scope.fromDate == null || $scope.fromDate == ""){
            toastr.error("Please Select From Date",'Warning!');
        }else if($scope.toDate == null || $scope.toDate == ""){
            toastr.error("Please Select To Date",'Warning!');
        }else if(endDate < startDate){
          toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
        }else{*/        
        if($scope.fromDate && $scope.toDate){
            if(endDate < startDate){
                toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
                return false;
            }
        }
        if($scope.fromDate){
            if($scope.toDate == null || $scope.toDate == ""){
                toastr.error("Please Select To Date",'Warning!');
                return false;
            }
        }
            var data = $.param({
                dateFrom        : $scope.fromDate,
                dateTo          : $scope.toDate,
                salesUserId     : $scope.user,
                distributorid   : $scope.distributor,
                timevalue       : $scope.time
            });

            var config = {
                headers : {
                  'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
            
            $http.post(main_url+'creditreport/get_credit_filter_web_by_combinations',data,config)
              .then(function(response){
                if(response.data.status == "success"){
                  $scope.progressbar.complete();
                  $scope.credits    =   response.data.message;
                  $scope.creditTotalAmount   =   0;
                  for(var i=0; i<$scope.credits.length; i++){
                    $scope.creditTotalAmount = $scope.creditTotalAmount + parseFloat($scope.credits[i].outstanding);
                  }
                  /*$scope.progressbar.complete();
                  toastr.success(response.data.message,'Success!');
                  $scope.orders = response.data.message;*/
                }else if(response.data.status == "failure"){
                  $scope.progressbar.complete();
                  toastr.warning(response.data.message, 'Warning!');
                }else{
                  $scope.progressbar.complete();
                  toastr.error('Please Select any filter option', 'Warning!'); 
                }

              });
        //}
    }
    
    $scope.exportToPDF = function(printSectionId){
    html2canvas(document.getElementById(printSectionId), {
      onrendered: function (canvas) {
          var data = canvas.toDataURL();
          var docDefinition = {
              content: [{
                  image: data,
                  width: 500,
              }]
          };
          pdfMake.createPdf(docDefinition).download("report.pdf");
      }
    });
  }

    $scope.exportToExcel=function(tableId){
    var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
    $timeout(function(){location.href=exportHref;},100);
  }

    $scope.printToReport = function(printSectionId) {
    var innerContents = document.getElementById(printSectionId).innerHTML;
    var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    popupWinindow.document.open();
    popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
    popupWinindow.document.close();
  }
});

//************************ Rochak Credit Report Controller *********************//

//************************ Rochak Stock Listing Controller *********************//

    app.controller('allStockslist', function($scope, $http, toastr, ngProgressFactory){
    
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
  
        getAllRochakStocks = () => {

            $http.get(main_url+'rochakstocks/get_stocks_list')
                .then(function(response){
                $scope.approchakstocks      =   response.data.message;
                $scope.dataloading          =   false;
            });
        }
  
        getAllRochakStocks();
  
        $scope.sort             =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        };

        $scope.refreshTable     =   function(){
            getAllRochakStocks();
            toastr.success("Table Refreshed",'Success!');
        };
  
        $scope.getStockDetails  = function getStockDetails(id){
      
            var data = $.param({
                id  :   id
            });

            var config = {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }
        
            $http.post(main_url+'rochakstocks/get_single_stock',data,config)
                .then(function(response){
                if(response.data.status == "success"){
                    $scope.rochakstockslist      =   response.data.message;
                }
                else if(response.data.status == "failed"){
                    $scope.progressbar.complete();
                    toastr.warning(response.data.message, 'Warning!');
                }
                else{
                    $scope.progressbar.complete();
                    toastr.error('Something problem in Internet, Please try Again', 'Warning!'); 
                }
            });
        }    
    });

//************************ Rochak Orders Controller *********************//

//************************ Rochak Stock Report Controller *********************//

    app.controller('rochakstockreport', function($scope, $http, toastr, Excel, $timeout, ngProgressFactory){
        $scope.dataloading  =   true;
        $scope.progressbar  =   ngProgressFactory.createInstance();
      
        /*-------------------Time Period Filteration-------------------*/
        $scope.timePeriod = true;
        $scope.datePeriod = true;
      
        $scope.dateChecker      =   function(){
            $scope.time         =   angular.copy(null);
            $scope.datePeriod   =   false;
            $scope.timePeriod   =   true;
        }
      
        $scope.timeChecker      =   function(){
            $scope.fromDate     =   angular.copy('');
            $scope.toDate       =   angular.copy('');
            $scope.timePeriod   =   false;
            $scope.datePeriod   =   true;
        }
        /*-------------------Time Period Filteration-------------------*/

        $('#fromDate, #toDate').bootstrapMaterialDatePicker({
            time: false,
            clearButton: true
        });
      
        getAllDistributors = () => {
            $http.get(main_url+'orderreport/distributor_list')
                .then(function(response){
                $scope.appdistributors      =   response.data;
                $scope.dataloading          =   false;
            });
        }
      
        getAllAppusers = () => {
            $http.get(main_url+'orderreport/app_user_list')
                .then(function(response){
                $scope.appusers         =   response.data;
                $scope.dataloading      =   false;
            });
        }
      
        getAllItems = () => {
            $http.get(main_url+'itemmanagement/allitems')
            .then(function(response){
                $scope.appItems         =   response.data;
                $scope.dataloading      =   false;
            });
        }
      
        getAllDistributors();
        getAllAppusers();
        getAllItems();
      
        $scope.sort     =   function(keyname){
            $scope.sortKey  =   keyname; 
            $scope.reverse  =   !$scope.reverse; 
        };
        
        $scope.refreshTable     =   function(){
            getAllDistributors();
            toastr.success("Table Refreshed",'Success!');
        };
      
        $scope.generateReports = function(){

            $scope.fromDate =   $('#fromDate').val();
            $scope.toDate   =   $('#toDate').val();

            var startDate   =   new Date($scope.fromDate);
            var endDate     =   new Date($scope.toDate);
            if($scope.fromDate && $scope.toDate){
                $scope.reportDateInfo   =   "( "+$scope.fromDate+" to "+$scope.toDate+" )";
            }
            else{
                $scope.reportDateInfo   =   "";
            }
              /*if($scope.fromDate == null || $scope.fromDate == ""){
                  toastr.error("Please Select From Date",'Warning!');
              }else if($scope.toDate == null || $scope.toDate == ""){
                  toastr.error("Please Select To Date",'Warning!');
              }else if(endDate < startDate){
                toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
              }else{*/        
            if($scope.fromDate && $scope.toDate){
                if(endDate < startDate){
                    toastr.error('End Date must be greater than or equal to the Start Date', 'Warning!');
                    return false;
                }
            }
            if($scope.fromDate){
                if($scope.toDate == null || $scope.toDate == ""){
                    toastr.error("Please Select To Date",'Warning!');
                    return false;
                }
            }
                var data = $.param({
                    dateFrom        : $scope.fromDate,
                    dateTo          : $scope.toDate,
                    salesUserId     : $scope.user,
                    distributorid   : $scope.distributor,
                    stockCartItemId : $scope.item,
                    timevalue       : $scope.time
                });

                var config = {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                    }
                }
                
                $http.post(main_url+'stockreport/get_stock_filter_web_by_combinations',data,config)
                .then(function(response){
                    if(response.data.status == "success"){
                        $scope.progressbar.complete();
                        $scope.stocks    =   response.data.message;
                        $scope.stockTotalAmount   =   0;
                        for(var i=0; i<$scope.stocks.length; i++){
                          $scope.stockTotalAmount = $scope.stockTotalAmount + parseFloat($scope.stocks[i].totalPrice);
                        }
                        /*$scope.progressbar.complete();
                        toastr.success(response.data.message,'Success!');
                        $scope.orders = response.data.message;*/
                    }
                    else if(response.data.status == "failure"){
                        $scope.progressbar.complete();
                        toastr.warning(response.data.message, 'Warning!');
                    }
                    else{
                        $scope.progressbar.complete();
                        toastr.error('Please Select any filter option', 'Warning!'); 
                    }
                });
        }
      
        $scope.exportToPDF = function(printSectionId){
            html2canvas(document.getElementById(printSectionId),{
                onrendered: function (canvas){
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500,
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("report.pdf");
                }
            });
        }

        $scope.exportToExcel=function(tableId){
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100);
        }

        $scope.printToReport = function(printSectionId) {
            var innerContents = document.getElementById(printSectionId).innerHTML;
            var popupWinindow = window.open('', '_blank', 'width=1200,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
            popupWinindow.document.open();
            popupWinindow.document.write('<html><head></head><body onload="window.print()">' + innerContents + '</html>');
            popupWinindow.document.close();
        }
    });

//************************ Rochak Stock Report Controller *********************//