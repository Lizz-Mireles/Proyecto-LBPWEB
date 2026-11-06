<?php
include("sidebar.php");
?>
<div class="contenido">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-plus-circle">
                </i>Nuevo Ticket
            </h4>
        </div>
        <div class="card-body">
            
                <form action="guardar_ticket.php" method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                            <select name="tipo_reporte" id="tipo_reporte" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="hardware">Hardware</option>
                                <option value="Software">Software</option>
                                <option value="Impresora">Impresora</option>
                                <option value="Internet">Internet</option>
                                <option value="Correo">Correo</option>
                                <option value="Telefonia">Telefonia</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="prioridad" class="form-label">Prioridad</label>
                            <select name="prioridad" id="prioridad" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="critica">Critica</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="titulo" class="form-label">Titulo</label>
                            <input type="text" name="titulo" class="form-control" maxlength="255" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="departamento" class="form-label">Departamento</label>
                            <input type="text" name="departamento" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ubicacion" class="form-label">Ubicacion</label>
                            <input type="text" name="ubicacion" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inventario" class="form-label">Inventario</label>
                            <input type="text" name="inventario" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="anydesk" class="form-label">Anydesk</label>
                            <input type="text" name="anydesk" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="quien_reporta" class="form-label">Quien reporta</label>
                            <input type="text" name="quien_reporta" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="extension" class="form-label">Extension</label>
                            <input type="text" name="extension" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="tel" name="telefono" class="form-control" pattern="[0-9]{10}" maxlength="10">
                        </div>
                    </div>
                    <?php if(isset($_GET['ok'])){?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            Ticket creado correctamente
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i>Guardar ticket
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>Cancelar
                    </a>
                </form>
        </div>
    </div>
</div>