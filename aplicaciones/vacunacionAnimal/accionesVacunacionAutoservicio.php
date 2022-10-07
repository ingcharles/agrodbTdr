<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$contador = 0;
$resultado = "";
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

if($opcion==1){
	$idOperadorVacunacion = htmlspecialchars ($_POST['cmbEmpresa'],ENT_NOQUOTES,'UTF-8');
	$vacunadoresOficiales=$vdr->listarVacunadoresOficialesAutoservicio($conexion, $idOperadorVacunacion);
	echo '<label>Vacunador oficial </label>';
	echo '<select id="cmbVacunador" name="cmbVacunador">';
	echo '<option value="0">Seleccionar...</option>';
	foreach ($vacunadoresOficiales as $fila){
		echo '<option data-identificador-operador="'. $fila['identificador_administrador'].'"  data-identificador-distribuidor="'. $fila['identificador_distribuidor'].'" value="'. $fila['identificador_vacunador'].'">'.$fila['identificador_vacunador'].' -  '.$fila['nombre_vacunador'].'</option>';
	}
	echo '</select>';
}

if($opcion==2){
	$id_sitio = htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8');
	$areas = $vdr->listaArea($conexion,4, $id_sitio);
	echo '<select id="areas" name="areas">';
	echo '<option value="0">Seleccionar...</option>';
	foreach ($areas as $areasArray){
		echo '<option value="' . $areasArray['id_area'] . '">' . $areasArray['nombre_area'] . '</option>';
	}
	echo '</select>';
}

if($opcion==3){
	$valorTipoVacunacion = htmlspecialchars ($_POST['tipo_vacunacion'],ENT_NOQUOTES,'UTF-8');
	$serie = htmlspecialchars($_POST['especie_valorada'],ENT_NOQUOTES,'UTF-8');	
	$especieValorada = $vdr->numeroSerievalorada2($conexion, 'vacunacion', $serie);
	if(pg_num_rows($especieValorada)==0){
		echo '<label>0</label>';
		echo '<input type="hidden" id="numero_especie" name="numero_especie" value="0" readonly/>';
		echo "<script type='text/javascript'>
    				alert('El número de certificado de vacunación, ya esta registrado !!');
    		  </script>";		
	}else{
		while ($fila = pg_fetch_assoc($especieValorada)){
			if($fila['estado']=='ingresado'){
				echo '<label>No.Certificado: </label>';
				echo '<label> '.$fila['numero_documento'].'</label>';				
				echo '<input type="hidden" id="numero_especie" name="numero_especie" value="'. $fila['numero_documento'].'" />';			
			}			
		}
	}	
}

if($opcion==4){
	$id_sitio = htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8');
	$id_area = htmlspecialchars ($_POST['cmbArea'],ENT_NOQUOTES,'UTF-8');
	$id_especie = htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8');
	$catastroProducto = $vdr->catastroValorProductoVacunado($conexion, $id_sitio, $id_area, $id_especie);
	echo "<table id='tablaVacunaAnimal' >";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Productos</th>";
	echo "<th>Existentes</th>";
	echo "<th style='width:200px; '>Vacunados</th>";
	echo "<th style='width:200px; '>Observación</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody id='tabCatastro'>";
	
	$totalExistencias=0;
	$valorInicaial = 0;	
	while ($fila = pg_fetch_assoc($catastroProducto)){	
		if($fila['codigo']!='PORHON'){
	  		if($fila['codigo']!='PORLTO'){
				$codigoProducto = $fila['id_producto'];
				$nombreProducto = $fila['producto'];
				$existencia = $fila['total_existencias'];		
				$edad = $fila['edad_producto'];
				$fecha_nacimiento = $fila['fecha_nacimiento'];
				$totalExistencias = $totalExistencias+$existencia;		
				echo "<tr>";
				echo "<td>";
				echo "<input id='hCodProductos' name='hCodProductos[]' value='".$codigoProducto."' type='hidden'>";
				echo "<input id='hEdad' name='hEdad[]' value='".$edad."' type='hidden'>";
				echo "<input id='hFechaNacimiento' name='hFechaNacimiento[]' value='".$fecha_nacimiento."' type='hidden'>";
				echo "<input id='hProductos' name='hProductos[]' value='".$nombreProducto."' type='hidden'>".$nombreProducto;
				echo "</td>";
				echo "<td>";
				echo "<input id='hExistencias' name='hExistencias[]' value='".$fila['total_existencias']."' type='hidden'>".$fila['total_existencias'];
				echo "</td>";
				if ($fila['total_existencias']==0){
					echo "<td>";
					echo "<div data-linea='1'>";
					echo "<input id='existenciaCatastro' name='existenciaCatastro' value='0' type='text'  maxlength='8' disabled='disabled'>";
					echo "</div>";
					echo "<div data-linea='1'>";
					echo "<input id='hCantidad' name='hCantidad[]'  value='0' type='hidden' class='cantidad'>";
					echo "</div>";
					echo "</td>";
					echo "<td>";
					echo "<div data-linea='1'>";
					echo "<input id='observacionCatastro' name='observacionCatastro' value='0' type='text' ' maxlength='40' disabled='disabled'>";
					echo "</div>";
					echo "<div data-linea='1'>";
					echo "<input id='hObservacion' name='hObservacion[]'  type='hidden' value=''>";
					echo "</div>";
					echo "<div data-linea='1'>";
					echo "</div>";
					echo "</td>";
				}else{
					echo "<td  >";
					echo "<div data-linea='1'>";
					echo "<input id='hCantidad' name='hCantidad[]' value='0' type='text' maxlength='8' onkeypress='ValidaSoloNumeros()' class='cantidad'>";
					echo "</div>";
					echo "<div data-linea='1'></div>";
					echo "</td>";
					echo "<td>";
					echo "<div data-linea='1'>";
					echo "<input id='hObservacion' name='hObservacion[]' value='0' type='text'  maxlength='40'>";
					echo "</div>";
					echo "<div data-linea='1'>";
					echo "</div>";
					echo "<div data-linea='1'>";
					echo "</div>";
					echo "</td>";
				}
				echo "</tr>";		
		 	}
		}
	}
	echo "</tbody>";
	echo "</table>";
	echo "<div data-linea='1'>";
	echo "<label>Total existentes</label>";
	echo "<input type='text' id='totalExistentes' name='totalExistentes' value='".$totalExistencias."' disabled='disabled'/>";
	echo "</div>";
	echo "<div data-linea='1'>";
	echo "<label>Total vacunados</label>";
	echo "<input type='text' id='totalVacunados' name='totalVacunados'  value='0' disabled='disabled' />";
	echo "</div>";
}

if($opcion==10){	
	$estadoCV=false;
	$fecha_vencimiento3 = htmlspecialchars ($_POST['fecha_emision'],ENT_NOQUOTES,'UTF-8');
	$fecha_emision=str_replace("/","-",$fecha_vencimiento3);
	$fecha_vencimiento1 = strtotime ('6 month',strtotime($fecha_emision)) ;
	$fecha_vencimiento = date('d-m-Y',$fecha_vencimiento1);
	$datos = array('id_sitio' => htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8')
			,'id_area' =>  htmlspecialchars ($_POST['cmbArea'],ENT_NOQUOTES,'UTF-8')
			,'id_especie' => htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8')
			,'nombre_especie' => htmlspecialchars ($_POST['nombre_especie'],ENT_NOQUOTES,'UTF-8')			
			,'identificador_administrador' => htmlspecialchars ($_POST['operadorVacunacion'],ENT_NOQUOTES,'UTF-8')								
			,'identificador_distribuidor' => htmlspecialchars ($_POST['distribuidorVacunacion'],ENT_NOQUOTES,'UTF-8')
			,'identificador_vacunador' => htmlspecialchars ($_POST['cmbVacunador'],ENT_NOQUOTES,'UTF-8')
			,'id_lote' => htmlspecialchars ($_POST['lote'],ENT_NOQUOTES,'UTF-8') //lote de vacución
			,'id_tipo_vacuna' => htmlspecialchars ($_POST['tipoVacuna'],ENT_NOQUOTES,'UTF-8')//tipo de vacuna es autoservicio
			,'num_certificado' => htmlspecialchars ($_POST['numero_especie'],ENT_NOQUOTES,'UTF-8') //numero de la especie
			,'control_areteo' => 'si'//pasa el valor desde interface
			,'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8')
			,'costo_vacuna' => htmlspecialchars ($_POST['costo_vacuna'],ENT_NOQUOTES,'UTF-8')
			,'estado_vacunado' => 'activo'
			,'fecha_vacunacion' => htmlspecialchars ($_POST['fecha_emision'],ENT_NOQUOTES,'UTF-8')
			,'fecha_vencimiento' => $fecha_vencimiento
	);
	$id_producto = $_POST['hCodProductos'];
	$existente = $_POST['hExistencias'];
	$vacunado = $_POST['hCantidad'];
	$observacion = $_POST['hObservacion'];
	$edadProducto = $_POST['hEdad'];
	$fechaNacimiento = $_POST['hFechaNacimiento'];
	$total_existente = 0;
	$total_vacunado = 0;
	//se corrigio esta parte para que no se guarde la vacunacion animal con valor 0
	for($k=0; $k<count($id_producto); $k++){
		if($vacunado[$k]>0){
			$detalles = array('vacunados' => $vacunado[$k]);
			$total_vacunados = $total_vacunados + $detalles['vacunados'];
		}
	}
	if($total_vacunados!=0){
		  $Vacuna = $vdr->guardarDatosVacunacion($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['nombre_especie']
		, $datos['identificador_administrador'], $datos['identificador_distribuidor'], $datos['identificador_vacunador']
		, $datos['id_lote'], $datos['id_tipo_vacuna'], $datos['num_certificado'], $datos['control_areteo'], $datos['usuario_responsable']
		, $datos['costo_vacuna'], $datos['estado_vacunado'], $datos['fecha_vacunacion']);
	      $idVacuna = pg_fetch_result($Vacuna, 0, 'id_vacuna_animal');
	}
	//Detalle de la vacuna
	for($j=0; $j<count($id_producto); $j++){
	  if($vacunado[$j]>0){
		$detalle = array(
				'id_vacuna_animal' => $idVacuna,
				'id_producto' =>  $id_producto[$j],
				'existente' => $existente[$j],
				'vacunado' => $vacunado[$j],
				'observacion' => $observacion[$j]
		);

		$Detalle = $vdr->guardarDetalleVacunacion($conexion, $detalle['id_vacuna_animal'], $detalle['id_producto'], $detalle['existente'], $detalle['vacunado'], $detalle['observacion']);
		$idDetalle = pg_fetch_result($Detalle, 0, 'id_vacuna_animal_detalle');
		$total_existente = $total_existente + $detalle['existente'];
		$total_vacunado = $total_vacunado + $detalle['vacunado'];
		$idConceptos = 10;
		$idCoeficiente = 1; //Registro de vacunación	
		$catastro = array(
				'id_sitio' => $datos['id_sitio']
				,'id_area' => $datos['id_area']
				,'id_especie' => $datos['id_especie']
				,'nombre_especie' => $datos['nombre_especie']
				,'id_concepto_catastro' => $idConceptos
				,'numero_documento' => $datos['num_certificado']
				,'edad_producto' => $edadProducto[$j]
				,'id_producto' => $detalle['id_producto']
				,'coeficiente' => $idCoeficiente
				,'cantidad_vacunado' => $detalle['vacunado']
				,'total_vacunado' => $detalle['vacunado'] * $idCoeficiente
				,'estado' => 'creado'
				,'usuario_responsable' => $datos['usuario_responsable']
				,'fecha_nacimiento' => $fechaNacimiento[$j]
				,'fecha_vacunacion' => $datos['fecha_vacunacion']
		);
		
		$Valida = $vdr-> validarProductoVacunacion($conexion, $catastro['id_especie'], $catastro['id_producto']);
		if(pg_num_rows($Valida) > 0 ){
			$saldo = 0;
			$control = $vdr->validarCatastroAnimalVacunado($conexion, $catastro['id_sitio'], $catastro['id_area'], $catastro['id_especie'], $catastro['id_producto'], $datos['num_certificado']);
			while ($fila = pg_fetch_assoc($control)){
				$saldo = $fila['total_vacunado'];
			}
			$total =  $catastro['total_vacunado']+$saldo;
			$Catastro = $vdr->guardarDatosCatastroVacunacion($conexion, $catastro['id_sitio'], $catastro['id_area'], $catastro['id_especie'], $catastro['nombre_especie']
					, $catastro['id_concepto_catastro'], $catastro['numero_documento'], $catastro['edad_producto'], $catastro['id_producto'], $catastro['coeficiente']
					, $catastro['cantidad_vacunado'], $total, $catastro['estado'], $catastro['fecha_nacimiento'], $catastro['fecha_vacunacion'], $catastro['usuario_responsable'],'');
		}

		$Vacuna = $vdr->actualizarDatosVacunacionTotales($conexion, $idVacuna, $total_existente, $total_vacunado, $datos['costo_vacuna']);						
		$estadoCV = true;
	   }	
	}
	//Grabar el contro areteo
	$id_areaArete = $_POST['hCodSerie_aretes'];
	$serie_inicio = $_POST['hSerie_inicio'];
	$serie_fin = $_POST['hSerie_fin'];
	//Control Areteo //si --> se aretea
	if($datos['control_areteo']=='si'){
		for($i=0; $i<count($id_areaArete); $i++){
			if($serie_inicio[$i] == $serie_fin[$i]){ //Serie Individual
				echo "Serie Individual";
				$serie = $serie_inicio[$i];
				$Arete = $vdr->guardarDatosVacunacionAnimalArete($conexion, $idVacuna, $serie, $datos['fecha_vacunacion'], $datos['fecha_vencimiento']);
			}
			else{ //Serie colectivo
				echo "Serie colectivo";
				$contador = ($serie_fin[$i] - $serie_inicio[$i])+1;
				$valor = 0;
				for($j=0; $j<$contador; $j++){
					$serie = $serie_inicio[$i]+$valor;
					$Arete = $vdr->guardarDatosVacunacionAnimalArete($conexion, $idVacuna, $serie, $datos['fecha_vacunacion'], $datos['fecha_vencimiento']);
					$valor++;
				}
			}
		}//fin del for
	}
	//Actualizar numero de documento de vacunación
	if($estadoCV){
		$actualizarNumeroDocumento = array(
				'id_especie' => $datos['id_especie']
				,'nombre_especie' => $datos['nombre_especie']
				,'tipo_documento' => 'vacunacion'
				,'numero_documento' => $datos['num_certificado']
				,'estado' => 'activo'
		);
		$EspecieDocumento = $vdr->actualizarNumeroCertificado($conexion, $actualizarNumeroDocumento['nombre_especie']
				, $actualizarNumeroDocumento['tipo_documento'], $actualizarNumeroDocumento['numero_documento'], $actualizarNumeroDocumento['estado']);
	}
	$conexion->desconectar();   
}
?>
<script type="text/javascript">     
	 var array_area = <?php echo json_encode($areas); ?>;
	 $(document).ready(function(){	
			if($('#numero_especie').val()!="0"){
				$("#infoEspecie").hide();
				$("#infoVacuna").show();
				$("#infoSitio").show();
				if($("#opcion").val()==3)
					$("#fecha_emision").focus();
			}			     
	 });
	 
	 $("#tabCatastro input.cantidad").change(function(){
			var total=0;
			var indice= $("#tabCatastro input.cantidad").index(this);
			var existencia = $("#tabCatastro tr").eq(indice).find("input[id='hExistencias']").val();
			var vacunados = parseInt($(this).val());
			if(vacunados <= existencia){
				$("#tabCatastro input.cantidad").each(function(){					
					total+=parseInt($(this).val());
				});		
				$('#totalVacunados').val(total);
				$('#tVacunados').val(total);	
			}else{
				$("#tabCatastro tr").eq(indice).find("input[id='hCantidad']").val(0);
				alert("Cantidad es incorrecta");
			}				
	 });
	 
	 function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))		 
		  event.returnValue = false;
	 }
	 
	 $("#cmbSitio").change(function(){ 
		if($("#cmbSitio").val() != 0){
			sarea = '0';
			sarea = '<option value="">Seleccione...</option>';
			for(var i=0;i<array_area.length;i++){	
				if ($("#cmbSitio").val()==array_area[i]['id_sitio']){	    
					sarea += '<option value="'+array_area[i]['id_area']+'">'+array_area[i]['nombre_area']+' -  '+array_area[i]['tipo_area']+'</option>';
				}			  
			}   
		    $('#areas').html(sarea);
		 	$("#areas").removeAttr("disabled");	
		}	          					 				
	 });
	 
     $("#numero_especie").change(function(){
    	 if($("#numero_especie").val() != 0){        	
          	$("#infoEspecie").hide();         	
          	$("#infoVacuna").show();
          	$("#infoSitio").show();         	
     	 }else
     		 $('#numero_especie').val('');
     }); 
     
     $("#cmbVacunador").change(function(){
 		$("#operadorVacunacion").val($("#cmbVacunador option:selected").attr('data-identificador-operador'));
 		$("#distribuidorVacunacion").val($("#cmbVacunador option:selected").attr('data-identificador-distribuidor'));
 	});         
</script>