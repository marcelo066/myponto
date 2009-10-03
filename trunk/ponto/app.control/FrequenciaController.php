<?php

class FrequenciaController extends Controller
{
    private $view;
    private $model;

    public function __construct()
    {
        new Sessao();
        $oPeriodo = Sessao::getObject("oPeriodo");
        $oProf = Sessao::getObject("oProf");
        $this->model = new Frequencia($oProf, $oPeriodo);
        $this->view = new View("frequencia.html");
    }

    public function show()
    {
        try{
            // topo
            $this->view->addFile("TOPO","topo.html");
            // cabeçalho
            $vTitulo = ":: FOLHA DE FREQUENCIA :: ". $this->model->getMes() . "/" . $this->model->getAno() . " :: " . $this->model->getMatricula() . " - " . $this->model->getNome() . " :: " . $this->model->getFuncao();
            $this->view->setValue("TITULO", $vTitulo);
            // resumo
            $this->view->setValue("RES_NORMAIS", $this->model->getTotHorNormais());
            $this->view->setValue("RES_EXTRA50", $this->model->getTotHorExtras50());
            $this->view->setValue("RES_EXTRA100", $this->model->getTotHorExtras100());
            $this->view->setValue("RES_TOTAL", $this->model->getTotHorTrabalhadas());
            $this->view->setValue("RES_ATRAB", $this->model->getTotHorATrabalhar());
            $this->view->setValue("RES_SALDO", $this->model->getSaldoHoras());

            // registro
            foreach($this->model->getRegistro() as $oFreq)
            {
                if($bol){
                    $bol = false;
                    $cor = "#ffffff";
                } else {
                    $bol = true;
                    $cor = "#f4f4f3";
                }
                $this->view->setValue("COR", $cor);
                //$this->view->setValue("DATA", $vFreq->getData()->format("d/m/Y"));
                $this->view->setValue("DATA", $oFreq->getData());
                $this->view->setValue("DIA", $oFreq->getDiaDaSemana());
                $this->view->setValue("ENTRADA", $oFreq->getEntradaManha());
                $this->view->setValue("SAIDA", $oFreq->getSaidaManha());
                $this->view->setValue("ENTRADAT", $oFreq->getEntradaTarde());
                $this->view->setValue("SAIDAT", $oFreq->getSaidaTarde());
                $this->view->setValue("ENTRADAN", $oFreq->getEntradaNoite());
                $this->view->setValue("SAIDAN", $oFreq->getSaidaNoite());
                $this->view->setValue("TOTAL", $oFreq->getTotal());
                $this->view->setValue("EXTRA50", $oFreq->getExtra50());
                $this->view->setValue("EXTRA100", $oFreq->getExtra100());
                //$this->view->setValue("IDAJUSTE",  "?_task=Registro&_action=edit&_Id=" . $vFreq->getData()->format('d/m/Y'));
                $this->view->setValue("IDAJUSTE",  "?_task=Registro&_action=edit&_token=" . $oFreq->getData());
                $this->view->parseBlock("BLOCK_FREQUENCIA", true);
            }

            // footer
            $this->view->addFile("FOOTER","rodape.html");

            // exibe frequencia na view
            $this->view->show();
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }
}
?>