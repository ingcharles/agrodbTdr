<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$conexion = new Conexion();
$controladorInformacion= new ControladorServiciosInformacionTecnica();
$idCertificado= $_POST['id'];
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Ver Certificado</h1>

</header>

<div id="estado"></div>


<form id="LoteEtiquetado" data-rutaAplicacion="serviciosInformacionTecnica">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="opcion2" name="opcion2" value="visualizar">
	<input type="hidden" id="usuario" name="usuario" value=<?php echo $usuario?>> 
	<?php $resultado=pg_fetch_assoc($controladorInformacion->obtenerCertificadoXId($conexion, $idCertificado))?>
	<fieldset>	
		<legend>Datos Generales:</legend>			
		<div data-linea="2">
			<label for="certificado" >Certificados:</label>
			<input type="text" id="certificado" name="certificado" maxlength="30" value="<?php echo $resultado['certificado'];?>">
		</div>
		<div data-linea="3">
			<label for="fechaIngreso" >Fecha de ingreso:</label>
			<input type="text" id="fechaIngreso" name="fechaIngreso" maxlength="30" value="<?php echo date('Y-m-d',strtotime($resultado['fecha_ingreso']));?>">
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de Certificado:</legend>
		<embed id="visor" src="<?php echo $resultado['ruta_archivo'];?>" width="550" height="620">
	</fieldset>
	
	<fieldset>
		<legend>Firmas Autorizadas:</legend>
		<?php $resultado=$controladorInformacion->listarFirmas($conexion, $idCertificado)?>
		<table id="tablaItems" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
			<thead>
				<tr>
					<th style="width: 15%;">Cargo</th>
					<th style="width: 15%;">Nombre Funcionario</th>
					<th style="width: 60%;">Firma</th>
					<th style="width: 10%;">Estado</th>										
				</tr>
			</thead>
			<tbody>
			<?php
			while($fila=pg_fetch_assoc($resultado)){
			    echo '<tr>
                    <td>'.$fila['cargo'].'</td>
                    <td>'.$fila['nombre_funcionario'].'</td>
                    <td>';
				if($fila['ruta_archivo']!='0'){
					echo'<embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150"> ';
				}
				echo'</td>
                    <td>'.$fila['estado'].'</td></tr>';
			}
			?>
			</tbody>
		</table>
	</fieldset>
		
	<fieldset>
		<legend>Histórico de Cambios:</legend>
		<?php $resultado=$controladorInformacion->listarFirmasHistorial($conexion, $idCertificado)?>
		<div data-liniea="1" id="contenedorHistorial" style="width:100%;">
    		<table id="tablaHistorial" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
    			<thead>
    				<tr>
    					<th style="width: 15%;">Cargo</th>
    					<th style="width: 15%;">Nombre Funcionario</th>
    					<th style="width: 45%;">Firma</th>
    					<th style="width: 10%;">Estado</th>										
    					<th style="width: 15%;">Fecha y Hora Modificación</th>
    				</tr>
    			</thead>
    			<tbody>
    			<?php
    			while($fila=pg_fetch_assoc($resultado)){
    			    echo '<tr>
                        <td>'.$fila['cargo'].'</td>
                        <td>'.$fila['nombre_funcionario'].'</td>
                        <td>';
					if($fila['ruta_archivo']!='0'){
						echo'<embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150"> </td>';
					}
                    echo'<td>'.$fila['estado'].'</td>
    			        <td>'.$fila['fecha'].'</td></tr>';
    			}
    			?>
    			</tbody>
    		</table>
		</div>
	</fieldset>
	
</form>

<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();

	$("#certificado").attr("readonly","readonly");
		
});



	
	
</script>
