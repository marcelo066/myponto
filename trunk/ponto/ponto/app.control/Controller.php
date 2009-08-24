<?php

abstract class Controller {
    protected function redirecionar($url){
        header('Location: ' . $url);
    }

    protected function error($error){
        // se algum erro acontecer, chamo esse metodo,
        // que vai fazer alguma coisa (mandar um email,
        // gravar log, etc )
        $this->log($error);
    }

    protected function log($mensagem){
        // grava uma menagem de log em algum arquivo
        // qualquer definido aqui
    }
}

?>