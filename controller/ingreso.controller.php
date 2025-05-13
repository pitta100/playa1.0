<?php
require_once 'model/ingreso.php';
require_once 'model/egreso.php';
require_once 'model/deuda.php';
require_once 'model/venta.php';
require_once 'model/venta_tmp.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';

class ingresoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new ingreso();
        $this->egreso = new egreso();
        $this->deuda = new deuda();
        $this->venta = new venta();
        $this->cliente = new cliente();
        $this->venta_tmp = new venta_tmp();
        $this->cierre = new cierre();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/ingreso/ingreso.php';
        require_once 'view/footer.php';
       
    }

    public function Balance(){
        require_once 'view/header.php';
        require_once 'view/informes/balance.php';
        require_once 'view/footer.php';
       
    }

    public function Listar(){
        require_once 'view/ingreso/ingreso.php';
    }

    public function Deposito(){

        require_once 'view/header.php';
        if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])) {     
            require_once 'view/ingreso/deposito.php';
        }else{
            echo "<h1>Debe hacer apertura de caja</h1>";
        }
        require_once 'view/footer.php';
    }


    public function detalles(){
        require_once 'view/deuda/pago_detalles.php';
    }
    
    public function BalanceMes(){
        require_once 'view/informes/balancemespdf.php';
    }
    
    public function Recibo(){
        require_once 'view/informes/recibopdf.php';
    }

    public function ListarAjaxingreso(){
        $ingreso = $this->model->Listartodoingresos(0);
        echo json_encode($ingreso, JSON_UNESCAPED_UNICODE);
    }

    
    public function Crud(){
        $ingreso = new ingreso();
        
        if(isset($_REQUEST['id'])){
            $ingreso = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/ingreso/ingreso-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $ingreso = new ingreso();
        
        if(isset($_REQUEST['id'])){
            $ingreso = $this->model->Obtener($_REQUEST['id']);
        }
        if (!isset($_SESSION['user_id'])) {
            session_start();
        }

        if ($_SESSION['nivel']>1){
            require_once 'view/ingreso/deposito-editar.php';
        }else{
            require_once 'view/ingreso/ingreso-editar.php';
        }
        
        
    }

    
    public function Guardar(){
        $ingreso = new ingreso();
        
        $ingreso->id = $_REQUEST['id'];
        $ingreso->id_cliente = $_REQUEST['id_cliente'];
        session_start();
        $cierre = $this->cierre->Consultar($_SESSION['user_id']);
        
        if($_REQUEST['forma_pago']=="Efectivo"){
            $ingreso->id_caja = 3;
        }else{
            $ingreso->id_caja = 2;
        }
        
        $ingreso->id_venta = (isset($_REQUEST['id_venta']))? $_REQUEST['id_venta']:0;
        $ingreso->fecha = $_REQUEST['fecha'];
        $ingreso->categoria = $_REQUEST['categoria'];
        $ingreso->concepto = $_REQUEST['concepto'];
        $ingreso->comprobante = $_REQUEST['comprobante'];
        $ingreso->monto = $_REQUEST['monto'];
        $ingreso->forma_pago = $_REQUEST['forma_pago'];   
        $ingreso->sucursal = $_REQUEST['sucursal'];   
      

        $ingreso->id > 0 
            ? $this->model->Actualizar($ingreso)
            : $this->model->Registrar($ingreso);
        
        if ($_SESSION['nivel']>1){
            header('Location: index.php?c=ingreso&a=deposito');
        }else{
            header('Location: index.php?c=ingreso');
        }
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=ingreso');
    }

    public function Anular(){
        $this->model->Eliminar($_REQUEST['id']);
        $ingreso = new ingreso();
        $ingreso = $this->model->Obtener($_REQUEST['id']);

        $deuda = new deuda();
        $deuda->id=$ingreso->id_deuda;
        $deuda->monto=$ingreso->monto;

        $this->model->Eliminar($_REQUEST['id']);
        $this->deuda->SumarSaldo($deuda);
        header('Location: index.php?c=ingreso&a=deposito');
    }

    public function EliminarPago(){

        $ingreso = new ingreso();
        $ingreso = $this->model->Obtener($_REQUEST['id']);

        $deuda = new deuda();
        $deuda->id=$ingreso->id_deuda;
        $deuda->monto=$ingreso->monto;

        $this->model->Eliminar($_REQUEST['id']);
        $this->deuda->SumarSaldo($deuda);
        header('Location: index.php?c=ingreso');
    }

}