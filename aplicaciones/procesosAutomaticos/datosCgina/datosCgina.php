<?php
if ($_SERVER['REMOTE_ADDR'] == ''){

	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorCgina.php';

	$conexion = new Conexion();
	$conexionCgina = new Conexion('192.168.200.13', '5432', 'agrocalidad', 'postgres', 'pgC4l1d4d');
	$cCgina = new ControladorCgina();
	$cm = new ControladorMonitoreo();

	$fecha = date('Y-m-d');

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_DATO_CGINA');

	// ///// CARACTERIZACION FRUTICOLA ///////

	if ($resultadoMonitoreo){

		$datosFruticula = $cCgina->obtenerCaracterizacionFruticola($conexion, $fecha);

		$valores = '';
		while ($filas = pg_fetch_row($datosFruticula)){
			$valores .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
                    ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','$filas[14]','$filas[15]','$filas[16]','now()'),";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio de ingreso de caracterización frutícula<br/>';
			$cCgina->insertarCaracterizacionFruticola($conexionCgina, $valores);
			echo '<br/> <<< Fin de ingreso de caracterización frutícula<br/>';
		}

		// ///// FIN ///////

		// ///// MONITOREO VIGILANCIA ///////

		$datosVigilancia = $cCgina->obtenerMonitoreoVigilancia($conexion, $fecha);

		while ($filas = pg_fetch_row($datosVigilancia)){

			$filas[16] = $filas[16] == '' ? 'null' : $filas[16];
			$filas[17] = $filas[17] == '' ? 'null' : $filas[17];
			$filas[26] = $filas[26] == '' ? 'null' : $filas[26];
			$filas[27] = $filas[27] == '' ? 'null' : $filas[27];
			$filas[28] = $filas[28] == '' ? 'null' : $filas[28];

			$valores .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
                    ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','$filas[14]','$filas[15]',$filas[16],$filas[17],'$filas[18]'
                    ,'$filas[19]','$filas[20]','$filas[21]','$filas[22]','$filas[23]','$filas[24]','$filas[25]',$filas[26],$filas[27]
                    ,$filas[28],'$filas[29]','$filas[30]','$filas[31]','$filas[32]','$filas[33]','$filas[34]','$filas[35]','$filas[36]'
                    ,'$filas[37]','$filas[38]','now()'),";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio de ingreso de monitoreo vigilancia<br/>';
			$cCgina->insertarMonitoreoVigilancia($conexionCgina, $valores);
			echo '<br/> <<< Fin de ingreso de monitoreo vigilancia<br/>';
		}

		// ///// FIN ///////

		// ///// MONITOREO VIGILANCIA DETALLE ///////

		$datosFruticula = $cCgina->obtenerSeguimientoCuarentenario($conexion, $fecha);

		$valores = '';
		while ($filas = pg_fetch_row($datosFruticula)){

			$filas[14] = $filas[14] == '' ? 'null' : $filas[14];
			$filas[15] = $filas[15] == '' ? 'null' : $filas[15];
			$filas[16] = $filas[16] == '' ? 'null' : $filas[16];
			$filas[19] = $filas[19] == '' ? 'null' : $filas[19];
			$filas[20] = $filas[20] == '' ? 'null' : $filas[20];
			$filas[21] = $filas[21] == '' ? 'null' : $filas[21];

			$valores .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
                    ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]',$filas[14],$filas[15],$filas[16],'$filas[17]','$filas[18]'
                    ,$filas[19],$filas[20],$filas[21],'$filas[22]','$filas[23]','$filas[24]','$filas[25]','$filas[26]','$filas[27]'
                    ,'$filas[28]','now()'),";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio de ingreso de seguimineto cuarentenario<br/>';
			$cCgina->insertarSeguimientoCuarentenario($conexionCgina, $valores);
			echo '<br/> <<< Fin de ingreso de seguimineto cuarentenario<br/>';
		}

		// ///// FIN ///////

		// ///// PRODUCTOS AGRICOLAS ///////

		$datosFruticula = $cCgina->obtenerProductosRIA($conexion, $fecha, 'IAP');

		$valoresNuevos = '';
		$valoresActualizar = '';

		while ($filas = pg_fetch_row($datosFruticula)){

			$date = date_create($filas[14]);

			if ($fecha === date_format($date, 'Y-m-d')){
				$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','now()'),";
			}else{
				$valoresActualizar .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','now()'),";
			}
		}

		$valoresNuevos = trim($valoresNuevos, ',');
		$valoresActualizar = trim($valoresActualizar, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Inicio de ingreso de productos ria agricolas<br/>';
			$cCgina->insertarProductosRIA($conexionCgina, $valoresNuevos, 'productos_agricolas');
			echo '<br/> <<< Fin de ingreso de productos ria agricolas<br/>';
		}

		if ($valoresActualizar != ''){
			echo '<br/> >>>>Actualizacion de productos ria agricolas<br/>';
			$cCgina->actualizarProductosRIA($conexionCgina, $valoresActualizar, 'productos_agricolas');
			echo '<br/> <<< Fin de Actualizacion de productos ria agricolas<br/>';
		}

		// ///// FIN ///////

		// ///// PRODUCTOS FERTILIZANTES ///////

		$datosFruticula = $cCgina->obtenerProductosRIA($conexion, $fecha, 'IAF');

		$valoresNuevos = '';
		$valoresActualizar = '';

		while ($filas = pg_fetch_row($datosFruticula)){

			$date = date_create($filas[14]);

			if ($fecha === date_format($date, 'Y-m-d')){
				$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','now()'),";
			}else{
				$valoresActualizar .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','now()'),";
			}
		}

		$valoresNuevos = trim($valoresNuevos, ',');
		$valoresActualizar = trim($valoresActualizar, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Inicio de ingreso de productos ria fertilizantes<br/>';
			$cCgina->insertarProductosRIA($conexionCgina, $valoresNuevos, 'productos_fertilizantes');
			echo '<br/> <<< Fin de ingreso de productos ria fertilizantes<br/>';
		}

		if ($valoresActualizar != ''){
			echo '<br/> >>>>Actualizacion de productos ria fertilizantes<br/>';
			$cCgina->actualizarProductosRIA($conexionCgina, $valoresActualizar, 'productos_fertilizantes');
			echo '<br/> <<< Fin de Actualizacion de productos ria fertilizantes<br/>';
		}

		// ///// FIN ///////

		// ///// PRODUCTOS VETERINARIOS ///////

		$datosFruticula = $cCgina->obtenerProductosVeterinarios($conexion, $fecha);

		$valoresNuevos = '';
		$valoresActualizar = '';

		while ($filas = pg_fetch_row($datosFruticula)){

			$date = date_create($filas[12]);

			if ($fecha === date_format($date, 'Y-m-d')){
				$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','now()'),";
			}else{
				$valoresActualizar .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
            ,'$filas[10]','$filas[11]','now()'),";
			}
		}

		$valoresNuevos = trim($valoresNuevos, ',');
		$valoresActualizar = trim($valoresActualizar, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Inicio de ingreso de productos veterinarios<br/>';
			$cCgina->insertarProductosVeterinarios($conexionCgina, $valoresNuevos);
			echo '<br/> <<< Fin de ingreso de productos veterinarios<br/>';
		}

		if ($valoresActualizar != ''){
			echo '<br/> >>>>Actualizacion de productos veterinarios<br/>';
			$cCgina->actualizarProductosVeterinarios($conexionCgina, $valoresActualizar);
			echo '<br/> <<< Fin de Actualizacion de productos veterinarios<br/>';
		}

		// ///// FIN ///////

		// ///// INGREDIENTES ACTIVOS ///////
		
 $datosFruticula = $cCgina->obtenerIngredientesActivos($conexion, $fecha);

    $valoresNuevos = '';
    $valoresActualizar = '';  

    while($filas = pg_fetch_row($datosFruticula)){                

        $date = date_create($filas[7]);

        if($fecha === date_format($date, 'Y-m-d')){           
            $valoresNuevos.= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','now()'),";
        }

        else{           
            $valoresActualizar.= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','now()'),";
        }
    }

    $valoresNuevos = trim($valoresNuevos ,',');
    $valoresActualizar = trim($valoresActualizar,',');

    if($valoresNuevos != ''){
        echo '<br/> >>>>Inicio de ingreso de ingredientes activos<br/>';
        $cCgina->insertarIngredientesActivos($conexionCgina,$valoresNuevos);
        echo '<br/> <<< Fin de ingreso de ingredientes activos<br/>';
    }

    if($valoresActualizar != ''){       
        echo '<br/> >>>>Actualizacion de ingredientes activos<br/>';  
        $cCgina->actualizarIngredientesActivos($conexionCgina,$valoresActualizar);
        echo '<br/> <<< Fin de Actualizacion de ingredientes activos<br/>'; 
    }

    /////// FIN  ///////



    /////// CULTIVOS ///////

		$datosFruticula = $cCgina->obtenerCultivos($conexion, $fecha);

		$valores = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valores .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio de ingreso de cultivos<br/>';
			$cCgina->insertarCultivos($conexionCgina, $valores);
			echo '<br/> <<< Fin de ingreso de cultivos<br/>';
		}

		// ///// FIN ///////

		// ///// PLAGAS ///////

		$datosFruticula = $cCgina->obtenerPlagas($conexion, $fecha);

		$valoresNuevos = '';
		$valoresActualizar = '';

		while ($filas = pg_fetch_row($datosFruticula)){

			$date = date_create($filas[4]);

			if ($fecha === date_format($date, 'Y-m-d')){
				$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
			}else{
				$valoresActualizar .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
			}
		}

		$valoresNuevos = trim($valoresNuevos, ',');
		$valoresActualizar = trim($valoresActualizar, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Inicio de ingreso de plagas<br/>';
			$cCgina->insertarPlagas($conexionCgina, $valoresNuevos);
			echo '<br/> <<< Fin de ingreso de plagas<br/>';
		}

		if ($valoresActualizar != ''){
			echo '<br/> >>>>Actualizacion de plagas<br/>';
			$cCgina->actualizarPlagas($conexionCgina, $valoresActualizar);
			echo '<br/> <<< Fin de Actualizacion de plagas<br/>';
		}

		// ///// FIN ///////

		// ///// MOVILIZACION ///////

		$datosFruticula = $cCgina->obtenerMovilizacion($conexion, $fecha);

		$valoresNuevos = '';
		$valoresActualizar = '';

		while ($filas = pg_fetch_row($datosFruticula)){

			$date = date_create_from_format('d-m-Y H:i:s', $filas[30]);

			if ($fecha === date_format($date, 'Y-m-d')){
				
				$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
                              ,'$filas[10]',$$$filas[11]$$,'$filas[12]','$filas[13]','$filas[14]','$filas[15]','$filas[16]','$filas[17]','$filas[18]'
                              ,'$filas[19]','$filas[20]','$filas[21]',$$$filas[22]$$,'$filas[23]','$filas[24]','$filas[25]','$filas[26]','$filas[27]'
                              ,'$filas[28]','$filas[29]','$filas[30]','$filas[31]','$filas[32]','$filas[33]','$filas[34]','$filas[35]','$filas[36]'
                              ,'$filas[37]','$filas[38]','now()'),";
			}else{
				$valoresActualizar .= "('$filas[0]','$filas[33]','$filas[34]','$filas[35]','$filas[36]','now()'),";
			}
		}

		$valoresNuevos = trim($valoresNuevos, ',');
		$valoresActualizar = trim($valoresActualizar, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de nuevas movilizaciones<br/>';
			$cCgina->insertarMovilizacion($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso nuevas movilizaciones<br/>';
		}

		if ($valoresActualizar != ''){
			echo '<br/> >>>>Actualizacion de movilizaciones<br/>';
			$cCgina->actualizarMovilizacion($conexionCgina,$valoresActualizar);
			echo '<br/> <<< Fin de actualizacion de movilizaciones<br/>';
		}

		// ///// FIN ///////

		// ///// COMPOSICION ///////

		$datosFruticula = $cCgina->obtenerComposicion($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de composición <br/>';
			$cCgina->insertarComposicion($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de composición<br/>';
		}

		// ///// FIN ///////

		// ///// FABRICANTE FORMULADOR ///////

		$datosFruticula = $cCgina->obtenerFabricanteFormulador($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de fabricante formulador<br/>';
			$cCgina->insertarFabricanteFormulador($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de fabricante formulador<br/>';
		}

		// ///// FIN ///////

		// ///// PRESENTACION ///////

		$datosFruticula = $cCgina->obtenerPresentacion($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de presentación<br/>';
			$cCgina->insertarPresentacion($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de presentación<br/>';
		}

		// ///// FIN ///////

		// ///// PLAGAS IAV IAF ///////

		$datosFruticula = $cCgina->obtenerPlagasIavIaf($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de plagas iav iaf<br/>';
			$cCgina->insertarPlagasIavIaf($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de plagas iav iaf<br/>';
		}

		// ///// FIN ///////

		// ///// PLAGAS IAV ///////

		$datosFruticula = $cCgina->obtenerPlagasIav($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de plagas iav<br/>';
			$cCgina->insertarPlagasIav($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de plagas iav<br/>';
		}

		// ///// FIN ///////

		// ///// VACUNACION ARETES ///////

		$datosFruticula = $cCgina->obtenerVacuncacionAretes($conexion, $fecha);

		$valoresNuevos = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valoresNuevos .= "('$filas[0]','$filas[1]','$filas[2]','$filas[3]','$filas[4]','$filas[5]','$filas[6]','$filas[7]','$filas[8]','$filas[9]'
                    ,'$filas[10]','$filas[11]','$filas[12]','$filas[13]','$filas[14]','$filas[15]','$filas[16]','$filas[17]','$filas[18]'
					,'now()'),";
		}

		$valoresNuevos = trim($valoresNuevos, ',');

		if ($valoresNuevos != ''){
			echo '<br/> >>>>Ingreso de vacunación de aretes <br/>';
			$cCgina->insertarVacunacionAretes($conexionCgina,$valoresNuevos);
			echo '<br/> <<< Fin de ingreso de vacunación de aretes <br/>';
		}

		// ///// FIN ///////

		/**
		 * ***************************************** ELIMINACION DE REGISTROS *********************
		 */

		// ///// ELIMINAR COMPOSICION ///////

		$datosFruticula = $cCgina->obtenerComposicionElimiandos($conexion, $fecha);

		$valores = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valores .= "'$filas[0]',";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio eliminación composición<br/>';
			$cCgina->eliminarComposicion($conexionCgina,$valores);
			echo '<br/> <<< Fin eliminación composición<br/>';
		}

		// ///// FIN ///////

		// ///// ELIMINAR FABRICANTE FORMULADOR ///////

		$datosFruticula = $cCgina->obtenerFabricanteFormuladorEliminados($conexion, $fecha);

		$valores = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valores .= "'$filas[0]',";
		}

		$valores = trim($valores, ',');

		if ($valores != ''){
			echo '<br/> >>>>Inicio eliminación fabricante formulador<br/>';
			$cCgina->eliminarFabricanteFormulador($conexionCgina,$valores);
			echo '<br/> <<< Fin eliminación fabricante formulador<br/>';
		}

		// ///// FIN ///////

		// ///// ELIMINAR PRESENTACION ///////

		$datosFruticula = $cCgina->obtenerPresentacionEliminados($conexion, $fecha);

		$valores = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			$valores .= "(id_producto = '$filas[0]' and subcodigo = '$filas[1]') or";
		}

		$valores = trim($valores, 'or');

		if ($valores != ''){
			echo '<br/> >>>>Inicio eliminación presentacion<br/>';
			$cCgina->eliminarPresentacion($conexionCgina,$valores);
			echo '<br/> <<< Fin eliminación presentacion<br/>';
		}

		// ///// FIN ///////

		// ///// ELIMINAR PRESENTACION ///////

		$datosFruticula = $cCgina->obtenerPlagasIapIafIavEliminados($conexion, $fecha);

		$valores = '';

		$valoresIav = '';
		$valoresIapIaf = '';

		while ($filas = pg_fetch_row($datosFruticula)){
			if ($filas[1] == ''){
				$valoresIapIaf .= "$filas[0],";
			}else{
				$valoresIav .= "$filas[0],";
			}
		}

		$valoresIapIaf = trim($valoresIapIaf, ',');
		$valoresIav = trim($valoresIav, ',');

		if ($valoresIapIaf != ''){
			echo '<br/> >>>>Inicio eliminación plagasIapIaf<br/>';
			$cCgina->eliminarPlagasIapIafIav($conexionCgina, $valoresIapIaf, 'productos_plagas_iap_iaf');
			echo '<br/> <<< Fin eliminación plagasIapIaf<br/>';
		}

		if ($valoresIav != ''){
			echo '<br/> >>>>Inicio eliminación plagasIav<br/>';
			$cCgina->eliminarPlagasIapIafIav($conexionCgina, $valoresIav, 'productos_plagas_iav');
			echo '<br/> <<< Fin eliminación plagasIav<br/>';
		}
	}
	// ///// FIN ///////
}else{

	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_datos_cgina" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}
