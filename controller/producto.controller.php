<?php
require_once 'model/producto.php';
require_once 'model/categoria.php';
require_once 'model/marca.php';
require_once 'model/imagen.php';
require_once 'model/sucursal.php';
require_once 'model/cierre.php';
require_once 'model/cliente.php';
class productoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new producto();
        $this->categoria = new categoria();
        $this->cierre = new cierre();
        $this->marca = new marca();
        $this->imagen = new imagen();
        $this->sucursal = new sucursal();
        $this->cliente = new cliente();

    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/producto/producto.php';
        require_once 'view/footer.php';
       
    }

    public function Listar(){
        require_once 'view/producto/producto.php';
    }
     public function ListarAJAX(){
    $producto = $this->model->ListarAjax();
       echo json_encode($producto, JSON_UNESCAPED_UNICODE);
    }
    
    public function ListarAJAXproducto(){
    $producto = $this->model->ListartodoProductos();
       echo json_encode($producto, JSON_UNESCAPED_UNICODE);
    }

    public function Crud(){
        $producto = new producto();
        
        if(isset($_REQUEST['id'])){
            $producto = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/producto/producto-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $producto = new producto();
        
        if(isset($_REQUEST['id'])){
            $producto = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/producto/producto-editar.php';
        
    }

    public function Buscar(){
        
        if(isset($_REQUEST['id'])){
            $producto = $this->model->Obtener($_REQUEST['id']);
        }
        echo json_encode($producto);
        
    }
    
    public function Guardar(){
    $producto = new producto();

    // Datos básicos
    $producto->id = $_REQUEST['id'];
    $producto->codigo = $_REQUEST['codigo'];
    $producto->id_categoria = $_REQUEST['id_categoria'];
    $producto->producto = $_REQUEST['producto'];
    $producto->marca = $_REQUEST['marca'];
    $producto->marcaVehiculo = $_REQUEST['marcaVehiculo'];
    $producto->modelo = $_REQUEST['modelo'];
    $producto->anio = $_REQUEST['anio'];
    $producto->version = $_REQUEST['version'];
    $producto->color = $_REQUEST['color'];
    $producto->puertas = $_REQUEST['puertas'];
    $producto->combustible = $_REQUEST['combustible'];
    $producto->transmision = $_REQUEST['transmision'];
    $producto->traccion = $_REQUEST['traccion'];
    $producto->placa = $_REQUEST['placa'];
    $producto->tipo_vehiculo = $_REQUEST['tipo_vehiculo'];
    $producto->vin = $_REQUEST['vin'];
    $producto->motor = $_REQUEST['motor'];
    $producto->kilometraje = $_REQUEST['kilometraje'];
    $producto->importado = $_REQUEST['importado'];
    $producto->pais_origen = $_REQUEST['pais_origen'];
    $producto->fecha_importacion = $_REQUEST['fecha_importacion'];
    $producto->usado = $_REQUEST['usado'];
    $producto->dueno_anterior = $_REQUEST['dueno_anterior'];
    $producto->cedula_rif = $_REQUEST['cedula_rif'];
    $producto->descripcion = $_REQUEST['descripcion'];
    $producto->precio_costo = $_REQUEST['precio_costo'];
    $producto->precio_mayorista = $_REQUEST['precio_mayorista'];
    $producto->precio_minorista = $_REQUEST['precio_minorista'];
    $producto->precio_financiado = isset($_REQUEST['precio_financiado']) ? $_REQUEST['precio_financiado'] : 0;
    $producto->entrega_minima = isset($_REQUEST['entrega_minima']) ? $_REQUEST['entrega_minima'] : 0;
    $producto->cuotas_minimas = isset($_REQUEST['cuotas_minimas']) ? $_REQUEST['cuotas_minimas'] : 0;
    $producto->cant_refuerzo = $_REQUEST['cant_refuerzo'];
    $producto->monto_minimo_refuerzo = $_REQUEST['monto_minimo_refuerzo'];

    $producto->stock = $_REQUEST['stock'];
    $producto->stock_minimo = $_REQUEST['stock_minimo'];
    $producto->descuento_max = $_REQUEST['descuento_max'];
    $producto->iva = $_REQUEST['iva'];
    $producto->sucursal = $_REQUEST['sucursal'];

    // Documentos adjuntos
        $documentos = [
            'titulo_propiedad',
            'factura_original',
            'revision_tecnica',
            'permiso_circulacion'
        ];

        foreach ($documentos as $doc) {
            if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === UPLOAD_ERR_OK) {
                // Si se seleccionó un archivo nuevo
                $nombre = uniqid() . '_' . basename($_FILES[$doc]['name']);
                $destino = 'assets/documentos/' . $nombre;
                if (move_uploaded_file($_FILES[$doc]['tmp_name'], $destino)) {
                    $producto->$doc = $nombre;  // Actualiza el nombre del archivo
                }
            } else {
                // Si no se subió un archivo nuevo, mantener el nombre del archivo actual
                if (isset($_REQUEST[$doc . '_actual'])) {
                    $producto->$doc = $_REQUEST[$doc . '_actual'];
                }
            }
        }


    // Imágenes múltiples
    $ultimo = $this->model->Ultimo();
    $ultimo_id = $producto->id > 0 ? $producto->id : ($ultimo->id + 1);

    $imagen = new imagen();
    $imagen->id_producto = $ultimo_id;

    if (isset($_FILES["imagen"]) && count($_FILES["imagen"]["tmp_name"]) > 0 && $_FILES["imagen"]["name"][0] != "") {
        for ($x = 0; $x < count($_FILES["imagen"]["name"]); $x++) {
            $file = $_FILES["imagen"];
            $nombre = rand(1000, 1000000) . $file["name"][$x];
            $tipo = $file["type"][$x];
            $ruta = $file["tmp_name"][$x];
            $size = $file["size"][$x];
            $carpeta = "assets/img/";

            if (in_array($tipo, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']) && $size <= 5 * 1024 * 1024) {
                move_uploaded_file($ruta, $carpeta . $nombre);
                $imagen->imagen = $nombre;
                $this->imagen->Registrar($imagen);
            }
        }
    }

    // Guardar producto (manteniendo tu ternario estilo ninja 😎)
    $producto->id > 0 
        ? $this->model->Actualizar($producto)
        : $this->model->Registrar($producto);

    $producto->id > 0 
        ? $accion = "Modificado"
        : $accion = "Agregado";

    header('Location:' . getenv('HTTP_REFERER'));
}

    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?success=Eliminado&c='.$_REQUEST['c']);
    }
    public function informeVehicular(){
       
        require_once 'view/informes/reportevehiculopdf.php';
        
    }
    public function datosFinanciacion() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $producto = $this->producto->Obtener($id); // Ajustá esto a tu modelo real

        echo json_encode([
            'entrega' => $producto->entrega,
            'cuotas' => $producto->cuotas,
            'montoRefuerzo' => $producto->monto_refuerzo,
            'cantidadRefuerzo' => $producto->cantidad_refuerzo,
        ]);
    }
}

   

}