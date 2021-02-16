@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="userID" content="{{ auth()->user()->id }}">
    @endauth

    <title>SIED</title>

    <title>@yield('title') SIED </title>
    <link rel="shortcut icon" type="image/x-icon" href="images/logo/favicon.ico">

    {{-- Include core + vendor Styles --}}
    @include('panels/styles')


    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{asset('vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" />



  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

{{-- {!! Helper::applClasses() !!} --}}
@php
$configData = Helper::applClasses();
@endphp
@isset($configData["mainLayoutType"])
@extends((( $configData["mainLayoutType"] === 'horizontal') ? 'layouts.horizontalLayoutMaster' :
'layouts.verticalLayoutMaster' ))
@endisset
<script>
    window.onload = function(){
        var timeout;
        //funcion para reiniciar el contador de tiempo
        function resetTimer() {
        console.log('timer started');
        clearTimeout(timeout);
        //contador de tiempo para mostrar la alerta de inactividad
        timeout = setTimeout(function(){
            Swal.fire({
                         title: "Se cerrará tu sesión por inactividad",
                         type: "warning",
                         timer: 300000,
                         confirmButtonText: "Continuar trabajando",
                       }).then((result)=>{
                        if (result.isConfirmed) {
                                resetTimer();
                            }else if(result.dismiss=="timer"){
                                window.location.href = '/logout';
                            }
                       });
        }, 1800*1000);
        }
        resetTimer();
        //reinicar el contador cpn cada click
        document.onclick = resetTimer;

        function actual() {
             fecha=new Date(); //Actualizar fecha.
             hora=fecha.getHours(); //hora actual
             minuto=fecha.getMinutes(); //minuto actual
             segundo=fecha.getSeconds(); //segundo actual
             if (hora<10) { //dos cifras para la hora
                 hora="0"+hora;
                 }
             if (minuto<10) { //dos cifras para el minuto
                 minuto="0"+minuto;
                 }
             if (segundo<10) { //dos cifras para el segundo
                 segundo="0"+segundo;
                 }
             //devolver los datos:
             mireloj = hora+" : "+minuto+" : "+segundo;
             return mireloj;
         }


         function actualizar() { //función del temporizador
            mihora=actual(); //recoger hora actual
            mireloj=document.getElementById("reloj"); //buscar elemento reloj
            mireloj.innerHTML=mihora; //incluir hora en elemento
         	 }
         setInterval(actualizar,1000);
    }
</script>

