<?php 
$licenciaPath = __DIR__ . '/../../../licencia/licencia.key';
$claveEsperada = 'p&8automotoressapitta100198830';

// Verifica si el archivo existe y si contiene la clave correcta
if (!file_exists($licenciaPath) || trim(file_get_contents($licenciaPath)) !== $claveEsperada) {
    die('🔒 Acceso denegado. Esta instalación no está autorizada.');
}
 ?>
<h1 class="page-header">Usuarios </h1>
<a class="btn btn-success pull-right" href="#usuarioModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="usuario"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-user"></i> Usuario</th>
            <th> <th><i class="fa-solid fa-lock"></i> Contraseña</th>
            <th> <i class="fa-solid fa-box-archive"></i> Rol</th>
            <th> <i class="fa-solid fa-money-bill-1-wave"></i> Comisión (%)</th>
            <th> <i class="fa-solid fa-code-branch"></i> Sucursal</th>            
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($this->model->Listar() as $r): ?>
        <?php 
            if($r->nivel==1){
                $nivel = 'sector Administrativo';
            }elseif($r->nivel==2){
                $nivel = 'Propietarios';
            }elseif($r->nivel==3){
                $nivel = 'sector Limpieza';
            }elseif($r->nivel==4){
                $nivel = 'sector Informatico';
            }elseif($r->nivel==5){
                $nivel = 'sector Tasacion';
            }elseif($r->nivel==6){
                $nivel = 'sector Produccion';
            }else
            {
                $nivel = 'Sector Otros ';
            }
        ?>
        <tr class="click">
            <td><a href="?c=venta&a=listarusuario&id_usuario=<?php echo $r->id; ?>&mes=<?php echo date("Y-m"); ?>"><?php echo $r->user; ?></a></td>
            <td>............</td>
            <td><?php echo $nivel; ?></td>
            <td><?php echo $r->comision; ?></td>
            <td><?php echo $r->sucursal; ?></td>
            <td>
                <a  class="btn btn-success edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="usuario"> <i class="fa-solid fa-pen-to-square"></i> Editar</a>
            </td>
            <td>
                <a  class="btn btn-danger delete" href="?c=usuario&a=Eliminar&id=<?php echo $r->id; ?>"> <i class="fa-solid fa-trash-can"></i> Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
</div> 
<?php include("view/crud-modal.php"); ?>

<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>


</dir>