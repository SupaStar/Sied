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
      <!-- <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button> -->
    </div>
  </div>
</section>
<!-- invoice functionality end -->
<section class="card invoice-page">
  <div id="invoice-template" class="card-body">

    <!-- Invoice Items Details -->
    <div id="invoice-items-details" class="pt-1 invoice-items-table">


      <div class="container-fluid">
        <div class="row justify-content-center mb-4">
          <div class="offset-6 col-3 text-center">
            <a href="{{ url('/uploads/personas-morales/ine/'.$datos->personasMorales[0]->id.'-frontal.png') }}"
              target="_blank"> <img
                src="{{ url('/uploads/personas-morales/ine/'.$datos->personasMorales[0]->id.'-frontal.png') }}"
                alt="INE" height="100"></a>
          </div>
          <div class="col-3 text-center">
            <a href="{{ url('/uploads/fisicas/ine/'.$miid.'-trasera.jpg') }}"
              target="_blank"> <img
                src="{{ url('/uploads/fisicas/ine/'.$miid.'-trasera.jpg') }}"
                alt="INE" height="100"></a>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="table-responsive col-sm-12">

          <p></p>

          <br>
          <h3>Cliente Persona Moral</h3>

          <!-- Step 1 -->
          <h6><i class="step-icon feather icon-user"></i> Datos Personales</h6>
          <fieldset>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="firstName3">
                    Nombre(s)
                  </label>
                  <input type="text" readonly class="form-control required" id="nombre" name="nombre"
                    value="@if(isset($datos->nombre)) {{ $datos->nombre }} @endif">
                </div>
              </div>



          </fieldset>

          <!-- Step 2 -->
          <h6><i class="step-icon feather icon-map-pin"></i> Dirección</h6>
          <fieldset>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    Calle
                  </label>
                  <input type="text" readonly value="@if(isset($datos->street)) {{ $datos->street }} @endif"
                    class="form-control required" name="calle">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    # Exterior
                  </label>
                  <input type="text" readonly value="@if(isset($datos->exterior)) {{ $datos->exterior }} @endif"
                    class="form-control required" name="exterior">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    # Interior
                  </label>
                  <input type="text" readonly value="@if(isset($datos->inside)) {{ $datos->inside }} @endif"
                    class="form-control required" name="interior">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    Codigo Postal
                  </label>
                  <input type="text" readonly value="@if(isset($datos->pc)) {{ $datos->pc }} @endif"
                    class="form-control required" name="cp" id="cp" onchange="sepomex();">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    Colonia
                  </label>
                  <input type="text" readonly value="@if(isset($datos->colony)) {{ $datos->colony }} @endif"
                    class="form-control required">
                </div>
              </div>


              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    Alcaldia o Municipio
                  </label>
                  <input type="text" readonly value="@if(isset($datos->town)) {{ $datos->town }} @endif"
                    class="form-control required">
                </div>
              </div>


              <div class="col-md-4">
                <div class="form-group">
                  <label for="proposalTitle3">
                    Ciudad o Población
                  </label>
                  <input type="text" readonly value="@if(isset($datos->city)) {{ $datos->city }} @endif"
                    class="form-control required">
                </div>
              </div>



              <div class="col-md-4">
                <div class="form-group">
                  <label for="firstName3">
                    Entidad Federativa
                  </label>
                  <select class="form-control" id="entidad" name="entidad" disabled>
                    <option selected disabled>Seleccionar</option>
                    @foreach($entidad as $dd)
                      <option @if(isset($datos->ef) && $datos->ef == $dd->code) selected @endif
                        value="{{ $dd->code }}">{{ $dd->entity }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="firstName3">
                    País
                  </label>
                  <select class="form-control" id="pais" name="pais" disabled>
                    @if(isset($datos->country))
                      <option disabled>Seleccionar</option>
                      <option selected value="México">México</option>
                    @else
                      <option selected disabled>Seleccionar</option>
                      <option value="México">México</option>
                    @endif

                  </select>
                </div>
              </div>
            </div>


          <!-- Step 3 -->
          <h6><i class="step-icon feather icon-file-plus"></i> Datos Adicionales</h6>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="firstName3">
                    Número Teléfonico 1
                  </label>
                  <input type="text" readonly value="@if(isset($datos->phone1)) {{ $datos->phone1 }} @endif"
                    class="form-control" name="telefono1">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="lastName3">
                    Número Teléfonico 2
                  </label>
                  <input type="text" readonly value="@if(isset($datos->phone2)) {{ $datos->phone2 }} @endif"
                    class="form-control " name="telefono2">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="lastName3">
                    Email
                  </label>
                  <input type="text" readonly value="@if(isset($datos->email)) {{ $datos->email }} @endif"
                    class="form-control " id="memail" name="memail">
                </div>
              </div>
            </div>
            <div class="row">


              <div class="col-md-4">
                <div class="form-group">
                  <label for="lastName3">
                    RFC
                  </label>
                  <input type="text" readonly value="@if(isset($datos->rfc)) {{ $datos->rfc }} @endif"
                    class="form-control required" name="rfc" id="rfc">
                </div>
              </div>
            </div>

          </fieldset>


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
