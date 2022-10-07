<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



//use Agrodb\laboratorios\Modelos\IModelo;
/**
 * Description of EntidadesBancariasLogicaNegocio
 *
 * @author moralesl
 */
namespace Agrodb\Catalogos\Modelos;

class EntidadesbancariasLogicaNegocio implements IModelo{
    
  private $modelo = null;

    function __construct()
    {
        $this->modelo = new EntidadesbancariasModelo();
    }

    public function borrar($id)
    {}

    public function buscar($id)
    {}

    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {}

    public function buscarTodo()
    {}

    /**
     * Busca el catálogo de Entidades Bancarias
     *
     * @return ResultSet Registron categoria=1
     */
    public function buscarEntidadesBancarias()
    {
        return $this->modelo->buscarLista('cuenta_agrocalidad = true');
    }
}
