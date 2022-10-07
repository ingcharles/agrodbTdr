<?php
// Realiza el catastro de las especie de animales
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$contador = 0;
$resultado = "";
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$operador= htmlspecialchars ($_POST['operador'],ENT_NOQUOTES,'UTF-8');

	if($opcion==1){// Busqueda de los sitios por especie

		$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
		$nombreSitio = '%'.htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8').'%';
		$nombreOperador = '%'.htmlspecialchars ($_POST['nombreOperador'],ENT_NOQUOTES,'UTF-8').'%';
		$codigoArea =htmlspecialchars ($_POST['codigoArea'],ENT_NOQUOTES,'UTF-8');
		
		$buscarSitioOrigen = $vdr->filtrarSitiosCatastro($conexion, $identificadorOperador, $nombreOperador, $nombreSitio, $codigoArea );
		echo '<label>Nombre sitio: </label>';
		echo '<select id="cmbSitio" name="cmbSitio">';
		echo '<option value="0">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($buscarSitioOrigen)){
			echo '<option value="'. $fila['id_sitio'].'">'.$fila['identificador_operador'].' - '.$fila['granja'].' - '.$fila['provincia'].' </option>';
		}
		echo '</select>';
		
	}
	
	if($opcion==6){// Busqueda de los sitios por especie

		$idSitio = htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8');
		$areas = $vdr->listaArea($conexion,$idSitio);
		
		echo '<label>Nombre área: </label>';
		echo '<select id="areas" name="areas">';
		echo '<option value="0">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($areas)){
			echo '<option value="'. $fila['id_area'].'">'.$fila['nombre_area'].' </option>';
		}
		echo '</select>';
	
	}
	
	if($opcion==2){// Guardar el catastro animal
		$idEspecie = $_POST['hEspecies'];
		$nombreEspecie = $_POST['hNombreEspecies'];
		$idSitios = $_POST['hSitios'];
		$idAreas = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
		$idConceptos = $_POST['hConceptos'];
		$idCoeficiente = $_POST['hCoeficiente'];
		$idProductos = $_POST['hCodProductos'];
		$nombreProductos = $_POST['hProductos'];
		$cantidad = $_POST['hCantidad'];
		$dia = $_POST['hDia'];
		$fechaNacimiento = $_POST['hFechaNacimiento'];
	
		$fechaNacimientoI =  htmlspecialchars ($_POST['fecha_nacimiento'],ENT_NOQUOTES,'UTF-8'); //nacimiento del animal
		$fechaMortalidad = $_POST['fecha_muerte']; //muerte del animal
		$diaI = htmlspecialchars ($_POST['numeroDias'],ENT_NOQUOTES,'UTF-8'); //numero de dìas
			
		for($i=0; $i<count($idEspecie); $i++){
			if($cantidad[$i]>0){//Se valida para que se graben transacciones mayores a 0
				$producto = $nombreProductos[$i];
				$fechaNacimientoAnimal = $fechaNacimiento[$i];
				$fechaMortalidadAnimal = $fechaMortalidad;
				$idConceptosCatastro = $idConceptos[$i];
				$diaNacimiento = $dia[$i];
					
				if($nombreEspecie[$i]=='Porcinos'){//control xespecie
					if($idConceptosCatastro==2){//control por conceptos de catastro
						$fechaNacimientoAnimal = $fechaNacimientoI;
						$diaNacimiento = $diaI;
					}
					if($idConceptosCatastro<>7  ){//control por conceptos de catastro
						$fechaMortalidadAnimal = '';
					}
					
					
				}
	
				$datos = array('id_sitio' => $idSitios[$i]
						,'id_area' => $idAreas
						,'id_especie' => $idEspecie[$i]
						,'nombre_especie' => $nombreEspecie[$i]
						,'id_concepto_catastro' => $idConceptos[$i]
						,'numero_documento' => 'Ninguno'
						,'edad_producto' => $diaNacimiento
						,'id_producto' => $idProductos[$i]
						,'coeficiente' => $idCoeficiente[$i]
						,'cantidad' => $cantidad[$i]
						,'subTotal' => $cantidad[$i] * $idCoeficiente[$i]
						,'estado' => 'creado'
						,'fecha_nacimiento' => $fechaNacimientoAnimal
						,'fecha_mortalidad' => $fechaMortalidadAnimal
						,'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8')
				);
	
				$saldo = 0;
					
				$control = $vdr->validarCatastroAnimal($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['id_producto']);
				while ($fila = pg_fetch_assoc($control)){
					$saldo = $fila['total'];
				}
				$total =  $datos['subTotal']+$saldo;
				
				if ($total>=0){
					echo $fechaMortalidadAnimal ;
					$Catastro = $vdr->guardarDatosCatastro($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['nombre_especie']
							, $datos['id_concepto_catastro'], $datos['numero_documento'], $datos['edad_producto'], $datos['id_producto'], $datos['coeficiente']
							, $datos['cantidad'], $total, $datos['estado'], $datos['fecha_nacimiento'], $datos['fecha_mortalidad'], $datos['usuario_responsable'],'');
				}
					
				if ($datos['id_concepto_catastro']==7)// 7;"Muerte del animal"
				{
					$id_producto_condicion = 0;
					$controlProductoCondicion = $vdr->validarProductoCondicion($conexion, $datos['id_especie'], $datos['id_producto']);
					while ($fila = pg_fetch_assoc($controlProductoCondicion)){
						$id_producto_condicion = $fila['id_producto'];
						echo "id_producto_condicion => ".$id_producto_condicion;
					}
	
					if($id_producto_condicion==0){//si es diferente de lechon, que no se vacuna
						$saldo = 0;
							
						$controlVO = $vdr->validarCatastroVacunados($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['id_producto']);
	
						while ($fila = pg_fetch_assoc($controlVO)){
							$id_concepto_catastro = 11;//"Vacunación origen"
							$saldo = $fila['total'];
							$fecha_vacunacion = $fila['fecha_vacunacion'];
							$numero_documento = $fila['numero_documento'];
						}
	
						$total =  $saldo - $datos['cantidad'];
							
						if ($total>=0){
							echo "paso 2";
							$CatastroVacunacion = $vdr->guardarCatastroVacunacion($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['nombre_especie']
									, $datos['id_concepto_catastro'], $numero_documento, $datos['edad_producto'], $datos['id_producto'], $datos['coeficiente']
									, $datos['cantidad'], $total, $datos['estado'], $datos['fecha_nacimiento'], $fecha_vacunacion, $datos['usuario_responsable'], $datos['fecha_mortalidad']);
						}
						echo "...Catastro vacunacion --> fin";
					}
				}
				
				
				
				
	
			}//fin del if
		}
	}

	
	if($opcion==3){
	
		// Busqueda de los sitios por especie
		$nombreProvincia= htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
		$operador= htmlspecialchars ($_POST['operador'],ENT_NOQUOTES,'UTF-8');
		$qDistribuidores = $vdr->listarDistribuidoresXprovincia($conexion, 	$nombreProvincia, $operador);
		//echo '<input value= "'.pg_num_rows($qDistribuidores).'">';
		echo '<select id="distribuidor" name="distribuidor" style="width: 420px;">
			  <option value="">Selecionar....</option>';
	
		if($operador=="1" && pg_num_rows($qDistribuidores)>0)
			echo '<option value="TODOS">TODOS</option>';
		
		while ($fila = pg_fetch_assoc($qDistribuidores)){
			echo '<option value="'. $fila['identificador'].'">'.$fila['nombre_distribuidor'].' </option>';
		}
		
		echo '</select>';
	}

	if($opcion==4){
		// Busqueda de los sitios por especie
		$distribuidor= htmlspecialchars ($_POST['distribuidor'],ENT_NOQUOTES,'UTF-8');
		$nombreProvincia= htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
		$qDistribuidores = $vdr->listarVacunadoresXprovincia($conexion, $nombreProvincia, $operador);
		
		if($distribuidor=="TODOS" ){
			echo '<select id="vacunador" name="vacunador" style="width: 420px;" disabled>';
			echo '<option value="">Selecionar.....</option>';
			echo '</select>';
			
		}else{
			echo '<select id="vacunador" name="vacunador" style="width: 420px;" >';
			echo '<option value="">Selecionar....</option>';
			echo '<option value="TODOS">TODOS</option>';
			
			while ($fila = pg_fetch_assoc($qDistribuidores)){
				echo '<option value="'. $fila['identificador'].'">'.$fila['nombre_vacunador'].' </option>';
			}
			
			echo '</select>';
			
		}
	}
	
	if($opcion==5){
		
		$sitios =$vdr->listaSitioProvincia($conexion, $_POST['provincia']);
		echo '<select id="sitio" name="sitio" style="width: 425px;" >';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($sitios)){
			echo '<option value="'. $fila['id_sitio'].'">'.$fila['granja'].' - '.$fila['identificador_operador'].'</option>';
		}
		echo '</select>';
	
	}
	
	
$conexion->desconectar();
?>

<script type="text/javascript">     
	 //var array_area = <?php echo json_encode($areas); ?>;

	 $(document).ready(function(){	
			
		 if($("#opcion").val()==1)
			$("#cmbSitio").focus();

		 distribuirLineas(); 
	 });


	 $("#distribuidor").change(function(event){ 
		 
		$("#divEsconderVacunador").hide();
			
		if($("#distribuidor").val()!='0'){		
			 $('#filtrarVacunacionAnimal').attr('data-opcion','accionesCatastroAnimal');
			 $('#filtrarVacunacionAnimal').attr('data-destino','resultadoVacunador');
		     $('#opcion').val('4');		     	
			 abrir($("#filtrarVacunacionAnimal"),event,false);
		}
		
	}); 
	 
     $("#cmbSitio").change(function(event){         

    	 if($("#cmbSitio").val()!='0'){		
			 $('#nuevoRegistroCatastro').attr('data-opcion','accionesCatastroAnimal');
			 $('#nuevoRegistroCatastro').attr('data-destino','resultadoArea');
		     $('#opcion').val('6');		     	
			 abrir($("#nuevoRegistroCatastro"),event,false);
		}
    	 distribuirLineas(); 
			 				
     }); 

     $("#areas").change(function(event){
 		if ($("#areas").val()!='0'){

 			$("#idArea").val($("#areas").val());
			 $("#cmbConceptoCatastro").removeAttr("disabled");	
			 
 			$("#div1").hide();
 			$("#div2").hide();
 			$("#div3").hide();
 		//	$("#res_sitio").hide(); 			
 			$("#div4").show();
 			$("#divPie").show();
 			$("#div6").show();
 			$("#div7").show();
 			$("#div8").show();
 			$("#div9").show();
 			$("#div10").show();
 			
 			$('#txtNombreSitio').val($("#cmbSitio option:selected").text());
 		}
 		
 	});
   	
</script>


