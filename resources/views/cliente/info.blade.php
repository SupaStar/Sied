@extends('layouts/contentLayoutMaster')

@section('title', 'Información')

@section('page-style')
        {{-- Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/pages/invoice.css')) }}">
@endsection
@section('content')
<!-- invoice functionality start -->
<section class="invoice-print mb-1">
    <div class="row">
      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
      </fieldset>
      <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
        <button class="btn btn-primary btn-print mb-1 mb-md-0"> <i class="feather icon-file-text"></i> Imprimir</button>
        <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button>
      </div>
    </div>
  </section>
  <!-- invoice functionality end -->
<section class="card invoice-page">
  <div id="invoice-template" class="card-body">

      <!-- Invoice Items Details -->
      <div id="invoice-items-details" class="pt-1 invoice-items-table">
          <div class="row">
              <div class="table-responsive col-sm-12">
                  
              </div>
          </div>
      </div>

      <!-- Invoice Footer -->
      <div id="invoice-footer" class="text-right pt-3">
          <p>
          </p>
      </div>
      <!--/ Invoice Footer -->
  </div>
</section>
@endsection
@section('page-script')
        <!-- Page js files -->
        <script src="{{ asset(mix('js/scripts/pages/invoice.js')) }}"></script>
@endsection
