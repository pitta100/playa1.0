<h1 class="page-header">Lista de categorias </h1>
<a class="btn btn-success pull-right" href="#categoriaModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-c="categoria"> <i class="fa-solid fa-arrow-right-from-bracket"></i>  Agregar</a>
<br><br><br>
<table class="table table-striped table-bordered display responsive nowrap datatable" width="100%" id="tabla">

    <thead>
        <tr style="background-color: #000; color:#fff">
            <th> <i class="fa-solid fa-code"></i> Código</th>
            <th> <i class="fa-solid fa-code-fork"></i>  Sub Categoría de</th>
            <th> <i class="fa-solid fa-sitemap"></i> Nombre de la categoría</th>        
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($this->model->Listar() as $r): ?>
        <?php
        if($r->id_padre==0){
            $padre="Principal";
        }else{
            $categoria = $this->model->Obtener($r->id_padre);
            $padre = $categoria->categoria;
        } ?>
        <tr class="click">
            <td><?php echo $r->id; ?></td>
            <td><?php echo $padre; ?></td>
            <td><?php echo $r->categoria; ?></td>
            <td>
                <a  class="btn btn-success edit" href="#crudModal" class="btn btn-success" data-toggle="modal" data-target="#crudModal" data-id="<?php echo $r->id;?>" data-c="categoria"> <i class="fa-solid fa-pen-to-square"></i> Editar</a>
            </td>
            <td>
                <a  class="btn btn-danger delete" href="?c=categoria&a=Eliminar&id=<?php echo $r->id; ?>"> <i class="fa-solid fa-trash-can"></i> Eliminar</a>
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
        <li><a href="https://PITTA100.com" class="download">&copy;PITTA100 Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>

</dir>

