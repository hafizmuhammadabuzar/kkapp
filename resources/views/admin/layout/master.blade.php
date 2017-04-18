<?php
if (strpos($_SERVER['REQUEST_URI'], "view-categories") > 0 || strpos($_SERVER['REQUEST_URI'], "add-category") > 0 || strpos($_SERVER['REQUEST_URI'], "edit-category") > 0) {
	$category = 'class="active"';
} else if (strpos($_SERVER['REQUEST_URI'], "view-users") > 0 || strpos($_SERVER['REQUEST_URI'], "add-user") > 0 || strpos($_SERVER['REQUEST_URI'], "edit-user") > 0) {
	$user = 'class="active"';
} else if (strpos($_SERVER['REQUEST_URI'], "-event") > 0) {
	$event = 'class="active"';
} else if (strpos($_SERVER['REQUEST_URI'], "-type") > 0) {
	$type_class = 'class="active"';
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>KK App</title>
	<meta charset="utf-8">
	<meta name="author" content="Alpha Beta">
	<meta name="format-detection" content="telephone=no"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<!--	<link rel="shortcut icon" href="images/favicon.png">-->
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/jquery-ui.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/animate.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/modal.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('public/admin/css/style.css') }}">
	<script type="text/javascript" src="{{ URL::asset('public/admin/js/jquery-1.11.2.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('public/admin/js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('public/admin/js/modal.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('public/admin/js/all.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.msg-success, .msg-error').fadeOut(3000);
		});
	</script>
	<style type="text/css">
		.add-btn{
			float: right;
			padding: 5px;
			border: 1px #81807e solid;
		}
		#map {
			padding: 100px;
			margin-bottom: 5px;
			width: 100%;
	        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 60%;
        margin: 0;
        padding: 0;
      }
	</style>
</head>
<body>
	<div id="add-location" style="display: none;">
		<form action="javascript:" class="form-location">
			<div class="form-group">
				<label for="city">Select City</label>
				<select name="event_city" id="event-city" class="form-control" required="required">
				@if(isset($cities)):
					@foreach($cities as $city):
						<option value="{{$city->city_name.','.$city->latitude.','.$city->longitude}}">{{$city->city_name}}</option>
					@endforeach
				@endif
				</select>
			</div>
			<div class="form-group">
				<label for="event-location">Event Location</label>

				<input id="event-location" class="form-control" type="text" placeholder="Search Box">

				{{-- <input type="text" id="event-location" class="form-control" required="required"> --}}
				<input type="hidden" id="event-latlngs" class="form-control" required="required">
			</div>
			<div class="g-map" id="map"></div>
			<div class="form-group">
				<a href="#close-modal"><input type="submit" class="btn btn-info pull-right" value="Add Location"></a>
			</div>
		</form>
	</div>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<nav class="main-nav">
				<a href="{{ URL('/admin') }}" class="logo"><img src="{{ URL::asset('public/admin/images/logo.png')}}"></a>
				<ul class="main-menu">
					<li <?php if (isset($event)) {echo $event;}?>><a href="{{url('admin/view-events')}}">Events</a></li>
					<li <?php if (isset($user)) {echo $user;}?>><a href="{{url('admin/view-users')}}">Users</a></li>
					<li><a href="#">Verified Users</a></li>
					<li <?php if (isset($category)) {echo $category;}?>><a href="{{url('admin/view-categories')}}">Categories</a></li>
					<li <?php if (isset($type_class)) {echo $type_class;}?>><a href="{{url('admin/view-types')}}">Type</a></li>
					<li><a href="{{url('/admin/logout')}}">Logout</a></li>
					{{-- <li><a href="push.php">Push Notifications</a></li> --}}
					{{-- <li><a href="requests.php">Requests</a></li> --}}
				</ul>
			</nav>
		</div>
	</div>
	@yield('content')
	<footer id="footer">
		<div class="container">
			<div class="row">NULL
				<span class="copyright">&copy All Rights Reserved KK App <?php echo date('Y');?></span>
			</div>
		</div>
		<script>

		function initMap(lat, lng) {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 24.4539, lng: 54.3773},
          zoom: 12,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('event-location');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
        	deleteMarkers();
          addMarker(event.latLng);
        });

        // Adds a marker to the map and push to the array.
	      function addMarker(location) {
	        var marker = new google.maps.Marker({
	          position: location,
	          map: map
	        });
	        markers.push(marker);
	      }

	        // Sets the map on all markers in the array.
	      function setMapOnAll(map) {
	        for (var i = 0; i < markers.length; i++) {
	          markers[i].setMap(map);
	        }
	      }

	      // Removes the markers from the map, but keeps them in the array.
	      function clearMarkers() {
	        setMapOnAll(null);
	      }

	      // Deletes all markers in the array by removing references to them.
	      function deleteMarkers() {
	        clearMarkers();
	        markers = [];
	      }
      }


	      /*function initMap(lat, lng) {
	      	var latitude, longitude;
	        var map = new google.maps.Map(document.getElementById('map'), {
	          zoom: 12,
	          center: {lat: 24.4539, lng: 54.3773 }
	        });
      		map.setCenter(new google.maps.LatLng(lat, lng));

	        map.addListener('click', function(e) {
	          placeMarkerAndPanTo(e.latLng, map);
	        });
	      }

	      function placeMarkerAndPanTo(latLng, map) {
	      	// marker.setMap(null);
	        var latlngs = $('#event-latlngs').val();
	        var marker = new google.maps.Marker({
	          position: latLng,
	          map: map
	        });
	        $('#event-latlngs').val(latlngs+latLng);
	        map.panTo(latLng);
	      }*/
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDvM91-7P1Vm9CvI1jwygRn8budCXu2hP8&libraries=places" async defer></script>
		@yield('script')
	</footer>
</div>
</body>
</html>