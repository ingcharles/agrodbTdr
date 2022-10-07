<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 21/02/18
 * Time: 10:05
 */
require_once "../../Util.php";
$util = new Util();
?>
<meta charset="utf-8">
<link href="aplicaciones/inocuidad/componentes/archivo-adjunto/estilos/globaladjunto.css" rel="stylesheet"/>
<script>
    MAX_FILE_SIZE = <?php echo $util->file_upload_max_size(); ?>;
</script>
<script src="aplicaciones/inocuidad/componentes/archivo-adjunto/js/globaladjunto.js"/>
<div id="file_dialog">
    <div id="dataTable"></div>
    <div id="formContent"></div>
    <div id="file_msg_box" class=""></div>
</div>

<script>
    $("#formContent").load("aplicaciones/inocuidad/componentes/archivo-adjunto/file.php");
</script>