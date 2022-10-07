<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$qSitio = $cr ->abrirSitio($conexion, $_POST['id']);
$sitio = pg_fetch_assoc($qSitio);

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

?>



<header>
	<h1>Nuevo Sitio Operador</h1>
</header>

	<div class="pestania">

<form id='nuevoSitio' data-rutaAplicacion='registroOperador' data-opcion='actualizarSitio' data-destino="detalleItem">
	
	<div id="estado"></div>
	
	<input type="hidden" id="idSitioS" name="idSitioS"	value="<?php echo $_POST['id'];?>" />
	
	<fieldset>
		<legend>Información del Sitio</legend>
						
			<label>Nombre del sitio</label> 
			<input type="text" id="nombreSitio" name="nombreSitio" placeholder="Ej: Hacienda..." value="<?php echo $sitio['nombre_lugar'];?>" disabled="disabled" required="required"/>
			
			<label>Superficie total</label> 
				<input type="text" id="superficieTotal" name="superficieTotal" placeholder="Ej: 123.56" value="<?php echo $sitio['superficie_total'];?>" disabled="disabled" required="required"/>

		<div id="divLocalizacion">
			<label>Provincia</label>
				<select id="provincia" name="provincia" disabled="disabled">
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
	
			<label>Cantón</label>
				<select id="canton" name="canton" disabled="disabled">
				</select>
				
		</div>
		
			<label>Parroquia</label>
				<select id="parroquia" name="parroquia" disabled="disabled">
				</select>
		
			<label>Dirección</label> 
				<input type="text" id="direccion" name="direccion" placeholder="Ej: Santa Rosa" value="<?php echo $sitio['direccion'];?>" required="required" disabled="disabled"/>
				
			<label>Teléfono</label> 
				<input type="text" id="telefono" name="telefono" placeholder="Ej: 2456985" value="<?php echo $sitio['telefono'];?>" disabled="disabled" />
				
	</fieldset>
	
</form>

	</div>
	
	<div class="pestania">
	
	<form id='guardarNuevaArea' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevaArea' data-destino="detalleItem">
	
		<input type="hidden" id="idSitioP" name="idSitioP"	value="<?php echo $_POST['id'];?>" />

	</form>
					 
		<fieldset>
			<legend>Áreas agregadas</legend>
				
					 <div>
						<table id="listadoAreas">
							<thead>
								<tr>
								
									<th></th>
									<th>Nombre</th>
									<th>Tipo</th>
									<th>Superficie</th>
								
								<tr>
							</thead> 
							<tbody id="areas">
								
									<?php
							
										$res = $cr->listarAreaOperador($conexion, $_POST['id']);
																	
										while($fila = pg_fetch_assoc($res)){
											
											echo "<tr id='r_".$fila['nombre_lugar']."'>
													<td> 
														<form id='f_".$fila['nombre_lugar']."' data-rutaAplicacion='registroOperador' data-opcion='quitarArea'  >
															
															<input name='nombreArea' value='".$fila['nombre_lugar'] ."' type='hidden'>
															<input name='idSitio' value='".$fila['id_sitio'] ."' type='hidden'>
														</form>
													</td>
													<td>".$fila['nombre_lugar']."</td>
													<td>".$fila['tipo_area']."</td>
													<td>".$fila['superficie_utilizada']."</td>
												</tr>";
										}   
										
										
									?>
							
							</tbody>
						</table>
					</div>
	
		</fieldset>
	</div>

	


<script type="text/javascript">
	
	
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	
    $("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	});

    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});
    
		
	

	$(document).ready(function(){
		construirAnimacion($(".pestania"));	
		$("#divLocalizacion").hide();
		//$('<option value="< ?php echo $localizacion['id_localizacion'];?>">< ?php echo $localizacion['nombre'];?></option>').appendTo('#parroquia');			
	});

	


	$("#listadoAreas").on("submit","form",function(event){
		  
		event.preventDefault();
		ejecutarJson($(this));
		var texto=$(this).attr('id').substring(2);
		texto=texto.replace(/ /g,'');
		texto="#r_"+texto;
		$("#areas tr").eq($(texto).index()).remove();
			
	});

	

</script>
