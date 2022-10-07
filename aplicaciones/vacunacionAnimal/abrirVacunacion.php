<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$contador = 0;
$itemsFiltro[] = array();

$conexion = new Conexion();
$vc = new ControladorVacunacionAnimal();
$idVacunaAnimal=$_POST['id'];
$qvacuna = $vc->listaVacunacionAnimalFiltro($conexion, $idVacunaAnimal);
$vacuna = pg_fetch_assoc($qvacuna);

$qDetalleVacuna = $vc-> listaFiltroDetalleVacunacion($conexion,$idVacunaAnimal);
$qarete = $vc->listaDetalleArete ($conexion, $idVacunaAnimal);

?>

<header>
	<h1>Registro vacunación</h1>
</header>
<form id='nuevoVacunacionAnimal' data-rutaAplicacion='vacunacionAnimal' data-opcion='actualizarVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	
	<fieldset>
		<legend>Información </legend>
			<div data-linea="1">
				<label>Tipo Especie</label>	
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $vacuna['nombre_especie'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="1">
				<label>N° Certificado</label>	
				<input type="text" id="nombreEspecieValorada" name="nombreEspecieValorada" value="<?php echo $vacuna['numero_certificado'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="2">
				<label>Nombre del sitio</label> 
				<input type="text" id="nombreOperadorVacunador" name="nombreOperadorVacunador" value="<?php echo $vacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Nombre del area</label> 
				<input type="text" id="provincia_almacen" name="provincia_almacen" value="<?php echo $vacuna['nombre_area'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="3">
				<label>Administrador operador</label> 
				<input type="text" id="nombreOperadorVacunador" name="nombreOperadorVacunador" value="<?php echo $vacuna['nombre_administrador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Distribuidor</label> 
				<input type="text" id="provincia_almacen" name="provincia_almacen" value="<?php echo $vacuna['nombre_distribuidor'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="4">
				<label>Provincia distribución</label> 
				<input type="text" id="provincia_almacen" name="provincia_almacen" value="<?php echo $vacuna['provincia_distribuidor'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="4">
			    <label>Identificador vacunador</label> 
				<input type="text" id="identificador_vacunador" name="identificador_vacunador" value="<?php echo $vacuna['identificador_vacunador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Nombre del vacunador</label> 
				<input type="text" id="nombre_vacunador" name="nombre_vacunador" value="<?php echo $vacuna['nombre_vacunador'];?>" disabled="disabled"/>			
			</div>	
			<div data-linea="5">
			    <label>Laboratorio</label> 
				<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $vacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Lote</label> 
				<input type="text" id="lote" name="lote" value="<?php echo $vacuna['numero_lote'];?>" disabled="disabled"/>			
			</div>	
			<div data-linea="6">
			    <label>Tipo vacunación</label> 
				<input type="text" id="nombre_vacuna" name="nombre_vacuna" value="<?php echo $vacuna['nombre_vacuna'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="7">
			    <label>Fecha de vacunación</label> 
				<input type="text" id="fecha_vacunacion" name="fecha_vacunacion" value="<?php echo $vacuna['fecha_vacunacion'];?>" disabled="disabled"/>
			</div>
			<div data-linea="7">
				<label>Fecha de vencimiento</label> 
				<input type="text" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $vacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>			
			<div data-linea="8">
				<label>Fecha de registro</label> 
				<input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo $vacuna['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="8">
			    <label>Costo de vacuna</label> 
				<input type="text" id="costo_vacuna" name="costo_vacuna" value="<?php echo $vacuna['costo_vacuna'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="9">
			    <label>Total vacuna</label> 
				<input type="text" id="total_vacuna" name="total_vacuna" value="<?php echo $vacuna['total_vacuna'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="9">
			    <label>Estado certificado</label> 
				<input type="text" id="estado_vacuna" name="estado_vacuna" value="<?php echo $vacuna['estado_vacunacion'];?>" disabled="disabled"/>
			</div>											
	</fieldset>
	<fieldset>
		<legend>Detalle animales</legend>
		<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>#</th>
						<th>Animal</th>
						<th>Existentes</th>
						<th>Vacunados</th>									
						<th>Observación</th>					
					</tr>
				</thead>
					<?php 
					$i=1;
					foreach ($qDetalleVacuna as $detalleVacuna){
						echo  '<tr>
		                           <td>'.$i.'</th>
								   <td>'.$detalleVacuna['nombre_producto'].' </td>
								   <td>'.$detalleVacuna['existente'].'</td>
								   <td>'.$detalleVacuna['vacunado'].'</td>
								   <td>'.$detalleVacuna['observacion'].'</td>
		                       </tr>';				
						$i++;
					}	
				    ?>				
	      </table>
	      <div data-linea="1">
			    <label>Total vacunados : </label><?php echo $vacuna['total_vacunado'];?>				
		  </div>			
	</fieldset>
	<fieldset>
		<legend>Detalle Aretes</legend>
	    <div data-linea="7">
	   		<label> Serie Aretes</label>		    		    			
		</div>	
		<div data-linea="8">	   		
		    <textarea name="serie_arete1" rows="5" cols="100" disabled="disabled"><?php    
		    while($fila = pg_fetch_assoc($qarete)){
				$var1=$var1 ==''?$fila['serie']:$var1 .'; '. $fila['serie'];
			 }
			echo $var1;
		    ?></textarea>		    				
		</div>		
	</fieldset>
</form>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
});
</script>
