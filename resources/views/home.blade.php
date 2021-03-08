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

  <!-- // Basic Floating Label Form section end -->
@endsection
@section('page-script')
@endsection
