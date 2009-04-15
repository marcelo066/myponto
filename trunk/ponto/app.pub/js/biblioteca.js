//*******************************************
//Contagem Regressiva de tempo da sess�o (timeout)
//*******************************************

var tempo="120:01"
var parselimit=tempo.split(":")
parselimit=parselimit[0]*60+parselimit[1]*1

function contar()
{
	if (parselimit <= 0)
	{
		document.getElementById('tempo').innerHTML = "Tempo Esgotado!";
		window.alert('Tempo Esgotado!');
		//document.all('tempo').innerHTML = "";
		document.getElementById('tempo').innerHTML = "";
		//window.location.href = 'tempo_esgotado.asp';
	}
	else
	{
		parselimit = parselimit - 1;
		curmin=Math.floor(parselimit/60)
		cursec=parselimit%60
		//if (curmin!=0)
		//	curtime=curmin + ":" + cursec
		// else
		//	curtime=cursec + " segundos restantes"
		
		if (curmin > 0)
			curtime = curmin + "min " + cursec + "seg";	
		else
			curtime = cursec + "seg";		
		document.getElementById('tempo').innerHTML = curtime;
		setTimeout('javascript:contar();', 1000);
	}

	
}


//*******************************************
//Texto que acompanha o scroll da tela
//*******************************************
/*
//var Hoffset=50 // largura
var Voffset=70 //altura

var ieHoffset_extra=15
var cross_obj= document.all.staticbuttons

function positionit(){
    var dsocleft=document.body.scrollLeft
    var dsoctop=document.body.scrollTop
    var window_width=document.body.clientWidth+ieHoffset_extra
    var window_height=document.body.clientHeight
    //cross_obj.style.left=parseInt(dsocleft)+parseInt(window_width)-Hoffset - 5
    cross_obj.style.top=dsoctop //+parseInt(window_height)-Voffset
}

function initializeIT(){positionit()}
setInterval("initializeIT()",10)
*/

function ismaxlength(obj){

	var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
	
	if (obj.getAttribute && obj.value.length>mlength)
		obj.value=obj.value.substring(0,mlength)

}

function Mascara_Hora(Hora, campo, teclapress){
   var hora01 = '';

   if(teclapress.keyCode == 8) return null;

    somente_numero(campo);

   hora01 = hora01 + Hora;
   if (hora01.length == 2){
      hora01 = hora01 + ':';
      campo.value = hora01;
   }
   if (hora01.length == 5){
      hora01 = hora01 + ':';
      campo.value = hora01;
   }
   if (hora01.length == 8){
      Verifica_Hora(campo);
   }
}

function Verifica_Hora(campo){
   hrs = (campo.value.substring(0,2));
   min = (campo.value.substring(3,5));
   seg = (campo.value.substring(6,8));
   estado = "";
   if ((hrs < 00 ) || (hrs > 23) || ( min < 00) ||( min > 59) || ( seg < 00) ||( seg > 59)){
      estado = "errada";
   }
   if (campo.value == "") {
      estado = "errada";
   }
   if (estado == "errada") {
      document.getElementById("mensagem").innerHTML = "Hora inválida";
      campo.focus();
   }else{
       document.getElementById("mensagem").innerHTML = "";
   }
}

function somente_numero(campo){
    var digits="0123456789:"
    var campo_temp
    for (var i=0;i<campo.value.length;i++){
      campo_temp=campo.value.substring(i,i+1)
      if (digits.indexOf(campo_temp)==-1){
            campo.value = campo.value.substring(0,i);
            break;
       }
    }
}