@extends('layouts/contentLayoutMaster')

@section('title', 'Perfil Transacional')

@section('content')

<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="/clientes/fisicas/eperfil" method="POST" class="steps-validation wizard-circle" id="formss"
              name="formss">
              @csrf
              <input type="hidden" class="form-control required" id="id" name="id" value="{{ $id }}">

              <div class="form-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Frecuencia de los pagos que realizará en el siguiente semestre
                      </label>
                      <select class="form-control" id="frecuencia" name="frecuencia" @if(isset($datos))disabled @endif>
                        @if(isset($datos->frecuencia))
                          <option value="{{ $datos->frecuencia }}" selected>{{ $datos->frecuencia }}</option>
                        @else
                          <option selected disabled>Seleccionar</option>
                        @endif
                        <option value="Semanal">Semanal</option>
                        <option value="Quincenal">Quincenal</option>
                        <option value="Mensual">Mensual</option>
                        <option value="Bimestral">Bimestral</option>
                        <option value="Trimestral">Trimestral</option>
                        <option value="Semestral">Semestral</option>
                        <option value="A la medida">A la medida</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Monto estimado de pagos a realizar en los próximos seis meses
                      </label>
                      <input type="number" value="@if(isset($datos->monto)){{ $datos->monto }}@endif" @if(isset($datos))disabled @endif step="any"
                        class="form-control required" id="monto" name="monto">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Tipo de crédito que pretende utilizar en el siguiente semestre
                      </label>
                      <input type="text" value="@if(isset($datos->tcredito)){{ $datos->tcredito }}@endif"
                             @if(isset($datos))disabled @endif class="form-control required" id="tcredito" name="tcredito">
                    </div>
                  </div>
                  </div>

                  <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Origen de Recursos
                      </label>
                      <select class="form-control required" id="orecursos" name="orecursos" required  @if(isset($datos))disabled @endif>
                        <option selected disabled>Seleccionar</option>
                            @foreach($origen as $data)
                            @if(isset($datos->origen_recursos))
                            @if($datos->origen_recursos == $data->id)
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
                        Forma de Pago
                      </label>
                      <select class="form-control required" id="imonetario" name="imonetario" @if(isset($datos))disabled @endif>

                        <option selected disabled>Seleccionar</option>
                            @foreach($instrumento as $data)
                            @if(isset($datos->instrumento_monetario))
                            @if($datos->instrumento_monetario == $data->id)
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
                        Divisa
                      </label>
                      <select class="form-control required" id="divisa" name="divisa" @if(isset($datos))disabled @endif>
                        <option selected disabled>Seleccionar</option>
                            @foreach($divisa as $data)
                            @if(isset($datos->divisa))
                            @if($datos->divisa == $data->id)
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
                        Destino De Recursos
                      </label>
                      <select class="form-control required" id="drecursos" name="drecursos" @if(isset($datos))disabled @endif>
                        <option selected disabled>Seleccionar</option>
                            @foreach($destino as $data)
                           <option value="{{$data->id}}">{{$data->descripcion}}</option>
                            @endforeach

                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                      Disponibilidad del cliente para la entrega de documentación
                      </label>
                      <select class="form-control" id="disponibilidad" name="disponibilidad" @if(isset($datos))disabled @endif>

                        @if(isset($datos->frecuencia))
                          <option value="{{ $datos->frecuencia }}" selected>{{ $datos->frecuencia }}</option>
                        @else
                          <option selected disabled>Seleccionar</option>
                        @endif
                        <option value="total" @if(isset($datos->total)) @if($datos->total == 1) selected @endif @endif>Total</option>
                        <option value="aceptable" @if(isset($datos->aceptable)) @if($datos->aceptable == 1) selected @endif @endif>Aceptable</option>
                        <option value="difisil" @if(isset($datos->difisil)) @if($datos->difisil == 1) selected @endif @endif>Difícil</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                      Ingreso Mensual Estimado
                      </label>
                      <input type="number" value="@if(isset($datos->ingreso)){{ $datos->ingreso }}@endif" @if(isset($datos))disabled @endif step="any"
                        class="form-control required" id="ingreso" name="ingreso">
                    </div>
                  </div>

                  </div>
                  <div class="row">


                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="lastName3">
                        Especifique si observo alguna conducta inapropiada del cliente
                      </label>
                      <input type="text" class="form-control required" id="conducta" @if(isset($datos))disabled @endif name="conducta"
                        value="@if(isset($datos->conducta)){{ $datos->conducta }}@endif">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="lastName3">
                        Señale algún otro comentario sobre el cliente, que considere pueda incidir en la definición de
                        su perfil transaccional, como características específicas de su actividad, antecedentes o
                        proyectos
                      </label>
                      <input type="text" class="form-control required" id="comentario" @if(isset($datos))disabled @endif name="comentario"
                        value="@if(isset($datos->comentario)){{ $datos->comentario }}@endif">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <button type="reset" class="btn btn-secondary mr-1 mb-1" @if(isset($datos))disabled readonly hidden @endif>Limpiar</button>
                  </div>
                  <div class="col-md-6 text-left">
                    <button type="submit" class="btn btn-primary float-right mr-1 mb-1" @if(isset($datos))disabled readonly hidden @endif>Guardar</button>
                  <a href="/clientes/fisica"> <button type="button"
                      class="btn btn-danger float-right mr-1 mb-1" @if(isset($datos))disabled readonly hidden @endif>Cancelar</button></a>
                  </div>

                    <input value="{{$redireccion}}" id="redireccion" aria-label="redireccion" hidden>
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
@section('page-script')
  <script>
    $(document).ready(function() {
      let red = $("#redireccion").val();
      if (red == 1){
        Swal.fire({
          title: "Redireccionado",
          text: "Haz sido redireccionado para llenar tu perfil transacional!",
          type: "success",
          confirmButtonClass: 'btn btn-primary',
          buttonsStyling: false,
          animation: false,
          customClass: 'animated tada'
        });
      }
    });
  </script>
@endsection
