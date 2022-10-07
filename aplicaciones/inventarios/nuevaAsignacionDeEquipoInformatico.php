<?php
    // session_start();
    //require_once '../../clases/Conexion.php';
    //require_once '../../clases/ControladorInventarios.php';
    //require_once 'SoapClientAuth.php';

   // $conexion = new Conexion ();
    //$ci = new ControladorInventario();
    // $ca = new ControladorAuditoria();

    // Validar sesion
    // $conexion->verificarSesion();
    
	//$soapclient_options = array(); 
	//$soapclient_options['Username'] = 'AdmAGROCALIDAD01'; 
	//$soapclient_options['Password'] = 'a4de5d89de'; 
	//$soapclient_options['local_cert'] = 'prueba.pem';
	
//     $wsdl = 'https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl';
//     $localCert = 'prueba.pem';
//     $clientOptions = array(login => 'AdmAGROCALIDAD01', password => 'a4de5d89de',
//     		local_cert => $localCert, passphrase => 'bce1',
//     		soap_version => SOAP_1_1, encoding => 'UTF-8',
//     		compression => (SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP),
//     		location => $wsdl);
    
//     		$client = new SoapClient($wsdl, $clientOptions);
//     		$result = $client->webservice($parameters);
    		
//     		print_r($result);

?>


<html>
<head>
    <meta charset = "utf-8">

</head>
<body>
<header>
    <h1>Asignación de equipo informático</h1>
</header>

<div id = "estado"></div>

<form id = "asignarEquipoInformatico"
      data-rutaAplicacion = "inventarios"
      data-opcion = "asignarEquipoInformatico"
      data-accionEnExito = "ACTUALIZAR">
    <fieldset>
        <legend>Detalle de asignación</legend>
        <div data-linea = "1">
            <label for = "usuario">Cédula de usuario</label>
            <input id = "usuario" name = "identificador" />
        </div>
        <div data-linea = "1">
            <label for = "tipo">Tipo de equipo</label>
            <select id = "tipo" name = "tipo">
                <option value="Computador">Computador</option>
                <option value="Monitor">Monitor</option>
                <option value="Teclado">Teclado</option>
                <option value="Ratón">Ratón</option>
            </select>
        </div>
        <div data-linea = "2">
            <label for = "equipo">Número de serie</label>
            <input id = "equipo" name = "serial" />
        </div>
        <hr />
        <div data-linea = "9">
            <button id = "bs" type = "submit" class = "guardar">Asignar equipo</button>
        </div>
    </fieldset>
</form>
</body>
<script type = "text/javascript">

    $("document").ready(function () {
        distribuirLineas();
    });

    $("#asignarEquipoInformatico").submit(function (event) {
        $("#asignarEquipoInformatico button").attr("disabled", "disabled");
        event.preventDefault();
        ejecutarJson($(this));
    });

</script>
</html>