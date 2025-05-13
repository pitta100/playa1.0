<div id="mesModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="edit_form">  
                <form method="get">
                    <h1>GeoCad, genere su informe mensual</h1>
                    <input type="hidden" name="c" value="venta">
                    <input type="hidden" name="a" value="cierreMes">
                    <div class="form-group">
                        <label>Desde</label>
                        <input type="date" min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="desde" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Hasta</label>
                        <input type="date" min="2020-11-01" max="<?php echo date("Y-m-d") ?>" name="hasta" class="form-control">
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
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
            </div>
        </div>
    </div>
</div>
