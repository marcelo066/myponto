<?php

require_once("./checaLogin.php");

class RegistroController
{
    private $view;
    private $model;
    public function __construct()
    {
        // instanciamos os objetos
        $this->model = new RegistroDao();
        $this->view = new View("registro.html");
    }

    public function addRegistro()
    {
        // instancia ponto

        $oPonto = new Ponto;
        $oPonto->setData(date("Y-m-d"));
        $oPonto->setHora(date("H:i:s"));
        $oPonto->setTurno($_POST["optTurno"]);
        $oPonto->setApropriar($_POST["chkApropriar"]);
        // instancia profissional
        $oProf = new Profissional;
        $oProf = Sessao::getObject("oProf");        
        // ve se pode clicar
        $fw = new Firewall;
        if($fw->podeClicar($oProf, $oPonto))
        {
            // registra o ponto
            $this->model->add($oProf, $oPonto);
        }
        else
        {
            $this->view->setValue("MSG", "Não pode clicar.");
        }        
        $this->exibirRegistro();
    }

    public function exibirRegistro()
    {        
        // obtem profissional logado, armazenado na sessao
        $oProf = new Profissional;
        $oProf = Sessao::getObject("oProf");
        
        // checa quais
        $oRegistro = new Registro;        
        $oRegistro = $this->model->getByDate($oProf, new DateTime(date("Y-m-d")));

        if($oRegistro){
            // se ja clicou entrada, desabilita controle
            if($oRegistro->getEntradaManha())
            {
                $this->view->setValue("OPTENT", "disabled");
                $this->view->setValue("MSGENT", " - " . $oRegistro->getEntradaManha());
                $this->view->setValue("OPTALM", "checked");
            }
            // se ja clicou almoco, desabilita controle
            if($oRegistro->getSaidaManha())
            {
                $this->view->setValue("OPTALM", "disabled");
                $this->view->setValue("MSGALM", " - " . $oRegistro->getSaidaManha());
                $this->view->setValue("OPTRET", "checked");
            }
            // se ja clicou retorno almoco, desabilita controle
            if($oRegistro->getEntradaTarde())
            {
                $this->view->setValue("OPTRET", "disabled");
                $this->view->setValue("MSGRET", " - " . $oRegistro->getEntradaTarde());
                $this->view->setValue("OPTSAI", "checked");
            }
            // se ja clicou SAIDA, desabilita controle
            if($oRegistro->getSaidaTarde())
            {
                $this->view->setValue("OPTSAI", "disabled");
                $this->view->setValue("MSGSAI", " - " . $oRegistro->getSaidaTarde());
                $this->view->setValue("OPTBTN", "disabled");
            }
        }
        else
        {
            $this->view->setValue("OPTENT", "checked");
        }
        $this->view->addFile("TOPO","topo.html");
        $vTitulo = ":: REGISTRO DE PONTO :: ". date("H:i:s") . " :: " . $oProf->getMatricula() . " - " . $oProf->getNome() . " :: " . $oProf->getFuncao() ;
        $this->view->setValue("TITULO", $vTitulo);
        $this->view->addFile("FOOTER","rodape.html");
        $this->view->show();
    }

    public function editarRegistro()
    {
        // define view
        $view = new View("editar_registro.html");
        $view->addFile("TOPO", "topo.html");

        $vData = new DateTime(str_replace("/","-",$_GET["_rowData"] ));

        // preenche view com dados do profissional
        $oProf = new Profissional;
        $oProf = Sessao::getObject("oProf");
        $vTitulo = ":: EDITAR REGISTRO DE PONTO :: " . $oProf->getMatricula() . " - " . $oProf->getNome() . " :: " . $oProf->getFuncao() ;
        $view->setValue("TITULO", $vTitulo);

        // preenche view com dados do registro
        $oRegistro = new Registro;
        $oRegistro = $this->model->getByDate($oProf, $vData);
        $view->setValue("DATA", $vData->format("d-m-Y"));
        $view->setValue("DIASEMANA", Data::getDiaSemana($vData));
        if($oRegistro)
        {            
            $view->setValue("ENTRADA", $oRegistro->getEntradaManha());
            $view->setValue("ALMOCO", $oRegistro->getSaidaManha());
            $view->setValue("RETORNO", $oRegistro->getEntradaTarde());
            $view->setValue("SAIDA", $oRegistro->getSaidaTarde());
        }

        // carrega selects de ocorrencia
        $oOcorrencia = new Ocorrencia;
        $oOcorrenciaDao = new OcorrenciaDao;
        $oOcorrencia = $oOcorrenciaDao->getList();
        foreach($oOcorrencia as $row )
        {
            $view->setValue("CODIGO", $row->getCodigo());
            $view->setValue("DESCRICAO", $row->getDescricao());
            $view->parseBlock("BLOCK_OCORRENCIA1", true);
            $view->parseBlock("BLOCK_OCORRENCIA2", true);
            $view->parseBlock("BLOCK_OCORRENCIA3", true);
            $view->parseBlock("BLOCK_OCORRENCIA4", true);
        }

        // destroi objetos
        $oOcorrenciaDao = null;
        $oOcorrencia = null;

        // exibe view
        $view->addFile("FOOTER", "rodape.html");
        $view->show();

    }
}
?>
