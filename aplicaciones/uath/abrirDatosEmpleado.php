<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';


$conexion = new Conexion();
$cc = new ControladorCatastro();
$res = $cc->listaFichaEmpleados($conexion,$_POST['id'],'','');
$empleado = pg_fetch_assoc($res);


?>

<header>
	<h1>Datos Empleados</h1>
</header>

	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Detalles del funcionario</legend>
					<div data-linea="1">
						<label>Identificador:</label>
						<?php echo $empleado['identificador'];?>
					</div>
					
					<div data-linea="2">
						<label>Apellido:</label>
						<?php echo $empleado['apellido'];?>
					</div>
					<div data-linea="2">
						<label>Nombre:</label>
						<?php echo $empleado['nombre'];?>
					</div>
					<div data-linea="3">
						<label>Domicilio:</label>
						<?php echo $empleado['domicilio'];?>
					</div>
					<div data-linea="4">
						<label>Teléfono convencional:</label>
						<?php echo $empleado['convencional'];?>
					</div>
					<div data-linea="4">
						<label>Teléfono celular:</label>
						<?php echo $empleado['celular'];?>
					</div>
					<hr />
										
   
				</fieldset>
			</td>
		</tr>
		
		<?php
		$resAcademico = $cc->obtenerDatosAcadémicos($conexion,$_POST['id'],$_POST['id']);
		?>
	    <tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Académica</legend>
					<?php
					$contador=1;
					 while($dato_academico = pg_fetch_assoc($resAcademico)){
	
		            echo  '<input type="hidden" id="academico_seleccionado" value="'.$dato_academico['id_datos_academicos'].'" name="academico_seleccionado[]" />
					<div data-linea='.$contador.'>
						<label>Institucion</label> 
						'.$dato_academico['institucion']. '
					</div>			
					<div data-linea='.$contador++.'>
						<label>Años de estudio</label> 
						'.$dato_academico['anios_estudio'].'
					</div>
		            <div data-linea='.$contador.'>
						<label>Título</label> 
						'.$dato_academico['titulo'].' 
					</div>
					<div data-linea='.$contador++.'>
						<label>Pais</label> 
						'.$dato_academico['pais']. '
					</div>
					<div data-linea='.$contador.'>
						<label>Nivel de instrucción</label> 
		                '.$dato_academico['nivel_instruccion'].'
					</div>
					<div data-linea='.$contador++.'>
						<label>Carrera</label> 
		                '.$dato_academico['carrera'].'
					</div>
					<div data-linea='.$contador++.'>		
						<label>Certificado</label>';?>
						
	                <?php echo ($dato_academico['archivo_academico']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$dato_academico['archivo_academico'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div>
		            
					
					<div data-linea='.$contador++.'>
						<label>Observaciones</label> 
		                '.$dato_academico['observaciones'].'
					</div><hr/>';
	}
	if($contador==1)
		echo  '<div data-linea='.$contador.'>
						<label>No existe datos de capacitaciones registrados</label><hr/>
		       	</div>';
	?>
	</fieldset>
			</td>
		</tr>
		
		<?php
		$res3 = $cc->listaCapacitacionFuncionario($conexion,$_POST['id'],'APROBADOS');
		
		?>
	    <tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Capacitación</legend>
					<?php
					$contador=1;
					 while($academico = pg_fetch_assoc($res3)){
	
		            echo  '<input type="hidden" id="academico_seleccionado" value="'.$academico['id_datos_academicos'].'" name="academico_seleccionado[]" />
					<div data-linea='.$contador.'>
						<label>Título</label> 
						'.$academico['titulo_capacitacion']. '
					</div>			
					<div data-linea='.$contador++.'>
						<label>Institución</label> 
						'.$academico['institucion'].'
					</div>
		            <div data-linea='.$contador.'>
						<label>País</label> 
						'.$academico['pais'].' 
					</div>
					<div data-linea='.$contador++.'>
						<label>Horas</label> 
						'.$academico['horas']. '
					</div>
					<div data-linea='.$contador.'>
						<label>Estado</label> 
		                '.$academico['estado'].'
					</div>
					<div data-linea='.$contador++.'>		
						<label>Certificado</label>';?>
						
	                <?php echo ($academico['archivo_capacitacion']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$academico['archivo_capacitacion'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div>
		            
					
					<div data-linea='.$contador++.'>
						<label>Observaciones</label> 
		                '.$academico['observaciones'].'
					</div><hr/>';
	}
	if($contador==1)
		echo  '<div data-linea='.$contador.'>
						<label>No existe datos de capacitaciones registrados</label><hr/>
		       	</div>';
	?>
	</fieldset>
			</td>
		</tr>
		
		<?php
		$res4 = $cc->obtenerExperienciaLaboral($conexion, $_POST['id'],'Aceptado');
		
		
		?>
	    <tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Experiencia Laboral</legend>
					<?php
					$contador=1;
					$totaldias=0;
					$totalmeses=0;
					$totalanios=0;
					
					while($laboral = pg_fetch_assoc($res4)){
					$f_salida=$laboral['fecha_salida']!=''?date('d/m/Y',strtotime($laboral['fecha_salida'])):'Actualidad';	
		            echo  '<div data-linea='.$contador.'>
						<label>Tipo Institución</label> 
							'.$laboral['tipo_institucion'].'
					</div>
					<div data-linea='.$contador++.'>
						<label>Institución</label> 
						'.$laboral['institucion'].' 
					</div>
					<div data-linea='.$contador.'>
						<label>Unidad administrativa</label> 
						'.$laboral['unidad_administrativa'].'
					</div>
					<div data-linea='.$contador++.'>
						<label>Puesto</label> 
						'.$laboral['puesto']. '
					</div>
					<div data-linea='.$contador.'>
						<label>Fecha ingreso</label> 
						'.date('d/m/Y',strtotime($laboral['fecha_ingreso'])).'
					</div>
					
					<div data-linea='.$contador++.'>
						<label>Fecha salida</label> 
						'.$f_salida. '
					</div>
					<div data-linea='.$contador.'>
						<label>Motivo salida</label> 
						'.$laboral['motivo_salida'].'
					</div>
					<div data-linea='.$contador++.'>
						<label>Observaciones</label> 
						'.$laboral['observaciones_rrhh'].'
					</div>
					<div data-linea='.$contador.'>
						<label>Tiempo de trabajo</label>';?>
					<?php 
					$FechaInicio="";
					$FechaFin="";
					$FechaInicio = DateTime::createFromFormat('Y-m-d', $laboral['fecha_ingreso']);
					if($laboral['fecha_salida']!='')
						$FechaFin = DateTime::createFromFormat('Y-m-d', $laboral['fecha_salida']);
					else
					$FechaFin= new DateTime('now');
					$FechaInicio->setTime(0, 0, 0);
					$FechaFin->setTime(0, 0, 0);
					$fecha=$FechaFin->diff($FechaInicio);
					echo ''.$fecha->y.' años, '.$fecha->m.' meses, '.$fecha->d.' días ';
					
					$totaldias=$totaldias+$fecha->d;
					$totalmeses=$totalmeses+$fecha->m;
					$totalanios=$totalanios+$fecha->y;
					
					
					echo '</div>
					<div data-linea='.$contador++.'>		
						<label>Certificado</label>';?>
						
	                <?php echo ($laboral['archivo_experiencia']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$laboral['archivo_experiencia'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div>
					<div data-linea='.$contador++.'>
						<label>Estado</label> 
		                '.$laboral['estado'].'
					</div><hr/>';
	}
	while($totaldias>30){
		$totalmeses+=1;
		$totaldias-=30;
	}
	while($totalmeses>=12){
		$totalanios+=1;
		$totalmeses-=12;
	}
	
	if($contador==1)
		echo  '<div data-linea='.$contador.'>
						<label>No existe datos de experiencia laboral registrados</label><hr/>
    							</div>';
	else
		echo '<div data-linea='.$contador.'><label>Totales: '.$totalanios." años ".$totalmeses." meses ".$totaldias." días</label></div>";
	
	?>
	</fieldset>
			</td>
		</tr>	
		<?php
		$res5 = $cc->obtenerDatosDeclaracionJuramentada($conexion, $_POST['id'],'Aceptado');
		if(pg_num_rows($res5)){
		?>
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Declaración Juramentada Periódica</legend>
					<?php
					$contador=1;

					while($declaracion = pg_fetch_assoc($res5)){
	
		            echo  '<div data-linea='.$contador++.'>
								<label>Fecha de Ingreso:</label>
								'.date('j/n/Y',strtotime($declaracion['fecha'])).' 
					    </div>
					   	<div data-linea='.$contador++.'>
						  <label>Estado:</label>
						  '.$declaracion['estado'].'
					      </div>
						<div data-linea='.$contador++.'>
						  <label>Fecha declaración:</label>
						  '.date('j/n/Y',strtotime($declaracion['fecha_declaracion'])).'
					      </div>
						<div data-linea='.$contador.'>
						<label>Observación:</label>
						'.$declaracion['obsevacion'].'</div>
					  <div data-linea='.$contador++.'>		
						<label>Archivo Adjunto</label>';?>
					<?php echo ($declaracion['ruta_declaracion_juramentada']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$declaracion['ruta_declaracion_juramentada'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div><hr />';
						
						}?>
   
   

				</fieldset>
			</td>
		</tr>
		<?php
		}
		$res6 = $cc->obtenerDatosHistorialLaboralIess($conexion, $_POST['id'],'Aceptado');
		if(pg_num_rows($res6)){
		?>
		
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Historial Laboral IESS</legend>
					<?php
					$contador=1;

					while($historial = pg_fetch_assoc($res6)){
	
		            echo  '<div data-linea='.$contador++.'>
								<label>Fecha de Ingreso:</label>
								'.date('j/n/Y',strtotime($historial['fecha'])).' 
					    </div>
					   	<div data-linea='.$contador++.'>
						  <label>Estado:</label>
						  '.$historial['estado'].'
					      </div>
						<div data-linea='.$contador.'>
						<label>Observación:</label>
						'.$historial['observacion'].'</div>
					  <div data-linea='.$contador++.'>		
						<label>Archivo Adjunto</label>';?>
					<?php echo ($historial['ruta_historial_laboral']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$historial['ruta_historial_laboral'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div><hr />';
						
						}?>
   
   

				</fieldset>
			</td>
		</tr>
		<?php
		}
		$res2 = $cc->obtenerContratosXUsuario($conexion,$_POST['id'],'','');
		
		?>
		
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Detalles del Contrato</legend>
					<?php
					$contador=1;
					
					while($contrato = pg_fetch_assoc($res2)){
	
		            echo  '<div data-linea='.$contador++.'>
								<label>Tipo de Contrato:</label>
								'.$contrato['tipo_contrato'].' 
					    </div>
					   	<div data-linea='.$contador++.'>
						  <label>Número de contrato:</label>
						  '.$contrato['numero_contrato'].'
					      </div>
						<div data-linea='.$contador++.'>
							<label>Lugar de trabajo:</label>
							'.$contrato['direccion'].'-'.$contrato['coordinacion'].'
						</div>
						<div data-linea='.$contador++.'>
						<label>Puesto que ocupa:</label>
						'. $contrato['nombre_puesto'].'
						</div>
						<div data-linea='.$contador++.'>
							<label>Sueldo:</label>
						'.$contrato['remuneracion'].'
					   </div>
						<div data-linea='.$contador++.'>
						    <label>Regimen Laboral:</label>
							'.$contrato['regimen_laboral'].'
					</div>
					
					<div data-linea='.$contador.'>
						<label>Inicio de contrato:</label>
						'.date('j/n/Y',strtotime($contrato['fecha_inicio'])).'
					</div>
					<div data-linea='.$contador++.'>
						<label>Fin de contrato:</label>
						'.date('j/n/Y',strtotime($contrato['fecha_fin'])).'
					</div>
					
					<div data-linea='.$contador++.'>
						<label>Declaración: </label> Notaría';?>
						<?php if($contrato['numero_notaria']==''){
							echo '0';
						} else {echo $contrato['numero_notaria'];
						};?>
						
						<?php echo $contrato['lugar_notaria'];?>
						
						<?php echo '('.date('j/n/Y',strtotime($contrato['fecha_declaracion'])).')</div>
					<div data-linea='.$contador.'>
						<label>Observación:</label>
						'.$contrato['obsevacion'].'</div>
					  <div data-linea='.$contador++.'>		
						<label>Certificado</label>';?>
					<?php echo ($contrato['archivo_contrato']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$contrato['archivo_contrato'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div><hr />';
						
						}?>
   
   

				</fieldset>
			</td>
		</tr>		
	</table>
	


<script type="text/javascript">


	$(document).ready(function(){

		distribuirLineas();
		
		$( "#fecha_inicio" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fecha_fin" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		   $( "#fecha_declaracion" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		   
		//abrir($("#datosContrato input:hidden"),null,false);
	});

	
</script>

