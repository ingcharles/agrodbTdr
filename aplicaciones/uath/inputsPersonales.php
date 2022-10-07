<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();

$identificadorEmpleado = htmlspecialchars ($_POST['identificadorEmpleado'],ENT_NOQUOTES,'UTF-8');

$res = $cc->listaFichaEmpleados($conexion, $identificadorEmpleado,"","");
//$res2=$cc->verificarFechaContrato($conexion,$identificadorEmpleado);
$res3=$cc->obtenerContratosXUsuario($conexion, $identificadorEmpleado, '', '');
			
if(pg_num_rows($res) != 0){
	$fichaEmpleado = pg_fetch_array($res);
    echo '<div data-linea="2"><label>Apellidos</label>
		      <input type="text" id="apellidoEmpleado" name="apellidoEmpleado" readonly="readonly" value="'.$fichaEmpleado['apellido'].'"/>
          </div>
	     <div data-linea="3">
	  	      <label>Nombres</label>
	  	      <input type="text" id="nombreEmpleado" name="nombreEmpleado" readonly="readonly" value="'.$fichaEmpleado['nombre'].'"/>
	      </div>';
   
  		if(pg_num_rows($res3) != 0){
   			  echo '<br/><div>
   					<table id="ContratosUsuario">
   						<tr>
   							<th>Item</th>
   							<th>Presupuesto</th>
   							<th>Tipo de Contrato</th>
   							<th>Fecha</th>
   							<th>Tiempo</th>
   						</tr>
   						<tbody>
						';
      	      $totaldias=0;
   	          $totalmeses=0;
   	          $totalanios=0;
   	          while ($filaContrato = pg_fetch_assoc($res3)){
   	          	$FechaInicio = DateTime::createFromFormat('Y-m-d', $filaContrato['fecha_inicio']);
   	          	if($filaContrato['fecha_fin']!='')
   	          	$FechaFin = DateTime::createFromFormat('Y-m-d', $filaContrato['fecha_fin']);
   	          	else
   	          	$FechaFin= new DateTime('now');
   	          	$FechaInicio->setTime(0, 0, 0);
   	          	$FechaFin->setTime(0, 0, 0);
   	          	$fecha=$FechaFin->diff($FechaInicio);
   	          		echo '<tr>
   	          				<td><input type="checkbox" id="'.$filaContrato['id_datos_contrato'].'" name="estadoFecha[]" value="'.$filaContrato['id_datos_contrato'].'"';
   	    							if($filaContrato['contabilizar_dias']=='t')
   	          								echo 'checked="true"';
   	          		echo' data-anios="'.$fecha->y.'" data-meses="'.$fecha->m.'" data-dias="'.$fecha->d.'" /></td>
   	          				<td>'.$filaContrato['presupuesto'].'</td>
   	          				<td>'.$filaContrato['regimen laboral'].' -'.$filaContrato['tipo_contrato'].'</td>
   	          				<td>Desde: '.$filaContrato['fecha_inicio'].' Hasta:'.$filaContrato['fecha_fin'].'</td>';
   	          			
   	          		if(($filaContrato['contabilizar_dias']=='t')){
   	          			$totaldias=$totaldias+$fecha->d;
   	          			$totalmeses=$totalmeses+$fecha->m;
   	          			$totalanios=$totalanios+$fecha->y;
   	          		}
   	          		echo '<td>'.$fecha->y.' años, '.$fecha->m.' meses, '.$fecha->d.' días </td></tr>';
   	          	
   	          }
   	          echo '</tbody></table><button id="actualizarForm" type="button">Actualizar</button></div><div id="resultadoActualizar"></div>';
   	          while($totaldias>30){
   	          	$totalmeses+=1;
   	          	$totaldias-=30;
   	          }
   	          while($totalmeses>=12){
   	          	$totalanios+=1;
   	          	$totalmeses-=12;
   	          }
   	          echo '<div data-linea="4">
   						<label>Tiempo de trabajo contabilizado del empleado.</label>
   						<input type="text" id="mensaje_tiempo" name="mensaje_tiempo" readonly="readonly" value="Años= '.$totalanios.', Meses= '.$totalmeses.', Dias= '.$totaldias.'"/>
   						<input type="hidden" id="dias" name="dias" readonly="readonly" value="'.$totaldias.'"/>
						<input type="hidden" id="meses" name="meses" readonly="readonly" value="'.$totalmeses.'"/>
						<input type="hidden" id="anios" name="anios" readonly="readonly" value="'.$totalanios.'"/>
      				</div>';
        }
        else{
	          echo '<span class="alerta"><div data-linea="5"><label>No existen contratos del funcionario.</label></div>';
  }
}	 
else{
	echo '<div data-linea="6">
	<label><span class="alerta">No existe registro con esos datos.</span></label></div>';
	
}

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();			
	});

	 $("#actualizarForm").click(function (event) {
  			$("#datosContrato").attr('data-opcion', 'contabilizarFechas');
		    ejecutarJson($("#datosContrato")); //Se ejecuta ajax, busqueda de sub tipo producto
		    $("#buscarIdentificador").click();    
	});
		 
</script>

<style type="text/css">
#ContratosUsuario td {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
}

#ContratosUsuario th {
	font-size: 1em;
	border: 1px solid rgba(0, 0, 0, .1);
	padding: 3px 7px 2px 7px;
	background-color: rgba(0, 0, 0, .1)
}

</style>