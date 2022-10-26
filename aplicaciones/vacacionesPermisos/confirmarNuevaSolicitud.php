<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorAreas.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$cc = new ControladorCatastro();
	$ca = new ControladorAreas();
		
	$identificadorUsuario = $_POST['identificador'];
	$opcion = $_POST['opcion'];
	$identificador = $_POST['identificador'];
	$rutaArchivo = $_POST['archivo'];
	$subTipoSolicitud = $_POST['subTipoSolicitud'];
	
	//Cambio de formato en tiempo
	$horaSalida = substr($_POST['horaSalida'], 0, 2);
	$minutosSalida = substr($_POST['horaSalida'], 3, 2);
	
	$horaRetorno = substr($_POST['horaRetorno'], 0, 2);
	$minutosRetorno = substr($_POST['horaRetorno'], 3, 2);
	
	$subtipoPermiso = pg_fetch_assoc($cv->obtenerSubTipoPermiso($conexion,null,$_POST['subTipoSolicitud']));
	$minutosPermitidos = $subtipoPermiso['minutos_permitidos'];	
	$minutosDisponibles = pg_fetch_result($cv->consultarSaldoFuncionario($conexion,$identificador), 0, 'minutos_disponibles');

	if($_POST['fechaSuceso'] != "")
		$fechaSuceso= $_POST['fechaSuceso'];
	else{
		$fechaSuceso= $_POST['fechaSalida'];
	}
	$fechaSalida = new DateTime($_POST['fechaSalida']);
	date_time_set($fechaSalida,$horaSalida,$minutosSalida);

	$fechaRetorno = new DateTime($_POST['fechaRetorno']);
	date_time_set($fechaRetorno,$horaRetorno,$minutosRetorno);
	
	$fechaSalida = date_format($fechaSalida, 'Y-m-d H:i:s');
	$fechaRetorno = date_format($fechaRetorno, 'Y-m-d H:i:s');

	$diasHabiles = count($cv->DiasHabiles($fechaSalida, $fechaRetorno));
	$minutos_utilizados = 0;
	
	if(	$_POST['opcionTipoPermiso'] == 'EN-EF' || $_POST['opcionTipoPermiso'] == 'EN-EC' || 
		$_POST['opcionTipoPermiso'] == 'EC-HH' || 		 
		$_POST['opcionTipoPermiso'] == 'MA-MA' || $_POST['opcionTipoPermiso'] == 'CD-FP' || $_POST['opcionTipoPermiso'] == 'CD-AGP'||
		$_POST['opcionTipoPermiso'] == 'CD-FEP' || $_POST['opcionTipoPermiso'] == 'CD-FS' || $_POST['opcionTipoPermiso'] == 'CD-AGH' ||
		$_POST['opcionTipoPermiso'] == 'CD-FPR' || $_POST['opcionTipoPermiso'] == 'PE-CP' || $_POST['opcionTipoPermiso'] == 'PE-DA' ||
		$_POST['opcionTipoPermiso'] == 'PE-CE'){
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
			$minutos_utilizados = 480;
		}else $minutos_utilizados = $diasHabiles * 480;	
	}
	if(	$_POST['opcionTipoPermiso'] == 'PE-ER' || $_POST['opcionTipoPermiso'] == 'EN-RE' || 
		$_POST['opcionTipoPermiso'] == 'PE-RN' || $_POST['opcionTipoPermiso'] == 'PE-MH'){
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
			$minutos_utilizados = 120;
		}else $minutos_utilizados = $diasHabiles * 120;		
	}
	
	if(	$_POST['opcionTipoPermiso'] == 'VA-VA' || $_POST['opcionTipoPermiso'] == 'NA-MA' ||
				$_POST['opcionTipoPermiso'] == 'NA-MPA' || $_POST['opcionTipoPermiso'] == 'NA-PPC' || $_POST['opcionTipoPermiso'] == 'NA-PPM' ||
			    $_POST['opcionTipoPermiso'] == 'NA-PPN' || $_POST['opcionTipoPermiso'] == 'NA-PPN' || $_POST['opcionTipoPermiso'] == 'NA-NP' ||
			    $_POST['opcionTipoPermiso'] == 'NA-NED' || $_POST['opcionTipoPermiso'] == 'NA-PM' ){
		$diasSeleccionados = count($cv->devolverNumDias($fechaSalida, $fechaRetorno));
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
			$minutos_utilizados = 480;
		}else $minutos_utilizados = $diasSeleccionados * 480;
	}	
	
	if(	$_POST['opcionTipoPermiso'] == 'CD-SIN'){
		$diasSeleccionados = count($cv->devolverNumDias($fechaSalida, $fechaRetorno));
		if($cmp=strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
			$minutos_utilizados = 480;
		}else $minutos_utilizados = $diasSeleccionados * 480;	
	}
	if(	$_POST['opcionTipoPermiso'] == 'PE-AM' || $_POST['opcionTipoPermiso'] == 'PE-CL'){
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
			$minutos_utilizados = $cv->devolverMinutosHoras($_POST['horaSalida'],$_POST['horaRetorno'],0);
		}else $minutos_utilizados = $cv->devolverMinutSaldRetor($_POST['horaSalida'],$_POST['horaRetorno'],$diasHabiles);	
	}	
//-------------------------------------------------------------------------------------------------------
	if(	$_POST['opcionTipoPermiso'] == 'PE-PIV' || $_POST['opcionTipoPermiso'] == 'PE-PIVF'){

	if($_POST['opcionPermiso'] == "dias"){
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){
		    $minutos_utilizados = 480;
		}else $minutos_utilizados = $diasHabiles*480;
	}
	//-------------------------------------------------------------------------------------------------------
	if($_POST['opcionPermiso'] == "hora"){
		if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){		
	       $minutos_utilizados = $cv->devolverMinutosHoras($_POST['horaSalida'],$_POST['horaRetorno'],0);	       
	     }else $minutos_utilizados = $cv->devolverMinutosHoras($_POST['horaSalida'],$_POST['horaRetorno'],1);
	     if($minutos_utilizados >= 480 && $minutos_utilizados <= 510 )$minutos_utilizados = 480;
	     if($minutos_utilizados > 480)$minutos_utilizados = 0;
	}
	//-------------------------------------------------------------------------------------------------------	
	if($_POST['opcionPermiso'] == "horaDia"){	     
	     	if($cmp = strcmp($_POST['fechaSalida'], $_POST['fechaRetorno']) == 0){ 
	     	    $minutos_utilizados = $cv->devolverMinutosHoras($_POST['horaSalida'],$_POST['horaRetorno'],0);
	     		if($minutos_utilizados >= 480 && $minutos_utilizados <= 510 )$minutos_utilizados = 480;
	     		if($minutos_utilizados > 480)$minutos_utilizados = 0;
	     	}else {
	     		$minutos_utilizados = $cv->devolverMinutSaldRetor($_POST['horaSalida'],$_POST['horaRetorno'],$diasHabiles);
	     	}
	     }	     
	}    	
//--------------------------------------------------------------------------------------------------------
	if(	$_POST['opcionTipoPermiso'] == 'PE-PIV' || $_POST['opcionTipoPermiso'] == 'PE-PIVF' ){
		$minutos_utilizados += round($minutos_utilizados * 0.36);	
		$minutos_utilizados = round($minutos_utilizados);
	}
//---------------------------------------------------------------------------------------------------------- 
	     $minutosSolicitados = $cv->devolverFormatoDiasDisponibles($minutos_utilizados);
//----------------------------------------------------------------------------------------------------------	     
 	echo '
	<fieldset id="detalles1">
	<legend>Información</legend>
		<div data-linea="3" >
			<label>Fecha Salida:</label>
		</div>		
		<div data-linea="3" >
			'.$fechaSalida.'
		</div>
		<div data-linea="3" >
			<label>Fecha Retorno:</label>
		</div>		
		<div data-linea="3" >
			'.$fechaRetorno.'
		</div>

	<hr id="separador">	';
 	$bandera=0;
	if(	$minutos_utilizados > 0){
		
	echo '<div data-linea="5"> 
	<label id="etiquetaFechaSuceso">Tiempo Solicitado:  </label>'.$minutosSolicitados.'
	</div>';
 	if(	$_POST['opcionTipoPermiso']=='PE-PIV' || $_POST['opcionTipoPermiso']=='PE-PIVF' ){
 		$mensajePermi='Permisos imputables a vacaciones se incrementan un proporcional de los fines de semana.';
 		if($minutos_utilizados > $minutosDisponibles){
 			$bandera=1;
 			$mensajePermi='No dispone de tiempo para solicitar el permiso....!!';
 		}
 		
	echo '<hr id="separador">	
	<div data-linea="6"> 
	<label id="etiquetaFechaSuceso">Nota: </label><span class="alerta"><label id="letraTamano">'.$mensajePermi.'
	</span>
	</label>
	</div>';}	
	
	}else{
		echo '<div data-linea="7">
		<label id="etiquetaFechaSuceso">ALERTA...!!  </label><span class="alerta">El tiempo solicitado no puede ser cero o mayor a 8 horas, por favor seleccione correctamente la fecha y hora de finalización del permiso</span>
		</div>';
	}

	if(	$minutos_utilizados > $minutosPermitidos){
		echo '<div data-linea="7">
		<label id="etiquetaFechaSuceso">ALERTA...!!  </label><span class="alerta">El tiempo solicitado no puede ser mayor al tiempo permitido para el permiso seleccionado, por favor seleccione correctamente la fecha y hora de finalización del permiso</span>
		</div>';
	}
	echo '</fieldset>';
	
//-------------------------------verificar quien genera el permiso-----------------------------------------------------
	echo '<form id="guardarNueva" data-rutaAplicacion="vacacionesPermisos" data-opcion="gestionVacaciones" data-accionEnExito="ACTUALIZAR">';
	$banderaR=0;
	$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificador));
	if($numverifirespon=pg_num_rows($cc->verificarResponsable($conexion,$identificador, '', 'SI'))){
		$banderaR=1;
	}
	if(pg_num_rows($cc->verificarResponsablePuesto($conexion,$identificador, $areaUsuario['id_area']))){
		$banderaR=2;
	}
	
	if($banderaR != 0  and $minutos_utilizados >= 480 and $_POST['opcionTipoPermiso'] != 'PE-CL' and  $_POST['opcionTipoPermiso'] != 'PE-CP'){
	  $identifi=$identificador;
	  
	if(pg_num_rows($cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario['id_area'])) > 1){  
	  
	echo '<fieldset>
	<legend>Funcionarios</legend>';		
	echo '<div data-linea="1">
		  <label>Responsable Encargado:</label> <select name="responsableEncargado" id="responsableEncargado">';
			$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario['id_area']);
			$identificador_encargado = pg_fetch_result($cv->obtenerEncargadoPuestoArea($conexion,'','','','creado',$_POST['id_registro'],''), 0, 'identificador_encargado');
			$rutaArchivo = pg_fetch_result($cv->obtenerEncargadoPuestoArea($conexion,'','','','creado',$_POST['id_registro'],''), 0, 'ruta_subrogacion');
				
			$band=1;
			while($fila = pg_fetch_assoc($listaReporte)) {					
			if(strcmp($fila['identificador'], $identificador_encargado)==0){
				echo '<option value="' . $fila['identificador'] . '">' . $fila['nombre'].' </option>';
				$band=0;
			   }
			}
			if($band)echo '<option value="">Seleccione.... </option>';
			$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario['id_area']);			
			while($fila = pg_fetch_assoc($listaReporte)) {				
				if(!strcmp($fila['identificador'], $identificador)==0)
					if(!strcmp($fila['identificador'], $identificador_encargado)==0)
				echo '<option value="' . $fila['identificador'] . '">' . $fila['nombre'].' </option>';
		       }
	 echo '</select> </div>';
	 echo '</table></fieldset>';
	 
	 echo '<fieldset id="adjuntos">';
	 echo '<legend>Documento de Respaldo - Memorando designación responsable</legend>';
	 echo '<div data-linea="1">
						<label>Archivo Adjunto:  </label>';
	 echo ($rutaArchivo==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaArchivo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>');
	 
	 echo ' </div>';
	 echo '<div data-linea="8">';
	 echo '<input type="file" class="archivo" name="informeSub" id="informeSub" accept="application/pdf" />';
	 echo '<input type="hidden" class="rutaArchivo" name="archivoSub" id="archivoSub" value="'.$rutaArchivo.'" />';
	 echo '<div class="estadoCarga"> 
	 		En espera de archivo... (Tamaño máximo: ';
	 echo  ini_get("upload_max_filesize");
	 echo 'B)</div>';
	 				
	 echo '<button type="button" class="subirArchivo adjunto" id="informeReporte"
				data-rutaCarga="aplicaciones/uath/archivoSubrogacion">Subir
				archivo</button>
	 		</div>
	 	</fieldset>';
	 
	 }
	}
//--------------------------------------------------------------------------------------------------------------------	
	?>	    
		    <input type="hidden" id="minutosDisponibles" name="minutosDisponibles" value="<?php echo $minutos_utilizados; ?>"/>	    
		    <input type="hidden" id="opcionPermiso" name="opcionPermiso" value="<?php echo $_POST['opcionPermiso']; ?>"/>
		    <input type="hidden" id="opcionTipoPermiso" name="opcionTipoPermiso" value="<?php echo $_POST['opcionTipoPermiso']; ?>"/>
		    <input type="hidden" id="opcion" name="opcion" value="<?php echo $_POST['opcion']; ?>" /> 
		    <input type="hidden" id="archivo" name="archivo" value="<?php echo $_POST['archivo']; ?>" /> 
		    <input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />
			<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Nuevo" /> 
		    <input type="hidden" id="subTipoSolicitud" name="subTipoSolicitud"	value="<?php echo $_POST['subTipoSolicitud']; ?>" /> 
		    <input type="hidden" id="lugarComisionLocal" name="lugarComisionLocal" value="<?php echo $_POST['lugarComisionLocal']; ?>" /> 
		    <input type="hidden" id="lugarComisionProvincial" name="lugarComisionProvincial" value="<?php echo $_POST['lugarComisionProvincial']; ?>" />
		    <input type="hidden" id="lugarComisionExterior" name="lugarComisionExterior" value="<?php echo $_POST['lugarComisionExterior']; ?>" />		    
		    <input type="hidden" id="tipo" name="tipo" value="<?php echo $_POST['tipo']; ?>" /> 
		    <input type="hidden" id="fechaSuceso" name="fechaSuceso" value="<?php echo $_POST['fechaSuceso']; ?>" /> 
		    <input type="hidden" id="fechaSalida" name="fechaSalida" value="<?php echo $_POST['fechaSalida']; ?>" />	     
		    <input type="hidden" id="horaSalida" name="horaSalida"	value="<?php echo $_POST['horaSalida']; ?>" /> 
		    <input type="hidden" id="fechaRetorno" name="fechaRetorno" value="<?php echo $_POST['fechaRetorno']; ?>" /> 
		    <input type="hidden" id="horaRetorno" name="horaRetorno" value="<?php echo $_POST['horaRetorno']; ?>" />			    
		    <input type="hidden" id="id_registro" name="id_registro" value="<?php echo $_POST['id_registro']; ?>" />
			<button id="actualizar" type="submit" class="guardar" >Guardar</button>	&nbsp&nbsp
			<button id="modificar"  class="editar">Modificar</button>
		</form>
	<?php 	
	if(	$minutos_utilizados > 0 and $minutos_utilizados <= $minutosPermitidos and $bandera == 0){
	  echo '<script type="text/javascript"> distribuirLineas(); construirValidador();
			var error = false;
			$("#actualizar").show();
			$("#resultadoIni").hide();
		 $("#guardarNueva").submit(function(event){
		
		    $(".alertaCombo").removeClass("alertaCombo");
			
			event.preventDefault();
			if($("#responsableEncargado").val()==""){
				error = true;
				$("#responsableEncargado").addClass("alertaCombo");
			}
			if($("#archivoSub").val()==""){
						error = true;
						$("#informeSub").addClass("alertaCombo");
					}
			if (error == false){
				$("#actualizar").attr("disabled","disabled");
				ejecutarJson(this);
			}else
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass("alerta");
				
			});
			
			$("#modificar").click(function(event){
				$("#resultadoIni").show();
				$("#resultadoFin").hide();
				error = true;
				
		});
		$("#actualizar").click(function(event){
			error = false;
		});
		
   $("button.subirArchivo").click(function (event) {
	
    var boton = $(this);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split(".");
	        var estado = boton.parent().find(".estadoCarga");
		    numero = Math.floor(Math.random()*100000000);
	        if (extension[extension.length - 1].toUpperCase() == "PDF") {
				
	        		subirArchivo(
	    	                archivo
	    	                , numero+"_"+$("#identificador").val()+"_"+$("#fechaSuceso").val()
	    	                , boton.attr("data-rutaCarga")
	    	                , rutaArchivo
	    	                , new carga(estado, archivo, boton)
	    	            );
	        } else {
	            estado.html("Formato incorrecto, solo se admite archivos en formato PDF");
	            archivo.val("");
	        }
	});
		</script> ';
	}else{ echo '<script type="text/javascript"> distribuirLineas(); construirValidador();
					var error = true;
					$("#actualizar").hide();
					$("#resultadoIni").hide();				
					$("#guardarNueva").submit(function(event){
					event.preventDefault();
					if($("#responsableEncargado").val()==""){
						error = true;
						$("#responsableEncargado").addClass("alertaCombo");
					}
				   
					if (error == false){
						ejecutarJson(this);
					}else
						$("#estado").html("Por favor revise el formato de la información ingresada.").addClass("alerta");
				});
					$("#modificar").click(function(event){
					$("#resultadoIni").show();
					$("#resultadoFin").hide();
					error = true;
	});

	</script> ';
	  }	
	
	
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
	$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	$conexion->ejecutarLogsTryCatch($ex.'--'.$err);
}
?>
