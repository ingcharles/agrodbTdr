<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	
	$arrayPerfil=array('PFL_SANID_ANIMA','PFL_SANID_VEGET','PFL_LABORATORIO','PFL_INOCU_ALIME','PFL_INSUM_PLAGU','PFL_INSUM_VETER','PFL_INSUM_PRO_AU');
	$banderaPerfil=false;
	foreach ($arrayPerfil as $codificacionPerfil ){
	
		$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
		if(pg_num_rows($qVerificarUsuario)>0){
			$banderaPerfil=true;
			switch ($codificacionPerfil){
				case 'PFL_SANID_ANIMA':
					$areaTematica.= "'SA',";
					$areaTematicaDescripcion.= "Sanidad Animal,";
				break;
	
				case 'PFL_SANID_VEGET':
					$areaTematica.="'SV',";
					$areaTematicaDescripcion.= "Sanidad Vegetal,";
				break;
	
				case 'PFL_LABORATORIO':
					$areaTematica.="'LT',";
					$areaTematicaDescripcion.= "Laboratorios,";
				break;
	
				case 'PFL_INOCU_ALIME':
					$areaTematica.="'AI',";
					$areaTematicaDescripcion.= "Inocuidad de alimentos,";
				break;
					
				case 'PFL_INSUM_PLAGU':
					$areaTematica.="'IAP',";
					$areaTematicaDescripcion.= "Registro de insumos agrícolas,";
	
				break;
	
				case 'PFL_INSUM_VETER':
					$areaTematica.="'IAV',";
					$areaTematicaDescripcion.= "Registro de insumos pecuarios,";
				break;
				
				case 'PFL_INSUM_FERTIL':
					$areaTematica.="'IAF',";
					$areaTematicaDescripcion.= "Registro de insumos fertilizantes,";
				break;
				
				case 'PFL_INSUM_PRO_AU':
					$areaTematica.="'IAPA',";
					$areaTematicaDescripcion.= "Registro de insumos para plantas de autoconsumo,";
				break;
			}
		}
	}
	
	if(!$banderaPerfil)
		$areaTematica="''";
	
	$tipoProducto = $cr->listarTipoProductoXAreas($conexion,"(" . rtrim ( $areaTematica, ',' ) . ")");
	$pais = $cc->listarSitiosLocalizacion($conexion,'PAIS');
	$gruposPais = $cc->listarSitiosLocalizacion($conexion,'GRUPOS_PAISES');
	
?>

<header>
	<h1>Nuevo requisito de comercialización</h1>
</header>

<div id="estado"></div>

<form id="nuevoRequisitoComercio" data-rutaAplicacion="administracionRequisitos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" >
	
	<input type="hidden" id="opcionProductoPais" name="opcionProductoPais" value="">
	<fieldset id="grupoProducto">
		<legend>Producto</legend>
			<div data-linea="1">			
				<label>Tipo de producto</label> 
				<select id="tipoProducto" name="tipoProducto" required>
					<option value="">Tipo de producto....</option>
						<?php 
							while ($fila = pg_fetch_assoc($tipoProducto)){
								$opcionesTipoPorducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
							//echo 	 '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
							
							}
							
						?>
				</select>
			</div>
			
			<div data-linea="2" id="resultadoSubTipoProducto" required>			
				<label>Subtipo de producto</label> 
				<select id="subtipoProducto" name="subtipoProducto" >
					<option value="">Subtipo de producto....</option>
				</select>
			</div>
			
			<div data-linea="3" id="resultadoProducto" required>
				<label>Producto</label> 
				<select id="producto" name="producto" >
					<option value="">Producto....</option>	
				</select>	
			</div>	
	</fieldset>
	<fieldset>
		<legend>País</legend>
			<div data-linea="4">			
				<label>Nombre</label> 
				<select id="pais" name="pais" required>
					<option value="">País....</option>
				</select>
					
			</div>	
			<button type="submit" id="agregar" class="mas">Añadir</button>
	</fieldset>
		
</form>

<fieldset>
	<legend>Países</legend>
	<table id="requisitoComercio">
	</table>
</fieldset>

<script type="text/javascript">
	var array_pais= <?php echo json_encode($pais); ?>;
	var array_grupos_pais= <?php echo json_encode($gruposPais); ?>;
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoPorducto);?>;
	 		
	$('document').ready(function(){	
		acciones("#nuevoRequisitoComercio","#requisitoComercio");
		distribuirLineas();

		if(array_opcionesTipoProducto!=null){
			for(var i=0; i<array_opcionesTipoProducto.length; i++){
				$('#tipoProducto').append(array_opcionesTipoProducto[i]);
	    	}
	    }
		   
		spais ='0';
		spais = '<option value="">País....</option>';
		for(var i=0; i<array_grupos_pais.length; i++){
		    spais += '<option value="'+array_grupos_pais[i]['codigo']+'">'+array_grupos_pais[i]['nombre']+'</option>';
	    }
		for(var i=0; i<array_pais.length; i++){
		    spais += '<option value="'+array_pais[i]['codigo']+'">'+array_pais[i]['nombre']+'</option>';
	    }		
	    $('#pais').html(spais);
		
	});

	$("#tipoProducto").change(function(event){  	
		if( $("#tipoProducto").val()!=''){
			$('#nuevoRequisitoComercio').attr('data-destino','resultadoSubTipoProducto');
			$('#nuevoRequisitoComercio').attr('data-opcion','accionesRequisitoPais');
			$('#opcionProductoPais').val('listaSubTipoProducto');
			abrir($("#nuevoRequisitoComercio"),event,false);
			$("#producto").val('');
		}	 		
	 }); 
	 
	$("#agregar").click(function(event){
		$('#nuevoRequisitoComercio').attr('data-opcion','guardarNuevoProductoPais');
	});
	 
</script>