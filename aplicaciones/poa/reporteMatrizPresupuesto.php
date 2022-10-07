<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
require_once '../../clases/ControladorAreas.php';

$fecha = getdate();

$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();
$ca = new ControladorAreas();

$datosProceso = $cpoa1->obtenerSubprocesoXProceso($conexion, $fecha['year']);

while($fila = pg_fetch_assoc($datosProceso)){
	$subprocesos[]= array(id_proceso=>$fila['id_proceso'], descripcion_proceso=>$fila['descripcion_proceso'], id_subproceso=>$fila['id_subproceso'], descripcion_subproceso=>$fila['descripcion_subproceso']);
}

/*$datosProceso2= $cpoa1->obtenerComponenteXProceso($conexion, $fecha['year']);
while($fila = pg_fetch_assoc($datosProceso2)){
	$objetivoComponente[]= array(id_proceso=>$fila['id_proceso'], descripcion_proceso=>$fila['descripcion'], codigo=>$fila['codigo'], descripcion_componente=>$fila['componente']);
}*/

$datosProceso3= $cpoa1->obtenerActividadesXSubProceso($conexion, $fecha['year']);
while($fila = pg_fetch_assoc($datosProceso3)){
	$actividadesSubProceso[]= array(id_subproceso=>$fila['id_subproceso'], descripcion_subproceso=>$fila['sub_proceso'], descripcion_actividad=>$fila['descripcion_actividad']);
}
?>

<header>
	<h1>Reporte matriz presupuesto</h1>
	<nav>
		<form id="filtrar" action="aplicaciones/poa/reporteMPresupuestoFiltrados.php" target="_blank" method="post">
		<input type="hidden" id="coordinador" name="coordinador" value="0" />
		
			<table class="filtro">
				<tr>
					<th>Que contenga</th>
					<td>Dirección:</td>
					<td><select name="areaDireccion" id="areaDireccion">
							<option value="Todos">Todos</option>
							<?php
							//$res= $cpoa1->listarArea($conexion);
							$res = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Gestión','Unidad')", "(1,3,4,5)");

							while($fila = pg_fetch_assoc($res)){
					echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] .'</option>';
				}

				?>
					</select></td>
					<td>Objetivos Estratégicos:</td>
					<td><select id="listaObjetivoEstrategico"
						name="listaObjetivoEstrategico">
							<option value="">Todos</option>
							<?php 
							$res= $cpoa1->listarObjetivosEstrategicos($conexion, $fecha['year']);

							while($fila = pg_fetch_assoc($res)){
		                        echo '<option value="' . $fila['id_objetivo'] . '">' . $fila['descripcion'] .'</option>';
                            }?>
					</select></td>
				</tr>
				<tr>
					<th></th>
					<td>Proceso:</td>
					<td><select id="listaProcesos" name="listaProcesos">
							<option value="">Todos</option>
							<?php 
							$res= $cpoa1->listarProcesos($conexion, $fecha['year']);

							while($fila = pg_fetch_assoc($res)){
			                        echo '<option value="' . $fila['id_proceso'] . '">' . $fila['descripcion'] .'</option>';
                         	}?>
					</select></td>
					<td>Sub proceso:</td>
					<td><select id="listaSubprocesos" name="listaSubprocesos">
							<option value="">Todos</option>
				<?php 
				$res= $cpoa1->listarSubprocesos($conexion, $fecha['year']);

				while($fila = pg_fetch_assoc($res)){
                       echo '<option value="' . $fila['id_subproceso'] . '">' . $fila['descripcion'] .'</option>';
				}?>
			</select></td>
				</tr>
				<tr>
					<th></th>
					<!--td>Objetivos operativo:</td>
					<td><select id="listaComponentes" name="listaComponentes">
							<option value="">Todos</option>
				< ?php 					
				$res= $cpoa1->listarComponentes($conexion, $fecha['year']);

				while($fila = pg_fetch_assoc($res)){
			                        echo '<option value="' . $fila['descripcion'] ."". $fila['id_proceso'] . '">' . $fila['descripcion'] .'</option>';

	            }?>
			</select></td-->
					<td>Actividades:</td>
					<td colspan="3"><select id="listaActividades" name="listaActividades">
							<option value="">Todos</option>
				<?php 
					$res= $cpoa1->listarActividades($conexion, $fecha['year']);
    				while($fila = pg_fetch_assoc($res)){
	                       echo '<option value="' . $fila['descripcion'] . '">' . $fila['descripcion'] .'</option>';
					}?>
			</select></td>
				</tr>


				<tr>
					<th></th>
					<td>Item. presupuestario:</td>
					
					<td><select name="codigo_Item" id="codigo_Item">
					<option value="">Todos</option>
							<?php 
					$res= $cpoa1->listarItemPresupuestario($conexion);
    				while($fila = pg_fetch_assoc($res)){
    				    echo '<option value="' . $fila['codigo']. '">' . $fila['codigo']. ' - ' . $fila['descripcion'] .'</option>';
					}?>
					</select>
					</td>
					<td>Detalle del gasto:</td>
					
					<td><select name="detalle_gasto" id="detalle_gasto">
					<option value="">Todos</option>
							<?php 
					$res= $cpoa1->listarDetalleGasto($conexion, $fecha['year']);
    				while($fila = pg_fetch_assoc($res)){
	                       echo '<option value="' . $fila['detalle_gasto']. '">' . $fila['detalle_gasto'] .'</option>';
					}?>
					</select>
					</td>
				</tr>
				
			     <tr>
					<th>Entre las fechas</th>
					<td>inicio:</td>
					<td><input type="text" name="fi" id="fechaInicio" /></td>
					<td>fin:</td>
					<td><input type="text" name="ff" id="fechaFin" /></td>
				</tr>
				
				 <tr>
					<th>En los</th>
					<td>estados:</td>
					<td colspan="3"><select name="estadoFiltro" id="estadoFiltro">
							<option value="1">Creado</option>
							<option value="2">Revisión Coordinador</option>
							<option value="3">Revisión Administrador</option>
							<option value="4">Aprobados Planta Central</option>
						</select>
					</td>
					<td></td>
					<td></td>
				</tr>
				
				<tr>
					<th></th>
					<td></td>
					<td></td>
					<td colspan="5"><button>Generar Reporte</button></td>
				</tr>
			</table>
		</form>

	</nav>
</header>
<div id="tabla"></div>
<script>
	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
	});


	var array_subprocesos= <?php echo json_encode($subprocesos); ?>;
	//var array_componentes= < ?php echo json_encode($objetivoComponente); ?>;
	var array_actividades= <?php echo json_encode($actividadesSubProceso); ?>;
	
	$("#listaProcesos").change(function(){
		sresponsable ='0';
		sresponsable = '<option value="">Todos...</option>';
	    for(var i=0;i<array_subprocesos.length;i++){
		   
		    if ($("#listaProcesos").val()==array_subprocesos[i]['id_proceso']){
		    	sresponsable += '<option value="'+array_subprocesos[i]['id_subproceso']+'">'+array_subprocesos[i]['descripcion_subproceso']+'</option>';
			    }
	   		}

	    $('#listaSubprocesos').html(sresponsable);
	    $('#listaSubprocesos').removeAttr("disabled");
	     
	    /*scomponentes ='0';
	    scomponentes = '<option value="">Todos...</option>';
	   	   
	    for(var z=0;z<array_componentes.length;z++){
	     	   
		    if ($("#listaProcesos").val()==array_componentes[z]['id_proceso']){
			    scomponentes += '<option value="'+array_componentes[z]['descripcion_componente']+array_componentes[z]['id_proceso']+'">'+array_componentes[z]['descripcion_componente']+'</option>';
			    }
	   		}
  
	    $('#listaComponentes').html(scomponentes);
	    $('#listaComponentes').removeAttr("disabled");*/
 
	 });

	$("#listaSubprocesos").change(function(){

		sactividades ='0';
	    sactividades = '<option value="">Todos...</option>';
	   	   
	    for(var z=0;z<array_actividades.length;z++){
	     	   
		    if ($("#listaSubprocesos").val()==array_actividades[z]['id_subproceso']){
		    	sactividades += '<option value="'+array_actividades[z]['descripcion_actividad']+'">'+array_actividades[z]['descripcion_actividad']+'</option>';
			    }
	   		}
  	    $('#listaActividades').html(sactividades);
	    $('#listaActividades').removeAttr("disabled");
		
		
	});
	
	</script>
