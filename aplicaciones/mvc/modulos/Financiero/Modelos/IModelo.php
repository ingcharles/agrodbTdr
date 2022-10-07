<?php

/**
 * Plantilla de métodos de la clase modelo a implementar en la lógica del negocio
 *
 *
 * @author DATASTAR
 * @uses       IModelo
 * @package Financiero
 * @subpackage Modelo
 */
namespace Agrodb\Financiero\Modelos;

interface IModelo
{

    public function guardar(Array $datos);

    public function borrar($id);

    public function buscar($id);

    public function buscarTodo();

    public function buscarLista($where = null, $order = null, $count = null, $offset = null);
}
