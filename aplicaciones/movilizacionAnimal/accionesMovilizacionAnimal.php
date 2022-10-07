<?php
// Realiza el registro de movilizacion animal 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$cm = new ControladorMovilizacionAnimal();
$contador = 0;
$resultado = "";
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

if($opcion==1){//Búsqueda del responsables y autorizados para realizar la movilización --> ojo	
	$tipoSitioOrigen = htmlspecialchars ($_POST['cmbTipoMovilizacionOrigen'],ENT_NOQUOTES,'UTF-8'); //sitio/feria/centro de exposición
	$tipoBusquedaOrigen = 0;
	if($tipoSitioOrigen==1)	
		$tipoBusquedaOrigen = 1;//cedula		
	else
		$tipoBusquedaOrigen = 2;//nombre
			
	$idEspecie = htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8');
	$tipoAutorizado = htmlspecialchars ($_POST['cmbTipoAutorizado'],ENT_NOQUOTES,'UTF-8');//propietario, autorizado
	$varVacunador = htmlspecialchars ($_POST['txtBusquedaResponsable'],ENT_NOQUOTES,'UTF-8');	
	$varSitioOrigen = htmlspecialchars ($_POST['txtSitioBusquedaOrigen'],ENT_NOQUOTES,'UTF-8');
	
	echo '<label>Autorizado: </label>';
	echo '<select id="cmbAutorizado" name="cmbAutorizado">';
	echo '<option value="0">Seleccione...</option>';
	$autorizado = $cm->listaAutorizado($conexion,$tipoAutorizado,$tipoBusquedaOrigen, $varVacunador);
	$sitiosOrigen = $vdr->listaSitioEspecie($conexion,$tipoBusquedaOrigen, $varVacunador);
	$areasOrigen = $vdr->listaAreaEspecie1($conexion, 1, $varVacunador);
	$especie = $vdr->listaEspecieCatastro($conexion, $varVacunador);
		
	while ($fila = pg_fetch_assoc($autorizado)){
		echo '<option value="'. $fila['identificador'].'" data-autorizado="'.$fila['nombre_autorizado'].'">'. $fila['identificador'].' - '.$fila['nombre_autorizado'].'</option>';
	}
	echo '</select>';
	
}

if($opcion==2){//Búsqueda del responsables y autorizados para realizar la movilización para autoservicio	
	$idEmpresa = htmlspecialchars ($_POST['identificador_autoservicio'],ENT_NOQUOTES,'UTF-8');//propietario de autoservicio
	$identificador = htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8');
	
	$sitiosOrigenAutoservicio = $vdr->listaSitioMovilizacion($conexion,1, $idEmpresa, $identificador); //1 = ruc de la empresa de autoservicio
	$areasOrigen = $vdr->listaAreaEspecie1($conexion, 1, $idEmpresa); //1 = ruc de la empresa de autoservicio 
	$especie = $vdr->listaEspecieCatastro($conexion, $idEmpresa);
	
	echo '<label>Sitio origen: </label>';
	echo '<select id="cmbSitioOrigen" name="cmbSitioOrigen">';
	echo '<option value="0">Seleccione...</option>';
	while ($fila = pg_fetch_assoc($sitiosOrigenAutoservicio)){		
			echo '<option value="'. $fila['id_sitio']. '" data-codigo-provincia="'. $fila['codigo_provincia'].'">'.$fila['granja'].'</option>';
	}
	echo '</select>';
}

if($opcion==3){// Búsqueda del sitio destino      cmbTipoMovilizacionDestino
	$tipoSitioDestino = htmlspecialchars ($_POST['cmbTipoMovilizacionDestino'],ENT_NOQUOTES,'UTF-8');//sitios/ferias/cexposcion/camal
	$tipoBusquedaDestiono = 0;
	
	if($tipoSitioDestino==1){		
		$tipoBusquedaDestiono = 1;//cedula		
	}else{
		$tipoBusquedaDestiono = 2;//nombre
	}
	
	$varSitioDestino = htmlspecialchars ($_POST['txtSitioBusquedaDestino'],ENT_NOQUOTES,'UTF-8');
	$idEspecie = htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8');

	$sitiosDestino = $vdr->listaSitioEspecieCatastro($conexion,$tipoBusquedaDestiono, $varSitioDestino);
	$areasDestino = $vdr->listaAreaEspecie($conexion,$tipoBusquedaDestiono, $varSitioDestino, $idEspecie);
	echo '<label>Sitio destino: </label>';
	echo '<select id="cmbSitioDestino" name="cmbSitioDestino"  >';
	echo '<option value="0">Seleccione...</option>';
	while ($fila = pg_fetch_assoc($sitiosDestino)){
		echo '<option value="'. $fila['id_sitio'].'">'.$fila['granja'].'</option>';
	}
	echo '</select>';
}

if($opcion==4){//producto Catastro del sitio de origen	
	$idSitio2 = htmlspecialchars ($_POST['idSitioOrigen'],ENT_NOQUOTES,'UTF-8');
	$idArea2 = htmlspecialchars ($_POST['idAreaOrigen'],ENT_NOQUOTES,'UTF-8');
	$idEspecie2 = htmlspecialchars ($_POST['idEspecie'],ENT_NOQUOTES,'UTF-8');	
	$certificadoProductoVacunacion = $vdr->listaCertificadosProductoVacunacion($conexion, $idSitio2, $idArea2, $idEspecie2);
	$certificadoProductoMovilizacion = $vdr->listaCertificadosProductoMovilizacion($conexion, $idSitio2, $idArea2, $idEspecie2);
	
	echo '<label>Certificados de vacunación: </label>';
	echo '<select id="cmbNumeroCertificadoVacunacion" name="cmbNumeroCertificadoVacunacion" style="width: 356px;">';
	echo '<option value="0">Seleccione...</option>';
	foreach ($certificadoProductoMovilizacion as $fila){
		if($fila['numero_documento']=='Ninguno')
			echo '<option value="'.$fila['numero_documento'].'">'.$fila['numero_documento'].'</option>';
		else
			echo '<option value="'.$fila['numero_documento'].'">'.$fila['numero_documento'].' - Valido desde : '.$fila['fecha_vacunacion_desde'].' - hasta: '.$fila['fecha_vacunacion_hasta'].'</option>';
	}	
	echo '</select>';
}

if($opcion==5){//Búsqueda del responsables y autorizados para realizar la movilización para autoservicio
	$idEmpresa = htmlspecialchars ($_POST['identificador_autoservicio'],ENT_NOQUOTES,'UTF-8'); //propietario de autoservicio
	$tipoLugarDestino = htmlspecialchars ($_POST['cmbTipoMovilizacionDestinoAutoservicio'],ENT_NOQUOTES,'UTF-8');

	$idSitioOrigen = htmlspecialchars ($_POST['cmbSitioOrigen'],ENT_NOQUOTES,'UTF-8');//validar que no se repita
	$sitiosDestinoAutoservicio = $vdr->listaSitioAutoservicio($conexion, $tipoLugarDestino, $idEmpresa);
	$areasDestino = $vdr->listaAreaEspecie1($conexion, $tipoLugarDestino, $idEmpresa); //1 = ruc de la empresa de autoservicio

	echo '<label>Sitio destino: </label>';
	echo '<select id="cmbSitioDestinoAutoservicio" name="cmbSitioDestinoAutoservicio">';
	echo '<option value="0">Seleccione...</option>';
	while ($fila = pg_fetch_assoc($sitiosDestinoAutoservicio)){
		if($fila['id_sitio'] != $idSitioOrigen)
			echo '<option value="'. $fila['id_sitio'].'" data-codigo-provincia="'. $fila['codigo_provincia'].'" >'.$fila['provincia'].' - '.$fila['nombres'].' - '.$fila['granja'].'</option>';
	}
	echo '</select>';

}

if($opcion==6){//buscar el número certificado
	$serie = htmlspecialchars($_POST['serieCertificadoMovilizacion'],ENT_NOQUOTES,'UTF-8');
	$certificadoValorada1 = $vdr->numeroSerievalorada1($conexion, 'movilizacion', $serie);
	while ($filas = pg_fetch_assoc($certificadoValorada1)){
		 $certificado=$filas['estado'];
	
	}
	echo '<label>No.Certificado Movilización : </label>';
	echo '<select id="cmbCertificadoMovilizacion" name="cmbCertificadoMovilizacion">';
	echo '<option value="0">Seleccione...</option>';
	$certificadoValorada = $vdr->numeroSerievalorada($conexion, 'movilizacion', $serie, 'ingresado' );	
	while ($fila = pg_fetch_assoc($certificadoValorada)){
		echo '<option value="'. $fila['id_serie_documento'].'">'.$fila['numero_documento'].'</option>';	
	}
	echo '</select>';
if ($certificado!='inactivo' && $certificado!='activo' && $certificado!='anulado'   ){
		$certificado="Inexistente ( no registrado )";
	}
	if ($certificado=='activo'){
		$certificado="activo ( ya utilizado )";
	}
if(pg_num_rows($certificadoValorada)==0){
echo  '<script type="text/javascript">
var varjs="'.$certificado.'";			
alert("El número del certificado de movilización CSMI, esta como --> " + varjs )
</script>';
}}

$conexion->desconectar();
?>

<script type="text/javascript"> 
	 
	 var array_areaOrigen = <?php echo json_encode($areasOrigen); ?>;
	 var array_especies = <?php echo json_encode($especie); ?>;
	 var array_sitioOrigen = <?php echo json_encode($sitiosOrigen); ?>;
		 
	 var array_areaDestino = <?php echo json_encode($areasDestino); ?>;
	
	 var array_productoCatastro = <?php echo json_encode($certificadoProductoVacunacion); ?>;//$productoCatastro
	 var array_eventoOrigen= <?php echo json_encode($eventoOrigen); ?>;	 

	 $(document).ready(function(){		
		 if($("#opcion").val()==11)
			$("#cmbSitio").focus();	
		 distribuirLineas();     
	 });
	 

		
	 $("#cmbAutorizado").change(function(){        
    	if ($("#cmbAutorizado").val() !='0' ){
    		$('#identificacion_autorizado').val($("#cmbAutorizado").val());
    		$('#nombre_autorizado').val($("#cmbAutorizado option:selected").attr('data-autorizado'));
    		  
    		sSitioOrigen = '0';
    		sSitioOrigen = '<option value="0">Seleccione...</option>';
     		for(var i=0;i<array_sitioOrigen.length;i++){	
         		if ($("#cmbAutorizado").val()==array_sitioOrigen[i]['identificador_operador']){	    
     				sSitioOrigen += '<option value="'+array_sitioOrigen[i]['id_sitio']+'">'+array_sitioOrigen[i]['granja']+'</option>';
     			}			  
     		}   
     	    $('#cmbSitioOrigen').html(sSitioOrigen);
     	 	$('#cmbSitioOrigen').removeAttr("disabled");    

     	 	$('#fsLugarOrigen').show();  
     	 	$("#autorizadoMovilizacion").hide();   	 		  
    	}	    	
	 });
	 
     $("#cmbSitioOrigen").change(function(){         
 		sareaOrigen = '0';
 		sareaOrigen = '<option value="0">Seleccione...</option>';
 		for(var i=0;i<array_areaOrigen.length;i++){	
 			if ($("#cmbSitioOrigen").val()==array_areaOrigen[i]['id_sitio']){	    
 				sareaOrigen += '<option value="'+array_areaOrigen[i]['id_area']+'">'+array_areaOrigen[i]['nombre_area']+'</option>';
 			}			  
 		}   
 	    $('#cmbAreasOrigen').html(sareaOrigen);
 	 	$('#cmbAreasOrigen').removeAttr("disabled");
 	 	$('#codigoProvinciaOrigen').val($("#cmbSitioOrigen option:selected").attr("data-codigo-provincia"));
 	 	distribuirLineas();	 				
     }); 

     $("#cmbAreasOrigen").change(function(){         
  		sEspecie = '0';
  		sEspecie = '<option value="0">Seleccione...</option>';
  		for(var i=0;i<array_especies.length;i++){		  		
  			if (($("#cmbAreasOrigen").val()==array_especies[i]['id_area']) && ($("#cmbSitioOrigen").val()==array_especies[i]['id_sitio'])){	    
  				sEspecie += '<option value="'+array_especies[i]['id_especie']+'">'+array_especies[i]['nombre_especie']+'</option>';
  			}			  
  		}   
  	    $('#cmbEspecie').html(sEspecie);
  	 	$('#cmbEspecie').removeAttr("disabled");
 			 				
     }); 

     $("#cmbAreasOrigen").change(function(){
 		if ($("#cmbAreasOrigen").val()!='0'){
 			$('#sitio_origen').val($("#cmbSitioOrigen option:selected").text());
 			$('#sitio_origen2II').val($("#cmbSitioOrigen option:selected").text());
 			$('#area_origen2II').val($("#cmbAreasOrigen option:selected").text()); 
 			$('#id_sitio_origen').val($("#cmbSitioOrigen").val());
 			$('#id_area_origen').val($("#cmbAreasOrigen").val());			 				
 		}
 		
 	 });

     $("#cmbSitioDestino").change(function(){         
  		sareaDestino = '0';
  		sareaDestino = '<option value="0">Seleccione...</option>';
  		for(var i=0;i<array_areaDestino.length;i++){	
  			if ($("#cmbSitioDestino").val()==array_areaDestino[i]['id_sitio']){	 
  	  			if(array_areaDestino[i]['id_area']!=$("#cmbAreasOrigen").val())   
  					sareaDestino += '<option value="'+array_areaDestino[i]['id_area']+'">'+array_areaDestino[i]['nombre_area']+'</option>';
  			}			  
  		}   
  	    $('#cmbAreasDestino').html(sareaDestino);
  	 	$('#cmbAreasDestino').removeAttr("disabled");
 			 				
      }); 

     $("#cmbSitioDestinoAutoservicio").change(function(){  
        sareaDestino = '0';
   		sareaDestino = '<option value="0">Seleccione...</option>';   		
   		for(var i=0;i<array_areaDestino.length;i++){	
   			if ($("#cmbSitioDestinoAutoservicio").val()==array_areaDestino[i]['id_sitio']){	    	  			  
   					sareaDestino += '<option value="'+array_areaDestino[i]['id_area']+'">'+array_areaDestino[i]['nombre_area']+'</option>';
   			}			  
   		}    		  
   	    $('#cmbAreasDestinoAutoservicio').html(sareaDestino);
   	 	$('#cmbAreasDestinoAutoservicio').removeAttr("disabled");
   	    $('#codigoProvinciaDestino').val($("#cmbSitioDestinoAutoservicio option:selected").attr("data-codigo-provincia")); 				
       }); 

       $("#cmbAreasDestinoAutoservicio").change(function(){          
 		if ($("#cmbAreas_destino").val()!='0'){ 	 		
 			$('#sitio_destino').val($("#cmbSitioDestinoAutoservicio option:selected").text());
 			//$('#area_destino').val($("#cmbAreasDestinoAutoservicio option:selected").text());
 			$('#sitio_destino2').val($("#cmbSitioDestinoAutoservicio option:selected").text());
 			$('#area_destino2').val($("#cmbAreasDestinoAutoservicio option:selected").text());

 			$('#id_sitio_destino').val($("#cmbSitioDestinoAutoservicio").val());
 			$('#id_area_destino').val($("#cmbAreasDestinoAutoservicio").val()); 
 			$("#datosCertificado").show();			
 		}
   	  });    

     $("#cmbNumeroCertificadoVacunacion").change(function(){
    		if ($("#cmbNumeroCertificadoVacunacion").val()!='0'){    	 		
    			sproductoCatastro = '0';
    			sproductoCatastro = '<option value="0">Seleccione...</option>';
    	 		for(var i=0;i<array_productoCatastro.length;i++){        	 		
    	 			if ($("#cmbNumeroCertificadoVacunacion option:selected").val() == array_productoCatastro[i]['numero_documento']){    	 						   
    	 				sproductoCatastro += '<option value="'+array_productoCatastro[i]['id_producto']
    	 				                 +'" data-documento="'+array_productoCatastro[i]['numero_documento']
    	 								 +'" data-fecha="'+array_productoCatastro[i]['fecha_vacunacion_hasta']
    	 								 +'" data-fecha-nacimiento="'+array_productoCatastro[i]['fecha_nacimiento']
    	 								 +'" data-edad="'+array_productoCatastro[i]['edad_producto']
    	 				                 +'" data-existencia="'+array_productoCatastro[i]['total']+'">'
    	 				                 +array_productoCatastro[i]['producto']+'</option>';    	 				
    	 			}			  
    	 		}   
    	 	    $('#cmbAnimales').html(sproductoCatastro);
    	 	 	$('#cmbAnimales').removeAttr("disabled");	
    	 	 	$('#existente').val('');     	 	 	    	 	 	   	 	 	
    		}    	
     });

     $("#cmbAnimales").change(function(){
  		if ($("#cmbAnimales").val()!='0'){    	 	  	  		
  				$('#existente').val($("#cmbAnimales option:selected").attr('data-existencia'));  
  				$('#numeroCV').val('');  				  		
  		}
     });

</script>
