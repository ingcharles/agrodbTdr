<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$identificador = $_SESSION['usuario'];
$conexion = new Conexion();
$cv = new ControladorVacaciones();

$idArea=$cv->devolverJefeImnediato($conexion, $identificador);
$areaPadre= $idArea['idarea'];

	switch ($areaPadre) {
		case 'DDAT03':
		$opcion='<select name="zona" id="zona" style="width:70%">
					<option value="DDAT03">Zona 1 – Sucumbios</option>
				</select>';
		break;
		case 'DDAT04':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT04">Zona 2 – Pichincha</option>
			</select>';
		break;
		case 'DDAT07':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT07">Zona 3 – Tungurahua</option>
			</select>';
		break;
		case 'DDAT10':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT10">Zona 4 – Santo Domingo</option>
			</select>';
		break;
		case 'DDAT12':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT12">Zona 5 – Guayas</option>
			</select>';
		break;
		case 'DDAT14':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT14">Zona 6 -  Cañar</option>
			</select>';
		break;
		case 'DDAT16':
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="DDAT16">Zona 7 – El Oro</option>
			</select>';
			break;
		default:
		$opcion='<select name="zona" id="zona" style="width:70%">
			<option value="">Seleccione...</option>
			<option value="DGATH">Planta Central</option>
			<option value="DDAT03">Zona 1 – Sucumbios</option>
			<option value="DDAT04">Zona 2 – Pichincha</option>
			<option value="DDAT07">Zona 3 – Tungurahua</option>
			<option value="DDAT10">Zona 4 – Santo Domingo</option>
			<option value="DDAT12">Zona 5 – Guayas</option>
			<option value="DDAT14">Zona 6 – Cañar</option>
			<option value="DDAT16">Zona 7 – El Oro</option>
			</select>';
		break;
	}

?>
<header>
	<h1>Reportes</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="investigacionAccidentesIncidentes"  
		action="aplicaciones/investigacionAccidentesIncidentes/reportesSso.php" target="_blank" method="post">
		<input type="hidden" name="identificadorRegistro" id="identificadorRegistro"
				value="<?php echo $identificador; ?>" />	

			<?php if($identificador != ''){ 

				echo '
				<table class="filtro" style="width: 400px;" >
				<tbody>
				<tr>
				<th colspan="2">Buscar Solicitud:</th>
				</tr>
				
				<tr>
				<td>Zona:</td>
				<td > 
				'.$opcion.'
				</td>
				</tr>
				
				<tr>
				<td>Identificación:</td>
				<td> <input id="identificador" type="text" name="identificador" maxlength="10" value="'.  $_POST['identificador'] .'" style=" width:70%">	</td>
				</tr>
				
				<tr>
				<td>Estado:</td>
				<td><select name="estadoSolicitud" id="estadoSolicitud" style="width:70%">
					<option value="">Seleccione...</option>
					<option value="creado">Creado</option>
					<option value="Aprobado">Aprobado</option>
					<option value="Subsanar">Subsanar</option>
					<option value="Pendiente de Aprobación">Pendiente de Aprobación</option>
					<option value="Cerrado">Cerrado</option>
				</select></td>
				</tr>
				
				<tr>
				<td>Fecha Desde:</td>
				<td><input type="text" name="fechaDesde" id="fechaDesde" style=" width:70%" value="'. $_POST['fechaDesde'] .'" readonly/></td>
				</tr>
				
				<tr>
				<td>Fecha Hasta:</td>
				<td><input type="text" name="fechaHasta" id="fechaHasta" style=" width:70%" value="'. $_POST['fechaHasta'] .'" readonly/></td>
				</tr>
				
				<tr>
				<td colspan="2"> <button id="buscar" onclick="verificarDatos(); return false;">Buscar</button>	</td>
				</tr>
				<tr>
					<td id="mensajeError" colspan="2"></td>
				</tr>
				</tbody>
				</table> ';
				 }?>
		</form>
	</nav>
</header>
<div id="tabla"></div>
<script>

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Presione "Buscar" para generar el reporte.</div>');

		var estadoSolicitud= <?php echo json_encode($_POST['estadoSolicitud']); ?>;
		if(estadoSolicitud != '')
		cargarValorDefecto("estadoSolicitud","<?php echo $_POST['estadoSolicitud'];?>");

		var zona= <?php echo json_encode($_POST['zona']); ?>;
		if(zona != '')
		cargarValorDefecto("zona","<?php echo $_POST['zona'];?>");
		
	});

	function verificarDatos(){
		var identificadorRegistro = $('#identificadorRegistro').val();
        var identificador = $('#identificador').val();
        var zona = $('#zona').val();
        var estadoSolicitud = $('#estadoSolicitud').val();
        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        $('#mensajeError').html('')
           var consulta = $.ajax({
              type:'POST',
              url:'aplicaciones/investigacionAccidentesIncidentes/verificarRegistros.php',
              data:{identificador:identificador, zona:zona,estadoSolicitud:estadoSolicitud,fechaDesde:fechaDesde, fechaHasta:fechaHasta, opcion:2 },
              dataType:'JSON'
           });
           consulta.done(function(data){
           if(data.error!==undefined){
            	  $('#mensajeError').html(data.error).addClass("alerta");
              }else{         
            	  if($('#identificador').val().length<10 && $('#zona').val().length==0 && $('#estadoSolicitud').val().length==0 && $('#fechaDesde').val().length==0 && $('#fechaHasta').val().length==0)
      			{
      			event.preventDefault();
      			$('#mensajeError').html('<span class="alerta">Debe llenar la información de por lo menos un campo..!');
      			return false;
      		}
      		else if($('#identificador').val().length==10 || $('#zona').val().length!=0 || $('#estadoSolicitud').val().length!=0 || $('#fechaDesde').val().length!=0 || $('#fechaHasta').val().length!=0)
      			{
      			$( "#filtrar" ).submit();
      			 return true;
      			}
              }
           });
           consulta.fail(function(){
              $('#mensajeError').html('Por favor revise el formato de la información ingresada..!').addClass("alerta");
              return false;
           });  
	}	
	
$("#fechaHasta").datepicker({
	      changeMonth: true,
	      changeYear: true
   });
    
$("#fechaDesde").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      onSelect: function(dateText, inst) {
	  	  	$('#fechaHasta').datepicker('option', 'minDate', $("#fechaDesde" ).val()); 
	      }
  });

</script>
