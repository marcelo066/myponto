<?php

/**
 * Description of Periodo
 *
 * @author 80058442553
 */

require_once("PeriodoDao.php");

final class Periodo extends PeriodoDao
{
    private $inicio;
    private $fim;
    private $data;

    function  __construct($pData) {
        $this->data = $pData;
        $this->inicio = parent::getInicio($pData);
        $this->fim = parent::getFinal($pData);
    }

    public function getInicio() {
        return $this->inicio;
    }

    public function getFim() {
        return $this->fim;
    }
    
    public function getData() {
        return $this->data;
    }    
}
?>
