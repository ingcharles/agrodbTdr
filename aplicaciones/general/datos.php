<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$qPerfil = $ca->obtenerPerfilFuncionario($conexion, $_SESSION['usuario']);
	$perfil = pg_fetch_assoc($qPerfil);
	
	if($perfil['codificacion_perfil'] == 'PFL_USUAR_INT'){
	
?>	
	
	<header>
		<h1>Mis datos</h1>
	</header>
	<article id="0" class="item" data-rutaAplicacion="uath"	data-opcion="datosUsuario" draggable="true" ondragstart="drag(event)" data-destino="detalleItem">
		<div></div>
		<span>Cambio de clave y nombre de usuario</span>
		<aside></aside>
	</article>
	<article id="1" class="item" data-rutaAplicacion="uath" data-opcion="listadoContrato" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Contrato</span>
		<aside></aside>
	</article>
	<article id="2" class="item" data-rutaAplicacion="uath"	data-opcion="datosPersonales" data-destino="detalleItem" draggable="true" ondragstart="drag(event)" >
		<div></div>
		<span>Personales</span>
		<aside></aside>
	</article>
	<article id="3" class="item" data-rutaAplicacion="uath"	data-opcion="listaFamiliares" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Familiares y contacto</span>
		<aside></aside>
	</article>
	<!-- article id="4" class="item" data-rutaAplicacion="general"	data-opcion="discapacidad" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Discapacidad y enfermedades catastróficas</span>
		<aside></aside>
	</article-->
	<article id="5" class="item" data-rutaAplicacion="uath"	data-opcion="banco" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Cuenta bancaria</span>
		<aside></aside>
	</article>
	<article id="6" class="item" data-rutaAplicacion="uath"	data-opcion="listaDatosAcademicos" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Académicos</span>
		<aside></aside>
	</article>
	
	<article id="7" class="item" data-rutaAplicacion="uath"	data-opcion="listaDatosCapacitacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Capacitaciones</span>
		<aside></aside>
	</article>
	
	<article id="8" class="item" data-rutaAplicacion="uath"	data-opcion="listaExperienciaLaboral" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Experiencia laboral</span>
		<aside></aside>
	</article>
	
	<!--article id="9" class="item" data-rutaAplicacion="uath"	data-opcion="nuevoMensualizacionDecimo" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Mensualización décimos</span>
		<aside></aside>
	</article-->
	
	<article id="10" class="item" data-rutaAplicacion="uath" ondragstart="drag(event)" data-opcion="resultadoEvaluacionDesempenio" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Evaluación de Desempeño Anual</span>
		<aside></aside>
	</article>
	
	<article id="11" class="item" data-rutaAplicacion="uath" ondragstart="drag(event)" data-opcion="generarHojaDeVida" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Hoja de Vida en PDF</span>
		<aside></aside>
	</article>

	<article id="12" class="item" data-rutaAplicacion="uath"	data-opcion="listarRolPagos" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Rol de Pagos</span>
		<aside></aside>
	</article>
	
	<article id="13" class="item" data-rutaAplicacion="investigacionAccidentesIncidentes"	data-opcion="visualizarCitaMedica" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Cita Médica IESS(Accidentes laborales)</span>
		<aside></aside>
	</article>

	<article id="14" class="item" data-rutaAplicacion="uath"	data-opcion="listaDatosHistorialLaboralIess" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Historial Laboral -- IESS</span>
		<aside></aside>
	</article>
	
	<article id="15" class="item" data-rutaAplicacion="uath"	data-opcion="listaDatosDeclaracionJuramentada" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Declaración Juramentada</span>
		<aside></aside>
	</article>	
	
<?php 

	}else if ($perfil['codificacion_perfil'] == 'PFL_USUAR_CIV_PR'){

?>
    <article id="2" class="item" data-rutaAplicacion="uath"	data-opcion="datosPersonales" data-destino="detalleItem" draggable="true" ondragstart="drag(event)" >
    <div></div>
    <span>Personales</span>
    <aside></aside>
    </article>
    
<?php 
	}
?>

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("programas");  
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una ficha para editarla.</div>');
	});
</script>
