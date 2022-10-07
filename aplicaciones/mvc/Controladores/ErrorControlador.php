<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Agrodb\Controladores;

class ErrorControlador
{

    public function index()
    {
        require APP . '/vistas/error/index.php';
    }
}
