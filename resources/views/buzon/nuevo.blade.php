
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
              <form class="form" action="/buzon/nuevo" method="post">
                @csrf
                <div class="form-body">
                  <div class="row justify-content-md-center ">
                    <div class="col col-6">
                      <div class="form-label-group">
                        <input type="text" id="first-name-column" class="form-control" placeholder="Titulo:" name="titulo" required>
                        <label for="first-name-column">Titulo:</label>
                      </div>
                    </div>
                  </div>
                    <div class="row justify-content-md-center ">
                    <div class="col col-6">
                      <div class="form-label-group">
                        <textarea type="text" id="last-name-column" class="form-control" placeholder="Descripcion:" name="descripcion" required></textarea>
                        <label for="last-name-column">Descripcion:</label>
                      </div>
                    </div>
                    </div>
                      <div class="row justify-content-md-center ">
                    <div class="col col-6">
                      <div class="form-label-group">
                        <select class="form-control" id="prioridad" name="prioridad" readonly>

                            <option selected disabled>Seleccione la prioridad</option>
                            <option value="Alta">Alta</option>
                            <option value="Baja">Baja</option>


                        </select>
                        <label for="city-column">prioridad</label>
                      </div>
                    </div>
                      </div>
                        <div class="row justify-content-md-center ">
                    <div class="col col-3">
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
      @if (session('message'))
      Swal.fire({
        title: "Bien!",
        text: "Su buzón fue enviado correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif


    });
  </script>
@endsection


