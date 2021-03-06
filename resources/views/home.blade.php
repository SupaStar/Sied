@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('content')
  <style>
    #map {
      height: 100%;
      position: relative !important;
    }

    /* Optional: Makes the sample page fill the window. */
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
  <!-- // Basic multiple Column Form section start -->
  <section id="multiple-column-form">
    <div class="row match-height">

    </div>
  </section>
  <div id="map"></div>
  <!-- // Basic Floating Label Form section end -->
@endsection
@section('page-script')
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAK4wLltj-bl9LICOgb5MITEWtoupx0nc4&callback=initMap&libraries=&v=weekly"
    async></script>
  <script>
    let map;

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: -34.397, lng: 150.644},
        zoom: 8,
      });
    }
  </script>
@endsection
