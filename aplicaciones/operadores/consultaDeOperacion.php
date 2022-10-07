<?php
    session_start();

    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';

    $conexion = new Conexion();
    $cc = new ControladorCatalogos();

    $provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
    $operaciones = pg_fetch_all($cc->listarOperaciones($conexion));
?>

<header>
    <h1>Consulta de Operadoraciones</h1>
    <nav>
        <form id="listarOperadores"
              data-rutaAplicacion="operadores"
              data-opcion="listarOperadores2"
              data-destino="resultados">
            <table class="filtro">
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
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Operacion</td>
                    <td>
                        <select id="operacion" name="operacion">
                            <option value="Todas">Cualquier área</option>
                        </select>
                    </td>
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
    Aquí van los resultados
</section>

<script>

    var operaciones = null;

    $(document).ready(function () {
        operaciones = <?php echo json_encode($operaciones);?>;
        //console.debug(operaciones);
    });

    $("#area").change(function(){

        $("#operacion").html("");
        $("#operacion").append("<option value='Todas'>Cualquier operación</option>");
        area = $("#area").val();
        for(operacion in operaciones){
            alert(area + " " + operaciones[operacion]["id_area"])
            if (operaciones[operacion]["id_area"] == area){
                $("#operacion").append("<option value='" + operaciones[operacion]["id_tipo_operacion"] + "'>" + operaciones[operacion]["nombre"] + "</option>");
            }
        }
    });

    $("#listarOperadores").submit(function (e) {
        abrir($(this), e, false);
    });
</script>

