<?php

class Ponto {
    // atributos
    private $Data;
    private $Hora;
    private $Turno;
    private $Apropriar;

    public function getData() {
        return $this->Data;
    }

    public function setData($Data) {
        $this->Data = $Data;
    }

    public function getHora() {
        return $this->Hora;
    }

    public function setHora($Hora) {
        $this->Hora = $Hora;
    }

    public function getTurno() {
        return $this->Turno;
    }

    public function setTurno($Turno) {
        $this->Turno = $Turno;
    }

    public function getApropriar() {
        return $this->Apropriar;
    }

    public function setApropriar($Apropriar) {
        $this->Apropriar = $Apropriar;
    }

}
?>
