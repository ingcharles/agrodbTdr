
<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cr = new controladorRequisitos();
 
echo '<table class="noImprimir">
		<tr><td></td></tr>
		<tr><td><span class="detalleImpreso" >Requisito que únicamente se muestra en el certificado impreso.</span></td></tr>
	</table>';
 
echo '<div class="soloImpresion">
		<h1>Requisitos de comercio exterior para productos</h1>
		<h2>Agrocalidad - Ecuador</h2>
	</div>';


for ($n = 0; $n < count ( $_POST['producto']); $n++){
	$producto = pg_fetch_assoc($cr->mostrarDatosGeneralesDeProducto($conexion, $_POST['producto'][$n]));
?>

<hr />
<fieldset>
	<legend>Datos generales</legend>
	<table>
		<tr>
			<th>Tipo</th>
			<td><?php echo $producto['tipo']?></td>
		</tr>
		<tr>
			<th>Subtipo</th>
			<td><?php echo $producto['subtipo']?></td>
		</tr>
		<tr>
			<th>Nombre de producto <i>(nombre científico)</i>
			</th>
			<td><?php echo $producto['producto']?> <i>(<?php echo $producto['cientifico']?>)
			</i></td>
		</tr>
		<?php 
    		if($producto['id_area']==='IAP'){
    		    echo '<tr>
            			<th>Partidas recomendadas</th>';
    		              $qPartidas = $cr->listarPartidasArancelarias($conexion, $producto['id_producto']);
    		              while ($partidas = pg_fetch_assoc($qPartidas)){
    		                  $partidaArancelaria .= $partidas['partida_arancelaria'].', ';
    		                  $codigoProducto .= 'A'.$partidas['codigo_producto'].', ';
    		              }
            		        		    
    		              echo '<td>'.rtrim ($partidaArancelaria, ' ,' ).'</td>';//
    		    echo '</tr>';
    		}else{
    		    echo '<tr>
            			<th>Partida recomendada</th>
            			<td>' . $producto['partida_arancelaria'] .'</td>
        		      </tr>';
    		}
		?>
		
		<?php 
    		if($producto['id_area']!=='IAP'){
    		    echo '<tr>
            			<th>Unidad de medida según arancel</th>
            			<td>' . $producto['unidad_medida'] .'</td>
        		      </tr>';
    		}
		?>
		
		<?php 
    		if($producto['id_area']==='IAP'){
    		    echo '<tr>
            			<th>Código de Agrocalidad</th>';
    		    echo '<td>'.rtrim ($codigoProducto, ' ,' ).'</td>';//rtrim ($partidaArancelaria, ' ,' )
    		    echo '</tr>';
    		}else{
    		    echo '<tr>
            			<th>Códigos de Agrocalidad</th>
            			<td>A' . $producto['codigo_producto'] .'</td>
        		      </tr>';
    		}
		?>
	</table>

</fieldset>
<?php
if($_POST['tipoRequisito']!="('Importación','Exportación','Tránsito')"){
$requisitos=$cr->mostrarRequisitosFiltro($conexion, $_POST['tipoArea'], $_POST['pais'], $_POST['tipoRequisito'],$_POST['producto'][$n]);
	
while ($registro = pg_fetch_assoc($requisitos)) {
	$requisitoPais = json_decode($registro[row_to_json], true);
	if(count((array)$requisitoPais['requisito_pais'])>0){
		echo '<fieldset class="requisitos ocultado">' ;
			echo '<legend>' . $requisitoPais['nombre_pais'] . '</legend>';
			echo  '<div class="mapa">'.
					'<button type="button" class="mas"><span></span></button>';
			echo '<div style="display:none" class="mensajeImpresion"></div>';
		
		
			$requisitosDeExportacion = array();
			$requisitosDeImportacion = array();
			$requisitosDeTransito = array();
			 
			foreach ((array)$requisitoPais['requisito_pais'] as $tipoRequisito) { //CASTING A ARRAY PARA EVITAR EL WARNING
		            $requisitoTemporal = '<tr ><td class="ordinal"' . $tipoRequisito['orden'] . '</td><td class="requisito"><pre>';
		            if ($tipoRequisito['detalle']!='')
		            	$requisitoTemporal .= $tipoRequisito['detalle'] . '<br/>';
		
		            if($tipoRequisito['detalle_impreso'] != '')
		            	$requisitoTemporal .= '<span class="detalleImpreso">' . $tipoRequisito['detalle_impreso'] . '</span>';
		            $requisitoTemporal .= '</pre></td></tr>';
		
		            if ($tipoRequisito['tipo'] == 'Exportación'){
		            	$requisitosDeExportacion[] = $requisitoTemporal;
		          	} else if($tipoRequisito['tipo'] == 'Importación'){
		            	$requisitosDeImportacion[] = $requisitoTemporal;
					}else if($tipoRequisito['tipo'] == 'Tránsito'){
						$requisitosDeTransito[] = $requisitoTemporal;
					}
		   
		        }
		
		         
		        if (count($requisitosDeExportacion)) {
		            echo '<div class="exp">Requisitos para exportación</div>' .
		              '<div><table class="exp">';
		            foreach ($requisitosDeExportacion as $tipoRequisito)
		            	echo $tipoRequisito;
		            echo '</table></div>';
		        }
		        if (count($requisitosDeImportacion)) {
		            echo '<div class="imp">Requisitos para importación</div>' .
		              '<div><table class="imp">';
		            foreach ($requisitosDeImportacion as $tipoRequisito)
		            	echo $tipoRequisito;
		            echo '</table></div>';
		        }
		        
		        if (count($requisitosDeTransito)) {
		        	echo '<div class="tra">Requisitos para tránsito internacional</div>' .
		        			'<div><table class="tra">';
		        	foreach ($requisitosDeTransito as $tipoRequisito)
		        		echo $tipoRequisito;
		        	echo '</table></div>';
		        }

			echo '</fieldset>';
		}
}
}

if($_POST['tipoArea']=='IAP' || $_POST['tipoArea']=='IAV' || $_POST['tipoArea']=='IAF'  || $_POST['tipoArea']=='IAPA'){
    $res=$cr->buscarDatosEspecificosProductosIAVIAP($conexion, $_POST['tipoArea'], $_POST['producto'][$n]);
    
    echo '<fieldset>
    		<legend>Datos Específicos</legend>
    		<table>';
    		while ($registro = pg_fetch_assoc($res)) {
    			$usoss=null;
    			$presentaciones=null;
    			$formuladores=null;
    			$composiciones =  null;
    			$registros = json_decode($registro[row_to_json], true);
    			
    			echo '<tr><th>Número de registro</th>';
    			echo '<td style="text-aling=right;">'.$registros['numero_registro'].'</td>';
    			echo '</tr>';
    			
    			echo '<tr><th>Titular</th>';
    			echo '<td>'.$registros['razon_social'].'</td>';
    			echo '</tr>';
    			
    			echo'<tr>';		
    			echo'<th>Fabricante/Formulador</th>';
    			foreach ((array)$registros['formulador'] as $formulador) {
    				$formuladores.=$formulador['tipo'] .': '.$formulador['nombre_ff'].' - '.$formulador['pais_origen'].', ';
    				$formuladores.='<br>';
    			}
    			echo '<td>'.rtrim ($formuladores, ' ,' ).'</td></tr>';
    			
    			if($producto['id_area'] === 'IAP'){
    			    echo'<tr>';
    			    echo'<th>Manufacturador</th>';
    			    foreach ((array)$registros['manufacturador'] as $manufacturador) {
    			        $manufacturadores.=$manufacturador['manufacturador'].' - '.$manufacturador['pais_origen'].', ';
    			        $manufacturadores.='<br>';
    			    }
    			    echo '<td>'.rtrim ($manufacturadores, ' ,' ).'</td></tr>';
    			}
    			
    			echo'<tr><th>Uso Autorizado</th>';
    			if($producto['id_area'] !== 'IAP'){
        			foreach ((array)$registros['usos'] as $usos) {
        				$usoss.=$usos['nombre_uso'].' Aplicado a '. $usos['nombre_producto_inocuidad'].', ';
        			}
    			echo '<td>'.rtrim ($usoss, ' ,' ).'</td></tr>';
    			}else{
    			    foreach ((array)$registros['usos'] as $usos) {
    			        $usoss.='Cultivo: ' .$usos['cultivo_nombre_comun'].' - '.$usos['cultivo_nombre_cientifico'].' aplicado a Plaga: '. $usos['plaga_nombre_comun'].' - '. $usos['plaga_nombre_cientifico']. ' con dosis: ' . $usos['dosis'].' '. $usos['unidad_dosis'].' con período carencia: '. $usos['periodo_carencia']. ' con gasto de agua: '. $usos['gasto_agua'].' '. $usos['unidad_gasto_agua'].', ';
    			        $usoss.='<br><br>';
    			    }
    			    echo '<td>'.rtrim ($usoss, ' ,' ).'</td></tr>';
    			}
    			
    			echo'<tr><th>Presentaciones</th>';
    			foreach ((array)$registros['presentacion'] as $presentacion) {
    				$presentaciones.=$presentacion['presentacion'].' '.$presentacion['unidad_medida'].', ';
    			}
    			echo '<td>'. rtrim ($presentaciones, ' ,' ).'</td></tr>';
    			
    			if($_POST['tipoArea']!='IAV'){
    			    if($producto['id_area'] !== 'IAP'){
            			echo'<tr><th>Composición</th>';
            			foreach ((array)$registros['composicion'] as $composicion) {
            				$composiciones.= $composicion['ingrediente_activo'].' '.$composicion['concentracion'].' '.$composicion['unidad_medida'].' + ';
            			}
            			echo '<td>'. rtrim ($composiciones, ' +' ).'</td></tr>';
    			    }else{
    			        echo'<tr><th>Composición</th>';
    			        foreach ((array)$registros['composicion'] as $composicion) {
    			            $composiciones.=$composicion['tipo_componente'].': '.$composicion['ingrediente_activo'].' '.$composicion['concentracion'].' '.$composicion['unidad_medida'].' + ';
    			        }
    			        echo '<td>'.rtrim ($composiciones, ' +' ).'</td></tr>';
    			    }
    			}		    			
    		}
    		
    		echo '</table>
    		</fieldset>';
    
    }
}


?>


<script>

    $(document).ready(function(){

        $("fieldset.requisitos table").each(function(){
            $(this).find(".ordinal").each(function(contador){
                $(this).html("R" + (contador+1));
            });
        });
        
        $("fieldset div.mapa button").each(function () {
            $(this).parent().find("div").hide();
        });
       
    });


	$("fieldset").on("click","div.mapa button",function () {
	   visualizarPantalla = $(this).parent().find("div");
	   visualizarImpreso =$(this).parent().parent();
        if ($(this).hasClass("mas")) {
            $(this).removeClass("mas");
            $(this).addClass("menos");
            visualizarPantalla.show();
            visualizarImpreso.removeClass("ocultado");
        } else {
        	$(this).removeClass("menos");
            $(this).addClass("mas");
            visualizarPantalla.hide();
            visualizarImpreso.addClass("ocultado");
        }
    });
    

</script>
