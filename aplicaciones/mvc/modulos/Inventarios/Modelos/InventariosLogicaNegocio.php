<?php
/**
 * Lógica del negocio de  InventariosModelo
 *
 * Este archivo se complementa con el archivo   InventariosControlador.
 *
 * @author AGROCALIDAD
 * @uses    InventariosLogicaNegocio
 * @package Inventarios
 * @subpackage Modelo
 */
namespace Agrodb\Inventarios\Modelos;

//use Agrodb\Inventarios\Modelos\IModelo;

class InventariosLogicaNegocio implements IModelo{
    
    private $modelo = null;
    
    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new InventariosModeloMouse();
    }
    
    
    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new InventariosModeloMouse($datos);
        if ($tablaModelo->getIdRaton() != null && $tablaModelo->getIdRaton() > 0) {
            return $this->modelo->actualizar($datos,$tablaModelo->getIdRaton());
        } else {
            unset($datos["id_raton"]);
            return $this->modelo->guardar($datos);
        }
    }
    
    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modelo->borrar($id);
    }
    
    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return LaboratoriosModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }
    
    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }
    
    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where = null, $order = null, $count = null, $offset = null);
    }
    
    public function reporte(){
    	echo 'HOLA';
    }
        
}



?>