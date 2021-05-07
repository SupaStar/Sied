@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('vendor-style')
  {{-- vendor files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')) }}">
@endsection
@section('page-style')
  {{-- Page css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
@endsection

@section('content')
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">

              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#tab-pld" role="tab"
                     aria-controls="home-just" aria-selected="true">Configuración Alertas PLD</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-credito" data-toggle="tab" href="#tab-amortizacion" role="tab"
                     aria-controls="profile-just" aria-selected="true">Configuración Amortizaciones</a>
                </li>

              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="tab-pld" role="tabpanel" aria-labelledby="home-tab-justified">
                  <!-- invoice functionality start -->
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>
                      <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
                         <!-- <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button> -->
                      </div>
                    </div>
                  </section>
                  <!-- invoice functionality end -->
                  <section class="card invoice-page d-flex justify-content-center">
                    <h1>Alertas PLD</h1>
                    <div id="invoice-template" class="card-body">
                      <!-- Invoice Items Details -->
                      <div id="invoice-items-details" class="pt-1 invoice-items-table">
                        <div class="container-fluid">

                        </div>
                        <div class="row">
                          <form>
                            @csrf
                            <input type="hidden" name="id" value="">
                            <div class="form-group row">
                              <div class="col">
                                <label>Monto: </label>
                                <input type="number" name="monto" step="any" min="0" placeholder="$" class="form-control required" required>
                              </div>
                              <div class="col">
                                <label>Numero de Pagos: </label>
                                <input type="number" name="nPagos" step="any" min="0" placeholder="0" class="form-control required" required>
                              </div>

                            </div>
                            <div class="float-right">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-primary" >Aplicar</button>
                            </div>

                          </form>
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
                </div>

                <div class="tab-pane justify-content-center" id="tab-amortizacion" role="tabpanel" aria-labelledby="profile-tab-justified">
                  <section class="invoice-print mb-1">
                  </section>
                  <section class="card invoice-page d-flex justify-content-center">
                    <h1>Amortizaciones</h1>
                    <div id="invoice-template" class="card-body">
                      <!-- Invoice Items Details -->
                      <div id="invoice-items-details" class="pt-1 invoice-items-table">
                        <div class="container-fluid">

                        </div>
                        <div class="row">
                          <form>
                            @csrf
                            <input type="hidden" name="id" value="">
                            <div class="form-group row">
                              <div class="col">
                                <label>Gasto de Cobranza: </label>
                                <input type="number" name="GastoCobranza" step="any" min="0" placeholder="$" class="form-control required" required>
                              </div>
                              <div class="col">
                                <label>Iva: </label>
                                <input type="number" name="IVA" step="any" min="0" placeholder="$" class="form-control required" required>
                              </div>

                            </div>
                            <div class="float-right">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-primary" >Aplicar</button>
                            </div>

                          </form>
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

                </div>



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection
@section('vendor-script')
  {{-- vendor js files --}}
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>


  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>

@endsection
@section('page-script')
  {{-- Page js files --}}

@endsection
