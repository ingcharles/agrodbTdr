<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$conexion = new Conexion();
$controladorInformacion= new ControladorServiciosInformacionTecnica();

$idCertificado = htmlspecialchars ($_POST['idCertificado'],ENT_NOQUOTES,'UTF-8');
$opcion = $_POST['opcion'];

//$serie= $_POST['serie'];

switch ($opcion){
	case 'firmas':
	    $resultado=$controladorInformacion->listarFirmas($conexion, $idCertificado);
		
		echo '<table id="tablaItems" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
		<thead>
		<tr>
		<th style="width: 10%;">Cargo</th>
		<th style="width: 10%;">Nombre Funcionario</th>
		<th style="width: 60%;">Firma</th>
		<th style="width: 10%;">Estado</th>
		<th style="width: 10%;">Modificar</th>
		</tr>
		</thead>
		<tbody>';
		
		while($fila=pg_fetch_assoc($resultado)){
		    echo '<tr>
                        <td>'.$fila['cargo'].'<input type="hidden" name="idFirma[]" value="'.$fila['id_firma'].'"></td>
                        <td>'.$fila['nombre_funcionario'].'</td>
                        <td> <embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150"> <input type="hidden" name="rutaBaseCertificado[]" value="'.$fila['ruta_archivo'].'"></td>
                        <td>'.$fila['estado'].'</td>
    			        <td class="abrir"><button class="icono" onclick="modificarFirma(this);return false"></button></td></tr>';
		}
		
    		echo '</tbody>
    		</table>
            <div class="mas" id="agregarFila" onclick="agregarFila()"></div>';
		
	break;
	
	case 'historial':
	    $resultado=$controladorInformacion->listarFirmasHistorial($conexion, $idCertificado);
	   echo' <table id="tablaHistorial" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
	    <thead>
	    <tr>
	    <th style="width: 15%;">Cargo</th>
	    <th style="width: 15%;">Nombre Funcionario</th>
	    <th style="width: 45%;">Firma</th>
	    <th style="width: 10%;">Estado</th>
	    <th style="width: 15%;">Fecha y Hora Modificación</th>
	    </tr>
	    </thead>
	    <tbody>';
	    
	    while($fila=pg_fetch_assoc($resultado)){
	        echo '<tr>
                    <td>'.$fila['cargo'].'<input type="hidden" name="idFirma[]" value="'.$fila['id_firma'].'"></td>
                    <td>'.$fila['nombre_funcionario'].'</td>
                    <td> <embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150"> </td>
                    <td>'.$fila['estado'].'</td>
			        <td>'.$fila['fecha'].'</td></tr>';
	    }
	    
    	echo'</tbody>
    		 </table>';
    break;

}



?>


<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
	
});



</script>