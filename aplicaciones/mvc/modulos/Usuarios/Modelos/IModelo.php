<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Agrodb\Usuarios\Modelos;

/**
 *
 * @author Alvaro Sanchez
 */
interface IModelo
{

    //public function guardar(CuentasbancariasModelo $tabla);

    public function borrar($id);

    public function buscar($id);

    public function buscarTodo();

    public function buscarLista($where = null, $order = null, $count = null, $offset = null);
}
