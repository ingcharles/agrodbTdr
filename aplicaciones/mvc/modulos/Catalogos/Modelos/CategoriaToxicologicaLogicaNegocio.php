<?php
/**
 * Lógica del negocio de CategoriaToxicologicaModelo
 *
 * Este archivo se complementa con el archivo CategoriaToxicologicaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    CategoriaToxicologicaLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CategoriaToxicologicaLogicaNegocio implements IModelo
{

    private $modeloCategoriaToxicologica = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCategoriaToxicologica = new CategoriaToxicologicaModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CategoriaToxicologicaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCategoriaToxicologica() != null && $tablaModelo->getIdCategoriaToxicologica() > 0) {
            return $this->modeloCategoriaToxicologica->actualizar($datosBd, $tablaModelo->getIdCategoriaToxicologica());
        } else {
            unset($datosBd["id_categoria_toxicologica"]);
            return $this->modeloCategoriaToxicologica->guardar($datosBd);
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
        $this->modeloCategoriaToxicologica->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CategoriaToxicologicaModelo
     */
    public function buscar($id)
    {
        return $this->modeloCategoriaToxicologica->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCategoriaToxicologica->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCategoriaToxicologica->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCategoriaToxicologica()
    {
        $consulta = "SELECT * FROM " . $this->modeloCategoriaToxicologica->getEsquema() . ". categoria_toxicologica";
        return $this->modeloCategoriaToxicologica->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosCategoriaToxicologicaRIA($grupoProducto, $idCategoriaToxicologica=null)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en Categoria Toxicologica. ",
            'idCategoriaToxicologica' => null,
            'categoriaToxicologica' => null
        );
        
        if($grupoProducto == 1 || $grupoProducto == 2 || $grupoProducto == 5 || $grupoProducto == 6){
            // Categoría Toxicológica
             $query = "upper(categoria_toxicologica) = upper('No Aplica') and id_area = 'IAV' ORDER BY 1 LIMIT 1";
            $catTox = $this->buscarLista($query);
            
            if (isset($catTox->current()->id_categoria_toxicologica)) {
                $validacion['idCategoriaToxicologica'] = $catTox->current()->id_categoria_toxicologica;
                $validacion['categoriaToxicologica'] = $catTox->current()->categoria_toxicologica;
            } else {
                $validacion['idCategoriaToxicologica'] = 0;
                $validacion['categoriaToxicologica'] = "NA";
            }
        }else if($grupoProducto == 3 || $grupoProducto == 4){
            // Categoría Toxicológica
            if (isset($idCategoriaToxicologica)) {
                $query = "id_categoria_toxicologica = $idCategoriaToxicologica";
                $catTox = $this->buscarLista($query);
                
                if (isset($catTox->current()->id_categoria_toxicologica)) {
                    $validacion['idCategoriaToxicologica'] = $catTox->current()->id_categoria_toxicologica;
                    $validacion['categoriaToxicologica'] = $catTox->current()->categoria_toxicologica;
                } else {
                    $validacion['idCategoriaToxicologica'] = 0;
                    $validacion['categoriaToxicologica'] = "NA";
                }
            } else {
                $validacion['idCategoriaToxicologica'] = 0;
                $validacion['categoriaToxicologica'] = "NA";
            }
        }
        
        return $validacion;
    }
}