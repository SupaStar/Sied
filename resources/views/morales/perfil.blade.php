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
            <form action="/morales/morales/eperfil" method="POST" class="steps-validation wizard-circle" id="formss"
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
                      <select class="form-control" id="frecuencia" name="frecuencia">
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
                      <input type="number" value="@if(isset($datos->monto)){{ $datos->monto }}@endif" step="any"
                        class="form-control required" id="monto" name="monto">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Tipo de crédito que pretende utilizar en el siguiente semestre
                      </label>
                      <input type="text" value="@if(isset($datos->tcredito)){{ $datos->tcredito }}@endif"
                        class="form-control required" id="tcredito" name="tcredito">
                    </div>
                  </div>

                  <div class="col-12">
                    <h4>Origen de Recursos</h4>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="actividad" value="actividad" required
                          @if(isset($datos->actividad)&& $datos->actividad) checked  @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Actividad propia del solicitante</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="actividad" value="propietario"
                          @if(isset($datos->propietario)&& $datos->propietario)  checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Propietario Real</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="actividad" value="proovedor"
                          @if(isset($datos->proovedor)&& $datos->proovedor)  checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Proveedor de Recursos</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <h4>Destino de los recursos </h4>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="dactividad" value="dactividad" required
                          @if(isset($datos->dactividad)&&$datos->dactividad) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Actividad propia del solicitante</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="dactividad" value="dpasivos"
                          @if(isset($datos->dpasivos)&&$datos->dpasivos) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Pago de pasivos</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="dactividad" value="dotro"
                          @if(isset($datos->dotro)&&$datos->dotro) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Otro</span>
                      </div>
                      <input type="text" class="form-control required" id="dotro" name="dotro"
                        value="@if(isset($datos->dotro)&&$datos->dotro){{ $datos->dotro }}@endif">
                    </div>
                  </div>


                  <div class="col-12">
                    <h4>Disponibilidad del cliente para la entrega de documentación </h4>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="disponibilidad" value="total" required
                          @if(isset($datos->total)&&$datos->total) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Total</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="disponibilidad" value="aceptable"
                          @if(isset($datos->aceptable)&&$datos->aceptable) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Aceptable</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="disponibilidad" value="difisil"
                          @if(isset($datos->difisil)&&$datos->difisil) checked @endif>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Difícil</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="lastName3">
                        Especifique si observo alguna conducta inapropiada del cliente
                      </label>
                      <input type="text" class="form-control required" id="conducta" name="conducta"
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
                      <input type="text" class="form-control required" id="comentario" name="comentario"
                        value="@if(isset($datos->comentario)){{ $datos->comentario }}@endif">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <button type="reset" class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                  </div>
                  <div class="col-md-6 text-left">
                    <button type="submit" class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                  <a href="/clientes/fisica"> <button type="button"
                      class="btn btn-danger float-right mr-1 mb-1">Cancelar</button></a>
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
