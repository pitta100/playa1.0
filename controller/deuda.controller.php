<?php
require_once 'model/venta.php';
require_once 'model/venta_tmp.php';
require_once 'model/producto.php';
require_once 'model/ingreso.php';
require_once 'model/deuda.php';
require_once 'model/egreso.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';
require_once 'model/nota.php';

class deudaController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new deuda();
        $this->venta_tmp = new venta_tmp();
        $this->cierre = new cierre();
        $this->producto = new producto();
        $this->ingreso = new ingreso();
        $this->venta = new venta();
        $this->egreso = new egreso();
        $this->cliente = new cliente();
         $this->nota = new nota();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/deuda/deuda.php';
        require_once 'view/footer.php';
       
    }
    public function Listar(){
        require_once 'view/deuda/deuda.php';
    }
    public function formulario(){
        require_once 'view/deuda/nota.php';
    }


    
    public function Crud(){
        $deuda = new deuda();
        
        if(isset($_REQUEST['id'])){
            $deuda = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/deuda/deuda-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $deuda = new deuda();
        
        if(isset($_REQUEST['id'])){
            $deuda = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/deuda/deuda-editar.php';
        
    }

    public function clientepdf(){
        $deuda = new deuda();

        require_once 'view/informes/extractoclientepdf.php';
        
    }

    public function CobrarModal(){
        $deuda = new deuda();
        
        if(isset($_REQUEST['id'])){
            $r = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/deuda/cobrar-form.php';
        
    }
      public function calendar(){
        require_once 'view/header.php';
        require_once 'view/deuda/calendar.php';
        require_once 'view/footer.php';
       
    }
    
    public function Guardar(){
        $deuda = new deuda();

        session_start();
        
        $deuda->id = $_REQUEST['id'];
        $deuda->id_cliente = $_REQUEST['id_cliente'];
        $deuda->id_venta = $_REQUEST['id_venta'];
        $deuda->fecha = $_REQUEST['fecha'];
        $deuda->vencimiento = $_REQUEST['vencimiento'];
        $deuda->concepto = $_REQUEST['concepto'];
        $deuda->monto = $_REQUEST['monto'];
        $deuda->saldo = $_REQUEST['saldo'];  
        $deuda->sucursal = $_SESSION['sucursal'];
        $deuda->cuotas = $_REQUEST['cuotas'];    

        $deuda->id > 0 
            ? $this->model->Actualizar($deuda)
            : $this->model->Registrar($deuda);
        
        header('Location: index.php?c=deuda');
    }
    
    public function Cobrar() {
        session_start();
        $ingreso = new ingreso();

        $ingreso->id_cliente  = $_REQUEST['id_cliente'];

        if ($_REQUEST['forma_pago'] == "Efectivo") {
            $ingreso->id_caja = 3;
        } else {
            $ingreso->id_caja = 2;
        }

        $ingreso->id_venta      = $_REQUEST['id_venta'];
        $ingreso->id_deuda      = $_REQUEST['id'];
        $ingreso->forma_pago    = $_REQUEST['forma_pago'];
        $ingreso->fecha         = date("Y-m-d H:i");
        $ingreso->categoria     = 'Cobro de deuda';
        $ingreso->concepto      = "Cobro de deuda a ".$_REQUEST['cli'];
        $ingreso->comprobante   = $_REQUEST['comprobante'];
        $ingreso->sucursal      = $_SESSION['sucursal'];

        $deuda = new deuda();
        $deuda->id = $_REQUEST['id'];

        // 🔥 Verificamos si viene un refuerzo
        if (isset($_REQUEST['montoRefuerzo'])) {
            // Refuerzo
            $ingreso->monto         = $_REQUEST['montoRefuerzo'];
            $ingreso->cuotas        = $_REQUEST['cantidadRefuerzo'];
            $ingreso->vencimiento   = $_REQUEST['fecha_refuerzo'];

            $deuda->montoRefuerzo      = $_REQUEST['montoRefuerzo'];
            $deuda->cantidadRefuerzo   = $_REQUEST['cantidadRefuerzo'];
            $deuda->fecha_refuerzo     = $_REQUEST['fecha_refuerzo'];

        } else {
            // Cobro normal
            $ingreso->monto         = $_REQUEST['mon'];
            $ingreso->cuotas        = $_REQUEST['cuo'];
            $ingreso->vencimiento   = $_REQUEST['vencimi'];

            $deuda->monto        = $_REQUEST['mon'];
            $deuda->cuotas       = $_REQUEST['cuo'];
            $deuda->vencimiento  = $_REQUEST['vencimi'];
            $deuda->intereses    = $_REQUEST['inte'];
            $deuda->fecha_pago_cuota = date('Y-m-d H:i:s', strtotime($_REQUEST['fecha_pago']));

        }

        // Guardar el ingreso
        $this->ingreso->Registrar($ingreso);
        $ingresoID = $this->ingreso->UltimoID();

        // 🔥 Aplicamos la lógica correcta
        if (isset($_REQUEST['montoRefuerzo'])) {
            $this->model->disminuirRefuerzo($deuda);
            $this->model->disminuirCantidadRefuerzo($deuda); // <--- NUEVO
            $this->model->nuevaFechaRefuerzo($deuda);
        } else {
            $this->model->disminuir($deuda);
            $this->model->nuevafecha($deuda);
            $this->model->Restar($deuda);
            $this->model->Interes($deuda);
        }

        // Redirigir
        header('Location: index.php?c=ingreso&a=recibo&id=' . $ingresoID->id);
    }

    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=deuda');
    }

    public function listarDetalles(){
        $deuda = new deuda();
        
        if(isset($_REQUEST['id'])){
            $r = $this->model->listarDetallesModel($_REQUEST['id']);
        }
        
        require_once 'view/deuda/listados.php';
        
    }
     public function refuerzosModal(){
        $deuda = new deuda();
        
        if(isset($_REQUEST['id'])){
            $r = $this->model->listarDetallesModel($_REQUEST['id']);
        }
        
        require_once 'view/deuda/refuerzosFormulario.php';
        
    }
    public function listarDeudas(){
             $deuda = new deuda();

        if (isset($_GET['id'])) {
            $id_deuda = $_GET['id'];  // Obtenemos el valor del parámetro 'deuda'
            var_dump($id);
            
            // Llamamos al modelo para obtener las deudas asociadas a ese id
            $deudas = $this->model->listarDeudas($id);  // Pasamos el parámetro id_deuda al modelo
        } else {
            // Si no existe el parámetro 'deuda', podrías manejarlo con un error o una acción por defecto
            $deudas = [];  // Podrías devolver un array vacío si no se encuentra 'deuda' en la URL
        }

        // Incluimos la vista y pasamos las deudas obtenidas al archivo de vista
        include 'view/deuda/listado.php';  
    }
    public function listarDeudaCalendar() {
    try {
            $deuda = $this->model->listarDeudasCalendar();
            // Verifica si hay datos y luego devuelve la respuesta en formato JSON
            if ($deuda) {
                echo json_encode($deuda, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([]);
            }
        } catch (Exception $e) {
            // Si ocurre un error, devuelves un mensaje de error en JSON
            echo json_encode(["error" => "Hubo un problema al cargar los eventos"]);
        }
    }




}