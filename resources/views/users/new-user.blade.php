
@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('content')

<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
  <div class="row match-height">
      <div class="col-12">
          <div class="card">
              <div class="card-content">
                  <div class="card-body">
                      <form class="form" action="/usuarios/crear" method="post">
                        @csrf
                        <div class="form-body">
                              <div class="row">
                                  <div class="col-md-4 col-12">
                                      <div class="form-label-group">
                                          <input type="text" id="first-name-column" class="form-control" placeholder="Nombre(s)" name="name" required>
                                          <label for="first-name-column">Nombre(s)</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4 col-12">
                                      <div class="form-label-group">
                                          <input type="text" id="last-name-column" class="form-control" placeholder="Apellido Paterno" name="lastname" required>
                                          <label for="last-name-column">Apellido Paterno</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4 col-12">
                                      <div class="form-label-group">
                                          <input type="text" id="city-column" class="form-control" placeholder="Apellido Materno" name="olastname" required>
                                          <label for="city-column">Apellido Materno</label>
                                      </div>
                                  </div>
                                  <div class="col-md-8 col-12">
                                    <div class="form-label-group">
                                        <input type="email" id="country-floating" class="form-control" name="email" placeholder="Email" required>
                                        <label for="country-floating">Email</label>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12"></div>
                               <div class="col-md-6 col-12">
                                      <div class="form-label-group">
                                          <select class="form-control" id="basicSelect" name="rol" required>
                                              <option selected disabled>Rol o Perfil</option>
                                              <option value="1">Administrador</option>
                                              <option value="2">Analista</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <select class="form-control" id="basicSelect" name="status" required>
                                            <option selected disabled>Status</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Desactivado">Desactivado</option>
                                        </select>
                                    </div>
                                </div>
                              <div class="col-12">
                                      <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Cancelar</button>
                                      <button type="submit" class="btn btn-primary mr-1 mb-1">Aceptar</button>
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
@section('page-script')
        {{-- Page js files --}}
        <script src="{{ asset('js/scripts/ui/data-users.js') }}?{{rand()}}"></script>
        <script>
          $(document).ready(function () {
                @if ($errors->any())
                var errors = '';
                @foreach ($errors->all() as $error)
                errors = errors + ' ' + '{{ $error }}';
                @endforeach
                    Swal.fire({
                          title: "Error!",
                          text: errors,
                          type: "warning",
                          confirmButtonClass: 'btn btn-primary',
                          buttonsStyling: false,
                          animation: false,
                          customClass: 'animated tada'
                        });
                @endif
            });
        </script>
@endsection

