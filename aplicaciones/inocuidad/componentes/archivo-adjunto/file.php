<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 24/01/18
 * Time: 22:12
 */
 
 require_once '../../../../clases/Constantes.php';
 
 $constg = new Constantes();
 
$_SESSION['_ABSPATH_']=$_SERVER['DOCUMENT_ROOT'] . '/'.$constg::RUTA_APLICACION.'/';

?>
<script src="aplicaciones/inocuidad/componentes/archivo-adjunto/js/fileForm.js"/>
<form  id="adjuntoForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" value="" id="adjunto_tabla" name="adjunto_tabla">
    <input type="hidden" value="" id="adjunto_registro" name="adjunto_registro">
    <fieldset id="fs_detalle" class="adjuntos_form_tabla">
        <legend>Agregar Archivo</legend>
        <div data-linea="1">
            <label for="adjunto_nombre">Nombre</label>
            <input type="text" id="adjunto_nombre" name="adjunto_nombre" data-required required style="width: 176px"/>
        </div>
        <div data-linea="1">
            <label for="adjunto_descripcion">Descripción</label>
            <input type="text" id="adjunto_descripcion" name="adjunto_descripcion" data-required required style="width: 176px"/>
        </div>
        <div data-linea="2">
            <label for="adjunto_fecha_carga">Fecha Carga</label>
            <input type="text" id="adjunto_fecha_carga" name="adjunto_fecha_carga" data-required required/>
        </div>
        <div data-linea="2">
            <label for="adjunto_etiqueta">Etiqueta</label>
            <input type="text" id="adjunto_etiqueta"  name ="adjunto_etiqueta" data-required required/>
        </div>
        <div data-linea="3">
            <label for="adjunto_file">Ruta</label>
            <input type="file" name="adjunto_file" id="adjunto_file" multiple data-required required/>
        </div>
        <div data-linea="3">
            <a href="#" id="fileUpButt" class="material_link"><i class="material-icons">cloud_upload</i></a>
        </div>

    </fieldset>
</form>