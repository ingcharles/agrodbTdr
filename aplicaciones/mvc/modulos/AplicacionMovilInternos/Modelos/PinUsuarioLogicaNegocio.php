<?php
 /**
 * Lógica del negocio de PinUsuarioModelo
 *
 * Este archivo se complementa con el archivo PinUsuarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    PinUsuarioLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
 
class PinUsuarioLogicaNegocio implements IModelo 
{

	 private $modeloPinUsuario = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPinUsuario = new PinUsuarioModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PinUsuarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPin() != null && $tablaModelo->getIdPin() > 0) {
		return $this->modeloPinUsuario->actualizar($datosBd, $tablaModelo->getIdPin());
		} else {
		unset($datosBd["id_pin"]);
		return $this->modeloPinUsuario->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPinUsuario->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PinUsuarioModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPinUsuario->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPinUsuario->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPinUsuario->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPinUsuario()
	{
	$consulta = "SELECT * FROM ".$this->modeloPinUsuario->getEsquema().". pin_usuario";
		 return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Método para generar un código con encriptación md5
	 */
	private function generarPinAlfanumerico($longitud){
		$cadena="[^A-Z0-9]";
		return substr(preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())),
				0, $longitud);
	}


	/**
	 * Método para guar un registro de pin
	 */
	public function guardarPin($arrayParametros){

		$usuario = $arrayParametros['identificador'];

		$pin = $this->generarPinAlfanumerico(6);

			$consulta = "INSERT INTO a_movil_internos.pin_usuario(
								identificador, pin)
						VALUES ('$usuario','$pin')
						RETURNING id_pin;";

		return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Método para obtener un registro de pin
	 */
	public function obtenerdarPin($arrayParametros){

		$consulta = "SELECT 
						* 
					FROM 
						a_movil_internos.pin_usuario
					WHERE 
						id_pin = (SELECT max(id_pin) from a_movil_internos.pin_usuario)
						and identificador='".$arrayParametros['identificador']."'";

		return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}


	/**
	 * Método para obtener correo del usuario
	 */
	public function obtenerCorreoInterno($arrayParametros){

		$consulta = "SELECT 
						identificador, mail_personal, mail_institucional
					FROM 
						g_uath.ficha_empleado
					WHERE
						identificador='';";

		return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Método para obtener correo del usuario
	 */
	public function obtenerCorreo($arrayParametros){

		if($arrayParametros['tipo']=='interno'){
			$consulta = "SELECT 
						identificador, mail_personal, mail_institucional, tipo_empleado
					FROM 
						g_uath.ficha_empleado
					WHERE
						identificador='".$arrayParametros['identificador']."';";

		} else{
			$consulta="SELECT 
						correo mail_personal
					FROM 
						g_operadores.operadores
					WHERE
						identificador='".$arrayParametros['identificador']."';";
		}
		

		return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Método para obtener correo del usuario
	 */
	public function enviarCorreo($arrayParametros){
		$asunto = 'Agrocalidad - Código de acceso para aplicación móvil AGRO Servicios.';

		$familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
		$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";

		$cuerpoMensaje = '<table><tbody>
						<tr><td style="' . $familiaLetra . '; font-size:25px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Agrocalidad <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>					
						<tr><td style="' . $familiaLetra . '; padding-top:20px; font-size:42px; color:rgb(236,107,109);">Pin de acceso al aplicativo móvil AGRO Servicios</td></tr>
						<tr><td style="' . $familiaLetra . '; padding-top:20px; font-size:14px;color:#2a2a2a;">Le notificamos que generó un pin para acceder al aplicativo móvil AGRO Servicios de manera offline con una vigencia de 5 días.</tr>
						<tr><td style="' . $familiaLetra . '; padding-top:5px; font-size:14px;color:#2a2a2a;">Tu Pin de acceso es: <span style="' . $letraCodigo . ' font-size:14px; font-weight:bold; color:#2a2a2a;">' . $arrayParametros['pin']. '</span></td></tr>
						<tr><td style="' . $familiaLetra . '; padding-top:20px; font-size:14px;color:#2a2a2a;">Si necesita mas información puede comunicarse con nosotros al número 23960100 ext. 3203, 3204, 3205.</td></tr>
						<tr><td style="' . $familiaLetra . '; padding-top:20px; font-size:14px;color:#2a2a2a;">Gracias por utilizar nuestros servicios</td></tr>
						<tr><td style="' . $familiaLetra . '; padding-top:5px; font-size:14px;color:#2a2a2a;">El equipo de Desarrollo Tecnológico de Agrocalidad </td></tr>		
						</tbody></table>';
		
		$notifiacarMail = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();

		$notifiacarMail->guardarCorreoDestinatarioAdjunto(
			array(
				"correo" => $arrayParametros['correo'],
				"asunto" => $asunto,
				"cuerpo" => $cuerpoMensaje,
				"estado" => "Por enviar",
				"codigo_modulo" => "PRG_AGR_SRV",
				"tabla_modulo" => "a_movil_internos.pin_usuario",
				"id_tabla" => $arrayParametros['id_pin']
			)
		);
	 }

	 /**
	 * Método para obtener un registro de pin
	 */
	public function validarPin($arrayParametros){

		$consulta = "SELECT 
						* 
					FROM 
						a_movil_internos.pin_usuario
					WHERE 
						pin = '".$arrayParametros['pin']."'
						and identificador='".$arrayParametros['identificador']."'
						and fecha_caducidad >= now()";

		return $this->modeloPinUsuario->ejecutarSqlNativo($consulta);
	}

}
