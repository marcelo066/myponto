<?php

class Firewall extends Dao
{
    // parï¿½metros relativos ao click
    public function podeClicar(Profissional $pProf, Registro $pRegistro)
    {
        $pData = new DateTime($pRegistro->getData())   ;

/**
        return true;

        // checa se o ponto estï¿½ bloqueado
        // caso positivo, nï¿½o pode clicar 
        $sql = "SELECT locked " .
            "FROM hor_frequencia " .
            "WHERE data = '" . $pData->format("Y-m-d") . "' " .
            "AND cod_prof_funcao = " . $pCodProfFuncao . ";";
        $rs = $this->obterRecordSet($sql);
        if($rs[0]["locked"])
        {
            return false;
        }
**/
        // checa se o profissional estï¿½ na lista de programaï¿½ï¿½o
        $sql = "SELECT entrada, saida " .
            "FROM hor_autorizacoes " .
            "WHERE cod_prof_funcao = $pCodProfFuncao " .
            "AND '" . $pData->format("Y-m-d") . "' >= inicio " .
            "AND '" . $pData->format("Y-m-d") . "' <= fim " .
            "ORDER BY data ASC;";
        $rs = $this->obterRecordSet($sql);      
        if($rs)
        {
            if($pTurno == "entrada" || $pTurno == "retorno")
            {
                if($pHora >= $rs[0][$pTurno])
                {
                    return true;
                }
            }
            else if($pTurno == "almoco" || $pTurno == "saida")
            {
                if($pHora <= $rs[0][$pTurno])
                {
                    return true;
                }
            }
        }

        // proibe clicagem final de semana e feriado
        $vArray = array("Sábado", "Domingo", "Feriado");
        if(in_array(Data::getTipoDia($pData), $vArray))
        {
            return false;
        }
         
        // checa se está clicando dentro da faixa permitida
        $oHorarioPadrao = new HorarioPadrao($pProf->getCodProfFuncao(), $pData->format("w"));
        switch($pRegistro->getCampo())
        {
            case "entrada":
                $hora = Data::addHoras($oHorarioPadrao->getToleranciaAntes(),$oHorarioPadrao->getEntrada());
                if($pRegistro->getHora() < $hora)
                {
                    return false;
                }
                break;

            case "almoco":
                die( $oHorarioPadrao->getToleranciaAntes());
                if($pRegistro->getHora() > $oHorarioPadrao->getAlmoco())
                {                    
                    return false;
                }
                break;

            case "retorno":
                if($pRegistro->getHora() < $oHorarioPadrao->getRetorno())
                {
                    return false;
                }
                break;

            case "saida":
                 $hora = Data::addHoras($oHorarioPadrao->getToleranciaDepois(),$oHorarioPadrao->getSaida());
                 echo "pode clicar até: $hora<br>";
                 echo "clicando: {$pRegistro->getHora()}<br>";
                if($pRegistro->getHora() > $hora)
                {
                    return false;
                }
                break;
        }
        return true;
    }

    private function jaClicou($pEditando)
    {
        $sql = "SELECT * " .
            "FROM hor_frequencia " .
            "WHERE data = '" . $pData->format("Y-m-d") . "' " .
            "AND cod_prof_funcao = " . $pCodProfFuncao . ";";
        $rs = $this->obterRecordSet($sql);
        $varRet = false;

        // checa se ja registrou neste turno
        // caso positivo, nï¿½o pode clicar novamente
        // exceto se estiver editando
        if(!$pEditando)
        {
            if($rs){
                switch($pTurno)
                {
                    case "in1":
                        if($rs[0]["entrada"]){
                            return false;
                        }
                        break;
                    case "out1":
                        if($rs[0]["almoco"]){
                            return false;
                        }
                        break;
                    case "in2":
                        if($rs[0]["retorno"]){
                            return false;
                        }
                        break;
                    case "out2":
                        if($rs[0]["saida"]){
                            return false;
                        }
                        break;
                }
            }
            else
            {
                return true;
            }
        }
    }
}

?>