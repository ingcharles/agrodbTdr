<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$cm = new ControladorMovilizacionAnimal();
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

print_r($_POST);

if($opcion==1){// Busqueda de los funcionarios responsable de la movilización	
	$serie = htmlspecialchars($_POST['serieCertificadoMovilizacion'],ENT_NOQUOTES,'UTF-8');
	echo '<label>No.Certificado Movilización : </label>';
	echo '<select id="cmbCertificadoMovilizacion" name="cmbCertificadoMovilizacion">';
	echo '<option value="0">Seleccionar...</option>';
	$certificadoValorada = $vdr->numeroSerievaloradaAnular($conexion, 'movilizacion', $serie);	
	while ($fila = pg_fetch_assoc($certificadoValorada)){
		echo '<option value="'. $fila['numero_documento'].'" data-estado="'. $fila['estado'].'">'.$fila['numero_documento'].' Estado : '.$fila['estado'].'</option>';
	}
	echo '</select>';
	
	if(pg_num_rows($certificadoValorada)==0){
		echo "<script type='text/javascript'>
    				alert('El número del certificado de movilización CSMI, no existe !!');
    		  </script>";		
	}	
}

if($opcion==2){
	$datos = array(
		'numero_documento' => htmlspecialchars ($_POST['numero_documento'],ENT_NOQUOTES,'UTF-8'),
		'numero_documento_referencia' => htmlspecialchars ($_POST['tipoEmisor'],ENT_NOQUOTES,'UTF-8'),
		'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
		'fecha_anulacion' => htmlspecialchars ($_POST['fecha_anulacion'],ENT_NOQUOTES,'UTF-8'),			
		'observacion' => htmlspecialchars ($_POST['observacion_anular'],ENT_NOQUOTES,'UTF-8'),
		'estado' => htmlspecialchars ($_POST['cmbTipoAnulacion'],ENT_NOQUOTES,'UTF-8')		
	 );
					
	if($datos['estado']=='anulado'){
		echo ($datos['numero_documento']);
		//Paso 1.- Cambia estado => g_vacunacion_animal.serie_documentos
		$p1 = $cm->actualizarEstadoSerieDocumentos($conexion, $datos['numero_documento'], $datos['observacion'], $datos['estado']);		
		//Paso 2.- Cambia estado => g_movilizacion_animal.movilizacion_animales
		$p2 = $cm->actualizarEstadoMovilizacion($conexion, $datos['numero_documento'], $datos['observacion'], $datos['estado'], $datos['usuario_responsable']);
		//Paso 3.- Cambia estado => g_vacunacion_animal.catastro
		$p3a = $cm->catastroEstadoMovilizacion($conexion, $datos['numero_documento']);
		$p3b = $cm->catastroEstadoVacunacion($conexion,	$datos['numero_documento']);
		
	}	
	
	if($datos['estado']=='cambiado'){
		$dEmisorMovilizacion = $cm->actualizarEstadoMovilizacion($conexion,	$datos['numero_documento'], $datos['observacion'], $datos['estado']);
	}
			
	//$idVacunador = pg_fetch_result($dEmisorMovilizacion, 0, 'id_administrador_vacunador');		
	
	$conexion->desconectar();
	echo "Guardar los datos";	
	
}

?>

<script type="text/javascript">

	//var array_cabeceraMovilizacionAnimal = <?php echo json_encode($cabeceraMovilizacionAnimal); ?>;

	
	
	/*
	$("#cmbCertificadoMovilizacion").change(function(){ 
		alert("hola amigooo");
		
		if($("#cmbCertificadoMovilizacion").val() != 0){			
			for(var i=0;i<array_cabeceraMovilizacionAnimal.length;i++){	
				alert("hola amigooo");
     			//if ($("#cmbSitio").val()==array_cabeceraMovilizacionAnimal[i]['id_sitio']){	    
     			//	$("#certificadoMovilizacion").val(array_cabeceraMovilizacionAnimal[i]['numero_certificado']);
     			//}			  
     		}   					
		}		
		
	}); 

	
	$("#cmbCertificadoMovilizacion").change(function(){ 
		if($("#cmbCertificadoMovilizacion").val() != 0){
			visualizar2.style.visibility='visible'; 
			//$("#visualizar2").show();
			
			sVacunadorOficial = '0';
			sVacunadorOficial = '<option value="0">Seleccione...</option>';
			for(var i=0;i<array_vacunadorOficial.length;i++){	
				//if ($("#cmbSitio").val()==array_vacunadorOficial[i]['id_sitio']){	    
					sVacunadorOficial += '<option value="'+array_vacunadorOficial[i]['identificador_vacunador']+'">'+array_vacunadorOficial[i]['identificador_vacunador']+' - '+array_vacunadorOficial[i]['nombre_vacunador']+'</option>';
				//}			  
			}   
		    $('#cmbVacunador').html(sVacunadorOficial);
		 	$("#cmbVacunador").removeAttr("disabled");
		 
		}
		else
			visualizar2.style.visibility='hidden';
			         					 				
	}); 
	*/

	/*
	$("#cmbResponsableEmision").change(function(){		
    	if ($("#tipoEmisor").val() != 0){
			//Condiciones
			tEmisor = $("#tipoEmisor").val();
			if(tEmisor==1){				   	
				$("#funcionario_emisor").show();
				$("#otro_emisor").hide();
			}			
			if(tEmisor!=1){
				$("#funcionario_emisor").hide();
				$("#otro_emisor").show();
				$("#provincia2").val($('#cmbResponsableEmision option:selected').attr('data-provincia'));
				$("#canton2").val($('#cmbResponsableEmision option:selected').attr('data-canton'));
				$("#parroquia2").val($('#cmbResponsableEmision option:selected').attr('data-parroquia'));
				$("#lugar_emision").val($('#cmbResponsableEmision option:selected').attr('data-autoservicio'));
				$("#lugarEmision").val($('#cmbResponsableEmision option:selected').attr('data-autoservicio'));
				$("#nombre_emisor").val($('#cmbResponsableEmision option:selected').attr('data-autoservicio'));

				$("#id_provincia").val($('#cmbResponsableEmision option:selected').attr('data-id-provincia'));
				$("#provincia").val($('#cmbResponsableEmision option:selected').attr('data-provincia'));
				$("#id_provincia").val($('#cmbResponsableEmision option:selected').attr('data-id-provincia'));
				$("#nombre_provincia").val($('#cmbResponsableEmision option:selected').attr('data-provincia'));
				$("#id_canton").val($('#cmbResponsableEmision option:selected').attr('data-id-canton'));
				$("#nombre_canton").val($('#cmbResponsableEmision option:selected').attr('data-canton'));
				$("#id_parroquia").val($('#cmbResponsableEmision option:selected').attr('data-id-parroquia'));
				$("#nombre_parroquia").val($('#cmbResponsableEmision option:selected').attr('data-parroquia'));
				$("#identificador_autoservicio").val($('#cmbResponsableEmision option:selected').attr('data-identificador-autoservicio'));
				
			}			
			$("#nombre_emisor").val($('#cmbResponsableEmision option:selected').attr('data-nombre'));				   				
		}
	});
	*/
	

</script>








