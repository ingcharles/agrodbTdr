<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';


$conexion = new Conexion();
$ce=new ControladorEnsayoEficacia();



$clase = $_POST['id'];
$tabla = $_POST['opcion'];
$nombre = $_POST['nombreOpcion'];
$tipo = $_POST['idFlujo'];
$elementos = $_POST['elementos'];

$tablaNombres=explode (',',$tabla);
$tablaNombre=array_slice($tablaNombres,0,1);
$camposFijos=array_slice($tablaNombres,1);

$tablaEsquema=explode ('.',$tablaNombre[0]);

if($elementos!=null){
	$claves=explode (';',$elementos);			//Despues del nombre del campo, va los datos separados por punto y coma
	$tipoCampo=trim($claves[0]);

	$whereIn=trim($claves[1]);
	$inWhere='';
	if(substr($tipoCampo,0,1)=='$'){	
		$whereCampo=substr($tipoCampo,1);					//caso de datos numericos deben ir antepuesto el signo $
		$inWhere=$whereIn;	
	}
	else{
		$whereCampo=$tipoCampo;		
		$vector=explode (',',$whereIn);					//Los valores para where in deben ir separados por comas
		$inWhere=join("','",$vector);	
		$inWhere="'".$inWhere."'";
	}
	$items=$ce->obtenerTablaStandar($conexion,$tablaNombre[0],$whereCampo,$inWhere);
}
else{
	$items=$ce->obtenerTablaStandar($conexion,$tablaNombre[0]);	
}

$esquema=$ce->obtenerEsquema($conexion,$tablaEsquema[0],$tablaEsquema[1]);




?>

<div id="estado"></div>

<div id="P0" class="pestania" style="display: block;">

   <form id="nuevoItem" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarCatalogoOpciones">
      <input type="hidden" id="paso_catalogo" name="paso_catalogo" value="S1" />
      <input type="hidden" id="clase" name="clase" value="<?php echo $clase; ?>" />
      <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>" />
		<input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre; ?>" />
		<input type="hidden" name="elementos" value="<?php echo $elementos;?>" />
      <input type="hidden" name="tabla" value="<?php echo $tabla;?>" />

      <fieldset>
         <legend>Nuevo item para <?php echo $nombre;?></legend>      <?php
      foreach($esquema as $info){
      if($info['column_name']==$clase)
      continue;
      $fila='<div class="justificado"><label for="'.$info['column_name'].'">'.ucfirst($info['column_name']).'</label>';
      if($info['character_maximum_length']>128)
      $fila=$fila.'<textarea id="'.$info['column_name'].'" name="'.$info['column_name'].'"  maxlength="'.$info['character_maximum_length'].'" />';
      else
      $fila=$fila.'<input id="'.$info['column_name'].'" name="'.$info['column_name'].'" type="text" maxlength="'.$info['character_maximum_length'].'" />';
      $fila=$fila.'</div>';
      echo $fila;
      }
      ?>

         <button type="submit" class="mas">Añadir</button>
      </fieldset>
   </form>

	<fieldset>
		<legend>
			Items de <?php	echo $nombre; ?>
		</legend>
		<table id="tblItems">
			
			<?php
				foreach($items as $item){
					$fila=$ce->imprimirLineaTablaStandar($nombre,$tipo,$tabla,$camposFijos,$clase,$item,$elementos);
				
					echo $fila;


				}
			?>

			
		</table>
	</fieldset>


</div>



<script type="text/javascript">
	var tipo=<?php echo json_encode($tipo); ?>;

	$("document").ready(function () {

		
		acciones("#nuevoItem", "#tblItems");

		distribuirLineas();
		construirValidador();

		if (tipo == 'extendido')
			$('.extendido').show();
		else
			$('.extendido').hide();

	});




</script>
