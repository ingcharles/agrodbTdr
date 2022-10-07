<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'solicitudrequerimiento/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="ACTUALIZAR" method="post">			
    <fieldset>			
        <legend>Reactivos solicitado para el laboratorio</legend>
        <i class="fas fa-info-circle"></i><span> Para que se actualice el saldo en bodega es necesario que el Laboratorio realice el ingreso de lo entregado por Bodega.</span>
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Reactivo</th>
                    <th>Saldo en bodega</th>
                    <th>Unidad</th>
                    <th>Cantidad Solicitada</th>
                </tr></thead>
            <tbody id="tablaCantidades">
                <?php
                echo $this->itemsRequeridos;
                ?>
            </tbody>
        </table>
    </fieldset>
</form >
