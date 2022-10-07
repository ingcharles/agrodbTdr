<?php
 /**
 * Lógica del negocio de AlertasUsuarioModelo
 *
 * Este archivo se complementa con el archivo AlertasUsuarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AlertasUsuarioLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilExternos\Modelos;
  
  use Agrodb\AplicacionMovilExternos\Modelos\IModelo;
 
class AlertasUsuarioLogicaNegocio implements IModelo 
{

	 private $modeloAlertasUsuario = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAlertasUsuario = new AlertasUsuarioModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AlertasUsuarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAlerta() != null && $tablaModelo->getIdAlerta() > 0) {
		return $this->modeloAlertasUsuario->actualizar($datosBd, $tablaModelo->getIdAlerta());
		} else {
		unset($datosBd["id_alerta"]);
		return $this->modeloAlertasUsuario->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAlertasUsuario->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AlertasUsuarioModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAlertasUsuario->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAlertasUsuario->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAlertasUsuario->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAlertasUsuario()
	{
	$consulta = "SELECT * FROM ".$this->modeloAlertasUsuario->getEsquema().". alertas_usuario";
		 return $this->modeloAlertasUsuario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guarda las alertas enviadas por los usuarios.
	 * 
	 * @return array|ResultSet
	 */
	public function guardarNuevaAlerta($datosAlerta) {
		
		if($datosAlerta['imagen'] != ''){
			$rutaArchivo = 'modulos/AplicacionMovilExternos/archivos/alertas/'.md5(time()).'.jpg';
			file_put_contents($rutaArchivo, base64_decode($datosAlerta['imagen']));
			$rutaArchivo = 'aplicaciones/mvc/'.$rutaArchivo;
		}else{
			$rutaArchivo = '';
		}
		
		$datos = array('id_tipo_alerta' => $datosAlerta['id_tipo_alerta'],
			'descripcion' => $datosAlerta['descripcion'],
			'lugar' => $datosAlerta['lugar'],
			'latitud' => $datosAlerta['latitud'],
			'longitud' => $datosAlerta['longitud'],
			'nombre_usuario' => $datosAlerta['nombre_usuario'],
			'correo_usuario' => $datosAlerta['correo_usuario'],
			'telefono' => $datosAlerta['telefono'],
			'ruta_imagen' => $rutaArchivo
		);
		
		$idAlerta = $this->guardar($datos);

		$alerta = new \Agrodb\AplicacionMovilExternos\Modelos\TiposAlertaLogicaNegocio();
		
		$tipoAlerta = $alerta->buscarLista("id_tipo_alerta = " . $datosAlerta['id_tipo_alerta']);

		$asunto = 'Recepción Alerta fito/zoo sanitaria AGRO Móvil.';
		$cuerpo = '</br></br>Estimado, </br>
		Se ha receptado una alerta Alerta fito/zoo sanitaria por medio del aplicativo “AGRO Móvil”:<br/><br/>
		Los datos ingresados son:
		<ul>
			<li><b>Motivo: </b>'.$tipoAlerta->current()->descripcion.'</li>
			<li><b>Descripción: </b>'.$datosAlerta['descripcion'].'</li>
			<li><b>Lugar: </b>'.$datosAlerta['lugar'].'</li>
			<li><b>Nombre: </b>'.$datosAlerta['nombre_usuario'].'</li>
			<li><b>Correo: </b>'.$datosAlerta['correo_usuario'].'</li>
			<li><b>Teléfono: </b>'.$datosAlerta['telefono'].'</li>
		</ul>';

        $notifiacarMail = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();

        $arrayDatos = array(
            "correo" => $tipoAlerta->current()->corre_responsable,
            "asunto" => $asunto,
            "cuerpo" => $cuerpo,
            "estado" => "Por enviar",
            "codigo_modulo" => "PRG_AGR_MOV_EXT",
            "tabla_modulo" => "a_movil_externos.alertas_usuario",
            "id_tabla" => $idAlerta,
        );

		if($rutaArchivo != ''){
			$arrayDatos["ruta_archivo"] = $rutaArchivo;
		}

        $notifiacarMail->guardarCorreoDestinatarioAdjunto($arrayDatos);

		$asunto = 'Confirmación Alerta fito/zoo sanitaria AGRO Móvil';
		$cuerpo = '<H2>Estimado Usuario,</H2> <b>AGROCALIDAD</b> agradece su <b>Alerta</b> enviada a través de la aplicación “AGRO Móvil”. <br/><br/>Con su aporte ayuda a mejorar el estatus Fito y Zoosanitario del país.';

		$arrayDatos = array(
            "correo" => $datosAlerta['correo_usuario'],
            "asunto" => $asunto,
            "cuerpo" => $cuerpo,
            "estado" => "Por enviar",
            "codigo_modulo" => "PRG_AGR_MOV_EXT",
            "tabla_modulo" => "a_movil_externos.alertas_usuario",
            "id_tabla" => $idAlerta,
        );

		$notifiacarMail->guardarCorreoDestinatarioAdjunto($arrayDatos);
		
		return $idAlerta;
	}

	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar alertas de usuarios usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarAlertaXFiltro($arrayParametros)
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
						*
					FROM 
						a_movil_externos.alertas_usuario
					WHERE
						estado = '" . $arrayParametros['estado'] . "'" . $busqueda . "
						;";
        
        return $this->modeloAlertasUsuario->ejecutarSqlNativo($consulta);
    }

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function guardarEstado($datosAlerta){
		
		$consulta = "UPDATE  
							a_movil_externos.alertas_usuario
					SET 
							estado='".$datosAlerta['estado']."',
							observacion='".$datosAlerta['observacion']."'
					WHERE
							id_alerta=".$datosAlerta['id_alerta'].";";

		return $this->modeloAlertasUsuario->ejecutarSqlNativo($consulta);
	}

}
