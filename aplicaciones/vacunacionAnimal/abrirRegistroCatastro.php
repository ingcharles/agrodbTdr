<?php

//Permite visualizar el registro de Catastro
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$contador = 0;
$itemsFiltro[] = array();

$conexion = new Conexion();
$vc = new ControladorVacunacionAnimal();
$data =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
list($id_sitio, $id_area, $id_especie) = explode("@", $data);

$qCatastro = $vc->catastroAnimal($conexion, $id_sitio, $id_area, $id_especie);
$qCatastroEspecifico = $vc->catastroAnimalEspecifico($conexion, $id_sitio, $id_area, $id_especie); 
$qCatastroVacunado = $vc->catastroAnimalVacunado($conexion, $id_sitio, $id_area, $id_especie);
//print_r($qCatastroVacunado );
?>

<header>
	<h1>Catastro animal</h1>
</header>
<form id='abrirRegistroCatastro' data-rutaAplicacion='vacunacionAnimal' data-opcion='actualizarVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	
	<fieldset>
		<legend>Información del catastro</legend>
			<div data-linea="1">
				<label>Tipo Especie</label>	
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $qCatastro[0]['nombre_especie'];?>" disabled="disabled"/>													  
			</div>				
			<div data-linea="2">
				<label>Nombre del sitio</label> 
				<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $qCatastro[0]['nombre_sitio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Nombre del area</label> 
				<input type="text" id="nombreArea" name="nombreArea" value="<?php echo $qCatastro[0]['nombre_area'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Código Catastral</label> 
				<input type="text" id="codigoCatastral" name="codigoCatastral" value="<?php echo $qCatastro[0]['codigo_catastral'];?>" disabled="disabled"/>
			</div>																	
	</fieldset>
	<fieldset>
		<legend>Detalle del catastro</legend>
		<table id="tablaVacunaAnimal">
			<tr>
				<td >
					<table id="tablaVacunaAnimal">					
					<thead>
						<tr>
							<th colspan="5">Total de Catastros</th>
						</tr>
						<tr>
							<th>#</th>
							<th>Especie</th>
							<th>Producto</th>
							<th>Existentes</th>
							<th>Vacunados</th>																				
						</tr>
					</thead>
					
						<?php 
						$i=1;				
						foreach ($qCatastroEspecifico as $catastroEspecial){
							echo  '<tr>
			                           <td>'.$i.'</th>
									   <td>'.$catastroEspecial['nombre_especie'].' </td>
									   <td>'.$catastroEspecial['producto'].'</td>
									   <td>'.$catastroEspecial['total'].'</td>		
									   <td>'.$catastroEspecial['total_vacunado'].'</td>						   
			                       </tr>';				
							$i++;
						}	
					    ?>
					   
	   			 </table>
				</td>
				
			</tr>
		</table>
					
	</fieldset>
	<fieldset>
		<legend>Detalle de catastro de productos vacunados</legend>
		<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>#</th>
						<th>numero_documento</th>
						<th>Especie</th>
						<th>Producto</th>
						<th>Total</th>																			
					</tr>
				</thead>								
					<?php 
					$i=1;
					//echo $qCatastroVacunado['numero_documento'].'ya';					
										
						foreach ($qCatastroVacunado as $catastroVacunado){
if($catastroVacunado['numero_documento']!=""){
							echo  '<tr>
			                           <td>'.$i.'</th>
									   <td>'.$catastroVacunado['numero_documento'].' </td>
									   <td>'.$catastroVacunado['nombre_especie'].' </td>
									   <td>'.$catastroVacunado['producto'].'</td>
									   <td>'.$catastroVacunado['total_vacunado'].'</td>								   
			                       </tr>';				
							$i++;
						
						}
					}	
				    ?>				
		</table>			
	</fieldset>
</form>

<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
});
</script>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>