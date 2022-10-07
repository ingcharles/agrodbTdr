<header>
    <h1>&Oacute;rdenes de trabajo</h1>
</header>
<fieldset>
    <legend>&Oacute;rdenes de trabajo</legend>

    <table id="tablaOrdenTrabajo">
        <thead>
            <tr>
                <th>#</th>
                <th>C&oacute;digo</th>
                <th>Laboratorio</th>
                <th>Fecha de inicio</th>
                <th>Estado</th>
                <th>Costo en Orden</th>
                <th>Descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->itemsOrdenesTrabajo as $fila)
            {
                echo $fila[0];
            }
            ?>
        </tbody>
    </table>
</fieldset>
