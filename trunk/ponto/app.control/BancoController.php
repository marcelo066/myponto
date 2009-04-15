<?php

require_once("./checaLogin.php");

class BancoController {

    private $view;
    private $model;

    public function __construct()
    {
        // instanciamos os objetos
        $this->model = new BancoDao();
        $this->view = new View("banco.html");
    }

    public function exibirBanco()
    {
        $oBancoDao = new BancoDao();
        $oBanco = $oBancoDao->getBancoDeHoras(93);

        // obtém objetos da sessão
        $oPeriodo = Sessao::getObject("oPeriodo");
        $oProf = Sessao::getObject("oProf");

        $vInicio = new DateTime($oPeriodo->getInicio());

        $this->view->addFile("TOPO", "topo.html");

         // cabeçalho
        $vTitulo = ":: BANCO DE HORAS :: ". $vInicio->format("m") . "/" . $vInicio->format("y") . " :: " . $oProf->getMatricula() . " - " . $oProf->getNome() . " :: " . $oProf->getFuncao();
        $this->view->setValue("TITULO", $vTitulo);

        foreach($oBanco as $row)
        {        
            if($vBool){
                $vBool = false;
                $vCor = "#ffffff";
            }else{
                $vBool = true;
                $vCor = "#f4f4f3";
            }          
            $this->view->setValue("COR", $vCor);
            $this->view->setValue("PERIODO", $row->getPeriodo());
            $this->view->setValue("EXTRA50", $row->getHorhex50());
            $this->view->setValue("EXTRA100", $row->getHorhex100());
            $this->view->setValue("HORNORMAIS", $row->getHornormais());
            $this->view->setValue("TOTALTRABALHADO", $row->getHortrabalhadas());
            $this->view->setValue("HORASATRABALHAR", $row->getHoratrabalhar());
            $this->view->setValue("SALDODOPERIODO", $row->getHorsaldo());
            $this->view->setValue("HORASPAGAS", $row->getHorpagas());
            $this->view->setValue("SALDOACUMULADO", $row->getHoracumuladas());
            $this->view->parseBlock("BLOCK_BANCO_HORAS", true);
            $vSaldo = $row->getHoracumuladas();
        }
        $this->view->setValue("SALDO", $vSaldo);
        $this->view->addFile("FOOTER", "rodape.html");
        $this->view->show();
    }
}
?>
