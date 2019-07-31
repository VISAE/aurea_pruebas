window.addEventListener('resize', function(){

  displayFooterControl();

}, true);

window.onload=function(){
    displayFooterControl();
};

function displayFooterControl(){

  bodyHeight=document.body.clientHeight+0;

  windowHeight=window.innerHeight;

  if (bodyHeight<windowHeight) {
    document.getElementById('footer').classList.add("fixed");
  }
  else{
    document.getElementById('footer').classList.remove("fixed");
  }

}

formularioLogin=document.getElementById('formularioLogin');

alerta=document.getElementById('alerta');

usuarioCaja=document.getElementById('usuario');
usuarioLabel=document.getElementById('usuarioLabel');
usuarioNombre=document.getElementById('usuarioNombre');


contrasenaCaja=document.getElementById('contrasena');
contrasenaLabel=document.getElementById('contrasenaLabel');

codigoAutenticacionCaja=document.getElementById('codigoAutenticacion');


if (contrasenaCaja) {

usuarioNombre.innerHTML=getParameterByName('usuario');
usuarioCaja.value=getParameterByName('usuario');

}

if (codigoAutenticacionCaja) {

usuarioNombre.innerHTML=getParameterByName('usuario');

}

/**/

function mostrarOcultarLabel(caja,label,text){
  //console.log(caja.value);
    if(caja.value==""){
      label.innerHTML="&nbsp;";
    }
    else{
      label.innerHTML=text;
    }
}
if (usuarioCaja) {

usuarioCaja.addEventListener('keyup',function(){
  mostrarOcultarLabel(usuarioCaja,usuarioLabel,"Usuario");
  }
);

}

formularioLogin.addEventListener('submit',function(){
  event.preventDefault();
    if((usuarioCaja)&&(!contrasenaCaja)){
      if(usuarioCaja.value=='error'){
        alerta.classList.remove('alert-none');
        usuarioCaja.style.color="#721c24";
        usuarioCaja.style.borderBottomColor="#721c24";
        usuarioCaja.blur();
      }
      else{
        alerta.classList.add('alert-none');
        formularioLogin.submit();
      }
    }

    if(contrasenaCaja){
      if(contrasenaCaja.value=='error'){
        alerta.classList.remove('alert-none');
        contrasenaCaja.style.color="#721c24";
        contrasenaCaja.style.borderBottomColor="#721c24";
        contrasenaCaja.blur();
      }
      else{
        alerta.classList.add('alert-none');
        formularioLogin.submit();
      }
    }

    if(codigoAutenticacionCaja){
      if(codigoAutenticacionCaja.value=='error'){
        alerta.classList.remove('alert-none');
        codigoAutenticacionCaja.style.color="#721c24";
        codigoAutenticacionCaja.style.borderBottomColor="#721c24";
        codigoAutenticacionCaja.blur();
      }
      else{
        alerta.classList.add('alert-none');
      }
    }

});



function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
