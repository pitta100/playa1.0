<?php
require_once 'model/venta_tmp.php';
require_once 'model/venta.php';
require_once 'model/vendedor.php';
require_once 'model/producto.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';
require_once 'model/usuario.php';
require_once 'model/caja.php';
require_once 'model/pago_tmp.php';
require_once 'model/metodo.php';
require_once 'model/gift_card.php';

class venta_tmpController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new venta_tmp();
        $this->venta_tmp = new venta_tmp();
        $this->venta = new venta();
        $this->usuario = new usuario();
        $this->vendedor = new vendedor();
        $this->producto = new producto();
        $this->cliente = new cliente();
        $this->cierre = new cierre();
        $this->caja = new caja();
        $this->pago_tmp = new pago_tmp();
        $this->metodo = new metodo();
        $this->gift_card = new gift_card();
    }
    
    public function Index(){
        $fecha = date ('Y-m-d');
        require_once 'view/header.php';
        if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])) { 
            if($this->cierre->ConsultarCierre($_SESSION['user_id'],$fecha)){
                echo '<form method="get">
                    <h1>Generar cierre de caja</h1>
                    <input type="hidden" name="c" value="cierre">
                    <input type="hidden" name="a" value="cierre">
                    <div class = "row ">
                    <div class= "form-group text-center col-sm-4"></div>
                    <div class="form-group text-center col-sm-4">
                        <label>Monto en caja</label>
                        <input type="number" value="0" name="monto_cierre" class="form-control" required>
                    </div>
                    <div class= "form-group text-center col-sm-4"></div>
                    </div> 
                    <div class = "text-center">
                        <button class="btn btn-success ">Generar</button>
                    </div>
                    <h5 align="center"> PITTA100 Company, Sistemas Informatico, Electromedicina::Todos los derechos reservados Pitta-company tesh 2024</h5>     

                </form>';
            }else{
                require_once 'view/venta/nueva-venta.php';
            }
        }else{
            require_once 'view/venta/apertura.php';
        }
        require_once 'view/footer.php';
       
    }
    
   /* public function Index(){
        require_once 'view/header.php';
        if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])) {     
            require_once 'view/venta/nueva-venta.php';
        }else{
            require_once 'view/venta/apertura.php';
        }
        require_once 'view/footer.php';
       
    }*/
    
    public function Mayorista(){
        require_once 'view/header.php';
        if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])) {     
            require_once 'view/venta/nueva-ventamayor.php';
        }else{
            require_once 'view/venta/apertura.php';
        }
        require_once 'view/footer.php';
       
    }

    public function Editar(){

        $venta = new venta();
        
        if(isset($_REQUEST['id'])){
            $venta = $this->venta->ObtenerUno($_REQUEST['id']);
        }

        require_once 'view/header.php';
        require_once 'view/venta/venta-editar.php';
        require_once 'view/footer.php';
       
    }


    public function Listar(){
        require_once 'view/venta/nueva-venta.php';
    }


    
    public function Crud(){
        $venta_tmp = new venta_tmp();
        
        if(isset($_REQUEST['id'])){
            $venta_tmp = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/venta_tmp/venta_tmp-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $venta_tmp = new venta_tmp();
        
        if(isset($_REQUEST['id'])){
            $venta_tmp = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/venta_tmp/venta_tmp-editar.php';
        
    }

    public function ObtenerMoneda(){
        $venta_tmp = new venta_tmp();
        
        $venta_tmp = $this->model->ObtenerMoneda();
        
        
    }
    
    public function Guardar() {
        // Acceso al producto mediante el código (si es necesario)
        $producto = $this->producto->Codigo($_REQUEST['codigo']);
        $pro = $this->producto->Obtener($_REQUEST['id_producto']);
        
        // Inicia la sesión
        session_start();
        
        // Crear instancia de venta_tmp
        $venta_tmp = new venta_tmp();
        
        // Asigna valores al objeto venta_tmp
        $venta_tmp->id = 0;
        $venta_tmp->id_venta = 1;  // Aquí podrías asignar el ID de la venta si lo tienes
        $venta_tmp->id_vendedor = $_SESSION['user_id'];  // El ID del vendedor viene de la sesión
        $venta_tmp->id_producto = $_REQUEST['id_producto'];  // El ID del producto seleccionado
        $venta_tmp->precio_venta = ($_REQUEST['precio_venta']) ? $_REQUEST['precio_venta'] : $pro->precio_minorista;  // Si hay precio, se usa, sino el precio minorista
        $venta_tmp->cantidad = $_REQUEST['cantidad'];  // Cantidad del producto
        $venta_tmp->descuento = $_REQUEST['descuento'];  // Descuento aplicado
        $venta_tmp->fecha_venta = date("Y-m-d H:i");  // Fecha actual de la venta

        // Si tienes más campos ocultos en el formulario (como los valores de financiación), también debes recibirlos y asignarlos:
        $venta_tmp->entrega = $_REQUEST['entrega'];  // Valor de entrega
        $venta_tmp->cuota_vehiculo = $_REQUEST['cuota_vehiculo'];  // Cuota de financiación
        $venta_tmp->monto_refuerzo = $_REQUEST['monto_refuerzo'];  // Monto de refuerzo
        $venta_tmp->cantidad_refuerzo = $_REQUEST['cantidad_refuerzo'];  // Cantidad de refuerzo

        // Ahora que tienes todos los valores, puedes hacer el registro o actualización de la venta
        if ($venta_tmp->id > 0) {
            $this->model->Actualizar($venta_tmp);  // Actualiza la venta si ya existe
        } else {
            $this->model->Registrar($venta_tmp);  // Registra una nueva venta si es un nuevo registro
        }

        // Esto cargará la vista con la tabla de venta actualizada
        require_once "view/venta/tabla_venta.php";
    }

    
    public function GuardarMayorista(){

        $producto = $this->producto->Codigo($_REQUEST['codigo']);
        session_start();
        $venta_tmp = new venta_tmp();
        
        $venta_tmp->id = 0;
        $venta_tmp->id_venta = 1;
        $venta_tmp->id_vendedor = $_SESSION['user_id'];
        $venta_tmp->id_producto = $_REQUEST['id_producto'];
        $venta_tmp->precio_venta = $_REQUEST['precio_venta'];
        $venta_tmp->cantidad = $_REQUEST['cantidad'];
        $venta_tmp->descuento = $_REQUEST['descuento'];
        $venta_tmp->fecha_venta = date("Y-m-d H:i");
        

        $venta_tmp->id > 0 
            ? $this->model->Actualizar($venta_tmp)
            : $this->model->Registrar($venta_tmp);
        
        header('Location: index.php?c=venta_tmp&a=mayorista');
    }

    public function GuardarUno(){
         

        $venta = new venta();

        $costo = $_REQUEST['precio_costo'];
        $venta = $_REQUEST['precio_venta'];
        
        $venta->id = 0;
        $venta->id_venta = 1;
        $venta->id_cliente = $_REQUEST['id_cliente'];
        $venta->id_vendedor = $_REQUEST['id_venta'];
        $venta->id_producto = $_REQUEST['codigo'];
        $venta->precio_costo = $_REQUEST['precio_costo'];
        $venta->precio_venta = $_REQUEST['precio_venta'];
        $venta->subtotal = $_REQUEST['subtotal'];
        $venta->descuento = 0;
        $venta->iva = 0;
        $venta->total = $_REQUEST['total'];
        $venta->comprobante = $_REQUEST['comprobante'];
        $venta->nro_comprobante = $_REQUEST['nro_comprobante'];
        $venta->cantidad = $_REQUEST['cantidad'];
        $venta->margen_ganancia = round(((($venta - $costo)*100)/$costo),2);
        $venta->fecha_venta = $_REQUEST['fecha_venta'];
        $venta->metodo = $_REQUEST['metodo'];
        $venta->banco = $_REQUEST['banco'];
        $venta->contado = $_REQUEST['contado'];
        

        $venta->id > 0 
            ? $this->venta->Actualizar($venta_tmp)
            : $this->venta->Registrar($venta_tmp);

        if($venta->contado=='Cuota')
            $deuda = $this->deuda->EditarMonto($venta->id_venta, $venta->total);

        if($venta->contado=='Contado')
            $deuda = $this->ingreso->EditarMonto($venta->id_venta, $venta->total);

        header('Location: index.php?c=venta_tmp&a=editar&id='.$venta->id_venta);
    }

    public function Moneda(){

        $venta_tmp = new venta_tmp();
        
        $venta_tmp->id = 0;
        $venta_tmp->reales = $_REQUEST['reales'];
        $venta_tmp->dolares = $_REQUEST['dolares'];
        $venta_tmp->monto_inicial = $_REQUEST['monto_inicial'];
        
        $this->model->Moneda($venta_tmp);
        
        header('Location: index.php?c=venta_tmp');
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        require_once "view/venta/tabla_venta.php";
        //header('Location: index.php?c=venta_tmp');
    }
}