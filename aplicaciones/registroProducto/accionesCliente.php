<?php

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cop = new ControladorRegistroOperador();
$cr = new ControladorRequisitos();
$cc = new ControladorCatalogos();
$contador = 0;

$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
$varProducto = htmlspecialchars ($_POST['txtProductoBusqueda'],ENT_NOQUOTES,'UTF-8');
$operador = htmlspecialchars ($_POST['operador'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	case 'cliente':
		
		if($varCliente == '') $varCliente = $operador;
			$cliente = $cop->listarOperadoresEmpresa($conexion,$varCliente);
			$clientes = pg_fetch_assoc($cliente);
		
		if(pg_num_rows($cliente) != 0){
			echo'<div data-linea="1">
					<input type="hidden" id="empresa" name="empresa" value="'.$clientes['identificador'].'" >
				</div>
				<div data-linea="2">
					<label>Razón social: </label> '.$clientes['nombre_operador'].'
				</div>';

		}else{
			echo'<div data-linea="3">
					<input type="hidden" id="empresa" name="empresa" value= "'.$varCliente.'" />
				</div>';
		}
		
	break;
	
	case 'producto':
		
			$producto = $cr->listarProductosOperadores($conexion,$varProducto,'Cultivo','Cultivo');
			if(pg_num_rows($producto) != 0){
				$contador = 0;
				while($productos = pg_fetch_assoc($producto)){
					
					if($productos['tipo'] == '(Otros)'){
						echo'<div data-linea="'.$contador.'" class="productos" tabindex= " '. $contador.' "id= "pro_' .$productos['id_producto']. '"><p><span style = "color: #0040FF;"> '. ++$contador.' . '.$productos['btrim'].' - '.$productos['tipo'].' </span><input data-resetear="no" type="checkbox" name="usoProducto[]" id="'.$productos['id_producto'].'-'.$productos['btrim'].' " value="'.$productos['id_producto'].'-'.$productos['btrim'].'" ></p></div>';
						
					}else {
						
						echo'<div data-linea="'.$contador.'" class="productos" tabindex= " '. $contador.' "id= "pro_' .$productos['id_producto']. '"><p>'. ++$contador.' . '.$productos['btrim'].' - '.$productos['tipo'].'<input data-resetear="no" type="checkbox" name="usoProducto[]" id="'.$productos['id_producto'].'-'.$productos['btrim'].' " value="'.$productos['id_producto'].'-'.$productos['btrim'].'" ></p></div>';
					}
																																						  
				}
			}else{
				echo'<div data-linea="5"><label>El producto no existe</label></div>';
			}
	break;
	
	case 'clientePlaguicida':
	    
	    if($varCliente == ''){
	        $varCliente = $operador;
	    }
	    
	    //Tabla operadores
	    $cliente = $cop->listarOperadoresEmpresa($conexion,$varCliente);
	    $clientes = pg_fetch_assoc($cliente);
	    
	    if(pg_num_rows($cliente) == 0){
	        $cliente = $cc->listarEmpresa($conexion,$varCliente);
	        $clientes = pg_fetch_assoc($cliente);
	    }
	    
	    if(pg_num_rows($cliente) != 0){
	        echo'<div data-linea="3">
					<label>Razón social: </label> 
                    <input type="text" id="razonSocial" name="razonSocial" value= "'.$clientes['nombre_operador'].'"  readonly="readonly"/>
                    <input type="hidden" id="empresa" name="empresa" value="'.$clientes['identificador'].'">
				</div>';
	        
	    }else{
	        echo'<div data-linea="3">
                    <label>Razón social: </label> El RUC no está registrado
                    <input type="hidden" id="razonSocial" name="razonSocial" required="required" readonly="readonly"/>
                    <input type="hidden" id="empresa" name="empresa" required="required">
				</div>

                <script>
                    $("#txtClienteBusqueda").val("");
                </script>';	        
	    }
	    
	    break;
		
	default:
			echo 'Registro no encontrado';
}	

$conexion->desconectar();
?>
<script>
	distribuirLineas();
</script>