<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	
	$cr = new ControladorRegistroOperador();
	$cu = new ControladorUsuarios();
	$cc = new ControladorCatalogos();
	$ca = new ControladorAplicaciones();
	
	$latitud = '795204.2670016843';
	$longitud = '9849275.758170985';
	
	$clasificacion = htmlspecialchars (trim($_POST['clasificacion']),ENT_NOQUOTES,'UTF-8');	
	$ruc = htmlspecialchars (trim($_POST['numero']),ENT_NOQUOTES,'UTF-8');
	$razon = htmlspecialchars (trim($_POST['razon']),ENT_NOQUOTES,'UTF-8');
	$nombreLegal = htmlspecialchars (trim($_POST['nombreLegal']),ENT_NOQUOTES,'UTF-8');
	$apellidoLegal = htmlspecialchars (trim($_POST['apellidoLegal']),ENT_NOQUOTES,'UTF-8');
	$direccion = htmlspecialchars (trim($_POST['direccion']),ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars (trim($_POST['provincia']),ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars (trim($_POST['canton']),ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars (trim($_POST['parroquia']),ENT_NOQUOTES,'UTF-8');
	$telefono = htmlspecialchars (trim($_POST['telefono1']),ENT_NOQUOTES,'UTF-8');
	$celular = htmlspecialchars (trim($_POST['celular1']),ENT_NOQUOTES,'UTF-8');	
	$nombreSitio = htmlspecialchars (trim($_POST['nombreSitio']),ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars (trim($_POST['nombreArea']),ENT_NOQUOTES,'UTF-8');
	
	$provincia = $cc->obtenerNombreLocalizacion($conexion, $idProvincia);
	$canton = $cc->obtenerNombreLocalizacion($conexion, $idCanton);
	$parroquia = $cc->obtenerNombreLocalizacion($conexion, $idParroquia);
	
	$operador = $cr->buscarOperador($conexion, $ruc);
	$usuario = $cu->verificarUsuario($conexion, $ruc);
	
	if( pg_num_rows($usuario) == 0){
		//Crear Cuenta de usuario
		$cu->crearUsuario($conexion, $ruc, md5($ruc));
		
		//Activacion de la cuenta del nuevo operador
		$cu->activarCuenta($conexion, $ruc, md5($ruc));
		
		//Asignar perfil a usuario externo
		$qPerfilExterno = $cu->buscarPerfilUsuario($conexion, $ruc, 'Usuario externo');
		
		if(pg_num_rows($qPerfilExterno) == 0){
			$cu->crearPerfilUsuario($conexion,  $ruc, 'Usuario externo');
		}
	}
		
	if( pg_num_rows($operador) == 0){
		//Crear nuevo operador
		$cr -> guardarNuevoOperador($conexion, $clasificacion, $ruc, $razon, $nombreLegal, $apellidoLegal, pg_fetch_result($provincia, 0, 'nombre'),pg_fetch_result($canton, 0, 'nombre'),pg_fetch_result($parroquia, 0, 'nombre'), $direccion, $telefono, $celular);
		
		//Asignar perfil a usuario operador
		$qPerfilOperador = $cu->buscarPerfilUsuario($conexion, $ruc, 'Operadores');
		
		if(pg_num_rows($qPerfilOperador)==0){
			$cu->crearPerfilUsuario($conexion,  $ruc, 'Operadores');
		}
		
		//Asignacion de la aplicacion de "registro de operador" al operador
		$qAplicacion = $ca->obtenerIdAplicacion($conexion, 'PRG_REGISTROOPER');
		$aplicacion = pg_fetch_result($qAplicacion, 0, 'id_aplicacion');
		
		$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacion, $ruc);
		
		if (pg_num_rows($aplicacionOperadorRegistro) == 0){
			$ca->guardarAplicacionPerfil($conexion, $aplicacion, $ruc, 0, 'notificaciones');
		}
		
		//Generar código de sitio

		$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, pg_fetch_result($provincia, 0, 'nombre'), $ruc);
		$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
		
		//Crear sitio para operador
		
		if($telefono == '' && $celular != ''){
			$telefono = $celular;
		}
		
		$qIdSitio = $cr->guardarNuevoSitio($conexion, $nombreSitio, pg_fetch_result($provincia, 0, 'nombre'),
				pg_fetch_result($canton, 0, 'nombre'), pg_fetch_result($parroquia, 0, 'nombre'), $direccion, '', 0, $ruc, $telefono,
				$latitud, $longitud, $secuencialSitio, '0','17',substr(pg_fetch_result($provincia, 0, 'codigo_vue'),1));
		
		//Crear un área genérica
		//SA y SV
		
		$qCodigoArea = $cc->buscarAreaOperadorXNombre($conexion, 'Lugar de producción');
		$codigoArea = pg_fetch_assoc($qCodigoArea);
		
		$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $ruc, $codigoArea['codigo'],pg_fetch_result($provincia, 0, 'nombre'));
		$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
		
		$areas = $cr -> guardarNuevaArea($conexion, $nombreArea, 'Lugar de producción', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'), $codigoArea['codigo'], $secuencial);
		
		//IAP e IAV
		/*$areas = $cr -> guardarNuevaArea($conexion, 'Bodega', 'Bodega', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'));
		 $areas = $cr -> guardarNuevaArea($conexion, 'Lugar de formulación', 'Lugar de formulación', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'));
		$areas = $cr -> guardarNuevaArea($conexion, 'Lugar de fabricación', 'Lugar de fabricación', 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'));*/
		
	}	
	
	echo '<input type="hidden" id="' . $ruc . '" data-rutaAplicacion="registroOperador" data-opcion="nuevaOperacion" data-destino="detalleItem"/>';
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("input:hidden"),null,false);
	});	
</script>