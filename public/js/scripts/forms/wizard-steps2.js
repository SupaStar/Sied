/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with numbers setup
$(".number-tab-steps").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Guardar"
  },
  onFinished: function(event, currentIndex) {
    document.formss.submit();
  }
});

// Wizard tabs with icons setup
$(".icons-tab-steps").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Guardar"
  },
  onFinished: function(event, currentIndex) {
    jsShowWindowLoad();
    document.formss.submit();
  }
});

// Validate steps wizard

// Show form
var form = $(".steps-validation").show();
$.fn.steps.setStep = function(step) {
  var currentIndex = $(this).steps("getCurrentIndex");
  for (var i = 0; i < Math.abs(step - currentIndex); i++) {
    if (step > currentIndex) {
      $(this).steps("next");
    } else {
      $(this).steps("previous");
    }
  }
};
$(".steps-validation").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  enableCancelButton: true,
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Guardar"
  },
  onStepChanging: function(event, currentIndex, newIndex) {
    console.log('change');
    // Allways allow previous action even if the current form is not valid!
    if (currentIndex > newIndex) {
      return true;
    }

    // Needed in some cases if the user went back (clean up)
    if (currentIndex < newIndex) {
      // To remove error styles
      form.find(".body:eq(" + newIndex + ") label.error").remove();
      form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
    }
    form.validate().settings.ignore = ":disabled,:hidden";
    if(form.valid()){
      var formulario=new FormData($("#formss")[0]);
      let ruta=$("#ruta_api").val();
      $.ajax({
        type:"post",
        url:ruta,
        data:formulario,
        datatype:"json",
        contentType: false,
        processData: false,
        success:function (response){
        }
      });
    }
    return form.valid();
  },
  onFinishing: function(event, currentIndex) {
    form.validate().settings.ignore = ":disabled";
    return form.valid();
  },
  onFinished: function(event, currentIndex) {
    jsShowWindowLoad();
    document.formss.submit();
  },
  onCanceled: function(event) {
    $("#formss").steps("setStep", 0);
    document.getElementById("formss").reset();
  }
});

// Initialize validation
$(".steps-validation").validate({
  ignore: "input[type=hidden]", // ignore hidden fields
  errorClass: "danger",
  successClass: "success",
  highlight: function(element, errorClass) {
    $(element).removeClass(errorClass);
  },
  unhighlight: function(element, errorClass) {
    $(element).removeClass(errorClass);
  },
  errorPlacement: function(error, element) {
    error.insertAfter(element);
  },
  rules: {
    email: {
      email: true
    }
  }
});
