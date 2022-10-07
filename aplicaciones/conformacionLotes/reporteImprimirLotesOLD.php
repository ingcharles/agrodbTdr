<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

header("Content-type: application/octet-stream");
$operador = htmlspecialchars ($_POST['identificacionOperador'],ENT_NOQUOTES,'UTF-8');
$fecha = date('d-m-Y_H:i:s');
$ext   = '.xls';
$nomReporte = "REPORTE_LOTES_".$operador."-".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cl = new ControladorLotes();
$cac = new controladorAdministrarCaracteristicas();


$nOperador = htmlspecialchars ($_POST['nombreOperador'],ENT_NOQUOTES,'UTF-8');
$numeroLote = htmlspecialchars ($_POST['numeroLote'],ENT_NOQUOTES,'UTF-8');
$codigoLote = htmlspecialchars ($_POST['codigoLote'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');
$productoTrazabilidad = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');

/*
if ($fechaInicio=="")
	$fechaInicio = 0;
if ($fechaFin=="")
	$fechaFin = 0;
if (($estado=="0") || ($estado=="1"))
	$estado = 0;
*/

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteConformacionLotes
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporteConformacionLotes td, #tablaReporteConformacionLotes th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporteConformacionLotes th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#logoMagap{
width: 15%;
height:70px;
background-image: url(aplicaciones/conformacionLotes/img/magap_logo.jpg); background-repeat: no-repeat;
float: left;	
}
#logotexto{
width: 10%;
height:80px;
float: left;
}
#logoAgrocalidad{
width: 20%;
height:80px;
background-image: url(img/agrocalidad.png); background-repeat: no-repeat;
float:left;
}
#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
}
#direccion{
width: 10%;
height:80px;
background-image: url(img/direccion.png); background-repeat: no-repeat;
float: left;
}
#bandera{
width: 5%;
height:80px;
background-image: url(img/bandera.png); background-repeat: no-repeat;
float: right;
}
@page{
   margin: 5px;
}
</style>
</body>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">MINISTERIO DE AGRICULTURA Y GANADERÍA<br>
	AGROCALIDAD<br>
	CONFORMACIÓN DE LOTES<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

<div id="tabla">

 <?php
 
 echo '<table id="tablaReporteConformacionLotes" class="soloImpresion">
	<thead>
		<tr>
		    <th>Identificación Operador</th>
			<th>Nombre Operador</th>
			<th>Lote No.</th>
			<th>Código Lote</th>
			<th>Fecha de conformación de Lote</th>
			<th>Cantidad de Lote</th>
			<th>Producto</th>
			<th>País Destino</th>
			<th>Orgánico</th>
			<th>Convencional</th>
			<th>Etiquetado</th>
			<th>Cantidad Etiquetas</th>
			<th>Proveedores que conforman el Lote</th>';

 $productosLote = pg_fetch_assoc($cl->obtenerProductosLotesXoperador($conexion, $operador));
 
 $modulo=pg_fetch_assoc($cac->obtenerModulo($conexion, 'PRG_CONFO_LOTE'));
 
 $formulario=pg_fetch_assoc($cac->obtenerFormularioXidModulo($conexion, "nuevoLote",$modulo['id_aplicacion']));
 
 if($productosLote>0){
     if($formulario>0){
         if($productoTrazabilidad!=""){
             $campos = $cl->obtenerCamposCaracteristicas($conexion, $productoTrazabilidad,$formulario['id_formulario']);
         } else{
             $campos = $cl->obtenerCamposCaracteristicas($conexion, $productosLote['id_producto'],$formulario['id_formulario']);
         }
         
     }
 }
 
 while ($nCampos = pg_fetch_assoc($campos)){
     //if(strtoupper($nCampos['etiqueta']) == strtoupper('Tipo')){
        echo '<th>'.$nCampos['etiqueta'].'</th>';
     //}
 }
 
   
 echo '</tr>		
	</thead>
	<tbody>';
		
	 
	$conexion->ejecutarConsulta("begin;");
	
	 $cac->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.lotes', 'id_lote');
	 
	 $registrosTotal=pg_num_rows($cac->obtenerRegistrosTabla($conexion, 'v_caracteristica',''));
	 
	 $modulo=pg_fetch_assoc($cac->obtenerModulo($conexion, 'PRG_CONFO_LOTE'));	 
	 
	 $productosResultado=pg_fetch_assoc($cac->obtenerProductosConCaracteristicaXformulario($conexion, 'v_caracteristica', $formulario['id_formulario']));
	 
	 if($registrosTotal>0){
	     $resultado=1;
	     if($productoTrazabilidad!=""){
	         $resultado= pg_num_rows($cac->obtenerCaracteristica($conexion, $productoTrazabilidad, $formulario['id_formulario']));
	     } 
	     if($resultado>0){
	         
	         if($productoTrazabilidad!=""){
	             $cac->pivotearColumnas($conexion, 'tmp_c','v_caracteristica', $productoTrazabilidad, $formulario['id_formulario'], "'id_lote'", "'etiqueta'", 'nombre');
	         } else{	             
	             $cac->pivotearColumnas($conexion, 'tmp_c','v_caracteristica', $productosResultado['id_producto'], $formulario['id_formulario'], "'id_lote'", "'etiqueta'", 'nombre');
	         }
	         
	         $res = $cl->listaReporteLotesConformados($conexion,$operador,$nOperador,$numeroLote,$codigoLote, $fechaInicio, $fechaFin,$productoTrazabilidad,'tmp_c');
	         
	     } else{	         
	        $res = $cl->listaReporteLotesConformados($conexion,$operador,$nOperador,$numeroLote,$codigoLote, $fechaInicio, $fechaFin,$productoTrazabilidad,null);	         
	     }
	    
	 } else{	     
	     $res = $cl->listaReporteLotesConformados($conexion,$operador,$nOperador,$numeroLote,$codigoLote, $fechaInicio, $fechaFin,$productoTrazabilidad,null);	    
	 }
	 
	 while($fila = pg_fetch_row($res)){
	 	
	 	$convencional="";
	 	$organico="";		 	
	 	$cantidad= strval($fila[6]);	 	
	 	
	 	if($fila[9]=='convencional'){
	 	    $convencional="X";
	 	} else{
	 	    $organico="X";
	 	}
	 	
	 	echo '<tr>
				 <td>'.sprintf("&nbsp;%0s",$fila[1]).'</td>
			     <td>'.$fila[2].'</td>
				 <td>'.sprintf("&nbsp;%0s",$fila[3]).'</td>
				 <td>'.$fila[4].'</td>
				 <td>'.date('Y-m-d',strtotime($fila[5])).'</td>
			     <td style="text-align:right">'.$cantidad.'</td>
				 <td>'.$fila[7].'</td>
				 <td>'.$fila[8].'</td>'.	////////////////////////////////////
				 '<td style="text-align:center">'.$organico.'</td>
				 <td style="text-align:center">'.$convencional.'</td>
				 <td style="text-align:center">'.$fila[12].'</td>
				 <td>'.$fila[13].'</td>
			     <td>'.$fila[14].'</td>'; 
	 	
        $con=16;        
        if(count($fila)>16){
            while($con<count($fila)){
                if ($fila[$con]!=''){
                    echo "<td style=text-align:center>".$fila[$con]."</td>";
                } else{
                    echo "<td style=text-align:center>S/N</td>";
                }
                $con+=1;
            }
        }
        
        echo '</tr>';
	 	
	 }
	 
	 $conexion->ejecutarConsulta("commit;");

	 	
echo '	</tbody>
</table>';

?>
</div>
</html>