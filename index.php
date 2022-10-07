<?php 
//header('Location: ../agrodbOut.html');
								
require_once 'clases/Conexion.php';
require_once 'clases/ControladorUsuarios.php';
require_once 'clases/ControladorAuditoria.php';
require_once 'clases/ControladorChat.php';			  

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorAuditoria();
$cc = new ControladorChat();

//INICIO
// include_once('CAS.php');
// require_once 'config.php';

// phpCAS::setDebug();
// // initialize phpCAS
// phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
// // no SSL vlidation for the CAS server
// phpCAS::setNoCasServerValidation();
// // force CAS authentication
// phpCAS::forceAuthentication();
// // logout if desired
// if (isset($_REQUEST['logout'])) {
//     phpCAS::logoutWithUrl("index.php");
// }

// echo '<input name="identificadorSSO" id="identificadorSSO" type="hidden" value= "'.phpCAS::getUser().'">';

//FIN

$conexion->verificarSesion();

/*
*				   
*if (!isset($_SESSION['usuario'])){
*	header('Location: ingreso.php');
*}
*/

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Panel de control GUIA - Pruebas</title>
<!-- link
	href='http://fonts.googleapis.com/css?family=Text+Me+One|Poiret+One|Open+Sans'
	rel='stylesheet' type='text/css'-->
	<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb_papel.css' >
	<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb.css'>
	<link rel='stylesheet' href='aplicaciones/general/estilos/jquery-ui-1.10.2.custom.css'>
	<link rel='stylesheet' href='aplicaciones/mvc/resource/fontawesome-free-5.1.0/css/all.css'>
	<link rel='stylesheet' href='aplicaciones/mvc/resource/estilos/bootstrap.min.css'>
</head>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-97784251-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-97784251-2');
</script>

<body>
 <!--body onload="set_interval()" onmousemove="reset_interval()"  onmousedown="reset_interval(),capturarClick(2)" onmouseover="reset_interval()" onkeydown="reset_interval()" onwheel="reset_interval()"-->

<header id="barraGeneral" >
	<!-- header class="acerca">
		<p>Sistema Integrado</p>
		<p>Agrocalidad 2013</p>
		<p>Gestión tecnológica</p>
	</header-->
	
	<!--footer id="barraGeneral"-->
		<nav id="navegacionPrincipal">
			<a href="#" id="0" data-rutaAplicacion="general" class="_inicio" data-nombreAplicacion="Inicio" data-destino="ventanaAplicacion">Inicio</a>
			<a href="#" id="3" data-rutaaplicacion="ayuda" class="_ayuda" data-nombreaplicacion="Ayuda" data-destino="ventanaAplicacion">Ayuda</a>
			<a href="#" id="salir" class="_salir" >Salir</a>
		</nav>
				
			<?php 
				$res = $cu->obtenerNombresUsuario($conexion, $_SESSION['usuario']);
			    //$res = $cu->obtenerNombresUsuario($conexion, phpCAS::getUser());
				$fila = pg_fetch_assoc($res);		
				
				//--------------------AUDITORIA
				
				$ultimoAcceso = pg_fetch_result($ca -> buscarUltimoAcceso($conexion, $_SESSION['usuario']), 0, 'ultimo_acceso');
				
				//----------------------------
			?>		
			<div id="datosCuenta">
				<div id="indexMensajes">
					<div id="notificacionMensaje" ></div>
				</div>
				<div id="contenedorDatosCuenta">	   
					<div id="nombre"><?php echo $fila['nombre_usuario'];?> </div>
					<div id="ultimoAcceso">Últimos acceso: <?php echo date('j/n/Y (G:i)',strtotime($ultimoAcceso));?></div>
				</div>
				<div><img class="foto" height="40px" src="<?php echo $fila['fotografia'];?>"/></div>
			</div>
	<!--/footer-->
	</header>
	<section id="ventanaAplicacion"></section>
	<section id="areaNotificacion"></section>
	
<header class="contenedorChat">

	<div class="mainChat" id="mainChat">
	<input type="hidden" id="usIdC" value="<?php echo $_SESSION['usuario']?>"/>
	
		<div id="chatCabecera" class="cabeceraChat">
			<a id="chatCerrar" class="cerrarChat">x</a >
			<div class="contenedorBotonesChat">
				<button id="abrirContactos" class="botonChatActivo"></button>
				<button id="abrirEnviarSolicitudesitudes"></button>
				<button id="abrirSolicitudes"></button>
				<button id="abrirGrupoChat"></button>				
				<label class="switch" >
				  <input type="checkbox" id="chatSonido" value="true">
				  <span class="slider" onclick="configurarSonido(this)"></span>
				</label>			
			</div>			
		</div>
		<div class="cuerpoChat">
			<div class="contactosCuerpoChat">
			<div Class="seccionChat">Contactos</div>
				<div class="usuarioChat">
					<ul id="listaContactosChat">
							<?php
							$res = $cc->listarContactos ( $conexion, $_SESSION ['usuario'] );
							while ( $fila = pg_fetch_assoc ( $res ) ) {
								if($fila ['fotografia']!=''){
									$foto=$fila ['fotografia'];
								}else{ 
									$foto='aplicaciones/agroChat/img/user2.png';
								}
								$cadenaNombre = str_replace(' ', '', $fila['nombres']);
								echo '
									<li id="'.$cadenaNombre.'">
									<div class="contenedorContactoChat" id="ctc_' . $fila ['contacto']. '" name="'.$fila ['nombres'].'" onClick="abrirChat('."'".'ctc_' . $fila ['contacto']."'".',null)">
										<div class="fotoUsuarioChat"><img src="' . $foto. '" class="imgUsuarioChat"> </div>
										<div class="nombreUsuarioChat">' . $fila ['nombres'] . '</div>
										<span class="fechaMensajes" style="display:none;">'.$fila ['fecha'].'</span>
										<span class="fechaUltimoMensaje"  style="display:none;">'.$fila ['fecha_mensaje'].'</span>
									</div>
									</li>';
							}							
						  ?>
					</ul>
				</div>
				<div Class="seccionChat">Grupos</div>
				<div class="usuarioChat">
					<ul id="listaGruposChat">
						<?php
						$res = $cc->grupoPerteneciente($conexion,  $_SESSION ['usuario']);
						while ( $fila = pg_fetch_assoc ( $res ) ) {
							if($fila ['fotografia']!=''){
								$foto=$fila ['fotografia'];
							}else{
								$foto='aplicaciones/agroChat/img/user2.png';
							}
							$cadenaNombre = str_replace(' ', '', $fila['nombre_grupo']);
							echo '
								<li id="'.$cadenaNombre.'">
								<div class="contenedorContactoChat" id="ctcg_' . $fila ['id_grupo']. '" name="'.$fila ['nombre_grupo'].'" onClick="abrirChat('."'".'ctcg_' . $fila ['id_grupo']."'".','."'grp'".')">
									<div class="fotoUsuarioChat"><img src="' . $foto. '" class="imgUsuarioChat"> </div>
									<div class="nombreUsuarioChat">' . $fila ['nombre_grupo'] . '</div>
									<span class="fechaMensajes" style="display:none;">'.$fila ['fecha'].'</span>
									<span class="fechaUltimoMensaje"  style="display:none;">'.$fila ['fecha_mensaje'].'</span>
								</div>
								</li>';
						}							
					   ?>
					</ul>
				</div>
			</div>
			<div class="solicitudesCuerpoChat">
				<div class="busquedaContactoNuevo">
					<input type="text" id="nombreContactoNuevo"  placeholder="Buscar..." style="width: 230px;">
				</div>
				<div class="cuerpoListaContactosNuevo">					
					<ul id="listaContactosNuevoChat">					
					</ul>					
				</div>			
			</div>			
			<div class="solicitudesRecibidasCuerpoChat">				
				<div class="cuerpoListaContactosNuevo">
					<ul id="listaSolicitudesChat">
						<?php
						$res = $cc->obtenerSolicitudesRecibidas( $conexion, $_SESSION ['usuario'] );
							while ( $solicitud= pg_fetch_assoc ( $res ) ) {
								if($solicitud['fotografia']!=''){
									$foto=$solicitud['fotografia'];
							 	} else{ 
									$foto='aplicaciones/agroChat/img/user2.png';
							 	}
							 	
							 	$pie='<a class="enviarSolicitudLink"  title="Aceptar solicitud" onclick="aceptarSolicitud('."'".$solicitud['identificador_usuario']."'".')">Aceptar Solicitud</a> <a class="cancelarSolicitudLink" title="Rechazar solicitud" onclick="rechazarSolicitud('."'".$solicitud['identificador_usuario']."'".')">Rechazar</a>';
							 	
							 	$contenido='<li id="listn_'.$solicitud['identificador_usuario'].'">
										<div class="contenedorContactoNuevo" id="csn_'. $solicitud['identificador_usuario'].'" >'.
										'<div class="fotoUsuarioChatNuevo" ><img src="'.$foto.'" class="imgUsuarioChatNuevo"> </div>'.
										'<div class="contenedorUsuarioDatosNuevo">'.
										'<div class="nombreUsuarioNuevo" >'.$solicitud['nombres'].'</div>'.
										'<div class="contenedorEnviarSolicitud" id="sru_'.$solicitud['identificador_usuario'].'" >'.$pie.'</div>'.
										'</div>
								</div></li> ';
								
							 	echo $contenido;
								
							}							
						?>
					</ul>					
				</div>			
			</div>			
			<div class="gruposCuerpoChat">
				<div class="contenedorGrupoNuevo">
					<div class="contenedorAccionesGrupoChat">						
						<a id="nuevoGrupoChat" class="accionesGrupoChat" onclick="javascript:return mostrarGrupoChat(event)">Nuevo grupo</a>
						<a id="cancelarGrupoChat" class="accionesGrupoChat" onclick="cancelarGrupoChat()">Cancelar</a>
						<a id="crearGrupoChat" class="accionesGrupoChat" onclick="guardarGrupo()">Crear</a>
						<a id="actualizarGrupoChat" class="accionesGrupoChat" onclick="actualizarGrupo()">Actualizar</a>
						<a id="eliminarGrupoChat" class="accionesGrupoChat" onclick="actualizarGrupo()">Eliminar</a>
						<span id="grupoChatEditar"></span>
					</div>
					<div class="contenedorTextosGrupoChat">
						<input type="text" id="nombreGrupoChat" class="camposGrupoChat"  placeholder="Nombre del Grupo..." style="width: 230px;" >
						<input type="text" id="buscarContactoGrupoChat" class="camposGrupoChat"  placeholder="Buscar..." style="width: 230px;">
					</div>
					<div class="contenedorContactosAgregadosGrupoChat">
					</div>
					
					<div class="cuerpoListaContactosGrupoChat">
						<ul id="listaContactosGrupoChat">						
						</ul>
					</div>
				</div>
				<div class="contenedorGrupoCreados">
					<div Class="seccionChat">Grupos creados</div>					
					<ul id="listaGruposCreados">
        				<?php 
        				$res = $cc->listarGruposCreados($conexion,  $_SESSION ['usuario']);
        				while($fila=pg_fetch_assoc($res)){
        				    echo '<li class="itemLista" id="lsgrp_'.$fila['id_grupo'].'" onmouseleave="cancelarEliminarGrupoAutomatico('."'lsgrp_".$fila['id_grupo']."'".','."'".$fila['nombre_grupo']."'".')">	
									  <div class="contenedorContactoNuevo" id="ctgrp_'.$fila['id_grupo'].'" onclick="verMiembrosGrupo('."'ctgrp_".$fila['id_grupo']."'".')">
										<div class="fotoUsuarioChatNuevo">
											<img src=" aplicaciones/agroChat/img/user2.png" class="imgUsuarioChatNuevo"> 
										</div>
										<div class="contenedorUsuarioDatosNuevo">
											<div class="nombreGrupos">'.$fila['nombre_grupo'].'</div>
											<div class="contenedorEnviarSolicitud">
												<a class="accionesGrupoChatExistente" id="grp_'.$fila['id_grupo'].'" onclick="javascript:return mostrarGrupoChat(event,'."'ctgrp_".$fila['id_grupo']."'".')">Editar</a>
											</div>
										</div>
									  </div>
        					      </li>';
        				 }
        				?>	
					</ul>			
					<div class="seccionChat">Grupos a los que pertences</div>
					<ul id="listaGruposMiembro">
        				<?php 
        				$res = $cc->grupoPerteneciente($conexion,  $_SESSION ['usuario'], 'si');
        				while($fila=pg_fetch_assoc($res)){
        				    echo '<li class="itemLista" id="lsgrpm_'.$fila['id_grupo'].'" onmouseleave="cancelarSalirGrupoAutomatico('."'lsgrpm_".$fila['id_grupo']."','".$fila['nombre_grupo']."'".')">	
									  <div class="contenedorContactoNuevo" id="ctgrpm_'.$fila['id_grupo'].'" onclick="verMiembrosGrupo('."'ctgrpm_".$fila['id_grupo']."'".')">
										<div class="fotoUsuarioChatNuevo">
											<img src=" aplicaciones/agroChat/img/user2.png" class="imgUsuarioChatNuevo"> 
										</div>
										<div class="contenedorUsuarioDatosNuevo">
											<div class="nombreGrupos">'.$fila['nombre_grupo'].'</div>
											<div class="contenedorEnviarSolicitud">
												<a class="accionesGrupoChatExistente" id="grpm_'.$fila['id_grupo'].'" onclick="javascript:return prepararSalirGrupo(event,'."'grpm_".$fila['id_grupo'] ."'" .')">Salir del grupo</a>
		   
							 
											</div>
										</div>
									  </div>
								  </li>';        				    
        				 }
        				?>	
					</ul>
				</div>						
			</div>			
		</div>
		<div id="chatPie" class="pieChat"></div>
		</div>
	</header>
	
	<footer class="contenedorChatVentanas" id="contenedorChatVentanas"></footer>
	
<script src="aplicaciones/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
<script src="aplicaciones/general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
<script src="aplicaciones/general/funciones/agrdbfunc.js" type="text/javascript"></script>
<script src="aplicaciones/general/funciones/jquery.inputmask.js" type="text/javascript"></script>
<script src="aplicaciones/general/funciones/utm.js" type="text/javascript"></script>
<script src="aplicaciones/general/funciones/jquery.numeric.js"></script>
<script src="aplicaciones/general/funciones/html2canvas.js"></script>
<script src="aplicaciones/general/funciones/jquery.plugin.html2canvas.js"></script>
<script type="text/javascript" src="aplicaciones/general/funciones/agrdbChat.js"></script>
<script src="aplicaciones/mvc/resource/js/fSelect.js" type="text/javascript"></script>
<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
<script type="text/javascript" src="aplicaciones/mvc/resource/js/bootstrap.min.js"></script>
<script type="text/javascript">
	//history.go(1);
	//variables generales para definir ancho de la barra de opciones
	var anchoBarraOpcionesAplicacion=200;
	var altoBarraGeneral=0;
	var contactosChat = new Array();
	var fechaContactosChat = new Array();				 
	var usuarioIdentificadorChat = <?php echo json_encode($_SESSION['usuario'])?>;
	var tipoEmpleado = <?php echo json_encode($_SESSION['tipoEmpleado'])?>;
	var auxChat = <?php echo json_encode($_SESSION['auxChat'])?>;
	
	$("document").ready(function(){
		/*$("head").append("<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb_papel.css' >");		
		$("head").append("<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb.css'>");		*/
		abrir($("#navegacionPrincipal a").first(),null,true);
		//$("#cargando").delay("slow").fadeOut();
		/*$(document).bind("contextmenu",function(e){
		    return false;
		});*/
		
		//iniciarChat(); 

	      
	});

	function iniciarChat(){
		if (tipoEmpleado != "" && auxChat == "SI"){			
			$("#indexMensajes").show();
			cargarUsuarios();
			cargarSonidos();
			listarSolicitudes();
			setInterval(cargarSolicitudes, 9000);
			setInterval(cargarContactos, 8000);
			setInterval(obtenerNuevosMensajes, 5000);
			setInterval(cargarGrupos, 9000);
			cortarCadena(22,'.nombreUsuarioChat');
			cortarCadena(20,'.nombreUsuarioNuevo');
			comprobarMensajes();
			notificacionGeneral();
			if (document.queryCommandEnabled('enableObjectResizing')) {
			      document.execCommand('enableObjectResizing', false, 'false');
			    }
				$("#nombreGrupoChat").val("");
		}
	}
	$("#salir").click(function(e){
		 document.location.href = 'salir.php';
		//document.location.href = 'index.php?logout';
	});
	
	var timer = 0;	
	function set_interval() {			   
	  timer = setInterval("auto_logout()", 950000);	  
	}
	function reset_interval() {
		if (timer != 0) {
			clearInterval(timer);
		   timer = 0;		   
		   timer = setInterval("auto_logout()", 950000);
	  }
	}
	function auto_logout() {
		//console.log("entro a auto_logout");
		alert("Ha estado mucho tiempo en inactividad, por su seguridad se cerrará la sesión");
		setTimeout("location.href = 'salir.php';",100);
	  //window.location.href="salir.php";		
	}	
</script>
</body>
</html>

