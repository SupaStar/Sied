let map;
function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: {lat: -35.397, lng: 150.644},
    zoom: 8,
  });
  buscar();
}

function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function buscar() {
  var geocoder = new google.maps.Geocoder();
  var direccion = $("#street").val() + ' ' + $('#exterior').val() + ' ' + $("#cp").val() + ' '
    + $('select[name="colony"] option:selected').text() + ' ' + $('select[name="town"] option:selected').text();
  geocoder.geocode({'address': direccion}, function (results, status) {
    if (status === 'OK') {

      var resultados = results[0].geometry.location,
        resultados_lat = resultados.lat(),
        resultados_long = resultados.lng();

      $("#lat").val(resultados_lat);
      $("#long").val(resultados_long);
      map.zoom = 16;
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        title: "Ubicar",
        animation: google.maps.Animation.DROP,
        position: results[0].geometry.location
      });

      marker.addListener('click', toggleBounce);

      marker.addListener('dragend', function (event) {
        $("#lat").val(this.getPosition().lat());
        $("#long").val(this.getPosition().lng());
      });

    } else {
      var mensajeError = "";
      if (status === "ZERO_RESULTS") {
        mensajeError = "No hubo resultados para la dirección ingresada.";
      } else if (status === "OVER_QUERY_LIMIT" || status === "REQUEST_DENIED" || status === "UNKNOWN_ERROR") {
        mensajeError = "Error general del mapa.";
      } else if (status === "INVALID_REQUEST") {
        mensajeError = "Error de la web. Contacte con Name Agency.";
      }
      alert(mensajeError);
    }
  });
}
