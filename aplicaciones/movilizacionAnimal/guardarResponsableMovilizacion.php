<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();
$cv = new ControladorVacunacionAnimal();
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$tipoEmisor = htmlspecialchars($_POST['tipoEmisor'],ENT_NOQUOTES,'UTF-8');
$tipo = htmlspecialchars($_POST['tipoBusqueda'],ENT_NOQUOTES,'UTF-8');
$valor = htmlspecialchars ($_POST['responsableMovilizacion'],ENT_NOQUOTES,'UTF-8');  //"1709827867";

//print_r($_POST);

if($opcion==1){// Busqueda de los funcionarios responsable de la movilización
	
	$ResponsableMovilizacion = $cm->seleccionarResponsablesMovilizacionAnimal($conexion, $tipoEmisor, $tipo, $valor);
	
	$sitiosAdministrador = $cv->listaSitioEmpresas($conexion, $valor);
	

	
	if (pg_num_rows($ResponsableMovilizacion)>0)
	{	
	echo '<label>Emisor : </label>';
	echo '<select id="cmbResponsableEmision" name="cmbResponsableEmision">';
	echo '<option value="0">Seleccionar...</option>';
	while ($fila = pg_fetch_assoc($ResponsableMovilizacion)){
		echo '<option value="'.$fila['identificador'].'" data-nombre="'.$fila['nombres'].'" data-provincia="'.$fila['provincia'].'" data-id-provincia="'.$fila['id_provincia'].'" data-canton="'.$fila['canton'].'" data-id-canton="'.$fila['id_canton'].'" data-parroquia="'.$fila['parroquia'].'" data-id-parroquia="'.$fila['id_parroquia'].'" data-identificador-autoservicio="'.$fila['identificador_autoservicio'].'" data-autoservicio="'.$fila['autoservicio'].'">'.$fila['identificador'].' - '.$fila['nombres'].'  '.$fila['autoservicio'].' </option>';
	}
	
	echo '</select>';
	}else{
		echo "No hay resultados para la consulta";
	}
		
}

if($opcion==10){

	if($tipoEmisor==1){//funcionario
	
		$datos = array(
				'id_tipo_lugar_emision' => htmlspecialchars ($_POST['tipoEmisor'],ENT_NOQUOTES,'UTF-8'),
				'identificador_emisor' => htmlspecialchars ($_POST['cmbResponsableEmision'],ENT_NOQUOTES,'UTF-8'),
				'identificador_autoservicio' => htmlspecialchars ($_POST['idcoordinacions'],ENT_NOQUOTES,'UTF-8'),
				'nombre_emisor_movilizacion' => htmlspecialchars ($_POST['nombre_emisor'],ENT_NOQUOTES,'UTF-8'),
				'nombre_lugar_emision' => htmlspecialchars ($_POST['lugaremisions'],ENT_NOQUOTES,'UTF-8'),
	
				'id_provincia' => htmlspecialchars ($_POST['idprovincias'],ENT_NOQUOTES,'UTF-8'),
	
				'provincia' => htmlspecialchars ($_POST['provincias'],ENT_NOQUOTES,'UTF-8'),
					
				'id_canton' => htmlspecialchars ($_POST['idcantons'],ENT_NOQUOTES,'UTF-8'),
				'canton' => htmlspecialchars ($_POST['cantons'],ENT_NOQUOTES,'UTF-8'),
					
				'id_parroquia' => htmlspecialchars ($_POST['idparroquias'],ENT_NOQUOTES,'UTF-8'),
				'parroquia' => htmlspecialchars ($_POST['parroquias'],ENT_NOQUOTES,'UTF-8'),
	
			
				'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
				'estado' => 'activo');
	
		//Guardar datos del emisor de movilización
		$dEmisorMovilizacion = $cm->guardarResponsableMovilizacion($conexion, $datos['id_tipo_lugar_emision'], $datos['identificador_emisor'], $datos['identificador_autoservicio']
				, $datos['nombre_emisor_movilizacion'], $datos['nombre_lugar_emision'], $datos['id_provincia'], $datos['provincia']
				, $datos['id_canton'], $datos['canton'], $datos['id_parroquia'], $datos['parroquia'],0, $datos['usuario_responsable'], $datos['estado']);
	
		
		$conexion->desconectar();
		echo "Guardar los datos";
	
	}
if($tipoEmisor==5){//autoservicio
	$datos = array(
			'id_tipo_lugar_emision' => htmlspecialchars ($_POST['tipoEmisor'],ENT_NOQUOTES,'UTF-8'),
			'identificador_emisor' => htmlspecialchars ($_POST['cmbResponsableEmision'],ENT_NOQUOTES,'UTF-8'),
			'identificador_autoservicio' => htmlspecialchars ($_POST['identificador_autoservicio'],ENT_NOQUOTES,'UTF-8'),
			'nombre_emisor_movilizacion' => htmlspecialchars ($_POST['nombre_emisor'],ENT_NOQUOTES,'UTF-8'),
			'nombre_lugar_emision' => htmlspecialchars ($_POST['lugar_emision'],ENT_NOQUOTES,'UTF-8'),
			'id_provincia' => htmlspecialchars ($_POST['id_provincia'],ENT_NOQUOTES,'UTF-8'),
			'provincia' => htmlspecialchars ($_POST['nombre_provincia'],ENT_NOQUOTES,'UTF-8'),			
			'id_canton' => htmlspecialchars ($_POST['id_canton'],ENT_NOQUOTES,'UTF-8'),
			'canton' => htmlspecialchars ($_POST['nombre_canton'],ENT_NOQUOTES,'UTF-8'),
			'id_parroquia' => htmlspecialchars ($_POST['id_parroquia'],ENT_NOQUOTES,'UTF-8'),
			'parroquia' => htmlspecialchars ($_POST['nombre_parroquia'],ENT_NOQUOTES,'UTF-8'),
			'id_sitio' => htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8'),
			'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),						
			'estado' => 'activo');
				
	//Guardar datos del emisor de movilización
	//$Vacunador = $vdr->busquedaVacunador($conexion, $datos['identificador']);
	//if(pg_num_rows($Vacunador) == 0 ){

	$dEmisorMovilizacion = $cm->guardarResponsableMovilizacion($conexion, $datos['id_tipo_lugar_emision'], $datos['identificador_emisor'], $datos['identificador_autoservicio']
	, $datos['nombre_emisor_movilizacion'], $datos['nombre_lugar_emision'], $datos['id_provincia'], $datos['provincia']
	, $datos['id_canton'], $datos['canton'], $datos['id_parroquia'], $datos['parroquia'], $datos['id_sitio'], $datos['usuario_responsable'], $datos['estado']);
				
	//$idVacunador = pg_fetch_result($dEmisorMovilizacion, 0, 'id_administrador_vacunador');		
	$conexion->desconectar();
	echo "Guardar los datos";	
	
}
	

}

?>

<script type="text/javascript">

	var array_vacunadorOficial = <?php echo json_encode($vacunadorOficial); ?>;
	var array_sitioAdministrador = <?php echo json_encode($sitiosAdministrador); ?>;
	
	$("#cmbResponsableEmision").change(function(){		
    	if ($("#tipoEmisor").val() != 0){
			//Condiciones
			tEmisor = $("#tipoEmisor").val();
			if(tEmisor==1){				   	
				$("#funcionario_emisor").show();
				$("#otro_emisor").hide();
				/*$("#idprovincias").val($('#cmbResponsableEmision option:selected').text());
				$("#idcantons").val($('#cmbResponsableEmision option:selected').attr('data-canton'));
				$("#idparroquias").val($('#cmbResponsableEmision option:selected').attr('data-parroquia'));
				$("#lugaremisions").val($('#cmbResponsableEmision option:selected').attr('data-autoservicio'));
				//$("#lugarEmision").val($('#cmbResponsableEmision option:selected').attr('data-autoservicio'));
				$("#nombre_emisor").val($('#cmbResponsableEmision option:selected').attr('data-nombre'));

				$("#provincias").val($('#cmbResponsableEmision option:selected').attr('data-id-provincia'));
				$("#provincia").val($('#cmbResponsableEmision option:selected').attr('data-provincia'));
				$("#id_provincia").val($('#cmbResponsableEmision option:selected').attr('data-id-provincia'));
				$("#nombre_provincia").val($('#cmbResponsableEmision option:selected').attr('data-provincia'));
				$("#id_canton").val($('#cmbResponsableEmision option:selected').attr('data-id-canton'));
				$("#nombre_canton").val($('#cmbResponsableEmision option:selected').attr('data-canton'));
				$("#id_parroquia").val($('#cmbResponsableEmision option:selected').attr('data-id-parroquia'));
				$("#nombre_parroquia").val($('#cmbResponsableEmision option:selected').attr('data-parroquia'));
				$("#identificador_autoservicio").val($('#cmbResponsableEmision option:selected').attr('data-identificador-autoservicio'));

				sSitioAdministrador = '0';
				sSitioAdministrador = '<option value="0">Seleccione...</option>';
				for(var i=0;i<array_sitioAdministrador.length;i++){	
					if ($("#identificador_autoservicio").val()==array_sitioAdministrador[i]['identificador_operador']){	    
						sSitioAdministrador += '<option value="'+array_sitioAdministrador[i]['id_sitio']+'">'+array_sitioAdministrador[i]['granja']+'</option>';
					}							  
				}   
			    $('#cmbSitio').html(sSitioAdministrador);
			 	$("#cmbSitio").removeAttr("disabled");	*/
			}				
			if(tEmisor==5){
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

				sSitioAdministrador = '0';
				sSitioAdministrador = '<option value="0">Seleccione...</option>';
				for(var i=0;i<array_sitioAdministrador.length;i++){	
					if ($("#identificador_autoservicio").val()==array_sitioAdministrador[i]['identificador_operador']){	    
						sSitioAdministrador += '<option value="'+array_sitioAdministrador[i]['id_sitio']+'">'+array_sitioAdministrador[i]['granja']+'</option>';
					}			  
				}   
			    $('#cmbSitio').html(sSitioAdministrador);
			 	$("#cmbSitio").removeAttr("disabled");	
				
			}			
			$("#nombre_emisor").val($('#cmbResponsableEmision option:selected').attr('data-nombre'));	
		}
	});
	
	$("#cmbPuntoDistribucion").change(function(){ 
		if($("#cmbPuntoDistribucion").val() != 0){
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
	}); 

</script>








