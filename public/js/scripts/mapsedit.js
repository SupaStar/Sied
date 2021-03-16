let map;
var latitud=parseFloat(document.getElementById("lat").value)
var longitud=parseFloat(document.getElementById("long").value)
function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: {lat: latitud, lng: longitud},
  //  center: {lat: -35.397, lng: 150.644},
    zoom: 4,
  });
  const marker = new google.maps.Marker({
    position: {lat: latitud, lng: longitud},
    map: map,
  });
}

