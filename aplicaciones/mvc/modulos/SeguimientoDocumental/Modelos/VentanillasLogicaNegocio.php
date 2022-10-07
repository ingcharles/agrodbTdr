<?php
/**
 * Lógica del negocio de VentanillasModelo
 *
 * Este archivo se complementa con el archivo VentanillasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-13
 * @uses    VentanillasLogicaNegocio
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\SeguimientoDocumental\Modelos\IModelo;
use \Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use \Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;

class VentanillasLogicaNegocio implements IModelo
{

    private $modeloVentanillas = null;
    
    private $lNegocioUsuariosPerfiles = null;
    
    private $lNegocioUsuariosVentanilla = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloVentanillas = new VentanillasModelo();
        $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
        $this->lNegocioUsuariosVentanilla = new UsuariosVentanillaLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $datos['identificador'] = $_SESSION['usuario'];
        $datos['fecha_creacion'] = 'now()';
        
        $tablaModelo = new VentanillasModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdVentanilla() != null && $tablaModelo->getIdVentanilla() > 0) {
        	
        	//Verificar si se desativa la ventanilla y desactivar todos los usuarios asignados
        	if($datos['estado_ventanilla'] == 'Inactivo'){        		
	        	//Buscar registros existentes y cambiar de estado a inactivo
        		$otrasVentanillas = $this->lNegocioUsuariosVentanilla->buscarLista("id_ventanilla = '" . $datos['id_ventanilla'] . "';");
	        	if($otrasVentanillas->count() != 0){
	        		//recorrer para todos los registros
	        		foreach ($otrasVentanillas as $fila) {
	        			$this->lNegocioUsuariosVentanilla->cambiarEstadoUsuarioVentanilla($fila['id_usuario_ventanilla'], $fila['identificador'], 'Inactivo') ;
	        			$this->lNegocioUsuariosPerfiles->borrarPorIdentificadorPerfil($fila['identificador'], $fila['id_perfil']);
	        		}
	        	}
        	}
        	
            return $this->modeloVentanillas->actualizar($datosBd, $tablaModelo->getIdVentanilla());
        } else {
            unset($datosBd["id_ventanilla"]);
            return $this->modeloVentanillas->guardar($datosBd);
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
        $this->modeloVentanillas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return VentanillasModelo
     */
    public function buscar($id)
    {
        return $this->modeloVentanillas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloVentanillas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloVentanillas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarVentanillas()
    {
        $consulta = "SELECT * FROM " . $this->modeloVentanillas->getEsquema() . ". ventanillas";
        return $this->modeloVentanillas->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Ventanillas con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarVentanillasDatos()
    {
        $consulta = "SELECT 
                    	v.id_ventanilla, v.fecha_creacion, v.identificador, 
                    	v.nombre, v.id_unidad_destino, a.nombre as unidad_destino,
                        v.codigo_ventanilla,
                    	v.id_provincia, l.nombre as provincia,
                    	v.estado_ventanilla
                    FROM 
                    	g_seguimiento_documental.ventanillas v 
                    	INNER JOIN g_estructura.area a ON v.id_unidad_destino = a.id_area
                    	INNER JOIN g_catalogos.localizacion l ON v.id_provincia = l.id_localizacion;";
        
        $ventanillas = $this->modeloVentanillas->ejecutarSqlNativo($consulta);
        
        return $ventanillas;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Ventanillas con información completa por estados.
     *
     * @return array|ResultSet
     */
    public function buscarVentanillasDatosEstado($estado)
    {
    	$consulta = "SELECT
                    	v.id_ventanilla, v.fecha_creacion, v.identificador,
                    	v.nombre, v.id_unidad_destino, a.nombre as unidad_destino,
                        v.codigo_ventanilla,
                    	v.id_provincia, l.nombre as provincia,
                    	v.estado_ventanilla
                    FROM
                    	g_seguimiento_documental.ventanillas v
                    	INNER JOIN g_estructura.area a ON v.id_unidad_destino = a.id_area
                    	INNER JOIN g_catalogos.localizacion l ON v.id_provincia = l.id_localizacion
					WHERE
						v.estado_ventanilla in ('$estado');";
    	
    	$ventanillas = $this->modeloVentanillas->ejecutarSqlNativo($consulta);
    	
    	return $ventanillas;
    }
}
