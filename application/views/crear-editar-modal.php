<div class="modal-dialog">
    <form id="form-modal" method="POST" action="#" class="modal-content " novalidate>
        <input type="hidden" name="evento[id]" value="<?= isset($datos['id']) ? $datos['id'] : '0' ?>">
        <div class="modal-header">
            <h5 class="modal-title"><?= (isset($datos['id']) && intval($datos['id']) > 0) ? 'Editar' : 'Crear' ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="ititulo" class="form-label">T&iacute;tulo</label>
                        <input type="text" class="form-control" id="ititulo" name="evento[titulo]" placeholder="Nombres" required value="<?= isset($datos['titulo']) ? $datos['titulo'] : '' ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="idescripcion" class="form-label">Descripci&oacute;n</label>
                        <input type="text" class="form-control" id="idescripcion" name="evento[descripcion]" placeholder="Usuario" required value="<?= isset($datos['descripcion']) ? $datos['descripcion'] : '' ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="ifecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="ifecha_inicio" name="evento[fecha_inicio]" placeholder="Correo" required value="<?= isset($datos['fecha_inicio']) ? $datos['fecha_inicio'] : '' ?>" min="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="ifecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="ifecha_fin" name="evento[fecha_fin]" placeholder="Tel&eacute;fono" required value="<?= isset($datos['fecha_fin']) ? $datos['fecha_fin'] : '' ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>