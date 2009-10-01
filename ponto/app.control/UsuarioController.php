<?php

class UsuarioController extends Controller {

    private $view;
    private $model;

    public function __construct()
    {
        // define view e model
        $this->model = new Usuario();
        $this->view = new View("login.html");
    }

    public function login()
    {  

        // valida usuario e sanha
        if (!$_POST['matricula'] && !$_POST['senha'])
        {
            $this->view->show();
        }
        else
        {
            // checa login            
            $this->model->setLogin($_POST['matricula']);
            $this->model->setSenha($_POST['senha']);
            $varCodProfFuncao = $this->model->login();
            if(!$varCodProfFuncao)
            {
                // Redireciona para a página de login, com status de erro
                $this->view->setValue("MSG", "Usuário ou senha Inválidos");
                $this->view->show();
            }
            else
            {
                // Registra Sessão
                new Sessao();
                if(!Sessao::getValue('logado'))
                {
                    Sessao::SetValue('logado', true);
                }

                // Registra vari?veis globais
                $oProf = new Profissional();
                $oProf->getProfissional($varCodProfFuncao);
                Sessao::setObject('oProf', $oProf);
                $oPeriodo = new Periodo(date("d-m-Y"));
                //$oPeriodo = new Periodo("2009-03-05");
                Sessao::setObject('oPeriodo', $oPeriodo);

                $oRegistro = new RegistroController;
                $oRegistro->show();
                $oRegistro = null;

/*
                // Redireciona para p?gina principal
                $oFreq = new FrequenciaController;
                $oFreq->exibirFrequencia();
                $oFreq = null;
                //return true;*/
            }
        }
        //return false;
    }

    public function Logout()
    {
        // Libera sess?o
        new Sessao();
        Sessao::free();
        $this->view->show();
    }

    public function incluir()
    {
        //Configura usu?rio
        $user = new UsuarioVO;
        $user->setGrupo(1);
        $user->setLogin('teste login');
        $user->setNome('teste nome');
        $user->setSenha('123456');

        //insere no bd
        $model = new UsuarioModel();
        $model->incluir($user);
    }
}    
?>
