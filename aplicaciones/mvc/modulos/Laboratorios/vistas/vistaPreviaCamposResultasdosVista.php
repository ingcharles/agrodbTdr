<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	
<div class="modal-dialog">
    <div class="center-block" class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ingreso de resultados</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">
                    <?php echo $this->camposResultado; ?>
                </div>
            </div>
            <button type ="button" class="btnenviar">Guardar</button>
        </div>
    </div>
</div>

