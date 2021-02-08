
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
                      <form class="form">
                          <div class="form-body">
                            <div class="row">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="firstName3">
                                        Monto estimado de pagos a realizar en los próximos seis meses
                                      </label>
                                      <input type="text" class="form-control required" id="monto" name="nombre" >
                                  </div>
                              </div>

                              <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lastName3">
                                      Tipo de crédito que pretende utilizar en el siguiente semestre
                                    </label>
                                    <input type="text" class="form-control required" id="tcredito" name="apellidop">
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="lastName3">
                                    Frecuencia de los pagos que realizará en el siguiente semestre
                                  </label>
                                  <input type="text" class="form-control required" id="frecuencia" name="apellidom">
                              </div>
                          </div>
                          <div class="col-12">
                            <h3>Origen de Recursos</h3>
                        </div>

                          <div class="col-md-4">
                            <div class="form-group">
                                <div class="vs-radio-con vs-radio-primary">
                                  <input type="radio" name="actividad" value="false">
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
                                <input type="radio" name="propietario" value="false">
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
                              <input type="radio" name="proovedor" value="false">
                              <span class="vs-radio vs-radio-lg">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                              </span>
                              <span class="">Proveedor de Recursos</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                      <h3>Destino de los recursos </h3>
                  </div>

                    <div class="col-md-4">
                      <div class="form-group">
                          <div class="vs-radio-con vs-radio-primary">
                            <input type="radio" name="actividad" value="false">
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
                          <input type="radio" name="propietario" value="false">
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
                        <input type="radio" name="proovedor" value="false">
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Otra</span>
                      </div>
                  </div>
              </div>


              <div class="col-12">
                <h3>Disponibilidad del cliente para la entrega de documentación </h3>
            </div>

              <div class="col-md-4">
                <div class="form-group">
                    <div class="vs-radio-con vs-radio-primary">
                      <input type="radio" name="actividad" value="false">
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
                    <input type="radio" name="propietario" value="false">
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
                  <input type="radio" name="proovedor" value="false">
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
              <input type="text" class="form-control required" id="conducta" name="conducta">
          </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
            <label for="lastName3">
              Señale algún otro comentario sobre el cliente, que considere pueda incidir en la definición de su perfil transaccional, como características específicas de su actividad, antecedentes o proyectos
            </label>
            <input type="text" class="form-control required" id="conducta" name="conducta">
        </div>
    </div>
                <div class="col-12">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Guardar</button>
                            <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Limpiar</button>
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
