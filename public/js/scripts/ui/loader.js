function loaderOFF() {
  // eliminamos el div que bloquea pantalla
  $("#WindowLoad").remove();
}

function loaderON() {
  //si no enviamos message se pondra este por defecto
  message = '<img src="/images/loader.gif" alt="Please Wait...">';

  //centrar imagen gif
  height = 500;//El div del titulo, para que se vea mas arriba (H)
  var ancho = 0;
  var alto = 0;

  //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
  if (window.innerWidth == undefined) ancho = window.screen.width;
  else ancho = window.innerWidth;
  if (window.innerHeight == undefined) alto = window.screen.height;
  else alto = window.innerHeight;

  //operación necesaria para centrar el div que muestra el message
  var heightdivsito = alto/2 - parseInt(height)/2;//Se utiliza en el margen superior, para centrar

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

}
