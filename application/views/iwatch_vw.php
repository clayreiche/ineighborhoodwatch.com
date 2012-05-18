<style type="text/css">
	#map_canvas { height: 100% }
	.contextmenu{
		visibility:hidden;
		background:#ffffff;
		border:1px solid #8888FF;
		z-index: 1;  
		position: relative;
		width: 160px;
	}
	.contextmenu div{
    	padding-left: 5px;
    }
	a{
    	z-index: 99;
    }
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBHzd6gKSh4lByCqSJg5FMxQ8mzP_ep1Cs&sensor=true"></script>
<style type="text/css">

</style>
<script type="text/javascript">
	var map;
	function initialize() {
		var styleArray = [{featureType: "administrative.land_parcel", stylers: [{ hue: '#3d674e'}, {visibility: 'on'}, {lightness: '-25'}, {gamma: '1.0'},{saturation: '0'}]}];
		var watersEdgeMapType = new google.maps.StyledMapType(styleArray,{name: "Waters Edge"});
		
    	var myOptions = {
    		center: new google.maps.LatLng(28.287613,-82.623348),
    		zoom: 17,
        	mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: { mapTypeIds: [google.maps.MapTypeId.HYBRID, 'waters_edge']}
        };
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		//Associate the styled map with the MapTypeId and set it to display.
		map.mapTypes.set('waters_edge', watersEdgeMapType);
		map.setMapTypeId('waters_edge');
		google.maps.event.addListener(map, "rightclick",function(event){showContextMenu(event.latLng);});
    }
	
	function getCanvasXY(caurrentLatLng){
		var scale = Math.pow(2, map.getZoom());
		var nw = new google.maps.LatLng(
			map.getBounds().getNorthEast().lat(),
			map.getBounds().getSouthWest().lng()
		);
		var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
		var worldCoordinate = map.getProjection().fromLatLngToPoint(caurrentLatLng);
		var caurrentLatLngOffset = new google.maps.Point(
			Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
			Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
		);
		return caurrentLatLngOffset;
	}
	
	function setMenuXY(caurrentLatLng){
		var mapWidth = $('#map_canvas').width();
		var mapHeight = $('#map_canvas').height();
		var menuWidth = $('.contextmenu').width();
		var menuHeight = $('.contextmenu').height();
		var clickedPosition = getCanvasXY(caurrentLatLng);
		var x = clickedPosition.x ;
		var y = clickedPosition.y ;
		
		if((mapWidth - x ) < menuWidth)
			x = x - menuWidth;
		if((mapHeight - y ) < menuHeight)
			y = y - menuHeight;
			
		$('.contextmenu').css('left',x  );
		$('.contextmenu').css('top',y );
	};
	
	function showContextMenu(caurrentLatLng  ) {
		var projection;
		var contextmenuDir;
		projection = map.getProjection() ;
		$('.contextmenu').remove();
		contextmenuDir = document.createElement("div");
		contextmenuDir.className  = 'contextmenu';
		contextmenuDir.innerHTML = "<a id='menu1' href='/register?latlng=" + escape(caurrentLatLng) + "'><div id='livehere' class=context>I Live Here." + "<\/div><\/a>";
		$(map.getDiv()).append(contextmenuDir);
		
		setMenuXY(caurrentLatLng);
		
		contextmenuDir.style.visibility = "visible";
	}
	  
	$(function() {
		initialize();
		$('#map_canvas').click(function() {
			$('.contextmenu').remove();
		});
	});
</script>
<div id="map_canvas" style="width:100%; height:100%;"></div>

