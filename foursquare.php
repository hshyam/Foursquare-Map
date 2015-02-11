<!DOCTYPE html>
<!-- 
Name: Harshita Shyam
x500 id: shyam007
-->


<html>
   <head>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
       <title>foursquare</title>
       <?

       // Get querying location from url
       $lat = $_GET["lat"];
       $lng = $_GET["lng"];
       $k = $_GET["k"];
       $key = $_GET["key"];
       if ($lat == null) {
           $lat = 44.9799654;
       }
       if ($lng == null) {
           $lng = -93.2638361;
       }
       if ($k == NULL) {
           $k = 10;
       }
       if ($key == NULL) {
           $key = '4d4b7104d754a06370d81259';
       }
       $request = 'https://api.foursquare.com/v2/venues/search?ll=' . $lat . ',' .$lng . '&oauth_token=URP3EOBOEHCH10DDUBPDMWDGDVEJ2NEC2X1F0WYHRG4R5U3C&v=20140322&limit=' . $k . '&radius=8000&categoryId=' . $key;
       

         // Get response from foursquare
       $response = @file_get_contents($request);        

       // Parse the responded JSON file into the PHP array for the query location 
       $json_output = (json_decode($response));    
       
       ?>
       
       <!-- setup to use google maps --> 
       <link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
       <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script> 
       <script type="text/javascript">


           // num Results refers to the results being displayed by your search
           var numResults; 
           
           var lat=<? echo $lat ?>;
           var lng=<? echo $lng ?>;

          // venuecat refers to the categories of different 
           // venues and marker sets the default Google Map markers
           var venuecat; 
           var marker; 
	  
           
           function initialize() {
   	
               // mapOptions centers map to the query location
               var mapOptions = {
               zoom: 10,
               center: new google.maps.LatLng(lat, lng),
               mapTypeId: google.maps.MapTypeId.ROADMAP
               };
                 
               // New map object created
                map = new google.maps.Map(document.getElementById('map-canvas'),
                      mapOptions);

	       // Listen to right click event and then redirect to a new lat/lng based on the click    
               google.maps.event.addListener(map, "rightclick", function(event) {      
                   lat = event.latLng.lat(); 
                   lng = event.latLng.lng();     
                   marker.setPosition(event.latLng);
               });

               // Place a marker and use the lat/lng info from the map click
               marker = new google.maps.Marker({
                   position: new google.maps.LatLng(lat, lng),
                   map: map,
                   title: "Query Marker"
               });
     
<? 

// For each of the venues returned by the foursquare query, we go ahead and place the results 
// on the map. First we get the icons from foursquare for all the categories. Then, we create 
// the information window  which pops up a window for each venue with the content for the marker.

	if (isset($json_output))
	foreach ($json_output->response->venues as $venue) {
	   $i++;
	   echo "
	   var marker".$i." = new google.maps.Marker({
	       position: new google.maps.LatLng(".$venue->location->lat.",".$venue->location->lng."),
	       map: map,
	       icon: \"".$venue->categories[0]->icon->prefix."bg_44".$venue->categories[0]->icon->suffix."\",
	       title: \"Foursquare Venues \"
	   });
	   
	   var infowindow".$i." = new google.maps.InfoWindow({
	       content:\"<h3>Venue #".$i."</h3>"."<br>".$venue->name."<br>".$venue->categories[0]->name."\"
	   });
	   

	   google.maps.event.addListener(marker".$i.", 'click', function() {
	       infowindow".$i.".open(map,marker".$i.");
	   }); ";
	   
	}

?>

         } 
       
   
	function updateSlider(val) {
		numResults = val;
		document.getElementById('results').value=val;
	}

	function submitForm() {

               var catboxes = document.getElementsByName('categories');
               var len = catboxes.length;
               venuecat = null;
               for (var i = 0; i < len; i++) {
                   if (catboxes[i].checked) {
                       if (venuecat === null) {
                           venuecat = catboxes[i].value;
                       }
                       else {
                           venuecat += "," + catboxes[i].value;
                       }
                   }
               }
               if (lat === null || lng === null) {
                   lat = Marker.latLng.lat();
                   lng = Marker.latLng.lng();
               }

               if (venuecat !== null) {
                   window.location.replace("foursquare.php?" + "lat=" + lat + "&lng=" + lng + "&k=" + numResults + "&key=" + venuecat);
               }
               else {
                   window.location.replace("foursquare.php?" + "lat=" + lat + "&lng=" + lng + "&k=" + numResults);
               }
           }
           google.maps.event.addDomListener(window, 'load', initialize);
       </script>
   </head>
   <body>

 <!-- Checkboxes for different categories and slider with text input box -->

       <div id="header"> <h1>Foursquare Map</h1></div>
       <hr>
      <div id="menu" style="width: 30%;float:left;">
<form action="foursquare.php" method="get">
           <input type="checkbox" name="categories" value="4d4b7104d754a06370d81259">Arts & Entertainment<br>
           <input type="checkbox" name="categories" value="4d4b7105d754a06374d81259">Food<br>
           <input type="checkbox" name="categories" value="4d4b7105d754a06376d81259">Nightlife Spot<br>
           <input type="checkbox" name="categories" value="4d4b7105d754a06377d81259">Outdoors & Recreation<br>
           <input type="checkbox" name="categories" value="4d4b7105d754a06378d81259">Shop & Service<br>
           <input type="checkbox" name="categories" value="4d4b7105d754a06379d81259">Travel & Transport<br>                 
          
		
           Total Results: 0<input type="range" name="slider" onchange="updateSlider(this.value)" min="0" max="50">50
		<input type="text" id="results" value="" readonly>
		<br>
           	<input type="button" value="Submit" onclick="submitForm()">
            </form>  
          </div>
   

       <div id="map-canvas" style="background-color:lightblue;height:800px;width: 70%;float:left;">
       </div>  
   </body>
</html>
