<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	$clase = $_POST['clase'];
	$codigo = $_POST['codigo'];
	$nombre = $_POST['nombre'];

	
	$tipo = $_POST['tipo'];

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();

	switch($tipo){
		case 'simple':
		case 'configuracion':
			$item=$ce->obtenerItemDelCatalogo($conexion,$clase,$codigo);
			break;
		case 'extendido':
			$item=$ce->obtenerItemDelCatalogoEx($conexion,$clase,$codigo);
			break;
	}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle del item del catálogo </h1>
	</header>
	
	<div id="estado"></div>
   <form id="regresar" data-rutaAplicacion="ensayoEficacia" data-opcion="abrirCatalogoSimple" data-destino="detalleItem">
      <input type="hidden" name="id" value="<?php echo $clase;?>" />
      <input type="hidden" name="idFlujo" value="<?php echo $tipo;?>" />
		<input type="hidden" name="nombreOpcion" value="<?php echo $nombre;?>" />
      <button class="regresar">Regresar al catálogo</button>
   </form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<div >	
			
               <form id="actualizarItem" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarCatalogoOpciones">

                  <button id="modificar" type="button" class="editar">Editar</button>
                  <button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>

                  <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>" />
                  <input type="hidden" id="clase" name="clase" value="<?php echo $clase;?>" />
                  <input type="hidden" id="paso_catalogo" name="paso_catalogo" value="C1" />

                  <fieldset>
                     <legend>
                        Item de : <?php echo $nombre;?>
                     </legend>
                     <div data-linea="1">
                        <label for="codigoCatalogo">Código</label>
                        <input id="codigoCatalogo" name="codigoCatalogo" type="text" value="<?php echo $item['codigo'];?>" maxlength="8" disabled="disabled" />
                     </div>


                     <div class="justificado">
                        <label for="nombreCatalogo">Nombre</label>
                        <textarea id="nombreCatalogo" name="nombreCatalogo"  maxlength="1024" required="required" disabled="disabled">
									<?php echo $item['nombre'];?>
								</textarea>

                     </div>

                     <div class="extendido justificado">
                        <label for="nombre2">Descripción</label>
                        <textarea id="nombre2" name="nombre2"  maxlength="256" disabled="disabled">
									<?php echo $item['nombre2'];?>
								</textarea>

                     </div>

                     <div class="extendido justificado">
                        <label for="nombre3">Dato complementarios</label>
                        <textarea id="nombre3" name="nombre3"  maxlength="256" disabled="disabled">
									<?php echo $item['nombre3'];?>
								</textarea>

                     </div>

                  </fieldset>

               </form>	
				
				</div>
				
			</td>
		</tr>	
	</table>		
</body>

<script type="text/javascript">

	var tipo=<?php echo json_encode($tipo); ?>;

	$('document').ready(function(){
		if (tipo == 'extendido')
			$('.extendido').show();
		else
			$('.extendido').hide();

	   distribuirLineas();
		construirValidador();

  });

	$("#modificar").click(function () {
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled", "disabled");
	});

	$("#actualizarItem").submit(function (event) {
		event.preventDefault();

		
		var error = false;
		
		if (!$.trim($("#nombreCatalogo").val())) {
			error = true;
			$("#nombreCatalogo").addClass("alertaCombo");
		}
		if (!$.trim($("#codigoCatalogo").val())) {
			error = true;
			$("#codigoCatalogo").addClass("alertaCombo");
		}
		


		if (error) {
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		} else {
			ejecutarJson($(this));
		}

	});

	$("#regresar").submit(function (event) {
		abrir($(this), event, false);
	});


</script>
</html>
