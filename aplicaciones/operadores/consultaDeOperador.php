<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');

?>

<header>
    <h1>Consulta de Operadores</h1>
    <nav>
        <form id="listarOperadores" data-rutaAplicacion="operadores" data-opcion="listarOperadores" data-destino="resultados">
        	<input type="hidden" id="tipoProcesoCombo" name="tipoProcesoCombo">
            <table class="filtro">
                <tr>
                    <td class="obligatorio">Buscar por: </td>
                    <td><input name="tipo" type="radio" id="razon" value="razon"><label for="razon">Razón social</label>
                        <input name="tipo" type="radio" id="rucci" value="ruc" checked="checked"><label for="rucci">RUC/CI</label></td>
                </tr>
                <tr>
                    <td class="opcional">Razón / RUC</td>
                    <td><input type="text" name="textoDeBusqueda"/></td>

                </tr>

                <tr>
                    <td class="obligatorio">Provincia</td>
                    <td>
                        <select id="provincia" name="provincia">
                            <option value="Todas">
                                Cualquier provincia
                            </option>
                            <?php
                            while ($provincia = pg_fetch_assoc($provincias)) {
                                echo "<option value='" . $provincia['nombre'] . "'>" .
                                    $provincia['nombre'] .
                                    "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="obligatorio">Área</td>
                    <td>
                        <select id="area" name="area">
                            <option value="Todas">
                                Cualquier área
                            </option>
                            <option value="IAP">
                               Registro de insumos (Agrícolas)
                            </option>
                            <option value="IAV">
                                Registro de insumos (Pecuarios)
                            </option>
                            <option value="IAF">
                                Registro de insumos (Fertilizantes)
                            </option>
                            <option value="IAPA">
                                Registro de insumos (Plantas de autoconsumo)
                            </option>     
                            <option value="SA">
                                Sanidad animal
                            </option>
                            <option value="SV">
                                Sanidad vegetal
                            </option>
                            <option value="LT">
                                Laboratorios
                            </option>
                             <option value="AI">
                                Inocuidad de los alimentos
                            </option>
                        </select>
                    </td>
                </tr>
                <tr id="comboTipoOperacion">
                </tr>
                <tr>
                    <td colspan="4">
                        <button>Buscar</button>
                    </td>
                </tr>
            </table>
        </form>
    </nav>
</header>
<section id="resultados">
</section>

<script>
    $("#listarOperadores").submit(function(event){
    	event.stopImmediatePropagation();
		$("#listarOperadores").attr('data-opcion', 'listarOperadores');
		$("#listarOperadores").attr('data-destino', 'resultados');
        abrir($(this), event, false);
    });

    $("#area").change(function(event){
        if($("#area").val() == 'LT'){
			event.stopImmediatePropagation();
			$("#listarOperadores").attr('data-opcion', 'combosOperador');
    		$("#listarOperadores").attr('data-destino', 'comboTipoOperacion');
    		$("#tipoProcesoCombo").val('tipoOperacion');
			abrir($("#listarOperadores"),event,false);
        }
        $("#comboTipoOperacion").empty();
	});
</script>

