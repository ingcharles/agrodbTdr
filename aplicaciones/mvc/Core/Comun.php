<?php
namespace Agrodb\Core;

use Agrodb\Catalogos\Modelos\CultivosLogicaNegocio;
use Agrodb\Programas\Modelos\AccionesLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Financiero\Modelos\ServiciosLogicaNegocio as Financiero;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ArchivosAdjuntosLogicaNegocio;
use Agrodb\Core\Log;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Estructura\Modelos\AreaLogicaNegocio;
use Agrodb\Usuarios\Modelos\PerfilesLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesMedidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\MediosTransporteLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposTratamientoLogicaNegocio;
use Agrodb\Catalogos\Modelos\TratamientosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesDuracionLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesTemperaturaLogicaNegocio;
use Agrodb\Catalogos\Modelos\ConcentracionesTratamientoLogicaNegocio;
use Agrodb\Catalogos\Modelos\IdiomasLogicaNegocio;
use Agrodb\Catalogos\Modelos\PuertosLogicaNegocio;
use Agrodb\Catalogos\Modelos\MonedasLogicaNegocio;
use Agrodb\Catalogos\Modelos\RegimenAduaneroLogicaNegocio;

use Agrodb\Catalogos\Modelos\GrupoProductoLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigoComplementarioLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigoSuplementarioLogicaNegocio;

use Agrodb\Catalogos\Modelos\ClasificacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoComponenteLogicaNegocio;
use Agrodb\Catalogos\Modelos\IngredienteActivoInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\FormulacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\UsosLogicaNegocio;
use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\EfectosBiologicosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosConsumiblesLogicaNegocio;
use Agrodb\Catalogos\Modelos\DeclaracionVentaLogicaNegocio;
use Agrodb\Catalogos\Modelos\AnexosPecuariosLogicaNegocio;

use Agrodb\Correos\Modelos\CorreosLogicaNegocio;
use Agrodb\Correos\Modelos\DestinatariosLogicaNegocio;
use Agrodb\Correos\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ViaAdministracionLogicaNegocio;
use Agrodb\Catalogos\Controladores\CategoriaToxicologicaControlador;
use Agrodb\Catalogos\Modelos\CategoriaToxicologicaLogicaNegocio;
use Agrodb\Catalogos\Modelos\PartidasArancelariasLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesMedidasCfeLogicaNegocio;															 

class Comun{

	protected $usuarioInterno = false;

	protected $nombreUsuario;

	protected $identificador;

	protected $idProvinciaUsuario;

	protected $NombreLocalizacion;

	protected $codigoLocalizacion;

	protected $idAplicacion;

	protected $nombreProvincia;

	protected $idArea;

	// datos de la solicitud					 
	protected $datosSolicitud = null;

	// archivos adjuntos de la solicitud
	protected $archivosAdjuntosSolicitud = null;

	protected $totalMuestrasSolicitud = null;

	// total de muestras detectadas en la solicitud
	protected $totalAnalisisSolicitud = null;

	// total de analisis de la solicitud

	/**
	 * La session tienen los soguientes datos cuando es Usuario INTERNO
	 * datosUsuario -> Nombre y appelido.
	 * ej. Jaramillo Chamba Rusbel Antonio
	 * usuario -> Identificador (cédula/ruc) Ej. 1103869648
	 * nombre_usuario -> Isentificador Ej. 1103869648
	 * idLocalizacion -> Provincia que pertenece Ej. 1490
	 * nombreLocalizacion ->Nombre locación Ej. Laboratorios Tumbaco
	 * codigoLocalizacion -> Código de la localozación Ej. EC-P-01-04
	 * idAplicacion -> Id de la aplocación. Ej. 52
	 * nombreProvincia-> nombre de la provincia. Ej. Pichincha
	 * idArea -> Id del área Ej. GSFA
	 *
	 * PARA USUARIOS EXTERNOS SON LOS SIGUIENTES DATOS:
	 * datosUsuario
	 * nombre_usuario
	 * idLocalizacion
	 * nombreLocalizacion
	 * codigoLocalizacion
	 * idAplicacion
	 * nombreProvincia
	 * idArea
	 * @return Strng -Identificador
	 */
	protected function usuarioActivo(){
		if (isset($_SESSION['usuario'])){

			isset($_SESSION['datosUsuario']) ? $this->nombreUsuario = $_SESSION['datosUsuario'] : '';
			isset($_SESSION['usuario']) ? $this->identificador = $_SESSION['usuario'] : '';
			isset($_SESSION['idLocalizacion']) ? $this->idProvinciaUsuario = $_SESSION['idLocalizacion'] : '';
			isset($_SESSION['nombreLocalizacion']) ? $this->nombreLocalizacion = $_SESSION['nombreLocalizacion'] : '';
			isset($_SESSION['codigoLocalizacion']) ? $this->codigoLocalizacion = $_SESSION['codigoLocalizacion'] : '';
			isset($_SESSION['idAplicacion']) ? $this->idAplicacion = $_SESSION['idAplicacion'] : '';
			isset($_SESSION['idProvincia']) ? $this->idProvincia = $_SESSION['idProvincia'] : '';
			isset($_SESSION['nombreProvincia']) ? $this->nombreProvincia = $_SESSION['nombreProvincia'] : '';
			isset($_SESSION['idArea']) ? $this->idArea = $_SESSION['idArea'] : '';
			// Verificamos si es usuario interno

			$perfiles = $this->usuarioPerfiles($this->identificador);
			foreach ($perfiles as $fila){
				if ($fila->codificacion_perfil == 'PFL_USUAR_INT'){
					$this->usuarioInterno = true;
					break;
				}else{
					$this->usuarioInterno = false;
				}
			}

			return $_SESSION['usuario'];
		}else{
			session_destroy();
			Mensajes::fallo(Constantes::ERROR_USUARIO_INACTIVO);
			// throw new \Exception(Constantes::ERROR_USUARIO_INACTIVO);
			header("Location: " . URL);
		}
	}

	/**
	 * Busca los perfiles de un usuario filtrado por la aplicación
	 *
	 * @param type $idUsuario
	 * @param type $Perfil
	 * @return type
	 */
	public function usuarioPerfiles($idUsuario, $idAplicacion = null){
		$lNegocioUsuarioPerfiles = new UsuariosPerfilesLogicaNegocio();

		return $lNegocioUsuarioPerfiles->buscarUsuariosPerfiles($idUsuario);
	}

	/**
	 * Consulta las Direcciones de diagnostico y construye el combo
	 */
	public function comboDirecciones($idDireccion = null){
		$laboratorios = new LaboratoriosLogicaNegocio();
		$direcciones = "";
		$combo = $laboratorios->buscarDirecciones();

		foreach ($combo as $item){
			if ($idDireccion == $item->id_laboratorio){
				$direcciones .= '<option value="' . $item->id_laboratorio . '" selected>' . $item->nombre . '</option>';
			}else{
				$direcciones .= '<option value="' . $item->id_laboratorio . '">' . $item->nombre . '</option>';
			}
		}
		return $direcciones;
	}

	/**
	 * Consulta las Direcciones de diagnÃ³stico del sistema GUIA y construye el combo
	 *
	 * @param Integer $idDireccion
	 */
	public function comboDireccionesGUIA($idDireccion){
		$direcciones = new Financiero();
		$opcionesHtml = "";
		$combo = $direcciones->buscarDirecciones();

		foreach ($combo as $item){
			if ($idDireccion == $item->id_servicio){
				$opcionesHtml .= '<option value="' . $item->id_servicio . '" selected>' . $item->concepto . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item->id_servicio . '">' . $item->concepto . '</option>';
			}
		}
		return $opcionesHtml;
	}

	/**
	 * Consulta los laboratorios de una dirección seleccionada y construye el combo
	 *
	 * @param Integer $idDireccion
	 * @return string Código html para llenar el combo de laboratorios mediante ajax
	 */
	public function comboLaboratorios($idDireccion){
		$laboratorios = new LaboratoriosLogicaNegocio();
		$opcionesHtml = "";
		$combo = $laboratorios->buscarLaboratorios($idDireccion);
		$opcionesHtml .= '<option value="">Seleccionar....</option>';
		foreach ($combo as $item){

			$opcionesHtml .= '<option value="' . $item->id_laboratorio . '">' . $item->nombre . '</option>';
		}
		echo $opcionesHtml;
		exit();
	}

	/**
	 * Consulta los Laboratorios del sistema GUIA y construye el combo
	 *
	 * @param Integer $idLaboratorio
	 */
	public function comboLaboratoriosGUIA($idLaboratorio){
		$laboratorios = new Financiero();
		$opcionesHtml = "";
		$combo = $laboratorios->buscarLaboratorios();
		foreach ($combo as $item){
			if ($idLaboratorio == $item->id_servicio){
				$opcionesHtml .= '<option value="' . $item->id_servicio . '" selected>' . $item->concepto . '</option>';
			}else{
				$opcionesHtml .= '<option value="' . $item->id_servicio . '">' . $item->concepto . '</option>';
			}
		}
		return $opcionesHtml;
	}

	/**
	 * Consulta los Servicios en el esquema financiero del sistema GUIA
	 * de acuerdi a la Dirección seleccionada y construye el combo
	 *
	 * @param Integer $idDireccion
	 * @return string Código html para llenar el combo de servicios GUIA mediante ajax
	 */
	public function comboServicioGUIA($idLaboratorio){
		$serviciosGUIA = new Financiero();
		$opcionesHtml = "";
		$combo = $serviciosGUIA->buscarServicios($idLaboratorio);
		$opcionesHtml .= '<option value="">Seleccionar....</option>';
		foreach ($combo as $item){

			$opcionesHtml .= '<option value="' . $item->id_servicio . '">' . $item->concepto . '</option>';
		}
		echo $opcionesHtml;
		exit();
	}

	/**
	 * Consulta las provinvias y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboPaises($idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$paises = "";
		$combo = $localizacion->buscarPaises();

		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '" selected>' . $item->nombre . '</option>';
			}else{
				$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '">' . $item->nombre . '</option>';
			}
		}
		return $paises;
	}

	public function comboVariosPaises($idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$paises = "";
		$combo = $localizacion->buscarVariosPaises();

		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				$paises .= '<option value="' . $item->id_localizacion . '" selected>' . $item->nombre . '</option>';
			}else{
				$paises .= '<option value="' . $item->id_localizacion . '">' . $item->nombre . '</option>';
			}
		}
		return $paises;
	}

	/**
	 * Consulta las provinvias de Ecuador y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboProvinciasEc($idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$provincias = "";
		$combo = $localizacion->buscarProvinciasEc();

		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				$provincias .= '<option value="' . $item->id_localizacion . '" selected>' . $item->nombre . '</option>';
			}else{
				$provincias .= '<option value="' . $item->id_localizacion . '">' . $item->nombre . '</option>';
			}
		}
		return $provincias;
	}

	/**
	 * Consulta las Cantones y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboCantones($idProvincia, $idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$cantones = '<option value="">Seleccione...</option>';
		$combo = $localizacion->buscarCantones($idProvincia);
		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				$cantones .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '" selected>' . $item->nombre . '</option>';
			}else{
				$cantones .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '">' . $item->nombre . '</option>';
			}
		}
		echo $cantones;
		exit();
	}

	/**
	 * Consulta las Parroquias y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboParroquias($idCanton, $idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$parroquias = '<option value="">Seleccione...</option>';
		$combo = $localizacion->buscarParroquias($idCanton);
		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				$parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '" selected>' . $item->nombre . '</option>';
			}else{
				$parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '">' . $item->nombre . '</option>';
			}
		}
		echo $parroquias;
		exit();
	}
	
	/**
	 * Consulta las Parroquias y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboParroquiasXNombreProvinciaCanton($provincia, $canton, $parroquia = null){
	    $localizacion = new LocalizacionLogicaNegocio();
	    
	    $parroquias = '<option value>Seleccione...</option>';
	    
	    $queryProvincia = "upper(unaccent(nombre)) = upper(unaccent('$provincia')) and categoria = 1";
	    $idProvincia = $localizacion->buscarLista($queryProvincia);
	    //echo ($idProvincia->current()->id_localizacion);
	    $queryCanton = "upper(unaccent(nombre)) = upper(unaccent('$canton')) and categoria = 2 and id_localizacion_padre =". $idProvincia->current()->id_localizacion;
	    $idCanton = $localizacion->buscarLista($queryCanton);
	    
	    $combo = $localizacion->buscarParroquias($idCanton->current()->id_localizacion);
	    
	    foreach ($combo as $item){
	        if ($parroquia == $item['nombre']){
	            $parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '" selected>' . $item->nombre . '</option>';
	        }else{
	            $parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '">' . $item->nombre . '</option>';
	        }
	    }
	    return $parroquias;
	}

	/**
	 * MSD
	 * Consulta las Oficinas por Cantón y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboOficinas($idCanton, $categoria = null, $idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();
		$oficinas = '<option value="">Seleccione...</option>';
		$combo = $localizacion->buscarOficinas($idCanton, 3);
		foreach ($combo as $item){
			if ($idLocalizacion == $item['nombre']){
				$oficinas .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '" selected>' . $item->nombre . '</option>';
			}else{
				$oficinas .= '<option value="' . $item->id_localizacion . '" data-nombre="' . $item->nombre . '">' . $item->nombre . '</option>';
			}
		}
		echo $oficinas;
		exit();
	}

	/**
	 * Consulta las acciones que tiene permitido el usuario de acuerdo a su perfil
	 *
	 * @return string - Vista el cÃ³digo html para desplegar los botones
	 */
	public function crearAccionBotones(){
		if (! isset($_POST["opcion"])){
			Mensajes::fallo(Constantes::ERROR_MENU);
			throw new \Exception('Verifique que el controlador tenga implementada el método para esta acción');
		}
		if (! isset($_SESSION['usuario'])){
			Mensajes::fallo(Constantes::ERROR_USUARIO_INACTIVO);
			throw new \Exception('No se puede verificar las acciones del perfil de usuario, sesión del usuario a finalizado');
		}
		$acciones = new AccionesLogicaNegocio();
		$resultado = $acciones->obtenerAccionesPermitidas($_POST["opcion"], $_SESSION['usuario']);

		$botones = "";

		foreach ($resultado as $fila){
			$botones .= '<a href="#"
                id="' . $fila['estilo'] . '"data-destino="detalleItem" data-opcion="' . $fila['pagina'] . '"data-rutaAplicacion="' . (isset($fila['ruta_mvc']) ?  $fila['ruta_mvc'] : $fila['ruta']) . '"
		>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
		}
		return $botones;
	}

	/**
	 * Despliega las acciones que tiene permitido el usuario de acuerdo a la selección de la opción
	 *
	 * @return string - Vista el código html para desplegar los botones
	 */
	public function crearAccionBotonesListadoItems($opciones){
		$botones = "";

		foreach ($opciones as $fila){
			$botones .= '<a href="#"
                id="' . $fila['estilo'] . '"data-destino="detalleItem" data-opcion="' . $fila['pagina'] . '"data-rutaAplicacion="' . $fila['ruta'] . '"
		>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
		}
		return $botones;
	}

	/**
	 * Transforma una cadena con el formato adecuado para ponerlo en un formulario.
	 *
	 * @param String $cadena
	 * @return String
	 */
	public function quitarHtml($cadena){
		$mayusculas = 'Ã�Ã‰Ã�Ã“ÃšÃ‘:';
		$minusculas = 'Ã¡Ã©Ã­Ã³ÃºÃ± ';
		$cadena = strtr($cadena, $mayusculas, $minusculas);
		$cadena = str_replace("&#10003;", " ", $cadena); // Este cÃ³digo fue puesto para poner un check en los reportes
		$cadena = strtolower(strip_tags($cadena));
		return ucfirst($cadena);
	}

	/**
	 * Transforma una cadena con tildes a una sin tildes.
	 *
	 * @param String $cadena
	 * @return String
	 */
	public function quitarTildes($cadena){
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
    	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas, $cadena);
		return $texto;
	}

	/**
	 * Maneja las exepciones y las guarda en una base de datos
	 *
	 * @param type $excepciÃ³n
	 */
	public function manejadorExcepciones($excepcion){
		new Log($excepcion);
		http_response_code(400);
	}
	
	 /**
     * Maneja los errores de código que no entran en una excepción
     *
     * @param type $excepciÃ³n
     */
    public function manejadorExcepcionesSincatch($errno, $errstr, $errfile, $errline)
    { 
        echo 'Error Nro. ',$errno, ' Mensaje: ', $errstr, ' Archivo: ', $errfile, ' Línea: ', $errline;
        throw new ErrorException($errstr . ' en ' . $errfile. ' Línea' . $errline , 0, $errno, $errfile, $errline);
    }

	/**
	 * Habilita campos necesarios únicamente en ambiente de desarrollo
	 *
	 * @param type $excepciÃ³n
	 */
	public function devVisible(){
		$respuesta = "hidden";
		if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev'){
			$respuesta = "visible";
		}
		return $respuesta;
	}

	/**
	 * Crea un enlace para descar un archivo PDF
	 *
	 * @param type $url
	 * @return type
	 */
	public function descargaPdf($url){
		return "<a href='" . $url . "' target='_blank'><img src='" . URL . "resource/img/pdf.png' alt='PDF' /> </a>";
	}

	/**
	 * Incluye archivos css en la cabecera
	 *
	 * @param type $archivo
	 * @param type $urlBase
	 */
	public function incluirCSS($archivo, $urlBase = URL_RESOURCE){
		echo "$('head').append('<link rel='stylesheet' href='" . $urlBase . $archivo . "'>');";
	}

	/**
	 * Retorna TRUE o FALSE si el laboratorio tiene permiso a ciertas opciones como
	 * confirmación, derivación, acreditación
	 *
	 * @param type $idLaboratorio
	 * @param type $tipo
	 * @return boolean
	 */
	public function obtenerPermisoLaboratorio($idLaboratoriosProvincia, $tipo = null){
		// Buscar el permiso
		$lNLaboratorios = new LaboratoriosLogicaNegocio();
		$buscaLaboratorio = $lNLaboratorios->buscarDatosLaboratorio($idLaboratoriosProvincia);
		$fila = $buscaLaboratorio->current();

		if ($fila->atributos !== ''){
			$data = json_decode($fila->atributos, TRUE);
			$valor = "";
			foreach ($data as $key => $value){
				foreach ($value as $key2 => $value2){
					if ($value2 == $tipo){
						$valor = $value['display'];
					}
				}
			}
			if ($valor == 'block'){
				return true;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	/**
	 * Retorna TRUE o FALSE si el laboratorio tiene permiso a ciertas opciones como
	 * confirmación, derivación, acreditación
	 *
	 * @param type $idLaboratorio
	 * @param type $tipo
	 * @return boolean
	 */
	public function obtenerAtributoLaboratorio($idLaboratorio, $tipo = null){
		if ($idLaboratorio !== null & $idLaboratorio !== ''){
			// Buscar el permiso
			$lNLaboratorios = new LaboratoriosLogicaNegocio();
			$modeloLaboratorio = $lNLaboratorios->buscar($idLaboratorio);

			if ($modeloLaboratorio->getConfOrdenTrabajo() !== ''){
				$data = json_decode($modeloLaboratorio->getConfOrdenTrabajo(), TRUE);
				$valor = "";
				foreach ($data as $key => $value){
					foreach ($value as $key2 => $value2){
						if ($value2 == $tipo){
							$valor = $value['contenido'];
						}
					}
				}
				if ($valor !== ''){
					return "$valor *";
				}else{
					return 'C&oacute;digo de campo de la muestra *';
				}
			}else{
				return 'C&oacute;digo de campo de la muestra *';
			}
		}else{
			return 'C&oacute;digo de campo de la muestra *';
		}
	}

	/**
	 * Obtiene la etiqueta del campo para codigo de campo de la muestra configurado por laboratorio
	 *
	 * @param type $usuarioInterno
	 * @param type $idLaboratorio
	 * @return type
	 */
	public function atributosCodigoCampoMuestra($usuarioInterno, $idLaboratorio){
		if ($usuarioInterno){
			$confOT = $this->obtenerConfOrdenTrabajoLaboratorio($idLaboratorio, 'm_cod_campo');
		}else{
			$confOT = $this->obtenerConfOrdenTrabajoLaboratorio($idLaboratorio, 'm_cod_campo_e');
		}
		$etiquetaCodigoMuestra = $confOT['contenido'];
		// si no esta configurado muestre la etiqueta por defecto
		if ($etiquetaCodigoMuestra == ''){
			$etiquetaCodigoMuestra = 'C&oacute;digo de campo de la muestra';
		}
		return array(
			'visible' => $confOT['visible'],
			'etiqueta' => $etiquetaCodigoMuestra);
	}

	/**
	 * Retorna el valor del parametro configurado
	 *
	 * @param type $idLaboratorio
	 * @param type $tipo
	 * @return type
	 */
	public function obtenerConfOrdenTrabajoLaboratorio($idLaboratorio, $tipo = null){
		if ($idLaboratorio !== null & $idLaboratorio !== ''){
			// Buscar el permiso
			$lNLaboratorios = new LaboratoriosLogicaNegocio();
			$modeloLaboratorio = $lNLaboratorios->buscar($idLaboratorio);

			if ($modeloLaboratorio->getConfOrdenTrabajo() !== ''){
				$data = json_decode($modeloLaboratorio->getConfOrdenTrabajo(), TRUE);
				$valor = "";
				foreach ($data as $key => $value){
					foreach ($value as $key2 => $value2){
						if ($value2 == $tipo){
							return $value;
						}
					}
				}
			}
		}
	}

	/**
	 * Para mostrar un mensaje si tiene permiso o no a ciertas opciones
	 *
	 * @param type $idLaboratorio
	 * @param type $tipo
	 */
	public function verPermisoLaboratorio($idLaboratorio, $tipo = null){
		if (! $this->obtenerPermisoLaboratorio($idLaboratorio, $tipo)){
			echo Constantes::INF_PERMISO_LABORATORIO;
			Mensajes::exito(Constantes::INF_PERMISO_LABORATORIO);
			exit();
		}
	}

	/**
	 * Boton que se puede agregar en una grilla para ver los datos de la solicitud
	 * Ejemplo: $this->botonDatosSolicitud($fila->id_solicitud)
	 *
	 * @param type $idSolicitud
	 */
	public function botonDatosSolicitud($idSolicitud){
		$url = URL . "Laboratorios/Laboratorios/verDatosSolicitud";
		$boton = "<button type='button' title='Ver datos de la solicitud' onclick='fn_verDatosSolicitud($idSolicitud, " . "\"$url\"" . ")' class='fas fa-search'> </button>";
		return $boton;
	}

	/**
	 * Para desplegar una vista comun de los datos de la solicitud
	 */
	public function verDatosSolicitud($idSolicitud){
		// datos de la solicitud
		$lNSolicitudes = new SolicitudesLogicaNegocio();
		$buscaSolicitud = $lNSolicitudes->buscarDatosSolicitud($idSolicitud);
		$this->datosSolicitud = $buscaSolicitud->current();
		$buscaDetalleSolicitud = $lNSolicitudes->buscarDetalleSolicitud($idSolicitud);
		$this->tablaHtmlDetalle($buscaDetalleSolicitud);

		// obtener el total de las muestras
		$buscaMuestras = $lNSolicitudes->obtenerMuestrasSolicitud($idSolicitud);
		$this->totalMuestrasSolicitud = count($buscaMuestras);

		// buscar anexos de la solicitud
		$lNArchivosAdjuntos = new ArchivosAdjuntosLogicaNegocio();
		$buscaArchivos = $lNArchivosAdjuntos->buscarArchivosAdjuntosSolicitud($idSolicitud);
		$this->tablaHtmlArchivosAdjuntosSolicitud($buscaArchivos);
		require APP . 'Laboratorios/vistas/datosSolicitudVista.php';
	}

	/**
	 * Contruye código tabla de detalle de la solicitud
	 *
	 * @param type $tabla
	 */
	public function tablaHtmlDetalle($tabla){
		$total = 0;
		$contador = 0;
		$html = "";
		foreach ($tabla as $fila){
			$total += $fila->total_muestras;
			$html .= "<tr>" . "<td>" . ++ $contador . "</td>" . "<td>{$fila->nom_direccion}</td>" . "<td>{$fila->nom_laboratorio}</td>" . "<td>{$fila->rama_nombre}</td>" . "<td style='text-align: center'>{$fila->total_muestras}</td>" . "</tr>";
		}
		$this->detalleSolicitudesGuardado = $html;
		$this->totalAnalisisSolicitud = $total;
	}

	/**
	 * Contruye código tabla de detalle de la solicitud
	 *
	 * @param type $tabla
	 */
	public function tablaHtmlArchivosAdjuntosSolicitud($tabla){
		if (count($tabla) > 0){
			$html = "";
			$contador = 0;
			foreach ($tabla as $fila){
				$archivo = $fila->nombre_parametro . " (NO ADJUNTO)";
				if ($fila->nombre_archivo !== ""){
					$archivo = "<a class='fas fa-file-pdf' href='" . URL_DIR_FILES . "/$fila->nombre_archivo' target='_blank'>  $fila->nombre_parametro</a>";
				}
				$html .= "<tr><td>" . ++ $contador . "</td><td>$archivo</td><tr>";
			}
			$this->archivosAdjuntosSolicitud = $html;
		}else{
			$this->archivosAdjuntosSolicitud = "<tr><td colspan='2'>" . Constantes::NO_EXISTE_ADJUNTO_SOLICITUD . "</td></tr>";
		}
	}

	/**
	 * Para control de casos especiales en servicios
	 *
	 * @param type $idServicio
	 * @param type $codigo
	 * @return boolean
	 */
	public function casoEspecialServicio($idServicio, $codigo){
		$lNServicio = new \Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio();
		$buscaServicio = $lNServicio->buscar($idServicio);
		$codigoEspecial = $buscaServicio->getCodigoEspecial();
		$arrayCodigos = explode(';', $codigoEspecial);
		if (in_array($codigo, $arrayCodigos)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Para control de casos especiales en laboratorios
	 *
	 * @param type $idLaboratorio
	 * @param type $codigo
	 * @return boolean
	 */
	public function casoEspecialLaboratorio($idLaboratorio, $codigo){
		$lNLaboratorio = new \Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio();
		$buscaLaboratorio = $lNLaboratorio->buscar($idLaboratorio);
		$codigoEspecial = $buscaLaboratorio->getCodigoEspecial();
		$arrayCodigos = explode(';', $codigoEspecial);
		if (in_array($codigo, $arrayCodigos)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Retorna estilo para mostrar las alertas por fechas
	 *
	 * @param type $fechaFin
	 * @return string
	 */
	public function alertaFechas($fechaFin){
		$fechaActual = date('Y-m-d');
		$estilo = "";
		if ($fechaFin !== ''){
			if ($fechaActual == $fechaFin){
				$estilo = "warning";
			}else if ($fechaActual > $fechaFin){
				$estilo = "danger";
			}else{
				$estilo = "success";
			}
		}
		return $estilo;
	}

	/**
	 * Combo de dos estados ACTIVO/INACTIVO
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboActivoInactivo($opcion){
		$combo = "";
		if ($opcion == "Activo"){
			$combo .= '<option value="Activo" selected="selected">Activo</option>';
			$combo .= '<option value="Inactivo">Inactivo</option>';
		}else if ($opcion == "Inactivo"){
			$combo .= '<option value="Activo" >Activo</option>';
			$combo .= '<option value="Inactivo" selected="selected">Inactivo</option>';
		}else{
			$combo .= '<option value="" selected="selected">Seleccionar...</option>';
			$combo .= '<option value="Activo" >Activo</option>';
			$combo .= '<option value="Inactivo">Inactivo</option>';
		}
		return $combo;
	}

	/**
	 * Combo de solicitudes automatizadas
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboTipoSolicitudFinanciero(){
		$combo = "";

		$combo .= '<option value="" >Seleccione....</option>';
		$combo .= '<!--option value="Operadores">Registro Operador</option-->';
		$combo .= '<option value="Importación">Importación</option>';
		$combo .= '<option value="Fitosanitario">Fitosanitario</option>';
		$combo .= '<!--option value="Emisión de Etiquetas">Emisión de Etiquetas</option-->';
		$combo .= '<!--option value="mercanciasImportacionExportacion">Imp./Exp. Mercancias</option-->';
		$combo .= '<!--option value="dossierPecuario">Dossier Pecuario</option-->';
		$combo .= '<!--option value="dossierFertilizantes">Dossier Fertilizantes</option-->';
		$combo .= '<!--option value="ensayoEficacia">Ensayo Eficacia</option-->';
		$combo .= '<option value="Otros">Otros</option>';

		return $combo;
	}

	/*
	 * encriptacion de información *******************
	 *
	 */
	public static function encriptarClave($input, $key){
		$encryption_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($input, 'aes-256-cbc', $encryption_key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
	}

	public static function desencriptarClave($input, $Key){
		$encryption_key = base64_decode($Key);
		//list ($encrypted_data, $iv) = explode('::', base64_decode($input), 2);
		$datosEncriptacion = explode('::', base64_decode($input));
		$encrypted_data = $datosEncriptacion[0];
		if(isset($datosEncriptacion[1])){
			$iv = $datosEncriptacion[1];
		}else{
			$iv = '';
		}
		
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

	/**
	 * Consulta las Coordinaciones, Direcciones Generales y Distritales a nivel nacional y construye el combo
	 *
	 * @return string
	 */
	public function comboAreasCategoriaNacional($areaSeleccionada){
		$areas = new AreaLogicaNegocio();
		$comboArea = "";
		$area = $areas->buscarAreasCategoriaNacional();

		foreach ($area as $item){
			if ($areaSeleccionada == $item['id_area']){
				$comboArea .= '<option value="' . $item->id_area . '" selected>' . $item->nombre . '</option>';
			}else{
				$comboArea .= '<option value="' . $item->id_area . '">' . $item->nombre . '</option>';
			}
		}
		return $comboArea;
	}

	/**
	 * Consulta los Perfiles de una aplicación y construye el combo
	 *
	 * @return string
	 */
	public function comboPerfilesAplicacion($idPerfil){
		$perfiles = new PerfilesLogicaNegocio();
		$comboPerfil = "";
		$perfil = $perfiles->buscarPerfilesAplicacion($this->idAplicacion);
		foreach ($perfil as $item){
			if ($idPerfil == $item['id_perfil']){
				$comboPerfil .= '<option value="' . $item->id_perfil . '" data-codificacion = "' . $item->codificacion_perfil . '" selected="selected">' . $item->nombre . '</option>';
			}else{
				$comboPerfil .= '<option value="' . $item->id_perfil . '" data-codificacion = "' . $item->codificacion_perfil . '">' . $item->nombre . '</option>';
			}
		}
		return $comboPerfil;
	}

	/**
	 * Combo de dos estados SI/NO
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboSiNo($opcion = null){
		$combo = "";
		if ($opcion == "Si"){
			$combo .= '<option value="Si" selected="selected">Si</option>';
			$combo .= '<option value="No">No</option>';
		}else if ($opcion == "No"){
			$combo .= '<option value="Si" >Si</option>';
			$combo .= '<option value="No" selected="selected">No</option>';
		}else{
			$combo .= '<option value="" selected="selected">Seleccionar...</option>';
			$combo .= '<option value="Si" >Si</option>';
			$combo .= '<option value="No">No</option>';
		}
		return $combo;
	}

	/**
	 * Busca los datos de un funcionario por identificador
	 *
	 * @param type $idUsuario
	 * @param type $Perfil
	 * @return type
	 */
	public function datosFuncionario($identificador){
		$lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();

		return $lNegocioFichaEmpleado->buscar($identificador);
	}

	/**
	 * Consulta las unidades de medida y construye el combo
	 *
	 * @param Integer $idUnidadMedida
	 * @return string
	 */
	public function comboUnidadesMedida($codigoUnidadMedida = null){
		$unidadMedida = new UnidadesMedidasLogicaNegocio();
		$unidades = "";

		$query = "estado='Activo' order by nombre ASC";

		$combo = $unidadMedida->buscarLista($query);

		foreach ($combo as $item){
			if ($codigoUnidadMedida == $item['codigo']){
			    $unidades .= '<option value="' . $item->codigo . '" data-idunidadmedida="' . $item->id_unidad_medida . '" selected>' . $item->nombre . '</option>';
			}else{
			    $unidades .= '<option value="' . $item->codigo . '" data-idunidadmedida="' . $item->id_unidad_medida . '" >' . $item->nombre . '</option>';
			}
		}
		return $unidades;
	}

	/**
	 * Consulta los tipos de productos por área temática y construye el combo
	 *
	 * @param string $idArea
	 * @return string
	 */
	public function comboTipoProducto(){
		$idArea = $_POST['idArea'];

		$tipoProducto = new TipoProductosLogicaNegocio();
		$tipos = '<option value="">Seleccionar....</option>';

		$query = "estado = 1 and id_area = '$idArea' order by nombre ASC";

		$combo = $tipoProducto->buscarLista($query);

		foreach ($combo as $item){
			$tipos .= '<option value="' . $item->id_tipo_producto . '">' . $item->nombre . '</option>';
		}

		echo $tipos;
		exit();
	}

	/**
	 * Consulta los subtipos de producto por tipo de producto y construye el combo
	 *
	 * @param Integer $idTipoProducto
	 * @return string
	 */
	public function comboSubtipoProductos($idTipoProducto){
		$subtipoProducto = new SubtipoProductosLogicaNegocio();
		$subtipos = '<option value="">Seleccione...</option>';

		$query = "estado = 1 and id_tipo_producto = $idTipoProducto order by nombre ASC";

		$combo = $subtipoProducto->buscarLista($query);

		foreach ($combo as $item){
			$subtipos .= '<option value="' . $item->id_subtipo_producto . '" data-nombre="' . $item->nombre . '">' . $item->nombre . '</option>';
		}
		echo $subtipos;
		exit();
	}

	/**
	 * Consulta los subtipos de producto por tipo de producto y construye el combo
	 *
	 * @param Integer $idTipoProducto
	 * @return string
	 */
	public function comboProductos($idSubtipoProducto){
		$producto = new ProductosLogicaNegocio();
		$productos = '<option value="">Seleccione...</option>';

		$query = "estado = 1 and id_subtipo_producto = $idSubtipoProducto order by nombre ASC";

		$combo = $producto->buscarLista($query);

		foreach ($combo as $item){
			$productos .= '<option value="' . $item->id_producto . '" data-nombre="' . $item->nombre_comun . '">' . $item->nombre_comun . '</option>';
		}
		echo $productos;
		exit();
	}

	/**
	 * Consulta las Coordinaciones, Direcciones Generales y Distritales a nivel nacional y construye el combo
	 *
	 * @return string
	 */
	public function obtenerAreasDireccionesTecnicas($areaSeleccionada){
		$areas = new AreaLogicaNegocio();
		$comboArea = "";

		$where = "clasificacion IN ('Planta Central','Oficina Técnica') and 
                  estado= 1 and 
                  categoria_area IN (3,4,1)";

		$area = $areas->buscarLista($where, 'nombre');

		$comboArea .= '<option value="" selected="selected">Seleccionar...</option>';
		foreach ($area as $item){
			if ($areaSeleccionada == $item['id_area']){
				$comboArea .= '<option value="' . $item->id_area . '" selected>' . $item->nombre . '</option>';
			}else{
				$comboArea .= '<option value="' . $item->id_area . '">' . $item->nombre . '</option>';
			}
		}
		return $comboArea;
	}

	public function obtenerIpUsuario(){
		if (isset($_SERVER["HTTP_CLIENT_IP"])){
			return $_SERVER["HTTP_CLIENT_IP"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
			return $_SERVER["HTTP_X_FORWARDED"];
		}elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
			return $_SERVER["HTTP_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_FORWARDED"])){
			return $_SERVER["HTTP_FORWARDED"];
		}else{
			return $_SERVER["REMOTE_ADDR"];
		}
	}

	/**
	 * Consulta los medios de transporte y construye el combo
	 */
	public function comboMediosTransporte($idMedioTransporte = null){
		$medioTransporte = new MediosTransporteLogicaNegocio();

		$transporte = "";

		$combo = $medioTransporte->buscarTodo();

		foreach ($combo as $item){
			if ($idMedioTransporte == $item->id_medios_transporte){
				$transporte .= '<option value="' . $item->id_medios_transporte . '" selected>' . $item->tipo . '</option>';
			}else{
				$transporte .= '<option value="' . $item->id_medios_transporte . '">' . $item->tipo . '</option>';
			}
		}
		return $transporte;
	}

	/**
	 * Consulta los medios de transporte y construye el combo por nombre
	 */
	public function comboMediosTransportePorNombre($nombreMedioTransporte = null){
		$medioTransporte = new MediosTransporteLogicaNegocio();

		$transporte = "";

		$combo = $medioTransporte->buscarTodo();

		foreach ($combo as $item){
			if (strtoupper($this->quitarTildes($nombreMedioTransporte)) == strtoupper($this->quitarTildes($item->tipo))){
				$transporte .= '<option value="' . $item->id_medios_transporte . '" selected>' . $item->tipo . '</option>';
			}else{
				$transporte .= '<option value="' . $item->id_medios_transporte . '">' . $item->tipo . '</option>';
			}
		}
		return $transporte;
	}

	/**
	 * Consulta los puertos relacionados con un pais por id de puerto
	 */
	public function comboPuertosPorIdentificador($idPais, $idPuerto = null, $codigoPais = null){
		$lNegocioPuerto = new PuertosLogicaNegocio();

		$comboPuertos = "";

		$arrayParametros = array();

		if (isset($idPais)){
			$arrayParametros += [
				'id_pais' => $idPais];
		}

		if (isset($codigoPais)){
			$arrayParametros += [
				'codigo_pais' => $codigoPais];
		}

		$combo = $lNegocioPuerto->buscarLista($arrayParametros);

		foreach ($combo as $item){
			if ($idPuerto == $item->id_puerto){
				$comboPuertos .= '<option value="' . $item->id_puerto . '" data-nombre="' . $item->nombre_puerto . '" selected>[' . $item->codigo_puerto . '] - ' . $item->nombre_puerto . '</option>';
			}else{
				$comboPuertos .= '<option value="' . $item->id_puerto . '" data-nombre="' . $item->nombre_puerto . '">[' . $item->codigo_puerto . '] - ' . $item->nombre_puerto . '</option>';
			}
		}
		return $comboPuertos;
	}

	/**
	 * Consultade todos los tipos de monedas
	 */
	public function comboMoneda($idMoneda){
		$lNegocioMoneda = new MonedasLogicaNegocio();

		$comboMonedas = "";

		$combo = $lNegocioMoneda->buscarTodo();

		foreach ($combo as $item){
			if ($idMoneda == $item->id_moneda){
				$comboMonedas .= '<option value="' . $item->id_moneda . '" selected>' . $item->nombre . '</option>';
			}else{
				$comboMonedas .= '<option value="' . $item->id_moneda . '">' . $item->nombre . '</option>';
			}
		}
		return $comboMonedas;
	}

	/**
	 * Consulta regimen aduanero por id regimen
	 */
	public function comboRegimenAduanero($idRegimenAduanero){
		$lNegocioRegimenAduanero = new RegimenAduaneroLogicaNegocio();

		$comboRegimenAduanero = "";

		$combo = $lNegocioRegimenAduanero->buscarTodo();

		foreach ($combo as $item){
			if ($idRegimenAduanero == $item->id_regimen){
				$comboRegimenAduanero .= '<option value="' . $item->id_regimen . '" selected>' . $item->descripcion . '</option>';
			}else{
				$comboRegimenAduanero .= '<option value="' . $item->id_regimen . '">' . $item->descripcion . '</option>';
			}
		}
		return $comboRegimenAduanero;
	}

	/**
	 * Consulta los usuarios con un perfil asignado (y aplicación) y construye el combo
	 */
	public function comboUsuariosXPerfilAplicacion($identificador, $codificacionPerfil, $idAplicacion = null){
		$usuariosPerfiles = new UsuariosPerfilesLogicaNegocio();

		$usuarios = "";
		$combo = $usuariosPerfiles->buscarUsuariosXAplicacionPerfil($identificador, $codificacionPerfil, $idAplicacion = null);

		if ($combo != null){
			foreach ($combo as $item){
				$usuarios .= '<option value="' . $item->identificador . '">' . $item->usuario . '</option>';
			}
		}

		return $usuarios;
	}

	/**
	 * Construye los países por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idLocalizacion
	 * @return string
	 */
	public function comboPaisesPorIdioma($idioma, $idLocalizacion = null){
		$localizacion = new LocalizacionLogicaNegocio();

		$paises = "";

		$combo = $localizacion->buscarPaises();

		foreach ($combo as $item){
			if ($idLocalizacion == $item['id_localizacion']){
				if ($idioma == 'SPA'){
					$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '" selected>' . $item->nombre . '</option>';
				}else{
					$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '" selected>' . $item->nombre_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '">' . $item->nombre . '</option>';
				}else{
					$paises .= '<option value="' . $item->id_localizacion . '" data-CodigoPais="' . $item->codigo . '">' . $item->nombre_ingles . '</option>';
				}
			}
		}
		return $paises;
	}

	/**
	 * Consulta los medios de trasporte por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idMedioTransporte
	 * @return string
	 */
	public function comboMediosTransportePorIdioma($idioma, $idMedioTransporte = null){
		$medioTransporte = new MediosTransporteLogicaNegocio();

		$transporte = "";

		$combo = $medioTransporte->buscarTodo();

		foreach ($combo as $item){
			if ($idMedioTransporte == $item->id_medios_transporte){
				if ($idioma == 'SPA'){
					$transporte .= '<option value="' . $item->id_medios_transporte . '" selected>' . $item->tipo . '</option>';
				}else{
					$transporte .= '<option value="' . $item->id_medios_transporte . '" selected>' . $item->tipo_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$transporte .= '<option value="' . $item->id_medios_transporte . '">' . $item->tipo . '</option>';
				}else{
					$transporte .= '<option value="' . $item->id_medios_transporte . '">' . $item->tipo_ingles . '</option>';
				}
			}
		}
		return $transporte;
	}

	/**
	 * Consulta las unidades de medida por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idUnidadMedida
	 * @return string
	 */
	public function comboUnidadesMedidaCfePorIdioma($idioma, $idUnidadMedida = null){
		$unidadMedida = new UnidadesMedidasCfeLogicaNegocio();
		$unidades = "";									  
												  
		$query = "estado = 'Activo' ORDER BY nombre ASC";

		$combo = $unidadMedida->buscarLista($query);
		
		foreach ($combo as $item){
			if ($idUnidadMedida == $item['id_unidad_medida']){
				if ($idioma == 'SPA'){
					$unidades .= '<option value="' . $item->id_unidad_medida . '" selected>' . $item->nombre . '</option>';
				}else{
					$unidades .= '<option value="' . $item->id_unidad_medida . '" selected>' . $item->nombre_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$unidades .= '<option value="' . $item->id_unidad_medida . '">' . $item->nombre . '</option>';
				}else{
					$unidades .= '<option value="' . $item->id_unidad_medida . '">' . $item->nombre_ingles . '</option>';
				}
			}
		}
		return $unidades;
	}

	/**
	 * Consulta los tipos de tratamiento por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idTipoTratamiento
	 * @return string
	 */
	public function comboTiposTratamientoPorIdioma($idioma, $idTipoTratamiento = null){
		$qTiposTratamiento = new TiposTratamientoLogicaNegocio();
		$tiposTratamiento = "";

		$query = "estado_tipo_tratamiento='activo'";

		$combo = $qTiposTratamiento->buscarLista($query);

		foreach ($combo as $item){
			if ($idTipoTratamiento == $item['id_tipo_tratamiento']){
				if ($idioma == 'SPA'){
					$tiposTratamiento .= '<option value="' . $item->id_tipo_tratamiento . '" selected>' . $item->nombre_tipo_tratamiento . '</option>';
				}else{
					$tiposTratamiento .= '<option value="' . $item->id_tipo_tratamiento . '" selected>' . $item->nombre_tipo_tratamiento_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$tiposTratamiento .= '<option value="' . $item->id_tipo_tratamiento . '">' . $item->nombre_tipo_tratamiento . '</option>';
				}else{
					$tiposTratamiento .= '<option value="' . $item->id_tipo_tratamiento . '">' . $item->nombre_tipo_tratamiento_ingles . '</option>';
				}
			}
		}
		return $tiposTratamiento;
	}

	/**
	 * Consulta los tratamientos por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idTratamiento
	 * @return string
	 */
	public function comboTratamientosPorIdioma($idioma, $idTratamiento = null){
		$qTratamientos = new TratamientosLogicaNegocio();
		$tratamientos = "";

		$query = "estado_tratamiento = 'activo'";

		$combo = $qTratamientos->buscarLista($query);

		foreach ($combo as $item){
			if ($idTratamiento == $item['id_tratamiento']){
				if ($idioma == 'SPA'){
					$tratamientos .= '<option value="' . $item->id_tratamiento . '" selected>' . $item->nombre_tratamiento . '</option>';
				}else{
					$tratamientos .= '<option value="' . $item->id_tratamiento . '" selected>' . $item->nombre_tratamiento_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$tratamientos .= '<option value="' . $item->id_tratamiento . '">' . $item->nombre_tratamiento . '</option>';
				}else{
					$tratamientos .= '<option value="' . $item->id_tratamiento . '">' . $item->nombre_tratamiento_ingles . '</option>';
				}
			}
		}
		return $tratamientos;
	}

	/**
	 * Consulta las unidades de duración por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idUnidadDuracion
	 * @return string
	 */
	public function comboUnidadesDuracionPorIdioma($idioma, $idUnidadDuracion = null){
		$qUnidadesDuracion = new UnidadesDuracionLogicaNegocio();
		$unidadesDuracion = "";

		$query = "estado_unidad_duracion = 'activo'";

		$combo = $qUnidadesDuracion->buscarLista($query);

		foreach ($combo as $item){
			if ($idUnidadDuracion == $item['id_unidad_duracion']){
				if ($idioma == 'SPA'){
					$unidadesDuracion .= '<option value="' . $item->id_unidad_duracion . '" selected>' . $item->nombre_unidad_duracion . '</option>';
				}else{
					$unidadesDuracion .= '<option value="' . $item->id_unidad_duracion . '" selected>' . $item->nombre_unidad_duracion_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$unidadesDuracion .= '<option value="' . $item->id_unidad_duracion . '">' . $item->nombre_unidad_duracion . '</option>';
				}else{
					$unidadesDuracion .= '<option value="' . $item->id_unidad_duracion . '">' . $item->nombre_unidad_duracion_ingles . '</option>';
				}
			}
		}
		return $unidadesDuracion;
	}

	/**
	 * Consulta las unidades de temperatura por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idUnidadTemperatura
	 * @return string
	 */
	public function comboUnidadesTemperaturaPorIdioma($idioma, $idUnidadTemperatura = null){
		$qUnidadesTemperatura = new UnidadesTemperaturaLogicaNegocio();
		$unidadesTemperatura = "";

		$query = "estado_unidad_temperatura = 'activo'";

		$combo = $qUnidadesTemperatura->buscarLista($query);

		foreach ($combo as $item){
			if ($idUnidadTemperatura == $item['id_unidad_temperatura']){
				if ($idioma == 'SPA'){
					$unidadesTemperatura .= '<option value="' . $item->id_unidad_temperatura . '" selected>' . $item->nombre_unidad_temperatura . '</option>';
				}else{
					$unidadesTemperatura .= '<option value="' . $item->id_unidad_temperatura . '" selected>' . $item->nombre_unidad_temperatura_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$unidadesTemperatura .= '<option value="' . $item->id_unidad_temperatura . '">' . $item->nombre_unidad_temperatura . '</option>';
				}else{
					$unidadesTemperatura .= '<option value="' . $item->id_unidad_temperatura . '">' . $item->nombre_unidad_temperatura_ingles . '</option>';
				}
			}
		}
		return $unidadesTemperatura;
	}

	/**
	 * Consulta las concentraciones de tratamiento por idioma y construye el combo
	 *
	 * @param Integer $idioma,
	 *        	$idConcentracionTratamiento
	 * @return string
	 */
	public function comboConcentracionesTratamientoPorIdioma($idioma, $idConcentracionTratamiento = null){
		$qConcentracionesTratamiento = new ConcentracionesTratamientoLogicaNegocio();
		$concentracionesTratamiento = "";

		$query = "estado_concentracion_tratamiento = 'activo'";

		$combo = $qConcentracionesTratamiento->buscarLista($query);

		foreach ($combo as $item){
			if ($idConcentracionTratamiento == $item['id_concentracion_tratamiento']){
				if ($idioma == 'SPA'){
					$concentracionesTratamiento .= '<option value="' . $item->id_concentracion_tratamiento . '" selected>' . $item->nombre_concentracion_tratamiento . '</option>';
				}else{
					$concentracionesTratamiento .= '<option value="' . $item->id_concentracion_tratamiento . '" selected>' . $item->nombre_concentracion_tratamiento_ingles . '</option>';
				}
			}else{
				if ($idioma == 'SPA'){
					$concentracionesTratamiento .= '<option value="' . $item->id_concentracion_tratamiento . '">' . $item->nombre_concentracion_tratamiento . '</option>';
				}else{
					$concentracionesTratamiento .= '<option value="' . $item->id_concentracion_tratamiento . '">' . $item->nombre_concentracion_tratamiento_ingles . '</option>';
				}
			}
		}
		return $concentracionesTratamiento;
	}

	/**
	 * Consulta los idiomas y construye el combo
	 */
	public function comboIdiomas($idIdioma = null){
		$qIdiomas = new IdiomasLogicaNegocio();

		$idiomas = "";

		$combo = $qIdiomas->buscarTodo();

		foreach ($combo as $item){
			if ($idIdioma == $item->id_idioma){
				$idiomas .= '<option value="' . $item->id_idioma . '" data-codigoIdioma="' . $item->codigo_idioma . '" selected>' . $item->nombre_idioma . '</option>';
			}else{
				$idiomas .= '<option value="' . $item->id_idioma . '" data-codigoIdioma="' . $item->codigo_idioma . '" >' . $item->nombre_idioma . '</option>';
			}
		}
		return $idiomas;
	}

	/**
	 * Función que permite insertar un elemento a un array bidemencional en una posición antes a una llave dada
	 */
	function insertarElementoArrayPosicion(&$array, $position, $insert){
		if (is_int($position)){
			array_splice($array, $position, 0, $insert);
		}else{
			$pos = array_search($position, array_keys($array));
			$array = array_merge(array_slice($array, 0, $pos), $insert, array_slice($array, $pos));
		}
	}
	
	/**
     * Combo de áreas de la Coordinación de Registros
     * @param type $respuesta
     * @return string
     */
    public function comboAreasRegistroInsumosAgropecuarios($opcion=null)
    {
        $combo = "";
        if ($opcion == "IAP"){
            $combo .= '<option value="IAP" selected="selected">Registro de insumos agrícolas</option>';
            $combo .= '<option value="IAV">Registro de insumos pecuarios</option>';
            $combo .= '<option value="IAF">Registro de insumos fertilizantes</option>';
            $combo .= '<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>';
        } else if ($opcion == "IAV"){
            $combo .= '<option value="IAP">Registro de insumos agrícolas</option>';
            $combo .= '<option value="IAV" selected="selected">Registro de insumos pecuarios</option>';
            $combo .= '<option value="IAF">Registro de insumos fertilizantes</option>';
            $combo .= '<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>';
        } else if ($opcion == "IAF"){
            $combo .= '<option value="IAP">Registro de insumos agrícolas</option>';
            $combo .= '<option value="IAV">Registro de insumos pecuarios</option>';
            $combo .= '<option value="IAF" selected="selected">Registro de insumos fertilizantes</option>';
            $combo .= '<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>';
        } else if ($opcion == "IAPA"){
            $combo .= '<option value="IAP">Registro de insumos agrícolas</option>';
            $combo .= '<option value="IAV">Registro de insumos pecuarios</option>';
            $combo .= '<option value="IAF">Registro de insumos fertilizantes</option>';
            $combo .= '<option value="IAPA" selected="selected">Registro de insumos para plantas de autoconsumo</option>';
        } else{
            $combo .= '<option value="IAP">Registro de insumos agrícolas</option>';
            $combo .= '<option value="IAV" selected="selected">Registro de insumos pecuarios</option>';
            $combo .= '<option value="IAF">Registro de insumos fertilizantes</option>';
            $combo .= '<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>';
        }
        
        return $combo;
    }
	
	/**
     * Combo de áreas de la Coordinación de Registros
     * @param type $respuesta
     * @return string
     */
    public function comboAreasRegistroInsumosPecuarios($opcion=null)
    {
        $combo = "";
        $combo = '<option value="IAV" selected="selected">Registro de insumos pecuarios</option>';
        
        return $combo;
    }
    
    /**
     * Combo de proceso de revisión de solicitudes de Dossier Pecuario
     * @param type $respuesta
     * @return string
     */
    public function comboProcesosRevisionDossierPecuario($opcion)
    {
        $combo = "";
        if ($opcion == "Registro"){
            $combo .= '<option value="Registro" selected="selected">Registro</option>';
            $combo .= '<option value="Modificacion">Modificación</option>';
            $combo .= '<option value="Reevaluacion">Reevaluación</option>';
        } else if ($opcion == "Modificacion"){
            $combo .= '<option value="Registro">Registro</option>';
            $combo .= '<option value="Modificacion" selected="selected">Modificación</option>';
            $combo .= '<option value="Reevaluacion">Reevaluación</option>';
        } else if ($opcion == "Reevaluacion"){
            $combo .= '<option value="Registro">Registro</option>';
            $combo .= '<option value="Modificacion">Modificación</option>';
            $combo .= '<option value="Reevaluacion" selected="selected">Reevaluación</option>';
        } else{
            $combo .= '<option value="Registro" selected="selected">Registro</option>';
            $combo .= '<option value="Modificacion">Modificación</option>';
            $combo .= '<option value="Reevaluacion">Reevaluación</option>';
        }
        return $combo;
    }
	
	/**
     * Consulta los códigos complementarios y construye el combo
     */
    public function comboCodigoComplementario($idCodComplementario = null)
    {
        $codigoComp = new CodigoComplementarioLogicaNegocio();
        
        $combo = "";
        $query = "estado_codigo_complementario = 'Activo'";
        
        $codigo = $codigoComp->buscarLista($query);
        
        foreach ($codigo as $item)
        {
            if ($idCodComplementario == $item->id_cod_complementario)
            {
                $combo .= '<option value="' . $item->id_cod_complementario . '" selected>' . $item->codigo_complementario . '</option>';
            } else
            {
                $combo .= '<option value="' . $item->id_cod_complementario . '">' . $item->codigo_complementario . '</option>';
            }
        }
        return $combo;
    }
    
    /**
     * Consulta los códigos suplementarios y construye el combo
     */
    public function comboCodigoSuplementario($idCodSuplementario = null)
    {
        $codigoSup = new CodigoSuplementarioLogicaNegocio();
        
        $combo = "";
        $query = "estado_codigo_suplementario = 'Activo'";
        
        $codigo = $codigoSup->buscarLista($query);
        
        foreach ($codigo as $item)
        {
            if ($idCodSuplementario == $item->id_cod_suplementario)
            {
                $combo .= '<option value="' . $item->id_cod_suplementario . '" selected>' . $item->codigo_suplementario . '</option>';
            } else
            {
                $combo .= '<option value="' . $item->id_cod_suplementario . '">' . $item->codigo_suplementario . '</option>';
            }
        }
        return $combo;
    }
	
	/**
     * Consulta las unidades de medida y construye el combo
     *
     * @param Integer $idUnidadMedida
     * @return string
     */
    public function comboUnidadMedidaXId($idUnidadMedida = null)
    {
        $unidadMedida = new UnidadesMedidasLogicaNegocio();
        $unidades = "";
        
        $query="estado ='Activo' and
                tipo_unidad not in ('tiempo') and
                clasificacion not in ('sercop') 
                order by nombre ASC";
        
        $combo = $unidadMedida->buscarLista($query);
        
        foreach ($combo as $item)
        {
            if ($idUnidadMedida == $item['id_unidad_medida']) {
                $unidades .= '<option value="' . $item->id_unidad_medida . '" data-codigo="' . $item->codigo . '" selected>' . $item->nombre . '</option>';
            } else {
                $unidades .= '<option value="' . $item->id_unidad_medida . '" data-codigo="' . $item->codigo . '" >' . $item->nombre . '</option>';
            }
        }
        return $unidades;
    }
    
    /**
     * Consulta las unidades de medida y construye el combo
     *
     * @param Integer $idUnidadMedida
     * @return string
     */
    public function comboUnidadTiempoXId($idUnidadMedida = null)
    {
        $unidadMedida = new UnidadesMedidasLogicaNegocio();
        $unidades = "";
        
        $query="estado ='Activo' and
                tipo_unidad in ('tiempo')
                order by nombre ASC";
        
        $combo = $unidadMedida->buscarLista($query);
        
        foreach ($combo as $item)
        {
            if ($idUnidadMedida == $item['id_unidad_medida']) {
                $unidades .= '<option value="' . $item->id_unidad_medida . '" data-codigo="' . $item->codigo . '" selected>' . $item->nombre . '</option>';
            } else {
                $unidades .= '<option value="' . $item->id_unidad_medida . '" data-codigo="' . $item->codigo . '" >' . $item->nombre . '</option>';
            }
        }
        return $unidades;
    }
	
	 /**
     * Consulta los grupos de productos y construye el combo
     *
     * @param Integer $idGrupoProducto
     * @return string
     */
    public function comboGrupoProducto($idGrupoProducto = null)
    {
        $grupoProducto = new GrupoProductoLogicaNegocio();
        
        $grupo = '';
        $parametros = " estado_grupo_producto='Activo'";
        
        $grupoProducto = $grupoProducto->buscarLista($parametros);
        
        foreach ($grupoProducto as $item) {
            if ($idGrupoProducto == $item['id_grupo_producto']) {
                $grupo .= '<option value="' . $item->id_grupo_producto . '" data-codigo="' . $item->codigo_grupo_producto . '" selected>' . $item->grupo_producto . '</option>';
            } else {
                $grupo .= '<option value="' . $item->id_grupo_producto . '" data-codigo="' . $item->codigo_grupo_producto . '">' . $item->grupo_producto . '</option>';
            }
        }
        return $grupo;
    }
    
    /**
     * Consulta lo subtipos de productos por grupo de producto y construye el combo
     *
     * @param Integer $idLocalizacion
     * @return string
     */
    public function comboSubtipoProductoXGrupo()
    {
        $subtipoProducto = new SubtipoProductosLogicaNegocio();
        
        $idGrupoProducto = $_POST['idGrupo'];
        $idSubtipoProducto = $_POST['idSubtipo'];
        
        $subtipo = '<option value="">Seleccione....</option>';
        $parametros = " id_grupo_producto='$idGrupoProducto'";
        
        $subtipoProducto = $subtipoProducto->buscarLista($parametros);
        
        foreach ($subtipoProducto as $item) {
            if ($idSubtipoProducto == $item['id_subtipo_producto']) {
                $subtipo .= '<option value="' . $item->id_subtipo_producto . '" data-codigo="' . $item->codificacion_subtipo_producto . '" selected>' . $item->nombre . '</option>';
            } else {
                $subtipo .= '<option value="' . $item->id_subtipo_producto . '" data-codigo="' . $item->codificacion_subtipo_producto . '">' . $item->nombre . '</option>';
            }
        }
        echo $subtipo;
        exit();
    }
    
    /**
     * Consulta los grupos de productos y construye el combo
     */
    public function comboClasificacion($idClasificacion = null)
    {
        $clasificacion = new ClasificacionLogicaNegocio();
        
        $query = "estado_clasificacion = 'Activo'";
        $grupo = "";
        
        $combo = $clasificacion->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idClasificacion == $item->id_clasificacion) {
                $grupo .= '<option value="' . $item->id_clasificacion . '" selected>' . $item->clasificacion . '</option>';
            } else {
                $grupo .= '<option value="' . $item->id_clasificacion . '">' . $item->clasificacion . '</option>';
            }
        }
        return $grupo;
    }
	
	/**
     * Consulta los tipos de componente y construye el combo
     */
    public function comboTipoComponente($idArea, $idTipoComponente = null)
    {
        $tipoComponente = new TipoComponenteLogicaNegocio();
        
        $query = "estado_tipo_componente = 'Activo' and id_area = '$idArea' ORDER BY tipo_componente ASC";
        $componente = "";
        
        $combo = $tipoComponente->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idTipoComponente == $item->id_tipo_componente) {
                $componente .= '<option value="' . $item->id_tipo_componente . '" selected>' . $item->tipo_componente . '</option>';
            } else {
                $componente .= '<option value="' . $item->id_tipo_componente . '">' . $item->tipo_componente . '</option>';
            }
        }
        return $componente;
    }
	
	/**
     * Consulta los ingredientes activos por área y construye el combo
     */
    public function comboIngredienteActivo($idArea, $idIngrediente = null)
    {
        $ingredienteActivo = new IngredienteActivoInocuidadLogicaNegocio();
        
        $query = "estado_ingrediente_activo = 'Activo' and id_area = '$idArea' ORDER BY ingrediente_activo ASC";
        $ingrediente = "";
        
        $combo = $ingredienteActivo->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idIngrediente == $item->id_ingrediente_activo) {
                $ingrediente .= '<option value="' . $item->id_ingrediente_activo . '" selected>' . $item->ingrediente_activo . '</option>';
            } else {
                $ingrediente .= '<option value="' . $item->id_ingrediente_activo . '">' . $item->ingrediente_activo . '</option>';
            }
        }
        return $ingrediente;
    }
	
	/**
     * Consulta las formas física, farmacéutica, cosmética y construye el combo
     */
    public function comboFormaFisFarCos($idArea, $idFormaFisFarCos = null)
    {
        $formaFormulacion = new FormulacionLogicaNegocio();
        
        $query = "id_area = '$idArea' and estado_formulacion = 'Activo' ORDER BY formulacion ASC";
        $forma = "";
        
        $combo = $formaFormulacion->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idFormaFisFarCos == $item->id_formulacion) {
                $forma .= '<option value="' . $item->id_formulacion . '" selected>' . $item->formulacion . '</option>';
            } else {
                $forma .= '<option value="' . $item->id_formulacion . '">' . $item->formulacion . '</option>';
            }
        }
        return $forma;
    }
	
	/**
     * Consulta los usos por área y construye el combo
     */
    public function comboUsos($idArea, $idUso = null)
    {
        $usos = new UsosLogicaNegocio();
        
        $query = "estado_uso = 'Activo' and id_area = '$idArea' ORDER BY nombre_uso ASC";
        $uso = "";
        
        $combo = $usos->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idUso == $item->id_uso) {
                $uso .= '<option value="' . $item->id_uso . '" data-nombre_comun="'.$item->nombre_comun_uso.'" selected>' . $item->nombre_uso . '</option>';
            } else {
                $uso .= '<option value="' . $item->id_uso . '" data-nombre_comun="'.$item->nombre_comun_uso.'">' . $item->nombre_uso . '</option>';
            }
        }
        return $uso;
    }
	
	/**
     * Consulta las especie y construye el combo
     */
    public function comboEspecies($idEspecie = null)
    {
        $especies = new EspeciesLogicaNegocio();
        
        $query = "estado = 'activo' ORDER BY nombre ASC";
        $especie = "";
        
        $combo = $especies->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idEspecie == $item->id_especies) {
                $especie .= '<option value="' . $item->id_especies . '" selected>' . $item->nombre . '</option>';
            } else {
                $especie .= '<option value="' . $item->id_especies . '">' . $item->nombre . '</option>';
            }
        }
        return $especie;
    }
	
	/**
     * Consulta las especie y construye el combo
     */
    public function comboEspeciesXCodigo($codigo, $idEspecie = null)
    {
        $especies = new EspeciesLogicaNegocio();
        
        $query = "estado = 'activo' and codigo in (".$codigo.") ORDER BY nombre ASC";
        $especie = "'<option>Seleccione....</option>';";
        
        $combo = $especies->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idEspecie == $item->id_especies) {
                $especie .= '<option value="' . $item->id_especies . '" selected>' . $item->nombre . '</option>';
            } else {
                $especie .= '<option value="' . $item->id_especies . '">' . $item->nombre . '</option>';
            }
        }
        return $especie;
    }
	
	/**
     * Consulta los efectos biológicos no deseados y construye el combo
     */
    public function comboEfectosBiologicos($idEfecto = null)
    {
        $efectoBiologico = new EfectosBiologicosLogicaNegocio();
        
        $query = "estado_efecto_biologico = 'Activo' ORDER BY efecto_biologico ASC";
        $efecto = "";
        
        $combo = $efectoBiologico->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idEfecto == $item->id_efecto_biologico) {
                $efecto .= '<option value="' . $item->id_efecto_biologico . '" selected>' . $item->efecto_biologico . '</option>';
            } else {
                $efecto .= '<option value="' . $item->id_efecto_biologico . '">' . $item->efecto_biologico . '</option>';
            }
        }
        return $efecto;
    }
    
    /**
     * Consulta los productos de consumo y construye el combo
     */
    public function comboProductosConsumo($idProducto = null)
    {
        $productoConsumo = new ProductosConsumiblesLogicaNegocio();
        
        $query = "estado_producto_consumible = 'Activo' ORDER BY producto_consumible ASC";
        $efecto = "";
        
        $combo = $productoConsumo->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idProducto == $item->id_producto_consumible) {
                $efecto .= '<option value="' . $item->id_producto_consumible . '" selected>' . $item->producto_consumible . '</option>';
            } else {
                $efecto .= '<option value="' . $item->id_producto_consumible . '">' . $item->producto_consumible . '</option>';
            }
        }
        return $efecto;
    }
    
    /**
     * Consulta las declaraciones de venta y construye el combo
     */
    public function comboDeclaracionVenta($idDeclaracion = null)
    {
        $declaracionVenta = new DeclaracionVentaLogicaNegocio();
        
        $query = "estado_declaracion_venta = 'Activo' ORDER BY declaracion_venta ASC";
        $efecto = "";
        
        $combo = $declaracionVenta->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idDeclaracion == $item->id_declaracion_venta) {
                $efecto .= '<option value="' . $item->id_declaracion_venta . '" selected>' . $item->declaracion_venta . '</option>';
            } else {
                $efecto .= '<option value="' . $item->id_declaracion_venta . '">' . $item->declaracion_venta . '</option>';
            }
        }
        return $efecto;
    }
    
    /**
     * Consulta los documentos anexos por grupo de producto, fase de revisión y construye el combo
     */
    public function comboDocumentosAnexosPecuarios($idGrupo, $procesoRevision, $idDocumento=null)
    {
        $documentosAnexos = new AnexosPecuariosLogicaNegocio();
        
        $query = "id_grupo_producto = $idGrupo and proceso_revision = '$procesoRevision' and estado_anexo_pecuario = 'Activo' ORDER BY anexo_pecuario ASC";
        $efecto = "";
        
        $combo = $documentosAnexos->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idDocumento == $item->id_anexo_pecuario) {
                $efecto .= '<option value="' . $item->id_anexo_pecuario . '" selected>' . $item->anexo_pecuario . '</option>';
            } else {
                $efecto .= '<option value="' . $item->id_anexo_pecuario . '">' . $item->anexo_pecuario . '</option>';
            }
        }
        return $efecto;
    }
    
    /**
     * Consulta las vías de administración y construye el combo
     */
    public function comboViaAdministracion($idViaAdministracion = null)
    {
        $viasAdministracion = new ViaAdministracionLogicaNegocio();
        
        $query = "estado_via_administracion = 'Activo' ORDER BY via_administracion ASC";
        $viaAdmin = "";
        
        $combo = $viasAdministracion->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idViaAdministracion == $item->id_via_administracion) {
                $viaAdmin .= '<option value="' . $item->id_via_administracion . '" selected>' . $item->via_administracion . '</option>';
            } else {
                $viaAdmin .= '<option value="' . $item->id_via_administracion . '">' . $item->via_administracion . '</option>';
            }
        }
        return $viaAdmin;
    }
    
    /**
     * Consulta los efectos biológicos no deseados y construye el combo
     */
    public function comboCategoriaToxicologica($idArea, $idCategoria = null)
    {
        $categoria = new CategoriaToxicologicaLogicaNegocio();
        
        $query = "estado_categoria_toxicologica = 'Activo' and id_area='$idArea' ORDER BY categoria_toxicologica ASC";
        $categoriaToxicologica = "";
        
        $combo = $categoria->buscarLista($query);
        
        foreach ($combo as $item) {
            if ($idCategoria == $item->id_categoria_toxicologica) {
                $categoriaToxicologica .= '<option value="' . $item->id_categoria_toxicologica . '" selected>' . $item->categoria_toxicologica . '</option>';
            } else {
                $categoriaToxicologica .= '<option value="' . $item->id_categoria_toxicologica . '">' . $item->categoria_toxicologica . '</option>';
            }
        }
        return $categoriaToxicologica;
    }
    
    /**
     * Recibe la información del correo, destinatarios y documentos adjuntos para el envío 
     * de correos electrónicos automáticos
     */
    public function crearCorreoElectronico($arrayCorreo, $arrayDestinatario, $arrayAdjuntos=null)
    {
        $respuesta = true;
        
        $lNegocioCorreos = new CorreosLogicaNegocio();
        $lNegocioDestinatarios = new DestinatariosLogicaNegocio();
        $lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
        
        $mailsDestino = array_unique($arrayDestinatario);
        
        if (count($mailsDestino) > 0){
            //Guarda el correo
            $idCorreo = $lNegocioCorreos->guardar($arrayCorreo);
            
            //Guardar destinatarios
            foreach ($mailsDestino as $destino){
                $arrayParametros = array(
                    'id_correo' => $idCorreo,
                    'destinatario_correo' => $destino);
                
                $lNegocioDestinatarios->guardar($arrayParametros);
            }
            
            //Guardar documentos anexos
            if(isset($arrayAdjuntos)){
                $documentos = array_unique($arrayAdjuntos);
                
                if (count($documentos) > 0){
                    foreach ($documentos as $rutaDocumento){
                        $arrayParametros = array(
                            'id_correo' => $idCorreo,
                            'ruta_documento_adjunto' => $rutaDocumento);
                        
                        $lNegocioDocumentosAdjuntos->guardar($arrayParametros);
                    }
                }
            }
        }
        
        return $respuesta;
    }
	
	/**
     * Combo de género
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboGenero($opcion = null)
    {
        $combo = "";
        if ($opcion == "Masculino") {
            $combo .= '<option value="Masculino" selected="selected">Masculino</option>';
            $combo .= '<option value="Femenino">Femenino/option>';
        } else if ($opcion == "Femenino") {
            $combo .= '<option value="Masculino" >Masculino</option>';
            $combo .= '<option value="Femenino" selected="selected">Femenino</option>';
        } else {
            $combo .= '<option value="Masculino">Masculino</option>';
            $combo .= '<option value="Femenino">Femenino</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de género
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboPositivoNegativo($opcion = null)
    {
        $combo = "";
        if ($opcion == "Positivo") {
            $combo .= '<option value="Positivo" selected="selected">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo/option>';
        } else if ($opcion == "Negativo") {
            $combo .= '<option value="Positivo" >Positivo</option>';
            $combo .= '<option value="Negativo" selected="selected">Negativo</option>';
        } else {
            $combo .= '<option value="Positivo">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        }
        
        return $combo;
    }
	
	
	/*
	* Funcion de acceso a web services 
	*/
	public function consultarWebService($arrayParametros){
		$mensaje = array();
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Ha ocurrido un error!';

		$webServices = new ServiciosGubernamentales();

		$identificadorConsulta = $arrayParametros['numero'];
		$tipoIdentificacion = $arrayParametros['clasificacion'];
		$tipoAcceso = false;
		switch ($tipoIdentificacion) {

			case 'Cédula':
				$tipoAcceso = true;
				// $rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl';
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW03_Consultar_Ciudadano?wsdl';
			break;

			case 'Natural':
			case 'Juridica':
			case 'Publica':
				$tipoAcceso = true;
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/SRI/BSGSW01_Consultar_RucSRI?wsdl';
			break;

			case "Pasaporte":
				$tipoAcceso = true;
			break;

			case "Refugiado":
				$tipoAcceso = true;
			break;

			case 'Senecyt':
				$tipoAcceso = true;
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/SENESCYT/BSGSW04_Consultar_Titulos?wsdl';
			break;

			case 'AntMatriculaLicencia':
				$tipoAcceso = true;
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/ANT/BSGSW01_Consultar_MatriculaLic?wsdl';
			break;
		}
		try{

			try{

				if ($tipoAcceso){
					$resultadoAutenticacion = '';
					try{

						// $resultadoAutenticacion = $webServicesAutenticacion->consultarWebServicesAutenticacion('https://pru.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl', 'https://pru.bsg.gob.ec/sw/STI/BSGSW08_Acceder_BSG?wsdl');
						$resultadoAutenticacion = $webServices->consultarWebServicesAutenticacion($rutaWebervices);
					}catch (\Exception $e){
						echo $e;
					}

					$cabeceraSeguridad = $webServices->crearCabeceraSeguridadWebServices($resultadoAutenticacion);

					switch ($tipoIdentificacion) {

						case 'Cédula':
							$resultadoConsulta = $webServices->consultarWebServicesCedula($cabeceraSeguridad, $identificadorConsulta);
							$mensaje['valores'] = $resultadoConsulta;
						break;

						case 'Natural':
						case 'Juridica':
						case 'Publica':
							// Tipos de funciones del SRI: obtenerCompleto, obtenerDatos, obtenerSimple
							$resultadoConsulta = $webServices->consultarWebServicesRUC($cabeceraSeguridad, $identificadorConsulta, 'obtenerCompleto');
							$mensaje['valores'] = $resultadoConsulta;
						break;

						case "Pasaporte":

							if (strlen($identificadorConsulta) >= 7 && strlen($identificadorConsulta) <= 13)
								$resultadoConsulta = array(
									CodigoError => '000',
									Error => 'NO ERROR');
							else
								$resultadoConsulta = array(
									CodigoError => '001',
									Error => 'PASAPORTE DEBE TENER ENTRE 7 A 13 DIGITOS');

						break;

						case "Refugiado":

							if (strlen($identificadorConsulta) == 10)
								$resultadoConsulta = array(
									CodigoError => '000',
									Error => 'NO ERROR');
							else
								$resultadoConsulta = array(
									CodigoError => '001',
									Error => 'DOCUMENTO REFUGIADO DEBE TENER 10 DIGITOS');
						break;

						case "Senecyt":
							$titulos = array();
							$resultadoConsulta = $webServices->consultarWebServicesSenecyt($cabeceraSeguridad, $identificadorConsulta);

							if (count($resultadoConsulta['datos']) != 0){
								foreach ($resultadoConsulta['datos'] as $dato){
									if (count($resultadoConsulta['datos']) == 1){
										$titulos[] = array(
											titulo => $dato);
									}else{
										$titulos[] = $dato;
									}
								}
							}
							$mensaje['valores'] = $titulos;
						break;

						case "AntMatriculaLicencia":
							$resultadoConsulta = $webServices->consultarWebServicesANT($cabeceraSeguridad, $identificadorConsulta);
							$mensaje['valores'] = $resultadoConsulta;
						break;
					}
					if ($resultadoConsulta['CodigoError'] == '000'){
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = $resultadoConsulta['Error'];
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = $resultadoConsulta['Error'];
					}
				}
				return $mensaje;
			}catch (\Exception $ex){
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Error al ejecutar sentencia';
				return $mensaje;
				// $conexion->ejecutarLogsTryCatch($ex);
			}
		}catch (\Exception $ex){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error de conexión a la base de datos';
			return $mensaje;
			// $conexion->ejecutarLogsTryCatch($ex);
		}
	}
	
	/**
	 * Consulta los subtipos de producto por tipo de producto y construye el combo
	 *
	 * @param Integer $idTipoProducto
	 * @return string
	 */
	public function comboCatalogoTipoProductos(){
	    $tipoProducto = new TipoProductosLogicaNegocio();
	    $tipos = '<option value="">Seleccionar....</option>';
	    
	    $combo = $tipoProducto->buscarTodo();
	    
	    foreach ($combo as $item){
	        $tipos .= '<option value="' . $item->id_tipo_producto . '">' . $item->nombre . '</option>';
	    }
	    
	    return $tipos;
	}
	
	/**
	 * Consulta las unidades de medida por codigo y por idioma y construye el combo
	 *
	 * @param Integer Id,
	 *        	$idUnidadMedida
	 * @return string
	 */
	public function comboUnidadesMedidaCfePorCodigoPorIdioma($codigoUnidadMedida, $idioma){
	    $unidadMedida = new UnidadesMedidasCfeLogicaNegocio();
	    $unidades = "";
	    
	    $query = "estado='Activo' order by nombre ASC";
	    
	    $combo = $unidadMedida->buscarLista($query);
	    
	    foreach ($combo as $item){
	        if ($codigoUnidadMedida == $item['codigo']){
	            if ($idioma == 'SPA'){
	                $unidades .= '<option value="' . $item->id_unidad_medida . '" selected>' . $item->nombre . '</option>';
	            }else{
	                $unidades .= '<option value="' . $item->id_unidad_medida . '" selected>' . $item->nombre_ingles . '</option>';
	            }
	        }
	    }
	    return $unidades;
	}
	
	/**
     * Consulta las cultivos productos agricolas y construye el combo
     *
     * @param Integer $idCultivo
     * @param String $idArea
     * @return string
     */
    public function comboCultivos($idArea, $idCultivo = null){
        $cultivo = new CultivosLogicaNegocio();
        $cultivos = "";
        $combo = $cultivo->buscarCultivosCatalogo($idArea);

        foreach ($combo as $item){
            if ($idCultivo == $item['id_cultivo']){
                $cultivos .= '<option value="' . $item->id_cultivo . '" data-nombre_comun="'.$item->nombre_comun_cultivo.'" selected>' . $item->nombre_cientifico_cultivo . '</option>';
            }else{
                $cultivos .= '<option value="' . $item->id_cultivo . '" data-nombre_comun="'.$item->nombre_comun_cultivo.'">' . $item->nombre_cientifico_cultivo . '</option>';
            }
        }
        return $cultivos;
    }
	
	/**
	 * Consulta el estado del producto y construye el combo
	 *
	 * @param Integer $idProducto
	 * @return string
	 */
	public function comboEstadoProducto($estado = null){
	    
	    $estadoProducto = ""; 
	    $arrayEstados = array('1' => 'Vigente'
                    	        , '2' => 'Suspendido'
                    	        , '3' => 'Caducado'
                    	        , '4' => 'Cancelado'
                    	    );
	    
	    foreach ($arrayEstados as $llaveEstado => $valorEstado){
	        
	        if ($estado == $llaveEstado) {
	            $estadoProducto .= '<option value="' . $llaveEstado . '" selected>' . $valorEstado . '</option>';
	        } else {
	            $estadoProducto .= '<option value="' . $llaveEstado . '" >' . $valorEstado . '</option>';
	        }
	        
	    }
	    
	    return $estadoProducto;

	}
	
	/**
	 * Consulta las partidas arancelarias y construye el combo
	 *
	 * @param Integer $idProducto
	 * @return string
	 */
	public function comboPartidasArancelariasPorProducto($idProducto){
	    
	    $partidaArancelaria = new PartidasArancelariasLogicaNegocio();
	    $partidas = "";
	    
	    $query = "estado = 'activo' ORDER BY partida_arancelaria ASC";
	    
	    $combo = $partidaArancelaria->buscarLista($query);
	    
	    foreach ($combo as $item){
	        if ($idProducto == $item['id_producto']){
	            $partidas .= '<option value="' . $item->id_partida_arancelaria . '" data-codigoproducto="' . $item->codigo_producto . '">' . $item->partida_arancelaria . '</option>';
	        }
	    }
	    return $partidas;
	}
}