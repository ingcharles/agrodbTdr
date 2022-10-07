<header>
    <h1>Datos de la solicitud</h1>
</header>

<form>
    <fieldset>
        <legend>DATOS DE LA SOLICITUD</legend>
        <div data-linea="1">
            <label for="">C&oacute;digo</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->codigo; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="1">
            <label for="">Estado</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->estado; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="1">
            <label for="">Fecha registro</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->fecha_registro; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="2">
            <label for="">Muestreo nacional</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->muestreo_nacional; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="2">
            <label for="">Exoneraci&oacute;n de pago</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->exoneracion; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="2">
            <label for="">Oficio Exoneraci&oacute;n</label> 
            <input type="text" value="<?php echo!empty($this->datosSolicitud->oficio_exoneracion) ? $this->datosSolicitud->oficio_exoneracion : 'N/A'; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="3">
            <label for="">Provincia de la muestra</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->prov_muestra; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <div data-linea="3">
            <label for="">Provincia del laboratorio</label> 
            <input type="text" value="<?php echo $this->datosSolicitud->prov_laboratorio; ?>" 
                   style="background: transparent; border: 0" readonly/>
        </div>
        <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
            <thead>
                <tr>
                    <th colspan="5">Servicios solicitados</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Direcci&oacute;n</th>
                    <th>Laboratorio</th>
                    <th>Servicio</th>
                    <th>N&uacute;mero de an&aacute;lisis</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->detalleSolicitudesGuardado; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right">Total n&uacute;mero de an&aacute;lisis: </th>
                    <th><input type="text" id="totalAnalisisSolicitados" readonly style="background-color: transparent; border: 0; text-align: center" value="<?php echo $this->totalAnalisisSolicitud; ?>"</th>
                    </tr>
                <tr>
                    <th colspan="4" style="text-align: right">Total n&uacute;mero de muestras: </th>
                    <th><input type="text" id="totalSolicitados" readonly style="background-color: transparent; border: 0; text-align: center" value="<?php echo $this->totalMuestrasSolicitud; ?>"</th>
                </tr>
            </tfoot>
        </table>
               
        <table width="100%" class="lista">
            <thead>
                <tr>
                    <th colspan="5">Anexos de la solicitud</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th title="Dar clic sobre el nombre para ver al archivo adjnto">Archivo adjunto</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->archivosAdjuntosSolicitud; ?>
            </tbody>
        </table>
    </fieldset>
</form>
<script>
    distribuirLineas();
</script>