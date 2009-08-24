<?php

/**
 * @author ivan
 */

require("app.lib/Template.class.php");

class View {
    private $tpl;

    public function __construct($template) {
        $this->tpl = new Template("app.view/" . $template);
    }

    public function show(){
        $this->tpl->show();
    }

    public function setValue($var, $valor){
        $this->tpl->__set($var, $valor);
    }

    public function parseBlock($block, $append){
        $this->tpl->parseBlock($block, $append);
    }

    public function addFile($varname, $filename)
    {
        $this->tpl->addFile($varname, $filename);
    }
    
}
?>
