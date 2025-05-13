<div id="diaModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="edit_form">  
                <form method="get">
                    <h1>GeoCad, genere un informe del día</h1>
                    <input type="hidden" name="c" value="venta">
                    <input type="hidden" name="a" value="cierre">
                    <div class="form-group">
                        <label> <i class="fa-regular fa-calendar-days"></i> Buscar día</label>
                        <input type="date" min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="fecha" class="form-control">
                    </div>

                    <div class="text-right">
                        <button class="btn btn-success"> <i class="fa-solid fa-cloud-arrow-up"></i> Generar</button>
                    </div>
                                    

                </form>
                <dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-danger" data-dismiss="modal" value="Cancelar">
            </div>
        </div>
    </div>
</div>
