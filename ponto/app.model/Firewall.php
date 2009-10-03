<?php

class Firewall extends Dao
{
    // par�metros relativos ao click
    public function podeClicar(Profissional $pProf, Registro $pRegistro)
    {

        return true;

        // checa se o ponto est� bloqueado
        // caso positivo, n�o pode clicar 
        $sql = "SELECT locked " .
            "FROM hor_frequencia " .
            "WHERE data = '" . $pData->format("Y-m-d") . "' " .
            "AND cod_prof_funcao = " . $pCodProfFuncao . ";";
        $rs = $this->obterRecordSet($sql);
        if($rs[0]["locked"])
        {
            return false;
        }

        // checa se o profissional est� na lista de programa��o
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
        $vArray = array("S�bado", "Domingo", "Feriado");               
        if(in_array(Data::getTipoDia($pData), $vArray))
        {
            return false;
        }
         
        // checa se est� clicando dentro da faixa permitida
        $oHorarioPadraoDao = new HorarioPadraoDao();
        $oHorarioPadrao = $oHorarioPadraoDao->getHorario($pCodProfFuncao);

        // Testa permiss�es de hor�rios
        switch($pTurno)
        {
            case "entrada": 
                if($pHora->format("h:i:s") < $oHorarioPadrao->getEntrada())
                {
                    return false;
                }
                break;

            case "almoco":
                if($pHora > $oHorarioPadrao->getAlmoco())
                {
                    return false;
                }
                break;

            case "retorno":
                if($pHora < $oHorarioPadrao->getRetorno())
                {
                    return false;
                }
                break;

            case "saida":
                if($pHora > $oHorarioPadrao->getRetorno())
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
        // caso positivo, n�o pode clicar novamente
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
    
    private function subtraiHorario($pHora1, $pHora2)
    {
        $dif = (strtotime($pHora1)) - (strtotime($pHora2));
        if ($dif >= 3600)
            $hora = floor(($dif >= 86400) ? ($dif / 86400) * 24 : $dif / 3600);
        if ($calc = ($dif % 3600))
        {
            $minuto = floor($calc / 60);
            $segundo = $calc % 60;
        }
        return "$hora:$minuto:$segundo";
    }
}

?>