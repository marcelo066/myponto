function gE(tI) { var d=document, r, n, i;
  if (tI instanceof Array) {
    for (n=tI.length, i=0; i<n; i++) r[r.length]=d.getElementById ? d.getElementById(tI) : d.all ? d.all[tI] : false; return r;
  } else { return (d.getElementById) ? d.getElementById(tI) : (d.all) ? d.all[tI] : false; }
}
function adEvento(tO, tE, tF, tPs) { //3.1 - <str/arr tObject(s)>, <str tEvent>, <func tFunction> [, arr tParams]
  if (tO instanceof Array) { for (n=tO.length; n>0; ) { if (!adEvento(tO[--n], tE, tF, tPs)) return false; } return true; }
  var tnF=(tPs) ? function(e) { tF.apply(tO, Array(e).concat(tPs)); } : tF;
  /* Iniciar arrays, caso não existam */ if (!tO.eventos) { tO.eventos=[]; } if (!tO.eventos[tE]) { tO.eventos[tE]=[]; }
  tO.eventos[tE][tO.eventos[tE].length]={tE: tE,   tF: tF,   tPs: tPs };
  if (tO.attachEvent) { tO.attachEvent('on'+tE, tnF); return true; }
  if (tO.addEventListener) { tO.addEventListener(tE, tnF, true); return true; }
  /* alert('Erro!'); */ return false;
}
function poin(e) { try { e.preventDefault(); } catch(e) { event.returnValue=false; } return false; }


var rotateTimer;
function rotate(e, change) { var tB; if (!(tB=gE('banner'))) return false;
  if (change) {
    tB.style.display=(tB.style.display=='') ? 'none' : ''; rotate(); return true;
  }
  rotateTimer=window.setTimeout(function() { rotate('', 1); }, Number(tB.className.replace('tempo', '')) *1000);
  return true;
}

function getSubmit(e) {
  if (abreParceiro(document.getElementsByTagName('select')[0])) return poin(e); return true;
}
function abreParceiro(tE) {
  if (tE.options[tE.selectedIndex].value) { window.open(tE.options[tE.selectedIndex].value); return true; }
  return false;
}

function startBarra() { //rotate();
  adEvento(document.forms[0], 'submit', getSubmit);
}

adEvento(window, 'load', startBarra);
