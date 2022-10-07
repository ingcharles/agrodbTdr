<?php
    session_start();

    require_once '../../clases/Conexion.php';
    //require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    $identificador = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');
    $tmp = explode(".", htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8'));
    $area = $tmp[0];
    $provincia = $tmp[1];
    $idTipoOperacion = $tmp[2];

    $operadorJson = $cro->obtenerOperador($conexion, $identificador, $area, $provincia, $idTipoOperacion);
    $operador = (array)(json_decode($operadorJson['row_to_json']));

?>

<header>
    <h1>Datos del operador</h1>
</header>
<fieldset>
    <legend>
        Datos generales
    </legend>
    <div data-linea="1">
        <h2>Razón social: <?php echo $operador['razon_social'] ?></h2>
    </div>
    <div data-linea="3">
        <label>RUC/CI:</label>
        <span><?php echo $operador['identificador'] ?></span>
        <span>(Persona <?php echo $operador['tipo_operador'] ?>)</span>
    </div>
    <div data-linea="5">
        <label>Representante legal: </label>
        <span><?php echo $operador['apellido_representante'] . ', ' . $operador['nombre_representante']; ?></span>
    </div>

    <div data-linea="7">
        <label>Dirección (según RUC): </label>
        <span><?php echo $operador['provincia'] . ' - ' . $operador['canton'] . ' (' . $operador['parroquia'] . '), ' . $operador['direccion']; ?></span>
    </div>
    <hr/>
    <div data-linea="9">
        <label>Teléfonos:</label>
        <span><?php  echo '[TF1]: <u>' . $operador['telefono_uno'] . '</u>' .
                ' | [TF2]: <u>' . $operador['telefono_dos'] . '</u>' .
                ' | [FAX]: <u>' . $operador['fax'] . '</u>' .
                ' | [CL1]: <u>' . $operador['celular_uno'] . '</u>' .
                ' | [CL2]: <u>' . $operador['celular_dos'] . '</u>';?>
        </span>
    </div>
    <hr/>
    <div data-linea="11">
        <label>Correo electrónico:</label>
        <span><?php echo $operador['correo']; ?></span>
    </div>
    <hr/>
    <div data-linea="13">
        <label>Registro de orquídeas:</label>
        <span><?php echo $operador['registro_orquideas']; ?></span>
    </div>
    <div data-linea="13">
        <label>Registro de madera:</label>
        <span><?php echo $operador['registro_madera']; ?></span>
    </div>
    <div data-linea="13">
        <label>Código GS1:</label>
        <span><?php echo $operador['gs1']; ?></span>
    </div>
    <hr/>
    <div data-linea="17">
        <label>Representante técnico: </label>
        <span><?php echo $operador['apellido_tecnico'] . ', ' . $operador['nombre_tecnico']; ?></span>
    </div>
    
    <?php if ($operador['ruta_poa'] != ''){
	    echo '<hr/>
    			<div data-linea="18">
	        		<label>Certificado POA: </label>
	        		<span><a href='.$operador['ruta_poa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado de Registro de Operador Orgánico</a></span>
	    		</div>';
    	}
    ?>
</fieldset>

<header><h1>
        Operaciones registradas
    </h1></header>
<?php
    $operaciones = $operador['operaciones'];
    $contadorDeOperaciones = 0;
    /*echo "<pre>";
    print_r($operaciones);
    echo "</pre>";*/
    if (!empty($operaciones)) {
        foreach ($operaciones as $operacion) {
            ?>
            <fieldset>
                <legend><?php echo ++$contadorDeOperaciones; ?> [<?php echo $operacion->id_area; ?>
                    -<?php echo $operacion->codigo; ?>] <?php echo $operacion->nombre; ?></legend>
                <form
                    id="f_<?php echo $contadorDeOperaciones; ?>" data-rutaAplicacion="operadores/json" data-opcion="cargarDatosDeOperacion">
                    <input type="hidden" name="tipoResultado" value="html"/>
                    <input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
                    <input type="hidden" name="area" value="<?php echo $area;?>"/>
                    <input type="hidden" name="tipoOperacion" value="<?php echo $operacion->id_tipo_operacion;?>"/>
                    <input type="hidden" name="destino" value="o_<?php echo $contadorDeOperaciones; ?>"/>

                    <button class="mo_areas" type="submit">Mostrar/Ocutar áreas</button>
                </form>
                <div id="o_<?php echo $contadorDeOperaciones; ?>"></div>
            </fieldset>
        <?php
        }
    }
?>

<script>

    $(document).ready(function () {
        $("div.mapa div").hide();
        distribuirLineas();
    });

    $("form").submit(function (e) {
    	e.preventDefault();
        //alert("#"+$(this).find("input[name='destino']").val());        
        ejecutarJson($(this), new exito());
    });

    $("form").on("click",".mo_areas[type='button']",function () {
        $(this).parent().parent().find("div").toggle();
    });

    $("fieldset div").on("click","div.mapa button",function () {
        mapaDestino = $(this).parent().find("div");
        if ($(this).hasClass("mostrar")) {


            $(this).removeClass("mostrar");
            $(this).addClass("ocultar");
            mapaDestino.show();
            //if( mapaDestino.attr("data-estado") == "Por cargar mapa")
            {
                longitud = $(this).parent().parent().find("div.longitud span").html();
                latitud = $(this).parent().parent().find("div.latitud span").html();
                zona = $(this).parent().parent().find("div.zona span").html();
                iniciarMapa(latitud, longitud, zona, 10, mapaDestino);
                //mapaDestino.attr("data-estado","Mapa cargado");
            }

        } else {
            $(this).removeClass("ocultar");
            $(this).addClass("mostrar");
            mapaDestino.hide();
        }
    });

    function exito(){
        this.ejecutar = function(msg){

            $(msg.destino).html(msg.resultado);
            $(msg.destino).parent().find("form button").attr("type","button");
            distribuirLineas();
        };
    }

    function iniciarMapa(latitud, longitud, zona, porcentajeZoom, mapaDestino) {

        var _mapOptions = {
            zoom: porcentajeZoom,
            center: new google.maps.LatLng(latitud, longitud),
            mapTypeControl: false,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},

            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var _map = new google.maps.Map(mapaDestino[0], _mapOptions);


        if (latitud != '' && longitud != '' && zona != '') {

            var latLog = new Array(2);
            latLog = UTM2Lat(latitud, longitud, zona);

            var _latitud = latLog[0];
            var _longitud = latLog[1];

            marker = null;
            placeMarker(marker, new google.maps.LatLng(_latitud, _longitud), _map);
        }

        /*google.maps.event.addListener(map, 'click', function (e) {
         placeMarker(e.latLng, map);
         });*/
    }

    function placeMarker(marker, position, map) {

        if (marker != null)
            marker.setMap(null);

        marker = new google.maps.Marker({
            position: position,
            map: map
        });

        map.panTo(position);

        $("#zoom").val(map.zoom);

        var xya = new Array(3);
        xya = NuevaLat2UTM(position.lat(), position.lng());

        $("#latitud").val(xya[0]);
        $("#longitud").val(xya[1]);
        $("#zona").val(xya[2]);
    }


    /*$(window).resize(function () {

     if ($("#latitud").val() != '' && $("#longitud").val() != '' && $("#zona").val() != '') {

     var vLat = $("#latitud").val();
     var vLong = $("#longitud").val();
     var zona = $("#zona").val();
     var zoom = Number($("#zoom").val());

     var xy = new Array(2);
     xy = UTM2Lat(vLat, vLong, zona);

     var latitud = xy[0];
     var longitud = xy[1];

     } else {
     var latitud = -1.537901237431487;
     var longitud = -78.99169921875;
     var zoom = 6;
     }

     iniciarMapa(latitud, longitud, zoom);

     });
     */
</script>