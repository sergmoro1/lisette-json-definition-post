ymaps.ready(init);
var map;
var collection; 

yaMapParams = {'visible':1,'zoom':13,'width':'300px','height':'200px'};

var placemarkColor = [
	"twirl#lightblueStretchyIcon", "twirl#violetStretchyIcon", "twirl#greenStretchyIcon",
	"twirl#redStretchyIcon", "twirl#yellowStretchyIcon",
	"twirl#darkblueStretchyIcon", "twirl#nightStretchyIcon",
	"twirl#greyStretchyIcon", "twirl#blueStretchyIcon",
	"twirl#orangeStretchyIcon", "twirl#darkorangeStretchyIcon",
	"twirl#pinkStretchyIcon", "twirl#whiteStretchyIcon"
];

function init() {
if(yaMapPoints.length != 0) {
	var point=yaMapPoints[0];

	var mc=document.getElementById('map_canvas');
	var display=mc.style.display;
	mc.style.display='block';
	mc.style.width=yaMapParams['width'];
	mc.style.height=yaMapParams['height'];
		
	map = new ymaps.Map ('map_canvas', {center: [point['lat'], point['lng']], zoom: yaMapParams['zoom']}); 
	map.controls.add('smallZoomControl');
	map.controls.add('mapTools');

	collection = new ymaps.GeoObjectCollection();
	
	for(var i=0;i<yaMapPoints.length;i++) {
		point=yaMapPoints[i];
		collection.add(makePlacemark(point));
		}
	map.geoObjects.add(collection);
	if(yaMapPoints.length>1)
		map.setBounds(collection.getBounds());
	if(yaMapParams['visible'])
		mc.style.display='block';
	else
		if(display=='none') mc.style.display='none';
	}
	$('#toggler').click(toggle);
}

function makePlacemark(point) {
newPlacemark = new ymaps.Placemark([point['lat'], point['lng']], 
	{
		iconContent: point['icon'],
		balloonContentHeader: point['header'],
		balloonContentBody: '<em>' + point['body'] + '</em>',
		balloonContentFooter: point['footer'] 
	}, 
	{
		preset: placemarkColor[point['color'] !== undefined ? point['color'] : 0 ] //'twirl#blueStretchyIcon' 
	});
return newPlacemark;
}
