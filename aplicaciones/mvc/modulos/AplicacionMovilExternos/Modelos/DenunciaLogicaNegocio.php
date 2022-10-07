<?php
/**
 * Lógica del negocio de DenunciaModelo
 *
 * Este archivo se complementa con el archivo DenunciaControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses DenunciaLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\AplicacionMovilExternos\Modelos\IModelo;

class DenunciaLogicaNegocio implements IModelo{

	private $modeloDenuncia = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDenuncia = new DenunciaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DenunciaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDenuncia() != null && $tablaModelo->getIdDenuncia() > 0){
			return $this->modeloDenuncia->actualizar($datosBd, $tablaModelo->getIdDenuncia());
		}else{
			unset($datosBd["id_denuncia"]);
			return $this->modeloDenuncia->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloDenuncia->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DenunciaModelo
	 */
	public function buscar($id){
		return $this->modeloDenuncia->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDenuncia->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDenuncia->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDenuncia(){
		$consulta = "SELECT * FROM " . $this->modeloDenuncia->getEsquema() . ". denuncia";
		return $this->modeloDenuncia->ejecutarSqlNativo($consulta);
	}
	
	public function guardarNuevaDenuncia($datosDenuncia) {
		
		if($datosDenuncia['imagen'] != ''){
			$rutaArchivo = 'modulos/AplicacionMovilExternos/archivos/denuncias/'.md5(time()).'.jpg';
			file_put_contents($rutaArchivo, base64_decode($datosDenuncia['imagen']));
			$rutaArchivo = 'aplicaciones/mvc/'.$rutaArchivo;
		}else{
			$rutaArchivo = '';
		}
		
		$datos = array('id_motivo' => $datosDenuncia['id_motivo'],
			'descripcion' => $datosDenuncia['descripcion'],
			'lugar' => $datosDenuncia['lugar'],
			'latitud' => $datosDenuncia['latitud'],
			'longitud' => $datosDenuncia['longitud'],
			'imagen' => base64_encode($datosDenuncia['imagen']),
			'nombre_denunciante' => $datosDenuncia['nombre_denunciante'],
			'correo_denunciante' => $datosDenuncia['correo_denunciante'],
			'telefono' => $datosDenuncia['telefono'],
			'ruta_archivo' => $rutaArchivo
		);
		
		$idDenuncia = $this->guardar($datos);
		$notifiacarMail = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
		$notifiacarMail->notificarClienteDenuncia($idDenuncia, $datos['correo_denunciante']);
		
		$motivoDenuncia = new \Agrodb\AplicacionMovilExternos\Modelos\MotivosDenunciaLogicaNegocio();
		$nombreMotivoDenuncia = $motivoDenuncia->buscar($datos['id_motivo']);
		
		$notifiacarMail->notificaPlanificacionDenuncia($idDenuncia, $datos, $nombreMotivoDenuncia->getDescripcion());
		
		return $idDenuncia;
	}

	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar denuncias usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarDenunciaXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['descripcion']) && ($arrayParametros['descripcion'] != '')) {
            $busqueda .= "and upper(descripcion) ilike upper('%" . $arrayParametros['descripcion'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and fecha_registro >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and fecha_registro <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
		}
		
		$consulta= "SELECT 
						id_denuncia, id_motivo, descripcion, lugar, latitud, longitud, 
						imagen, nombre_denunciante, correo_denunciante, telefono, fecha_registro, 
						ruta_archivo, estado
					FROM 
						a_movil_externos.denuncia
					WHERE
						estado = '" . $arrayParametros['estado'] . "'" . $busqueda . "
						;";
        
        return $this->modeloDenuncia->ejecutarSqlNativo($consulta);
    }

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscaraMotivo($datosDenuncia){
		
		$consulta = "SELECT 
							id_motivo, descripcion, codigo
					FROM 
							a_movil_externos.motivos_denuncia
					WHERE
							id_motivo=".$datosDenuncia['id_denuncia'].";";

		return $this->modeloDenuncia->ejecutarSqlNativo($consulta);
	}


	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function guardarEstado($datosDenuncia){
		
		$consulta = "UPDATE  
							a_movil_externos.denuncia
					SET 
							estado='".$datosDenuncia['estado']."',
							observacion='".$datosDenuncia['observacion']."'
					WHERE
							id_denuncia=".$datosDenuncia['id_denuncia'].";";

		return $this->modeloDenuncia->ejecutarSqlNativo($consulta);
	}
}
