<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 27/6/2017
 * Time: 23:01
 */
class Log
{
    private $cadena = '';

    private function registrar($mensaje){
        $this->cadena .= $mensaje;
    }

    public function entrada($mensaje){
        $this->registrar(">>> $mensaje   ");
    }

    public function salida($mensaje) {
        $this->registrar("<<< $mensaje   ");
    }

    public function info($mensaje) {
        $this->registrar("--- $mensaje ---");
    }

    public function getLog(){
        return $this->cadena;
    }

}