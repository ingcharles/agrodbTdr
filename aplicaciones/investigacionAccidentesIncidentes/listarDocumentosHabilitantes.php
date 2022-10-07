<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../aplicaciones/investigacionAccidentesIncidentes/modelos/salidas.php';

$identificador = $_SESSION['usuario'];
$conexion = new Conexion();

$fechaBusque='';
$solicitud='';
$identificadorBusque='';
$estadoSolicitud='';

if ($_POST['fechaBusque']!=''){
	$fechaBusque=$_POST['fechaBusque'];
	$identificadorBusque='';
}

if($_POST['solicitud']!='' ){
	$solicitud=$_POST['solicitud'];
	$identificadorBusque='';
}
if($_POST['estadoSolicitud']!='')
{

	$estadoSolicitud=$_POST['estadoSolicitud'];

}
if($_POST['identificador']!='')
{
	$identificadorBusque=$_POST['identificador'];
}
?>
<header>
	<h1>Accidente</h1>
	<nav>
		<form id="filtrar"
			data-rutaAplicacion="investigacionAccidentesIncidentes"
			data-opcion="listarDocumentosHabilitantes" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion"
				value="<?php echo $_POST['opcion']; ?>" />

			<?php if($identificador != ''){ 
				filtroBusqueda($_POST['identificador'],$_POST['solicitud'],$_POST['fechaBusque'],3);
			 }?>
		</form>
	</nav>
</header>
<table id="descri">
	<thead>
		<tr>
			<th># Solicitud</th>
			<th>Nombre</th>
			<th>Estado</th>
		</tr>
	</thead>
	<?php 
	$cai = new ControladorAccidentesIndicentes();
	$cv = new ControladorVacaciones();
	
	$idArea=$cv->devolverJefeImnediato($conexion, $identificador);
	//	print_r($idArea);
	$areaPadre= $idArea['idarea'];
	if($idArea['idarea'] == 'DGATH' )
		$consulta=$cai->listarDatosAccidente($conexion,'', $estadoSolicitud,$id_area_padre=NULL,
				$tipo_Sso=NULL, 3,$solicitud, $identificadorBusque,$fechaBusque,'');
	else {
		
		$consulta=$cai->listarDatosAccidente($conexion,'','',$areaPadre,$tipo_Sso=NULL, 3,$solicitud, $identificadorBusque,$fechaBusque,'');
	}

	$contador = 0;

	while($cita = pg_fetch_assoc($consulta)){
		$consultaServidor=$valores_datos=pg_fetch_array($cai->buscarDatosServidor($conexion,$cita['identificador_accidentado']));
		
		$ruta='visualizarDocumentosHabilitantes';
		if($identificador == $cita['identificador_registro'] ){
		if($cita['estado']=='creado')$ruta='nuevoDocumentosHabilitantes';
		if($cita['estado']=='Subsanar')$ruta='modificarDocumentosHabilitantes';
		}
			
		if($areaPadre == 'DGATH')
			if($cita['estado']=='Pendiente de Aprobar' or $cita['estado']=='Subsanado')$ruta='aprobarDocumentosHabilitantes';
		
		echo '<tr 	id="'.$cita['cod_datos_accidente'].'"
		class="item"
		data-rutaAplicacion="investigacionAccidentesIncidentes"
		data-opcion="'.$ruta.'"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem"
		>
		<td>'.$cita['cod_datos_accidente'].'</td>
		<td style="white-space:nowrap;"><b>'.$consultaServidor['nombre'].' '.$consultaServidor['apellido'].'</b></td>
		<td>'.$cita['estado'].'</td>
		</tr>';
	}
	?>

</table>

<script>

	$(document).ready(function(){
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial"></div>');
		$("#fechaBusque").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		var estadoSolicitud= <?php echo json_encode($_POST['estadoSolicitud']); ?>;
		if(estadoSolicitud != '')
		cargarValorDefecto("estadoSolicitud","<?php echo $_POST['estadoSolicitud'];?>");
		$('#solicitud').numeric();
	});


	$("#filtrar").submit(function(event){
		event.preventDefault();
		
		if($('#identificador').val().length<10 && $('#solicitud').val().length==0 && $('#estadoSolicitud').val().length==0 && $('#fechaBusque').val().length==0)
		{		
			$('#descri').remove();
			$('#mensajeError').html('<span class="alerta">Debe llenar la información de por lo menos un campo..!');
			
		}

		else if($('#identificador').val().length==10 || $('#solicitud').val().length!=0 || $('#estadoSolicitud').val().length!=0 || $('#fechaBusque').val().length!=0)
		{		
			var identificadorRegistro = $('#identificadorRegistro').val();
	        var identificador = $('#identificador').val();
	        var solicitud = $('#solicitud').val();
	        var estadoSolicitud = $('#estadoSolicitud').val();
	        var fechaBusque = $('#fechaBusque').val();
	        var idArea = <?php echo json_encode($areaPadre);?>;

	        var consulta = $.ajax({
	              type:'POST',
	              url:'aplicaciones/investigacionAccidentesIncidentes/verificarRegistros.php',
	              data:{
		              idArea:idArea, 
		              identificador:identificador, 
		              solicitud:solicitud,
		              estadoSolicitud:estadoSolicitud,
		              fechaBusque:fechaBusque, 
		              identificadorRegistro:identificadorRegistro, 
		              opcion:3,
		              nivel:3 },
	              dataType:'JSON'
	           });
	           consulta.done(function(data){
	        	  
	           if(data.error!==undefined){
	            	  $('#mensajeError').html(data.error).addClass("alerta");
	            	  $('#descri').remove();
	                  return false;
	              }else{              
	            	  
	                 abrir($('#filtrar'),event, false);
	                 return true;
	              }
	           });
	           consulta.fail(function(){
	        	  $('#descri').remove();
	              $('#mensajeError').html('Por favor revise el formato de la información ingresada..!').addClass("alerta");
	              return false;
	           });
		}
		
	});

</script>
