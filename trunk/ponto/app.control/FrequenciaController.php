<?php


class FrequenciaController extends Controller
{
    private $view;
    private $model;
    
    public function __construct()
    {
        // instanciamos os objetos
        $this->model = new Frequencia();
        $this->view = new View("frequencia.html");
    }

    public function show()
    {
        // define período da frequencia
        // obtendo objeto armazenado na sessão
        new Sessao();
        $oPeriodo = Sessao::getObject("oPeriodo");
        $vInicio = new DateTime($oPeriodo->getInicial());
        $vFim = new DateTime($oPeriodo->getFinal());
        $oProf = Sessao::getObject("oProf");        
        $this->model->getFrequencia($oProf->getCodProfFuncao(), $vInicio, $vFim);

        /************************
        * Preenche View
        ************************/             
        
        // topo
        $this->view->addFile("TOPO","topo.html");
        // cabeÃ§alho
        $vTitulo = ":: FOLHA DE FREQUENCIA :: ". $oFreq->mes . "/" . $oFreq->ano . " :: " . $oProf->getMatricula() . " - " . $oProf->getNome() . " :: " . $oProf->getFuncao();
        $this->view->setValue("TITULO", $vTitulo);
        // resumo
        $this->view->setValue("RES_NORMAIS", $this->model->resumo->getTotHorNormais());
        $this->view->setValue("RES_EXTRA50", $this->model->resumo->getTotHorExtras50());
        $this->view->setValue("RES_EXTRA100", $this->model->resumo->getTotHorExtras100());
        $this->view->setValue("RES_TOTAL", $this->model->resumo->getTotHorTrabalhadas());
        $this->view->setValue("RES_ATRAB", $this->model->resumo->getTotHorATrabalhar());
        $this->view->setValue("RES_SALDO", $this->model->resumo->getSaldoHoras());

        // registro                
        foreach($this->model->registro as $vFreq)
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
            $this->view->setValue("DATA", $vFreq->getData());
            $this->view->setValue("DIA", $vFreq->getDiaDaSemana());
            $this->view->setValue("ENTRADA", $vFreq->getEntradaManha());
            $this->view->setValue("SAIDA", $vFreq->getSaidaManha());
            $this->view->setValue("ENTRADAT", $vFreq->getEntradaTarde());
            $this->view->setValue("SAIDAT", $vFreq->getSaidaTarde());
            $this->view->setValue("ENTRADAN", $vFreq->getEntradaNoite());
            $this->view->setValue("SAIDAN", $vFreq->getSaidaNoite());
            $this->view->setValue("TOTAL", $vFreq->getTotal());
            $this->view->setValue("EXTRA50", $vFreq->getExtra50());
            $this->view->setValue("EXTRA100", $vFreq->getExtra100());
            //$this->view->setValue("IDAJUSTE",  "?_task=Registro&_action=edit&_Id=" . $vFreq->getData()->format('d/m/Y'));
            $this->view->setValue("IDAJUSTE",  "?_task=Registro&_action=edit&_Id=" . $vFreq->getData());
            $this->view->parseBlock("BLOCK_FREQUENCIA", true);
        }        

        // footer
        $this->view->addFile("FOOTER","rodape.html");

        // exibe frequencia na view
        $this->view->show();
    }    
}
?>