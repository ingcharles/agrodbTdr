<?php
/**
 * Controlador Usuarios
 *
 * Este archivo controla la lógica del negocio del modelo: UsuariosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses UsuariosControlador
 * @package usuarios
 * @subpackage Controladores
 */
namespace Agrodb\Usuarios\Controladores;

use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;
use Agrodb\Core\ValidarDatos;
use Agrodb\Usuarios\Modelos\UsuariosModelo;
use Agrodb\Usuarios\Modelos\UsuariosLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;

class UsuariosControlador extends BaseControlador{

	private $lNegocioUsuarios = null;

	private $modeloUsuarios = null;

	private $lNegocioIngreso = null;

	private $modeloIngreso = null;
	
	private $lNegocioUsuariosPerfiles = null;

	private $accion = null;

	private $cValidarDatos = null;
	
	private $banderaUsuario = 'NO';
	
	private $mensajeBanderaUsuario = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioUsuarios = new UsuariosLogicaNegocio();
		$this->modeloUsuarios = new UsuariosModelo();
		$this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
		$this->cValidarDatos = new ValidarDatos();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		require APP . 'Usuarios/vistas/listaUsuariosVista.php';
	}

	/**
	 * Método para buscar usuario por identificador
	 */
	public function listarUsuarios(){
		$identificador = $_POST['identificador'];
		$arrayParametros = array(
			'identificador' => $identificador);
		$modeloUsuarios = $this->lNegocioUsuarios->buscarLista($arrayParametros);
		$this->tablaHtmlUsuarios($modeloUsuarios);
		echo \Zend\Json\Json::encode($this->itemsFiltrados);
		exit();
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Usuarios";
		require APP . 'Usuarios/vistas/formularioUsuariosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Usuarios
	 */
	public function guardar(){
		$correoUsuario = $_POST['correo_usuario'];
		$identificador = $_POST['identificador'];
		$tipoUsuario = $_POST['tipo_usuario'];

		$validarCorreo = $this->cValidarDatos->validarEmail($correoUsuario);

		if ($correoUsuario == $validarCorreo){

			$this->lNegocioUsuarios->guardar($_POST);
			$this->lNegocioUsuarios->actualizarCorreoElectronicoUsuario(array(
				'correoUsuario' => $correoUsuario,
				'tipoUsuario' => $tipoUsuario,
				'identificador' => $identificador));

			Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
		}
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Usuarios
	 */
	public function editar(){
		
		$this->accion = "Editar Usuarios";
		$this->modeloUsuarios = $this->lNegocioUsuarios->buscar($_POST["id"]);
		$tipoUsuario = $this->lNegocioUsuarios->buscarTipoUsuario(array(
			'identificador' => $_POST["id"]));
		
		$dPerfilAdministrador = $this->lNegocioUsuariosPerfiles->buscarUsuariosXAplicacionPerfil($_SESSION['usuario'], 'PFL_ADMIN_USU_GU');
		
		if(empty($dPerfilAdministrador->current())){
			$dPerfilAdministrador = $this->lNegocioUsuariosPerfiles->buscarUsuariosXAplicacionPerfil($_SESSION['usuario'], 'PFL_ADMI_TAL_HUM');
			$perfilAdministrador = $dPerfilAdministrador->current()->codificacion_perfil;
		}else{
			$perfilAdministrador = $dPerfilAdministrador->current()->codificacion_perfil;
		}
		
		$correoUsuario = $this->lNegocioUsuarios->buscarCorreoElectronicoUsuario(array(
			'identificador' => $tipoUsuario->current()->identificador,
			'tipoUsuario' => $tipoUsuario->current()->codificacion_perfil));

		if (isset($correoUsuario->current()->correo_usuario)){
			$this->correoUsuario = $correoUsuario->current()->correo_usuario;
		}else{
			$this->correoUsuario = "Usuario externo, no tiene cuenta con correo. Solicite cambio de perfil";
		}
		$this->tipoUsuario = $tipoUsuario->current()->codificacion_perfil;

		$ingresoAuditoria = new \Agrodb\Auditoria\Modelos\IngresoLogicaNegocio();
		$arrayParametros = array(
			'identificador' => $_POST["id"]);
		$historialAuditoriaIngreso = $ingresoAuditoria->buscarLista($arrayParametros, 'id_ingreso desc', '15', '0');
		$this->construirAuditoriaIngreso($historialAuditoriaIngreso);

		if($perfilAdministrador == "PFL_ADMI_TAL_HUM" && ($tipoUsuario->current()->codificacion_perfil == 'PFL_USUAR_INT' || $tipoUsuario->current()->codificacion_perfil == 'PFL_USUAR_CIV_PR')){
			$this->banderaUsuario = 'SI';			
		}else if($perfilAdministrador == "PFL_ADMIN_USU_GU" && ($tipoUsuario->current()->codificacion_perfil == 'PFL_REGIST_OPERA')){
			$this->banderaUsuario = 'SI';
		}else{
		    if($tipoUsuario->current()->codificacion_perfil == 'PFL_REGIST_OPERA'){
				$this->mensajeBanderaUsuario = 'Operadores';
			}else if($tipoUsuario->current()->codificacion_perfil == 'PFL_USUAR_INT' || $tipoUsuario->current()->codificacion_perfil == 'PFL_USUAR_CIV_PR'){
				$this->mensajeBanderaUsuario = 'Funcionarios o Servicios profesionales';				
			}else{
			    $this->mensajeBanderaUsuario = 'Usuarios externos, por favor solicite por GLPI un cambio de perfil dado que los usuarios externos no disponen de correo electrónico.';
			}
		}
		
		require APP . 'Usuarios/vistas/formularioUsuariosVista.php';
		
		
	}

	/**
	 * Método para borrar un registro en la base de datos - Usuarios
	 */
	public function borrar(){
		$this->lNegocioUsuarios->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Usuarios
	 */
	public function tablaHtmlUsuarios($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$observacion = explode('¬', $fila['observacion_usuario']);

				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['identificador'] . '"
                            class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Usuarios\usuarios"
                            data-opcion="editar" ondragstart="drag(event)" draggable="true"
                            data-destino="detalleItem">
                            <td>' . ++ $contador . '</td>
                            <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
                            <td>' . $fila['nombre_usuario'] . '</td>
                            <td>' . ($fila['estado'] == '1' ? 'Activo' : ($fila['estado'] == '3' ? 'Inactivo' : 'Actualizar estado')) . '</td>
                            <td>' . reset($observacion) . '</td>
                        </tr>');
			}
		}
	}

	public function construirAuditoriaIngreso($registros){
		$ipAcceso = array();

		$this->historialIngreso = '<table>
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Acción</th>
                                                <th>Ubicación</th>
                                                <th>Tipo</th>
                                            </tr>
                                        </thead>';

		foreach ($registros as $fila){

			if ($fila['ip_acceso'] != ''){
				$filaEncontrada = array_search($fila['ip_acceso'], array_column($ipAcceso, 'ip_acceso'));

				if (empty($filaEncontrada) && ! strlen($filaEncontrada)){
					$ubicacion = json_decode(file_get_contents('http://api.ipstack.com/' . $fila['ip_acceso'] . '?access_key=4e40cced2ef12dd3d958119acc96215d'));
					$acceso = ($ubicacion->city == null ? 'Sin información' : $ubicacion->city);
					$ipAcceso[] = array(
						'ip_acceso' => $fila['ip_acceso'],
						'localizacion' => $acceso);
				}else{
					$acceso = '';
				}
			}else{
				$acceso = 'Sin información';
			}

			$accion = str_replace('¬', '</br>Acción realizada por el usuario: ', $fila['accion']);

			$this->historialIngreso .= '<tr>
                        <td>' . date('Y-m-d (G:i:s)', strtotime($fila['fecha_inicio'])) . '</td>
                        <td>' . $accion . ($fila['intento'] != '' ? ' - Intento # ' . $fila['intento'] : '') . '</td>
                        <td>' . ($acceso == '' ? $ipAcceso[$filaEncontrada]['localizacion'] : $acceso) . '</td>
                        <td>' . ($fila['tipo'] == "EXITO" ? '<span class="nAprobado"></span>' : ($fila['tipo'] == "LOG" ? '<span class="nSin_notificar"></span>' : ($fila['tipo'] == "INACTIVO" ? '<span class="nDelegado"></span>' : (($fila['tipo'] == "ERROR" && $fila['intento'] <= '4') ? '<span class="nAlerta"></span>' : '<span class="nRechazado"></span>')))) . '</td>
                    </tr>';
		}

		$this->historialIngreso .= '</table>';
	}
}