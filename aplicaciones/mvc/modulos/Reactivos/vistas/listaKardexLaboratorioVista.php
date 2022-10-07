<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form>			
    <fieldset>
        <legend>Datos del Reactivo</legend>
        <table width="100%">
            <thead><tr>
                    <th>Reactivo</th>
                    <th>Unidad medida</th>
                    <th>Lote</th>
                    <th>Ingresos</th>
                    <th>Egresos</th>
                    <th>Saldo</th>
                    <th>Fecha Caducidad</th>
                </tr></thead>
            <tbody>
                <?php
                echo $this->itemsSaldosLote;
                ?>
            </tbody>
        </table>
    </fieldset>

    <fieldset>			
        <legend>K&aacute;rdex</legend>	
        <table width="100%" id="tblKardex">
            <thead><tr>
                    <th>#</th>
                    <th>Ingreso/Egreso</th>
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <th>Saldo</th>
                    <th>Fecha de registro</th>
                    <th>Raz&oacute;n salida</th>
                    <th>C&oacute;digo muestra</th>
                    <th>N&uacute;mero de resulta de an&aacute;lisis</th>
                </tr></thead>
            <tbody>
                <?php
                echo $this->listaKardexLaboratorios;
                ?>
            </tbody>
        </table>
    </fieldset>
</form>

