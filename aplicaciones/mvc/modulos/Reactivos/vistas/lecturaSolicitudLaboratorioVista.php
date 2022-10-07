<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'solicitudrequerimiento/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="ACTUALIZAR" method="post">			
    <fieldset>			
        <legend>Reactivos solicitado para el laboratorio</legend>			
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Unidad</th>
                    <th>Saldo</th>
                    <th>Cantidad solicitada</th>
                </tr></thead>
            <tbody id="tablaCantidades">
                <?php
                echo $this->itemsRequeridos;
                ?>
            </tbody>
        </table>
    </fieldset >
</form >
