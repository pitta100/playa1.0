<h1 class="page-header"> Lista de clientes  </h1>
</ul>
</ul>
<a class="btn btn-success pull-right" href="#clienteModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="cliente"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Agregar</a>
<br><br><br>

<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%" id="tabla">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-language"></i> CI/RUC</th>
            <th> <i class="fa-solid fa-user"></i> Nombre</th>
            <th> <i class="fa-solid fa-phone-volume"></i> Teléfono</th>
            <th> <i class="fa-solid fa-map-location-dot"></i> Direccion</th>
            <th>Correo</th>
            <th>Ubicacion</th>
            <th> <i class="fa-regular fa-calendar-days"></i>Dir-Trab.</th>
            <th> <i class="fa-regular fa-calendar-days"></i>Tele-Trab.</th>
            <?php if($_SESSION['nivel']<=1){ ?>
            <th></th>
            <th></th>
            <th></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($this->model->Listar() as $r):
        if ($r->gastado < 3000000) {
            $categoria = "Plata";
        }elseif ($r->gastado >= 3000000 && $r->gastado < 10000000) {
            $categoria = "Oro";
        }else{
            $categoria = "Platino";
        }
    ?>
        <tr class="click">
            <td><?php echo $r->ruc; ?></td>
            <td><a href="?c=venta&a=listarcliente&id_cliente=<?php echo $r->id; ?>"><?php echo $r->nombre; ?></a></td>
            <td><?php echo $r->telefono; ?></td>
            <td><?php echo $r->direccion; ?></td>
            <td><?php echo $r->correo; ?></td>
            <td>
                <a href="<?= $r->residencia_url; ?>" target="_blank" style="color: green;">Ver ubicación</a>
            </td>
            <td><?php echo $r->adressWork; ?></td>
            <td><?php echo $r->phoneWork; ?></td>
            <?php if($_SESSION['nivel']<=1){ ?>
            <td>
                <a  class="btn btn-success edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id; ?>" data-c="cliente"> <i class="fa-solid fa-pen-to-square"></i> Editar</a>
            </td>
            <td>
                <a  class="btn btn-danger delete" href="?c=cliente&a=Eliminar&id=<?php echo $r->id; ?>"> <i class="fa-solid fa-trash-can"></i> Eliminar</a>
            </td>
            <?php } ?>
            <td>
                <a  class="btn btn-warning" href="?c=cliente&a=resumenCliente&id=<?php echo $r->id; ?>"></i> PDF</a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<ol class="breadcrumb">
  <li><a class="btn btn-success pull-right"  href="?c=egreso"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Visite el sector Egreso.</a></li>
</ol>
<ol class="breadcrumb">
  <li><a class="btn btn-success pull-right"  href="?c=ingreso"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Visite el sector Ingreso.</a></li>
</ol>
<dir>
 <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina -P & Q AUTOMOTORES SA.<?php echo date("Y") ?></a></li>
    </ul>


</dir>
</div>
</div>
</div>
<?php include "view/crud-modal.php";?>

