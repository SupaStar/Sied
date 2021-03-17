@extends('layouts/contentLayoutMaster')

@section('title', 'Criterio de Riesgo para Entidad')

@section('content')

<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="/morales/eebr" method="POST" class="steps-validation wizard-circle" id="formss"
              name="formss">
              @csrf
              <input type="hidden" class="form-control required" id="id" name="id" value="{{ $id }}">

              <div class="form-body">

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Ocupación
                      </label>
                      <input type="text" class="form-control required" value="@if(isset($profesions)){{ $profesions }}@endif" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Profesión
                      </label>
                      <select readonly disabled class="form-control required" id="profesion" name="profesion" required>

                        <option  selected disabled>Seleccionar</option>
                        @foreach($profesiones as $data)
                          @if(isset($datos))
                            @if($datos->profesion == $data->id)
                              <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                            @else
                              <option value="{{$data->id}}">{{$data->descripcion}}</option>
                            @endif
                          @else
                            <option value="{{$data->id}}">{{$data->descripcion}}</option>
                          @endif
                        @endforeach

                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Actividad Economica/Criterio de riesgo.
                      </label>
                      <select readonly disabled class="form-control required" id="actividad" name="actividad" required>
                        <option selected disabled>Seleccionar</option>
                        @foreach($origen as $data)
                          @if(isset($datos))
                            @if($datos->actividad_giro == $data->id)
                              <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                            @else
                              <option value="{{$data->id}}">{{$data->descripcion}}</option>
                            @endif
                          @else
                            <option value="{{$data->id}}">{{$data->descripcion}}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Entidad Federativa
                      </label>
                      <input type="text" class="form-control required" value="@if(isset($residencia)){{ $residencia }}@endif" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Nivel de Criterio de Riesgo
                      </label>
                      <select disabled readonly class="form-control required" id="efr" name="efr" required>
                        <option  selected disabled>Seleccionar</option>
                        @foreach($efresidencia as $data)
                          @if(isset($datos))
                            @if($datos->efr == $data->id)
                              <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                            @else
                              <option value="{{$data->id}}">{{$data->descripcion}}</option>
                            @endif
                          @else
                            <option value="{{$data->id}}">{{$data->descripcion}}</option>
                          @endif
                        @endforeach

                      </select>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-md-6">
                    <button type="reset" hidden class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                  </div>
                  <div class="col-md-6 text-left">
                    <button type="submit" hidden class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                    <a href="/morales/morales"> <button type="button"
                                                        class="btn btn-primary float-right mr-1 mb-1">Ok</button></a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- // Basic Floating Label Form section end -->
@endsection
