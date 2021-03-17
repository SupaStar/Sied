@extends('layouts/contentLayoutMaster')

@section('title', 'Matriz de Riesgo')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/pages/invoice.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

@endsection
@section('content')

  <!-- // Basic multiple Column Form section start -->
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">
              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab"
                     aria-controls="home-just" aria-selected="true">MATRIZ DE RIESGO</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-justified1" data-toggle="tab" href="#profile-just1" role="tab"
                     aria-controls="profile-just1" aria-selected="true">ANTECEDENTES</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-justified2" data-toggle="tab" href="#profile-just2" role="tab"
                     aria-controls="profile-just2" aria-selected="true">ACTIVIDAD-PROFESIÓN</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-justified3" data-toggle="tab" href="#profile-just3" role="tab"
                     aria-controls="profile-just3" aria-selected="true">ORIGEN DE RECURSOS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-justified4" data-toggle="tab" href="#profile-just4" role="tab"
                     aria-controls="profile-just4" aria-selected="true">DESTINO DE RECURSOS</a>
                </li>
              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">


                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>CATEGORIAS</th>
                              <th>PUNTAJE RESULTANTE</th>
                              <th>PONDERACIÓN</th>
                              <th>RESULTADO</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="table-default">
                              <td>
                                <span class="font-weight-bold">ANTECEDENTES</span>
                              </td>
                              <td>{{$valorAntecedentes}}</td>
                              <td>
                                50%
                              </td>
                              <td>{{$valorAntecedentes*.5}}</td>
                            </tr>

                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">ACTIVIDAD / PROFESIÓN</span>
                              </td>
                              <td>{{$valorActEconomica}}</td>
                              <td>
                                17%
                              </td>
                              <td>{{$valorAntecedentes*.17}}</td>
                            </tr>
                            <tr class="table-default">
                              <td>
                                <span class="font-weight-bold">ORIGEN DE RECURSOS</span>
                              </td>
                              <td>{{$valorOrigenRecursos}}</td>
                              <td>
                                25%
                              </td>
                              <td>{{$valorOrigenRecursos*.25}}</td>
                            </tr>
                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">DESTINO DE RECURSOS</span>
                              </td>
                              <td>{{$valorDestino}}</td>
                              <td>
                                8%
                              </td>
                              <td>{{$valorDestino*.08}}</td>
                            </tr>
                            <tr @if($criesgo == 'BAJO') class="table-success"
                                @elseif($criesgo == 'MEDIO') class="table-warning" @else class="table-danger" @endif>
                              <td><span class="font-weight-bold">GRADO DE RIESGO</span></td>
                              <td><span class="font-weight-bold">{{$sumatoria}}</span></td>
                              <td><span class="font-weight-bold">100%</span></td>
                              <td><span class="font-weight-bold">{{$valorRes}}</span></td>
                            </tr>


                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>RIESGO</th>
                              <th>MINIMO</th>
                              <th>MAXIMO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($riesgo as $rg)
                              <tr @if($rg->riesgo == 'BAJO') class="table-success"
                                  @elseif($rg->riesgo == 'MEDIO') class="table-warning"
                                  @else class="table-danger" @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->riesgo}}</span>
                                </td>
                                <td>{{$rg->minimo}}</td>
                                <td>{{$rg->maximo}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>
                <div class="tab-pane" id="profile-just1" role="tabpanel" aria-labelledby="profile-tab-justified1">


                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>FACTOR</th>
                              <th>CRITERIO</th>
                              <th>PONDERACIÓN</th>
                              <th>PUNTAJE</th>
                              <th>RESULTADO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($antecedentes as $ante)
                              <tr @if($loop->index%2==0) class="table-default" @else class="table-secondary" @endif>
                                <td>
                                  <span class="font-weight-bold">{{strtoupper($ante['factor'])}}</span>
                                </td>
                                <td>{{strtoupper($ante['criterio'])}}</td>
                                <td>{{strtoupper($ante['ponderacion'])}}</td>
                                <td>{{strtoupper($ante['puntaje'])}}</td>
                                <td>{{$ante['puntaje']*($ante['ponderacion']/100)}}</td>
                              </tr>
                            @endforeach

                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">TOTAL</span>
                              </td>
                              <td></td>
                              <td>{{number_format(100,2)}}</td>
                              <td>{{number_format($sumaAntecedentes,2)}}</td>
                              <td>{{number_format($valorAntecedentes,2)}}</td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Alertas PLD/FT</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($pld as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Confirmado en Listas PEP Mexicanas</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($pepmx as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>


                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Confirmado en Listas PEP Extranjero</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($pepex as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>


                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Nacionalidad</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($anacionalidad as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Antigüedad en Domicilio</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($antiguedad as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg['descripcion']}}</span>
                                </td>
                                <td>{{$rg['puntaje']}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>


                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Personalidad Juridica</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($personalidad as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>


                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">Entidad Federativa Residencia</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($entidad as $rg)
                              <tr @if($i==0) class="table-default"
                                  @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                  </div>


                </div>
                <div class="tab-pane" id="profile-just2" role="tabpanel" aria-labelledby="profile-tab-justified2">


                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>FACTOR</th>
                              <th>CRITERIO</th>
                              <th>PONDERACIÓN</th>
                              <th>PUNTAJE</th>
                              <th>RESULTADO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($actEconomica as $ante)
                              <tr @if($loop->index%2==0) class="table-default" @else class="table-secondary" @endif>
                                <td>
                                  <span class="font-weight-bold">{{strtoupper($ante['factor'])}}</span>
                                </td>
                                <td>{{strtoupper($ante['criterio'])}}</td>
                                <td>{{strtoupper($ante['ponderacion'])}}</td>
                                <td>{{strtoupper($ante['puntaje'])}}</td>
                                <td>{{$ante['puntaje']*($ante['ponderacion']/100)}}</td>
                              </tr>
                            @endforeach

                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">TOTAL</span>
                              </td>
                              <td></td>
                              <td>{{number_format(100,2)}}</td>
                              <td>{{number_format($sumaActEconomica,2)}}</td>
                              <td>{{number_format($valorActEconomica,2)}}</td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">ACTIVIDAD / GIRO</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($actividad as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">PROFESION</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($profesion as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                  </div>


                </div>
                <div class="tab-pane" id="profile-just3" role="tabpanel" aria-labelledby="profile-tab-justified3">


                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>FACTOR</th>
                              <th>CRITERIO</th>
                              <th>PONDERACIÓN</th>
                              <th>PUNTAJE</th>
                              <th>RESULTADO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($origenR as $ante)
                              <tr @if($loop->index%2==0) class="table-default" @else class="table-secondary" @endif>
                                <td>
                                  <span class="font-weight-bold">{{strtoupper($ante['factor'])}}</span>
                                </td>
                                <td>{{strtoupper($ante['criterio'])}}</td>
                                <td>{{strtoupper($ante['ponderacion'])}}</td>
                                <td>{{strtoupper($ante['puntaje'])}}</td>
                                <td>{{$ante['puntaje']*($ante['ponderacion']/100)}}</td>
                              </tr>
                            @endforeach

                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">TOTAL</span>
                              </td>
                              <td></td>
                              <td>{{number_format(100,2)}}</td>
                              <td>{{number_format($sumaOrigenRecursos,2)}}</td>
                              <td>{{number_format($valorOrigenRecursos,2)}}</td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">ORIGEN RECURSOS</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($origen as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">INSTRUMENTO MONETARIO</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($imonetario as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">DIVISA</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($divisa as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>



                </div>
                <div class="tab-pane" id="profile-just4" role="tabpanel" aria-labelledby="profile-tab-justified4">


                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th>FACTOR</th>
                              <th>CRITERIO</th>
                              <th>PONDERACIÓN</th>
                              <th>PUNTAJE</th>
                              <th>RESULTADO</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($destinoR as $ante)
                              <tr @if($loop->index%2==0) class="table-default" @else class="table-secondary" @endif>
                                <td>
                                  <span class="font-weight-bold">{{strtoupper($ante['factor'])}}</span>
                                </td>
                                <td>{{strtoupper($ante['criterio'])}}</td>
                                <td>{{strtoupper($ante['ponderacion'])}}</td>
                                <td>{{strtoupper($ante['puntaje'])}}</td>
                                <td>{{$ante['puntaje']*($ante['ponderacion']/100)}}</td>
                              </tr>
                            @endforeach

                            <tr class="table-secondary">
                              <td>
                                <span class="font-weight-bold">TOTAL</span>
                              </td>
                              <td></td>
                              <td>{{number_format(100,2)}}</td>
                              <td>{{number_format($valorDestino,2)}}</td>
                              <td>{{$ante['puntaje']*($ante['ponderacion']/100)}}</td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="card">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="thead-dark">
                            <tr>
                              <th COLSPAN=2 class="text-center">DESTINO RECURSOS</th>
                            </tr>
                            <tr>
                              <th>DESCRIPCIÓN</th>
                              <th>PUNTAJE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=0)
                            @foreach($destino as $rg)
                              <tr @if($i==0) class="table-default" @php($i++) @else class="table-info" @php($i=0)  @endif>
                                <td>
                                  <span class="font-weight-bold">{{$rg->descripcion}}</span>
                                </td>
                                <td>{{$rg->puntaje}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- // Basic Floating Label Form section end -->
@endsection
@section('vendor-script')
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
