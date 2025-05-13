<?php $cierre = $this->cierre->Ultimo();?>
<div class="col-sm-4">
</div>
<div class="col-sm-4">
    <form id="crud-frm" method="post" action="?c=cierre&a=apertura">  
        <h1 style="color: black" align="center">Bienvenido genere su Apertura de caja</h1>
        <div class="form-group">
            <label style="color: black"> <i class="fa-solid fa-dollar-sign"></i> Cotización Dolar</label>
            <input type="number" id="dolares" name="cot_dolar" value="<?php echo $cierre->cot_dolar; ?>" class="form-control" min="1">
		</div>

        <div class="form-group">
            <label style="color: black"> <i class="fa-solid fa-brazilian-real-sign"></i> Cotización Real</label>
            <input type="number" id="reales" name="cot_real" value="<?php echo $cierre->cot_real; ?>" class="form-control" min="1">
        </div>

        <div class="form-group">
            <label style="color: black"> <i class="fa-solid fa-cash-register"></i> Caja</label>
                <select name="id_caja" class="form-control">
                    <?php //foreach($this->caja->ListarUsuario($_SESSION['user_id']) as $r): ?>
                    <option value="3">Caja chica</option>
                    <?php //endforeach; ?>
                </select>
            <!--<select name="id_caja" class="form-control">
                <?php //foreach($this->caja->ListarUsuario($_SESSION['user_id']) as $r): ?>
                <option value="<?php //echo $r->id ?>"><?php //echo $r->caja ?></option>
                <?php //endforeach; ?>
            </select>-->
        </div>

        <div class="form-group">
            <label style="color: black">Monto Inicial En caja</label>
            <input type="number" name="monto_apertura" value="0" class="form-control" min="0" >
        </div>
        <div class="form-group">
        	<input type="submit" class="btn btn-success" value="Dar Apertura">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
        </div>
    </form>
    <div class="form-group">
        <p style="color: gray" align="left">* Referencia Cambios Chaco</p>
        <iframe width="100%" height="300" src="http://www.cambioschaco.com.py/widgets/cotizacion/?lang=es" frameborder="0"></iframe>
    </div>
</div>
<div class="col-sm-4">
</div>