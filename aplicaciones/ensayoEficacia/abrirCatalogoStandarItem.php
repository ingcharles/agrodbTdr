<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	$claveValor= $_POST['claveValor'];
	$clave = $_POST['clave'];
	$tabla = $_POST['tabla'];
	$nombre = $_POST['nombre'];
	$tipo = $_POST['tipo'];
	$elementos = $_POST['elementos'];

	$clase=$clave;
	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();

	$tablaNombres=explode(',',$tabla);
	$tablaNombre=array_slice($tablaNombres,0,1);
	$camposFijos=array_slice($tablaNombres,1);

	$tablaEsquema=explode('.',$tablaNombre[0]);

	$items=$ce->obtenerTablaStandar($conexion,$tablaNombre[0],$clave,$claveValor);
	$dato=$items[0];
	$esquema=$ce->obtenerEsquema($conexion,$tablaEsquema[0],$tablaEsquema[1]);

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

   <form id="regresar" data-rutaAplicacion="ensayoEficacia" data-opcion="abrirCatalogoStandar" data-destino="detalleItem">
      <input type="hidden" name="id" value="<?php echo $clase;?>" />
		<input type="hidden" name="opcion" value="<?php echo $tabla;?>" />
      
      <input type="hidden" name="idFlujo" value="<?php echo $tipo;?>" />
      <input type="hidden" name="nombreOpcion" value="<?php echo $nombre;?>" />
		<input type="hidden" name="elementos" value="<?php echo $elementos;?>" />
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
                  <input type="hidden" name="tabla" value="<?php echo $tabla;?>" />
						<input type="hidden" name="elementos" value="<?php echo $elementos;?>" />
                  <input type="hidden" id="paso_catalogo" name="paso_catalogo" value="S1" />
						<input type="hidden" id="<?php echo $clase;?>" name="<?php echo $clase;?>" value="<?php echo $dato[$clase];?>" />
                  <fieldset>
                     <legend>
                        Item de : <?php echo $nombre;?>
                     </legend>
							<?php
								foreach($esquema as $info){
									if($info['column_name']==$clase)
										continue;
									$fila='<div class="justificado">
									<label for="'.$info['column_name'].'">'.ucfirst($info['column_name']).'</label>';
									if($info['character_maximum_length']>128)
									$fila=$fila.'<textarea id="'.$info['column_name'].'" name="'.$info['column_name'].'"  maxlength="'.$info['character_maximum_length'].'" disabled="disabled">'.$dato[$info['column_name']].'</textarea>';
									else
									$fila=$fila.'<input id="'.$info['column_name'].'" name="'.$info['column_name'].'" type="text" maxlength="'.$info['character_maximum_length'].'" disabled="disabled" value="'.$dato[$info['column_name']].'" />';
									$fila=$fila.'</div>';
									echo $fila;
								}
									?>



                  </fieldset>

               </form>	
				
				</div>
				
			</td>
		</tr>	
	</table>		
</body>

<script type="text/javascript">

	var camposFijos=<?php echo json_encode($camposFijos); ?>;

	$('document').ready(function(){
		
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
		if(camposFijos!=null){
			for(var i=0;i<camposFijos.length;i++){
				if (!$.trim($("#"+camposFijos[i]).val())) {
					error = true;
					$("#"+camposFijos[i]).addClass("alertaCombo");
				}
			}
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
