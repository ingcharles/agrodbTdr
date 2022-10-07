<?php

/**
 * Plantilla de métodos de la clase modelo a implementar en la lógica del negocio
 *
 *
 * @author AGROCALIDAD
 * @uses       InventariosLogicaNegocio
 * @package Inventarios
 * @subpackage Modelo
 */
namespace Agrodb\Inventarios\Modelos;

/**
 *
 * @author Edison Ayala
 */
interface IModelo
{

    public function guardar(Array $datos);

    public function borrar($id);

    public function buscar($id);

    public function buscarTodo();

    public function buscarLista($where = null, $order = null, $count = null, $offset = null);
}
