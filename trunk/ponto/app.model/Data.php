<?php

include_once ("FeriadoDao.php");

abstract class Data {
    /**
     * M�todo getDiaSemana
     * retorna o dia da semana de uma data
     * @param DateTime $data
     * @return String
     */
    public static function getDiaSemana(DateTime $data) {
        $ds = $data->format("w");
        $dias_semana = array('Domingo', 'Segunda-Feira', 'Terça-Feira',
                            'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira',
                            'Sábado');
        return $dias_semana[$ds];
    }

    /**
     * M�todo getTipoData()
     * checa se a data informada � feriado, s�bado ou domingo
     * @param DateTime $pData um objeto com a data
     * @return String "Feriado" ou o dia da semana
     */
    public static function getTipoDia(DateTime $pData)
    {
        $oFeriado = new FeriadoDao();
        $vFeriado = $oFeriado->getFeriado($pData);
        if(!$vFeriado)
        {
            return self::getDiaSemana($pData);
        }
        else
        {
            return 'Feriado';
        }
    }

}
?>
