<?php

die(PATH_SEPARATOR);

    $h1 = "06:00:00";
    $h2 = "00:10:30";
    
   if(!strtotime($h1)){
       echo "erro";
   }
   else
   {
       $dif = (strtotime($h1)) - (strtotime($h2));
       if ($dif >= 3600)
            $hora = floor(($dif >= 86400) ? ($dif / 86400) * 24 : $dif / 3600);
       if ($calc = ($dif % 3600)) 
       {
           $minuto = floor($calc / 60);
           $segundo = $calc % 60;

       }
       echo "$hora:$minuto:$segundo";
   }



/*
if ($diff >= 3600)
        $time[0] = floor(($diff >= 86400) ? ($diff / 86400) * 24 : $diff / 3600);

    if ($calc = ($diff % 3600)) {
        $time[1] = floor($calc / 60);
        $time[2] = $calc % 60;
    }
-----------------------------------------------

  $diacalc = "4-4-2006";
  $diferenca1 = strtotime("$diacalc 3:00:00") - strtotime("$diacalc 1:00:00");
  $hora = floor($diferenca1 / 3600); // valor de horas inteiras
  $diferenca1 %= 3600; // subtrai as horas inteiras da diferen�a
  $min = $diferenca1 / 60; // minutos que restaram

echo "$hora:$min";

    //echo (strtotime($h1) - strtotime($h2))/60;



/*
    require_once("./app.model/Firewall.php");

    $oFirewall = new Firewall();
    $oData = new DateTime("05-03-2009");
    $oHora = new DateTime("06:00:00");
    
    if($oFirewall->PodeClicar(93, $oData, $oHora, "entrada", false)){
        echo "Pode clicar";
    }
    else
    {
        echo "N�o Pode clicar";
    }
 *
 */
?>
