<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Agrodb\Catalogos\Modelos;

/**
 * Description of CuentasbancariasLogicaNegocio
 *
 * @author moralesl
 */
class CuentasbancariasLogicaNegocio implements IModelo{
    
  private $modelo = null;

    function __construct()
    {
        $this->modelo = new CuentasbancariasModelo();
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
    public function buscarCuentasBancarias($idBanco)
    {
        $where = "id_banco=" . $idBanco;
        return $this->modelo->buscarLista($where);
    }

}
