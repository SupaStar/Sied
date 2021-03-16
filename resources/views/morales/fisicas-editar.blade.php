@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">

  <style>

    #WindowLoad
    {
      position:fixed;
      top:0px;
      left:0px;
      z-index:3200;
      filter:alpha(opacity=85);
      -moz-opacity:85;
      opacity:0.85;
      background:#ededed;
    }

    span.error{
      color:#e74c3c;
      font-size:20px;
      display:flex;
      justify-content:center;
    }
    @media (min-width:720px) {
      #map{
        width: 300%;
      }

    }
  </style>
@endsection
@section('content')
  <div class="row">
    <div class="col-sm-12">
      <div class="card overflow-hidden">
        <div class="card-content">
          <div class="card-body">
            <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab"
                   aria-controls="home-just" aria-selected="true">DATOS PERSONALES</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab"
                   aria-controls="profile-just" aria-selected="true">PERFIL TRANSACIONAL</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="messages-tab-justified" data-toggle="tab" href="#messages-just" role="tab"
                   aria-controls="messages-just" aria-selected="false">CRITERIOS DE RIESGO</a>
              </li>
            </ul>

           <div class="tab-content pt-1">
              <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body">
                         <div>
                           <div id="validation">


                                       <form action="/morales/crear" enctype="multipart/form-data" method="POST"
                                             class="steps-validation wizard-circle" id="formss" name="formss">
                                         <form-wizard color="#7367f0" title="" subtitle="" next-button-text="Siguiente" next-button-text="Siguiente"
                                                      back-button-text="Anterior" finish-button-text="Finalizar" @on-complete="onComplete" @on-error="handleErrorMessage">
                                         @csrf
                                         <!-- Step 1 -->
                                           <tab-content title="Sociedad" icon="step-icon feather icon-user" :before-change="()=>validateAsync('paso0')" >
                                             <div id="paso0">
                                               <div class="row">
                                                 <h3>Empresa</h3>
                                                 <div class="col-md-12">
                                                   <hr>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group" id="div-1">
                                                     <label for="nombre">
                                                       Nombre de la empresa
                                                     </label>
                                                     <input value="{{$datos->nombre}}" type="text" class="form-control required" name="nombre" id="nombreEmpresa" required data-validation-required-message="This First Name field is required">
                                                   </div>
                                                 </div>
                                                 <div class="col-md-6">
                                                   <div class="form-group" id="div-1">
                                                     <label for="nombre_administrador">
                                                       Nombre (s) completo del administrador (es), Director, Gerente General o Apoderado Legal que, con su firma, puedan obligar a la persona moral para efectos de la celebración de un contrato o realización de la Operación que de trate
                                                     </label>
                                                     <input value="{{$datos->nombre_administrador}}" type="text" class="form-control required" name="nombre_administrador" id="nombre_administrador" required data-validation-required-message="This Name field is required">
                                                   </div>
                                                 </div>
                                               </div>
                                               <div class="row">
                                                 <h3>Integrantes</h3>
                                                 <div class="col-md-12">
                                                   <hr>
                                                 </div>
                                               </div>
                                               @foreach($datos->personasmorales as $dato)
                                                 <div>
                                                   <h1> Socio: {{$dato->name}}</h1>
                                                 </div>
                                               <div id="integrantes" v-for="(integrante, index) in integrantes">
                                                 <div>
                                                   <div class="row">
                                                     <div class="col-12 mt-1 mb-1">
                                                       <div class="alert alert-info">
                                                         <p> <i class="feather icon-info mr-1 align-middle"></i> Siguiente Socio.</p>
                                                       </div>
                                                     </div>
                                                   </div>
                                                 </div>
                                                 <div class="row">
                                                   <div class="col-md-6">
                                                     <div class="offset-6 col-3 text-center">
                                                       <a href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.png') }}"
                                                          target="_blank"> <img
                                                           src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.png') }}"
                                                           alt="INE" height="100"></a>

                                                       <a href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.png') }}"
                                                          target="_blank"> <img
                                                           src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.png') }}"
                                                           alt="INE" height="100"></a>
                                                     </div>
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         INE FRONTAL
                                                       </label>
                                                       <input type="file" @change="validateine(index)" data-toggle="tooltip" data-placement="top"
                                                              title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                                                              class="form-control required" :id="'inefront'+index"
                                                              :name="'personasMorales['+index+'][inefront]'" accept=".jpg, .jpeg, .png">
                                                     </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         INE TRASERA
                                                       </label>
                                                       <input type="file" @change="validateine(index)" data-toggle="tooltip" data-placement="top"
                                                              title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                                                              class="form-control required" :id="'ineback'+index"
                                                              :name="'personasMorales['+index+'][ineback]'" accept=".jpg, .jpeg, .png">
                                                     </div>
                                                   </div>
                                                 </div>
                                                 <div class="row">
                                                   <div class="col-md-4 offset-md-4">
                                                     <div class="form-group">
                                                       <label for="curp">
                                                         CURP
                                                       </label>
                                                       <input type="text" class="form-control required" :name="'personasMorales['+index+'][curp]'"
                                                              :id="'curp'+index" @change="checkcurp(index)">
                                                     </div>
                                                   </div>
                                                 </div>
                                                 <div class="row" id="firstRow">
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="firstName3">
                                                         Nombre(s)
                                                       </label>
                                                       <input value="{{$dato->name}}" type="text" class="form-control required" :id="'nombre'+index"
                                                              :name="'personasMorales['+index+'][name]'">
                                                     </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         Apellido Paterno
                                                       </label>
                                                       <input value="{{$dato->lastname}}" type="text" class="form-control required" :id="'apellidop'+index"
                                                              :name="'personasMorales['+index+'][lastname]'">
                                                     </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         Apellido Materno
                                                       </label>
                                                       <input value="{{$dato->o_lastname}}" type="text" class="form-control required" :id="'apellidom'+index"
                                                              :name="'personasMorales['+index+'][o_lastname]'">
                                                     </div>
                                                   </div>
                                                 </div>
                                                 <div class="row">
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="firstName3">
                                                         Genero
                                                       </label>
                                                       <select class="form-control" :name="'personasMorales['+index+'][gender]'"
                                                               :id="'genero'+index">
                                                         <option selected disabled>Seleccionar</option>
                                                         @if($dato->gender=="H")
                                                           <option selected value="H">Masculino</option>
                                                         @else
                                                           <option selected value="M">Femenino</option>
                                                         @endif
                                                         <option value="H">Masculino</option>
                                                         <option value="M">Femenino</option>
                                                       </select>
                                                     </div>
                                                   </div>

                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         Fecha de Nacimiento
                                                       </label>
                                                       <input value="{{$dato->date_birth}}" type='text' class="form-control" data-toggle="datepicker"
                                                              :name="'personasMorales['+index+'][date_birth]'" :id="'nacimiento'+index">

                                                     </div>
                                                   </div>

                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="firstName3">
                                                         País de Nacimiento
                                                       </label>
                                                       <select class="form-control" :id="'pais_nacimiento'+index"
                                                               :name="'personasMorales['+index+'][country_birth]'">
                                                         <option selected disabled>Seleccionar</option>
                                                         @foreach($paises as $dd)
                                                           <option value="{{ $dd->code }}">{{ $dd->pais }}</option>
                                                         @endforeach
                                                         @foreach($paises as $pais)
                                                           @if($pais->code==$dato->country_birth)
                                                             <option selected>{{ $pais->pais }}</option>

                                                           @endif
                                                         @endforeach
                                                       </select>
                                                     </div>
                                                   </div>
                                                 </div>

                                                 <div class="row">
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="firstName3">
                                                         Lugar de Nacimiento
                                                       </label>
                                                       <select class="form-control" :id="'lnacimiento'+index"
                                                               :name="'personasMorales['+index+'][place_birth]'">
                                                         <option selected disabled>Seleccionar</option>
                                                         @foreach($entidad as $dd)
                                                           <option value="{{ $dd->code }}">{{ $dd->entity }}</option>
                                                         @endforeach
                                                         @foreach($paises as $pais)
                                                           @if($pais->code==$dato->country_birth)
                                                             <option selected>{{ $pais->pais }}</option>

                                                           @endif
                                                         @endforeach
                                                       </select>
                                                     </div>
                                                   </div>


                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="firstName3">
                                                         Nacionalidad
                                                       </label>
                                                       <select class="form-control" :id="'nacionalidad'+index"
                                                               :name="'personasMorales['+index+'][nationality]'">
                                                         <option selected disabled>Seleccionar</option>
                                                         @foreach($nacionalidades as $dd)
                                                           <option value="{{ $dd->code }}">{{ $dd->country }}</option>
                                                         @endforeach
                                                         @foreach($entidad as $entidades)
                                                           @if($entidades->code==$dato->place_birth)
                                                             <option selected value="">{{ $entidades->entity }}</option>
                                                           @endif
                                                         @endforeach
                                                       </select>
                                                     </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                     <div class="form-group">
                                                       <label for="lastName3">
                                                         Ocupación
                                                       </label>
                                                       <input value="{{$dato->job}}" type="text" class="form-control required"
                                                              :name="'personasMorales['+index+'][job]'">
                                                     </div>
                                                   </div>
                                                 </div>
                                                 <div class="row">
                                                   <div class="col-md-6">
                                                     <button type="button" @click="agregarIntegrante"
                                                             class="btn btn-icon rounded-circle btn-outline-primary mr-1 mb-1 waves-effect waves-light"><i
                                                         class="feather icon-plus"></i></button>
                                                   </div>
                                                   <div class="col-md-6">
                                                     <button type="button" @click="removerIntegrante(index)"
                                                             class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1 waves-effect waves-light float-right"><i
                                                         class="feather icon-trash"></i></button>
                                                   </div>
                                                 </div>
                                               </div>
                                               @endforeach
                                             </div>
                                           </tab-content>

                                           <!-- Step 2 -->
                                           <tab-content title="Dirección" icon="step-icon feather icon-map-pin" :before-change="()=>validateAsync('paso1')">
                                             <div id="paso1">
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="proposalTitle3">
                                                       Calle
                                                     </label>
                                                     <input value="{{$datos->street}}" type="text" class="form-control required" id="street" name="street">
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="proposalTitle3">
                                                       # Exterior
                                                     </label>
                                                     <input value="{{$datos->exterior}}" type="text" class="form-control required" name="exterior" id="exterior">
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="proposalTitle3">
                                                       # Interior
                                                     </label>
                                                     <input value="{{$datos->intern}}" type="text" class="form-control " name="inside">
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="proposalTitle3">
                                                       Codigo Postal
                                                     </label>
                                                     <input value="{{$datos->pc}}" type="text" class="form-control required" name="pc" id="cp" @change="sepomex">
                                                   </div>
                                                 </div>

                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       Colonia
                                                     </label>
                                                     <select class="form-control" name="colony" id="colonia">
                                                       <option selected disabled>{{$datos->colony}}</option>

                                                     </select>
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       Alcaldia o Municipio
                                                     </label>
                                                     <select class="form-control" name="town" id="municipio">
                                                       <option selected disabled>{{$datos->town}}</option>
                                                     </select>
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       Ciudad o Población
                                                     </label>
                                                     <select class="form-control" name="city" id="ciudad">
                                                       <option selected disabled>{{$datos->city}}</option>
                                                     </select>
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       Entidad Federativa
                                                     </label>
                                                     <select class="form-control" id="entidad" name="ef" @change="initMap">
                                                       <option selected disabled>Seleccionar</option>
                                                       @foreach($entidad as $dd)
                                                         <option value="{{ $dd->code }}">{{ $dd->entity }}</option>
                                                       @endforeach
                                                       @foreach($entidad as $dd)
                                                         @if($dd->code==$datos->ef)
                                                           <option selected value="">{{ $dd->entity }}</option>
                                                         @endif
                                                       @endforeach
                                                     </select>
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       País
                                                     </label>
                                                     <select class="form-control" id="pais" name="country">
                                                       <option selected disabled>Seleccionar</option>
                                                       <option value="México">México</option>
                                                     </select>
                                                   </div>
                                                 </div>
                                               </div>
                                             </div>
                                           </tab-content>
                                           <!-- Step 3 -->
                                           <tab-content title="Datos Adicionales" icon="step-icon feather icon-file-plus" :before-change="()=>validateAsync('paso2')">
                                             <div id="paso2">
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="firstName3">
                                                       Número Teléfonico 1
                                                     </label>
                                                     <input value="{{$datos->phone1}}" type="text" class="form-control" name="phone1">
                                                   </div>
                                                 </div>

                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="lastName3">
                                                       Número Teléfonico 2
                                                     </label>
                                                     <input value="{{$datos->phone2}}" type="text" class="form-control " name="phone2">
                                                   </div>
                                                 </div>
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="lastName3">
                                                       Email
                                                     </label>
                                                     <input value="{{$datos->email}}" type="text" class="form-control required" id="memail" name="email"
                                                            @change="checkemail">
                                                   </div>
                                                 </div>
                                               </div>
                                               <div class="row">


                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="lastName3">
                                                       RFC
                                                     </label>
                                                     <input value="{{$datos->rfc}}" type="text" class="form-control required" name="rfc" id="rfc">
                                                   </div>
                                                 </div>
                                               </div>
                                             </div>
                                           </tab-content>
                                           <!-- Step 4 -->

                                           <tab-content title="Documentos Requeridos" icon="step-icon feather icon-folder-plus" :before-change="()=>validateAsync('paso3')">
                                             <div id="paso3">
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="eventName3">
                                                       Acta Constitutiva
                                                     </label>
                                                     <input type="file" class="form-control required" id="eventName3" name="filecurp">
                                                     <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                        target="_blank"> <img
                                                         src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                         alt="INE" height="100"></a></div>
                                                   </div>
                                                 </div>
                                               </div>
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="eventName3">
                                                       Comprobante de Domicilio
                                                     </label>
                                                     <input type="file" class="form-control required" id="eventName3" name="filedom"
                                                            accept=".jpg, .jpeg, .png">
                                                     <a href="{{ url('/uploads/morales/dom/'.$dato->id.'.jpeg') }}"
                                                        target="_blank"> <img
                                                         src="{{ url('/uploads/morales/dom/'.$dato->id.'.jpeg') }}"
                                                         alt="INE" height="100"></a>

                                                   </div>
                                                 </div>
                                               </div>
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="eventName3">
                                                       RFC
                                                     </label>
                                                     <input type="file" class="form-control " id="eventName3" name="filerfc"
                                                            accept=".jpg, .jpeg, .png">
                                                     <a href="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpeg') }}"
                                                        target="_blank"> <img
                                                         src="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpeg') }}"
                                                         alt="INE" height="100"></a>
                                                   </div>
                                                 </div>
                                               </div>

                                           </tab-content>
                                           <tab-content title="Croquis" icon="step-icon feather icon-folder-plus" :before-change="()=>validateAsync('paso4')">
                                             <div id="paso4">
                                               <div class="row">
                                                 <div class="col-md-4">
                                                   <div class="form-group">
                                                     <label for="eventName3">
                                                       Croquis
                                                     </label>
                                                     <div title="maps" id="map" frameborder="0" allowfullscreen="" style="position: initial !important; height: 400px" aria-hidden="false" tabindex="0"></div>
                                                     <input value="{{$datos->lat}}" aria-label="latitud" id="lat" name="lat" hidden>
                                                     <input value="{{$datos->long}}" aria-label="longitud" id="long" name="long" hidden>
                                                   </div>
                                                 </div>
                                               </div>
                                             </div>
                                           </tab-content>
                                           <tab-content title="Documentos" icon="step-icon feather icon-folder-plus" :before-change="()=>validateAsync('paso5')">
                                             <div id="paso5"></div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="giro">
                                                     Giro
                                                   </label>
                                                   <input value="{{$datos->giro}}" id="giro" type="text" class="form-control required" name="giro">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="fecha_constitucion">
                                                     Fecha de constitucion
                                                   </label>
                                                   <input value="{{$datos->fecha_constitucion}}"  id="fecha_constitucion" type="date" class="form-control required" name="fecha_constitucion">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="garantias">
                                                     Descripcion de las garantias
                                                   </label>
                                                   <textarea id="garantias" type="text" class="form-control required" name="garantias">{{$datos->garantias}}</textarea>
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="fotografia1">
                                                     Fotografia 1
                                                   </label>
                                                   <input id="fotografia1" type="file" accept="image/png, image/jpeg" class="form-control required" name="fotografia1">
                                                   <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                      target="_blank"> <img
                                                       src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                       alt="INE" height="100"></a></div>
                                                 </div>
                                               </div>

                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="fotografia2">
                                                     Fotografia 2
                                                   </label>
                                                   <input id="fotografia2" type="file" accept="image/png, image/jpeg" class="form-control required" name="fotografia2">
                                                   <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                      target="_blank"> <img
                                                       src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                       alt="INE" height="100"></a>
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="numero_empleados">
                                                     Numero de empleados
                                                   </label>
                                                   <input value="{{$datos->numero_empleados}}" id="numero_empleados" type="number" class="form-control required" name="numero_empleados">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="entrevista">
                                                     Entrevista de Identificacion firmada con Declaración de que actua por cuenta propia o de un tercero
                                                   </label>
                                                   <input id="entrevista" type="file" accept="application/pdf" class="form-control required" name="entrevista">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="autorizacion_reporte_circulo_credito">
                                                     Autorización Reporte de Circulo de Crédito
                                                   </label>
                                                   <input id="autorizacion_reporte_circulo_credito" type="file" accept="application/pdf" class="form-control required" name="autorizacion_reporte_circulo_credito">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="reporte">
                                                     Reporte Visita Ocular
                                                   </label>
                                                   <input id="reporte" type="file" accept="application/pdf" class="form-control required" name="reporte">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="ultima_declaracion_anual">
                                                     Última declaración anual de Impuestos
                                                   </label>
                                                   <input id="ultima_declaracion_anual" type="file" accept="application/pdf" class="form-control required" name="ultima_declaracion_anual">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="estados_financieros_anuales">
                                                     Estados Financieros Anuales del periodo anterior
                                                   </label>
                                                   <input id="estados_financieros_anuales" type="file" accept="application/pdf" class="form-control required" name="estados_financieros_anuales">
                                                 </div>
                                               </div>
                                             </div>
                                             <div class="row">
                                               <div class="col-md-4">
                                                 <div class="form-group">
                                                   <label for="estados_financieros_recientes">
                                                     Estados Financieros Recientes (no menos de 3 meses)
                                                   </label>
                                                   <input id="estados_financieros_recientes" type="file" accept="application/pdf" class="form-control required" name="estados_financieros_recientes">
                                                 </div>
                                               </div>
                                             </div>
                                           </tab-content>
                                         </form-wizard>
                                       </form>


                             </div>
                           </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">

                <form action="/morales/eperfil" method="POST" id="formss" name="formss">
                  @csrf
                  <input type="hidden" class="form-control required" id="id" name="id" value="@if(isset($datos->id)){{$datos->id}}@endif" >

                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Frecuencia de los pagos que realizará en el siguiente semestre
                          </label>
                          <select class="form-control" id="frecuencia" name="frecuencia" @if(isset($datos))@endif>
                            @if(isset($datos->perfil->frecuencia))
                              <option value="{{ $datos->perfil->frecuencia }}" selected>{{ $datos->perfil->frecuencia }}</option>
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
                          <input type="number" value="@if(isset($datos->perfil->monto)){{ $datos->perfil->monto }}@endif" @if(isset($datos)) @endif step="any"
                                 class="form-control required" id="monto" name="monto">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Tipo de crédito que pretende utilizar en el siguiente semestre
                          </label>
                          <input type="text" value="@if(isset($datos->perfil->tcredito)){{ $datos->perfil->tcredito }}@endif"
                                 @if(isset($datos)) @endif class="form-control required" id="tcredito" name="tcredito">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Origen de Recursos
                          </label>
                          <select class="form-control required" id="orecursos" name="orecursos" required  @if(isset($datos)) @endif>
                            <option selected disabled>Seleccionar</option>
                            @foreach($origen as $data)
                              @if(isset($datos->perfil->origen_recursos))
                                @if($datos->perfil->origen_recursos == $data->id)
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
                          <select class="form-control required" id="imonetario" name="imonetario" @if(isset($datos)) @endif>

                            <option selected disabled>Seleccionar</option>
                            @foreach($instrumento as $data)
                              @if(isset($datos->perfil->instrumento_monetario))
                                @if($datos->perfil->instrumento_monetario == $data->id)
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
                          <select class="form-control required" id="divisa" name="divisa" @if(isset($datos)) @endif>
                            <option selected disabled>Seleccionar</option>
                            @foreach($divisa as $data)
                              @if(isset($datos->perfil->divisas))
                                @if($datos->perfil->divisas == $data->id)
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
                          <select class="form-control required" id="drecursos" name="drecursos" @if(isset($datos)) @endif>
                            <option selected disabled>Seleccionar</option>
                            @foreach($destino as $data)
                              @if(isset($datos->perfil->destino_recursos))
                                @if($datos->perfil->destino_recursos == $data->id)
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
                            Disponibilidad del cliente para la entrega de documentación
                          </label>
                          <select class="form-control" id="disponibilidad" name="disponibilidad" @if(isset($datos)) @endif>

                            @if(isset($datos->frecuencia))
                              <option value="{{ $datos->frecuencia }}" selected>{{ $datos->frecuencia }}</option>
                            @else
                              <option selected disabled>Seleccionar</option>
                            @endif
                            <option value="total" @if(isset($datos->perfil->total)) @if($datos->perfil->total == 1) selected @endif @endif>Total</option>
                            <option value="aceptable" @if(isset($datos->perfil->aceptable)) @if($datos->perfil->aceptable == 1) selected @endif @endif>Aceptable</option>
                            <option value="dificil" @if(isset($datos->perfil->dificil)) @if($datos->perfil->dificil == 1) selected @endif @endif>Difícil</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Ingreso Mensual Estimado
                          </label>
                          <input type="number" value="@if(isset($datos->perfil->ingreso)){{ $datos->perfil->ingreso }}@endif" @if(isset($datos)) @endif step="any"
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
                          <input type="text" class="form-control required" id="conducta" @if(isset($datos)) @endif name="conducta"
                                 value="@if(isset($datos->perfil->conducta)){{ $datos->perfil->conducta }}@endif">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="lastName3">
                            Señale algún otro comentario sobre el cliente, que considere pueda incidir en la definición de
                            su perfil transaccional, como características específicas de su actividad, antecedentes o
                            proyectos
                          </label>
                          <input type="text" class="form-control required" id="comentario" @if(isset($datos))@endif name="comentario"
                                 value="@if(isset($datos->perfil->comentario)){{ $datos->perfil->comentario }}@endif">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <button type="reset" class="btn btn-secondary mr-1 mb-1" @if(isset($datos))disabled readonly hidden @endif>Limpiar</button>
                      </div>
                      <div class="col-md-6 text-left">
                        <button type="submit" class="btn btn-primary float-right mr-1 mb-1" @if(isset($datos)) @endif>Guardar</button>
                        <a href="/morales/morales"> <button type="button"
                                                            class="btn btn-primary float-right mr-1 mb-1" >Cancelar</button></a>
                      </div>

                    </div>
                  </div>

                </form>


              </div>
              <div class="tab-pane" id="messages-just" role="tabpanel" aria-labelledby="messages-tab-justified">

                <form action="/morales/eebr" method="POST" id="formss"
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
                          <input type="text" class="form-control required" value="@if(isset($profesion)){{ $profesion }}@endif">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Profesión
                          </label>
                          <select class="form-control required" id="profesion" name="profesion" required>

                            <option  selected disabled>Seleccionar</option>
                            @foreach($profesiones as $data)
                              @if(isset($datos))
                                @if($datos->perfil->profesion == $data->id)
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
                            Actividad ó Giro
                          </label>
                          <select  class="form-control required" id="actividad" name="actividad" required>
                            <option selected disabled>Seleccionar</option>
                            @foreach($origen as $data)
                              @if(isset($datos))
                                @if($datos->perfil->actividad_giro == $data->id)
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
                          <input type="text" class="form-control required" value="@if(isset($residencia)){{ $residencia }}@endif" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lastName3">
                            Criterio
                          </label>
                          <select class="form-control required" id="efr" name="efr" required>
                            <option  selected disabled>Seleccionar</option>
                            @foreach($efresidencia as $data)
                              @if(isset($datos))
                                @if($datos->perfil->efr == $data->id)
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
                        <button hidden type="reset" class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                      </div>
                      <div class="col-md-6 text-left">
                        <button type="submit" class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                        <a href="/morales/morales"> <button  type="button"
                                                             class="btn btn-primary float-right mr-1 mb-1">Cancelar</button></a>
                      </div>


                    </div>
                  </div>
                </form>



              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Form wizard with step validation section start -->

  <!-- Form wizard with step validation section end -->
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset('vendors/js/extensions/jquery.steps.min.js') }}"></script>


@endsection
@section('page-script')


  {{-- <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script> --}}
  <script src="/js/scripts/vue.min.js"></script>
  <script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>
  <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
  <script src="{{ asset('datepicker/datepicker.js') }}?{{rand()}}"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC2KCt-r7yAiuktmaXWVtTaVjilIcCPFM&libraries=&v=weekly"
    async></script>
  <script src="{{ asset('js/scripts/mapsedit.js') }}?{{rand()}}"></script>
  <script></script>
  <script>
    Vue.use(VueFormWizard);
    var app = new Vue({
      el: '#validation',
      data: {
        message: 'Hello Vue!',
        integrantes: [1,1],
        tabla: {},
        errorMsg: null,
        token : '{{csrf_token()}}',
        count:0
      },
      mounted() {
        (function (global, factory) {
          typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery')) :
            typeof define === 'function' && define.amd ? define(['jquery'], factory) :
              (factory(global.jQuery));
        }(this, (function ($) {
          'use strict';

          jQuery.fn.datepicker.languages['es-ES'] = {
            format: 'dd/mm/yyyy',
            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            daysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            weekStart: 1,
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
          };
        })));




        @for($i = 0; $i <= 1; $i++)
        $("#nacimiento{{$i}}").datepicker({
          format: 'mm-dd-yyyy',
          language: 'es-ES'
        });

        @endfor

        $('.pickadate-translations').pickadate({
          max: new Date({{date('Y') - 18}}, 1, 1),
          selectYears: true,
          selectMonths: true,
          format: 'mm-dd-yyyy',
          formatSubmit: 'mm-dd-yyyy',
          monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
          today: 'Hoy',
          clear: 'Limpiar',
          close: 'Cerrar'
        });

        //sepomex();
        //curp();
      },
      methods: {
        handleErrorMessage(errorMsg){
          this.errorMsg = errorMsg;
        },
        validateAsync(id){
          return new Promise((resolve,reject)=>{
            var elms = Array.from(document.getElementById(id).getElementsByClassName("required"));
            var label = document.createElement('label');
            label.innerText="Este campo es requerido."
            label.className="input-error danger";
            console.log(elms);
            var errorFlag=false
            elms.forEach((element,index) => {
              if(element.value == ""){
                errorFlag=true;
                element.parentNode.appendChild(label.cloneNode(true));

              }
            });
            if(errorFlag){
              reject('Complete los campos requeridos.')
            }else{
              resolve(true)
            }
          })
        },
        onComplete(){
          $('#formss').submit();
        },
        agregarIntegrante(){
          this.integrantes.push(1);
          var index= this.integrantes.length -1;
          Vue.nextTick()
            .then(function () {
              $("#nacimiento"+index).datepicker({
                format: 'mm-dd-yyyy',
                language: 'es-ES'
              });
            })
        },
        removerIntegrante(index){
          this.integrantes.splice(index,1);

        },
        checkemail() {
          var email = $('#memail').val();
          this.jsShowWindowLoad();
          var form_data = new FormData();
          form_data.append('_token', this.token);

          $.ajax({
            url: '/util/checkemail/' + email,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: (res) => {
              pars = JSON.parse(res);
              if (pars.message == 'exist') {
                $('#memail').val('');
                this.jsRemoveWindowLoad();
                Swal.fire({
                  title: "Error!",
                  text: "Este email ya se encuentra registrado!",
                  type: "error",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                });
              } else {
                this.jsRemoveWindowLoad();
                Swal.fire({
                  title: "Bien!",
                  text: "Este email se puede registrar!",
                  type: "success",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                });
              }
            }
          });

        },
        jsRemoveWindowLoad() {
          // eliminamos el div que bloquea pantalla
          $("#WindowLoad").remove();
        },
        jsShowWindowLoad() {
          //si no enviamos message se pondra este por defecto
          message = '<img src="{{asset('images/loader.gif ')}}" alt="Por Favor Espere...">';

          //centrar imagen gif
          height = 20; //El div del titulo, para que se vea mas arriba (H)
          var ancho = 0;
          var alto = 0;

          //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
          if (window.innerWidth == undefined) ancho = window.screen.width;
          else ancho = window.innerWidth;
          if (window.innerHeight == undefined) alto = window.screen.height;
          else alto = window.innerHeight;

          //operación necesaria para centrar el div que muestra el message
          var heightdivsito = alto / 2 - parseInt(height) / 2; //Se utiliza en el margen superior, para centrar

          //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
          imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + message + "</div><div class='loader-bubble loader-bubble-primary m-5'></div></div>";

          //creamos el div que bloquea grande------------------------------------------
          div = document.createElement("div");
          div.id = "WindowLoad"
          div.style.width = ancho + "px";
          div.style.height = alto + "px";
          $("body").append(div);

          //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
          input = document.createElement("input");
          input.id = "focusInput";
          input.type = "text"

          //asignamos el div que bloquea
          $("#WindowLoad").append(input);

          //asignamos el foco y ocultamos el input text
          $("#focusInput").focus();
          $("#focusInput").hide();

          //centramos el div del texto
          $("#WindowLoad").html(imgCentro);

        },
        checkcurp(id) {

          var curp = $("#curp" + id).val();
          this.jsShowWindowLoad();
          var myheaders = new Headers({
            dataType: 'text'
          });
          myheaders.append("X-CSRF-TOKEN", this.token);
          fetch("/util/checkcurp/" + curp, {
            method: 'POST',
            headers: myheaders
          }).then(async (response) => {
            res = await response.json();
            console.log(res)
            this.jsRemoveWindowLoad();
            if (res.estatus == "OK") {
              var estado = res.curp.substring(11, 13);
              $('#nombre' + id).val(res.nombre);
              $('#apellidop' + id).val(res.apellidoPaterno);
              $('#apellidom' + id).val(res.apellidoMaterno);
              $("#lnacimiento" + id).val(estado);
              Swal.fire({
                title: "¡Bien!",
                text: "CURP validada",
                type: "success",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                animation: false,
                customClass: 'animated tada'
              });

            } else if (res.estatus == "EXISTE"){
              var estado = res.curp.substring(11, 13);
              $('#nombre' + id).val(res.nombre);
              $('#apellidop' + id).val(res.apellidoPaterno);
              $('#apellidom' + id).val(res.apellidoMaterno);
              $("#lnacimiento" + id).val(estado);
              Swal.fire({
                title: "Cliente!",
                text: "Este cliente se encuentra registrado como persona fisica",
                type: "warning",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                animation: false,
                customClass: 'animated tada'
              });

            }
            else {
              Swal.fire({
                title: "¡Error!",
                text: res.mensaje,
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                animation: false,
                customClass: 'animated tada'
              });
            }
          });


        },
        validateine(id) {

          var inefront = $('#inefront' + id).val();
          var ineback = $('#ineback' + id).val();

          if (inefront != '' && ineback != '') {
            this.jsShowWindowLoad();

            var finefront = $('#inefront' + id).prop('files')[0];
            var fineback = $('#ineback' + id).prop('files')[0];

            var form_data = new FormData();
            form_data.append('inefront', finefront);
            form_data.append('ineback', fineback);
            form_data.append('_token', this.token);

            $.ajax({
              url: '/util/imgto64',
              dataType: 'text',
              cache: false,
              contentType: false,
              processData: false,
              data: form_data,
              type: 'post',
              success: (res)=> {
                pars = JSON.parse(res);
                if (pars.message == 'fail') {
                  this.jsRemoveWindowLoad();
                  Swal.fire({
                    title: "Error!",
                    text: "Alguno de los documentos no es formato de imagen valido!",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                    animation: false,
                    customClass: 'animated tada'
                  });
                } else if (pars.message == 'ManualChecking') {
                  this.jsRemoveWindowLoad();
                  Swal.fire({
                    title: "No se pudo verificar!",
                    text: "Se tiene que verificar visualmente!",
                    type: "warning",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                    animation: false,
                    customClass: 'animated tada'
                  });
                } else if (pars.message == 'ControlListManualChecking') {
                  this.jsRemoveWindowLoad();
                  Swal.fire({
                    title: "Advertencia!",
                    text: "Al parecer esta persona se encuentra en listas negras!",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                    animation: false,
                    customClass: 'animated tada'
                  });
                } else {
                  for (i = 0; i < pars.documentData.length; i++) {
                    if (pars.documentData[i].type == 'Name') {
                      $('#nombre' + id).val(pars.documentData[i].value);
                    }
                    if (pars.documentData[i].type == 'FatherSurname') {
                      $('#apellidop' + id).val(pars.documentData[i].value);
                    }
                    if (pars.documentData[i].type == 'MotherSurname') {
                      $('#apellidom' + id).val(pars.documentData[i].value);
                    }

                    if (pars.documentData[i].type == 'PersonalNumber') {
                      var curp = pars.documentData[i].value;
                      $('#curp' + id).val(curp);
                    }

                    if (pars.documentData[i].type == 'Sex') {
                      document.ready = document.getElementById("genero" + id).value = pars.documentData[i].value;
                    }

                    if (pars.documentData[i].type == 'Nationality') {
                      document.ready = document.getElementById("nacionalidad" + id).value = pars.documentData[i].value;
                    }

                    if (pars.documentData[i].type == 'DateOfBirth') {

                      var gdate = pars.documentData[i].value;
                      var gdatearray = gdate.split("/");
                      var gnewdate = gdatearray[1] + '/' + gdatearray[0] + '/' + gdatearray[2];

                      var stringDate = gnewdate;
                      var d = new Date(stringDate);
                      var date = ("0" + d.getDate()).slice(-2);
                      var month = ("0" + (d.getMonth() + 1)).slice(-2);
                      var year = d.getFullYear();
                      $('#nacimiento' + id).val(month + '-' + date + '-' + year);

                      $('[data-toggle="datepicker' + id + '"]').datepicker('setDate', month + '-' + date + '-' + year);

                    }

                  }
                  var listIndex = 0;
                  for (i = 0; i < pars.documentVerifications.length; i++) {
                    if (pars.documentVerifications[i].category == "ControlList" && pars.documentVerifications[i].inputFields != null && pars.documentVerifications[i].name != "Sin coincidencias") {
                      console.log(pars.documentVerifications[i].inputFields[0].value);
                      var html = '<input name="listasNegras[' + listIndex + '][name]" type="hidden" value="' + pars.documentVerifications[i].name + '">';
                      html += '<input name="listasNegras[' + listIndex + '][value]" type="hidden" value="' + pars.documentVerifications[i].inputFields[0].value + '">';
                      $("#firstRow" + id).append(html)
                      listIndex++;
                    }
                  }

                  document.ready = document.getElementById("pais_nacimiento" + id).value = 303;


                  this.jsRemoveWindowLoad();
                  Swal.fire({
                    title: "Existente!",
                    text: "Ine verificada!",
                    type: "success",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                    animation: false,
                    customClass: 'animated tada'
                  }).then((result) => {
                    this.checkcurp(id)
                  });
                  console.log(pars);
                }
              }
            });

          }



        },
        nsocio(id) {
          var nid = id + 1;
          $("#otsocio" + nid).css("display", "block");
          $("#socio" + nid).css("display", "block");
          $("#nsocio" + nid).css("display", "block");
        },
        conyuge() {
          $("#conyuge").css("display", "block");
        },
        sepomex() {
          var cp = $('#cp').val();
          var municipio = [];
          var ciudad = [];

          $.get('https://api-sepomex.hckdrk.mx/query/info_cp/' + cp, {}, function (data) {


            var select = document.getElementById("colonia");
            var length = select.options.length;
            for (i = length - 1; i >= 0; i--) {
              select.options[i] = null;
            }

            var select = document.getElementById("municipio");
            var length = select.options.length;
            for (i = length - 1; i >= 0; i--) {
              select.options[i] = null;
            }

            var select = document.getElementById("ciudad");
            var length = select.options.length;
            for (i = length - 1; i >= 0; i--) {
              select.options[i] = null;
            }

            for (i = 0; i < data.length; i++) {
              console.log(data[i]['response']['asentamiento']);
              $("#colonia").append(new Option(data[i]['response']['asentamiento'], data[i]['response']['asentamiento']));
              if (municipio.includes(data[i]['response']['municipio']) == false) {
                $("#municipio").append(new Option(data[i]['response']['municipio'], data[i]['response']['municipio']));
              }
              if (ciudad.includes(data[i]['response']['ciudad']) == false) {
                $("#ciudad").append(new Option(data[i]['response']['ciudad'], data[i]['response']['ciudad']));
              }
              municipio.push(data[i]['response']['municipio']);
              ciudad.push(data[i]['response']['ciudad']);
            }
          });

        }
      }
    })

  </script>
@endsection
