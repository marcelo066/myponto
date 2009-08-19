<?php

class FrequenciaDao 
{
    public function get($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        // instancia objetos que comp�e a frequencia
        $freq = new Frequencia();
        $reg = new RegistroDao();        
        $res = new ResumoDao();        

        // carrega resumo
        $freq->resumo = $res->getResumo($pCodProfFuncao, $pInicio, $pFim);

        // carrega registro
        $freq->registro = $reg->getByRange($pCodProfFuncao, $pInicio, $pFim);
        
        // la la la la la la la
        $freq->ano = $pInicio->format("Y");
        $freq->mes = $pInicio->format("m");

        return $freq;
    }
}
?>