<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>



<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title m-t-0 m-b-20">Google</h4>
                </div>
            </div>
			<div class="row">
				<div class="col-md-12">
					<div class="card-box">                        
						<form class="form-horizontal" action="">
				   
							<div class="form-group">
								<div class="col-md-6">
									<label >Address</label>
									<input type="text" class="form-control" id="address" name="address" value="" >
								</div>
								<div class="col-md-6">
									<label >City</label>
									<input type="text" class="form-control" id="city" name="city" value="" >
								</div>
							</div>
							 
							<div class="form-group">
								<div class="col-md-6">
									<label >Latitude</label>
									<input type="text" class="form-control" id="latitude" name="latitude" value="" >
								</div>
								<div class="col-md-4">
									<label >Altitude</label>
									<input type="text" class="form-control" id="longitude" name="longitude" value="" >
								</div>
							  	<div class="col-md-2">
                       			<label class="control-label">Click Get latitude and longitude</label>
                       			<br>
                       			<a href="javascript:void(0)"  data-toggle="modal" onclick="initMap()" data-target="#my_map">Map</a>
                    			</div> 
							</div>
						  
					 
							<div class="m-t-30 text-center">
								<button name="form_submit" type="button"  class="btn btn-primary" value="true">save</button>
								 
							</div>
						</form>                          
					</div>
				</div>
			</div>
		</div>
	</div>

	 <!-- Modal -->
<div id="my_map" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Map</h4>
        <p>Drag and point to get latitude and longitude</p>
      </div>
      <div class="modal-body">
            <div id="map" style="height:400px;background:rgb(243, 243, 139)"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> 


	  <script>

      function initMap() {

        var street_address  = $('#address').val();
        var city    = $('#city').val();
        var key = "AIzaSyDzviwvvZ_S6Y1wS6_b3siJWtSJ5uFQHoc";
        var google_address ='';
        var google_lat ='';
        var google_lng ='';

                if(street_address == '' && city == ''){
                    street_address = 'coimbatore';
                    city = 'tamil nadu';
                }
        var address = street_address+','+city;

        $.get('https://maps.googleapis.com/maps/api/geocode/json',{address:address,key:key},function(data, status){
            
            $(data.results).each(function(key,value){
                
                google_address = value.formatted_address;
                google_lat     = value.geometry.location.lat;
                google_lng     = value.geometry.location.lng;
                if($('#latitude').val() == ''){
                    $('#latitude').val(google_lat);    
                }
                if($('#longitude').val() == ''){
                    $('#longitude').val(google_lng);    
                }
                
            });
        });


        setTimeout(function() {

        var latitude = $('#latitude').val();
        var longitude = $('#longitude').val();
        if(latitude == ''){
            latitude = google_lat;
         }
         if(longitude == ''){
            longitude = google_lng;
         }
            load_msp_details(latitude,longitude)
        }, 1000);
 
      }

      function load_msp_details(latitude,longitude){
         var uluru = {
                    lat: parseFloat(latitude),
                    lng:parseFloat(longitude)
                    };
        var geocoder = new google.maps.Geocoder();
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: uluru
        });
        
        var marker = new google.maps.Marker({
          position: uluru,
          map: map,
          draggable: true 
        });    

            google.maps.event.addListener(marker, 'dragend', function() {

        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                      //$('#street_address').val(results[0].formatted_address);
                      $('#latitude').val(marker.getPosition().lat());
                      $('#longitude').val(marker.getPosition().lng());
                   }
                }
            });
    });
      }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzviwvvZ_S6Y1wS6_b3siJWtSJ5uFQHoc">
    </script>
</div>
</body>
</html>