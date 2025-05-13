<?php
require_once 'model/egreso.php';
require_once 'model/acreedor.php';
require_once 'model/compra.php';
require_once 'model/compra_tmp.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';
require_once 'model/caja.php';

class egresoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new egreso();
        $this->acreedor = new acreedor();
        $this->compra = new compra();
        $this->cliente = new cliente();
        $this->compra_tmp = new compra_tmp();
        $this->cierre = new cierre();
        $this->caja = new caja();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/egreso/egreso.php';
        require_once 'view/footer.php';
       
    }

    public function Balance(){
        require_once 'view/header.php';
        require_once 'view/informes/balance.php';
        require_once 'view/footer.php';
       
    }

    public function Listar(){
        require_once 'view/egreso/egreso.php';
    }
    
    public function EgresoRango(){
        
        require_once 'view/informes/egresosrangopdf.php';
        
    }
     public function ListarAjaxEngreso(){
        $ingreso = $this->model->ListartodoEgresos(0);
        echo json_encode($ingreso, JSON_UNESCAPED_UNICODE);
    }

    public function Extraccion(){
        require_once 'view/header.php';
        if ($this->cierre->Consultar($_SESSION['user_id'])) {
            require_once 'view/egreso/extraccion.php';
        }else{      
            echo "<h1>Debe hacer apertura de caja</h1>";
        }
        require_once 'view/footer.php';
    }

    public function detalles(){
        require_once 'view/acreedor/pago_detalles.php';
    }
    
    public function Crud(){
        $egreso = new egreso();
        
        if(isset($_REQUEST['id'])){
            $egreso = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/egreso/egreso-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $egreso = new egreso();
        
        if(isset($_REQUEST['id'])){
            $egreso = $this->model->Obtener($_REQUEST['id']);
        }
        
        if (!isset($_SESSION['user_id'])) {
            session_start();
        }

        if ($_SESSION['nivel']>1){
            require_once 'view/egreso/extraccion-editar.php';
        }else{
            require_once 'view/egreso/egreso-editar.php';
        }
        
    }

    
    public function Guardar(){
        $egreso = new egreso();
        
        $egreso->id = $_REQUEST['id'];
        $egreso->id_cliente = $_REQUEST['id_cliente'];
        session_start();
        $cierre = $this->cierre->Consultar($_SESSION['user_id']);
        if($_REQUEST['forma_pago']=="Efectivo"){
            $egreso->id_caja = 1;
        }else{
            $egreso->id_caja = 2;
        }
        $egreso->id_venta = (isset($_REQUEST['id_venta']))? $_REQUEST['id_venta']:0;
        $egreso->fecha = $_REQUEST['fecha'];
        $egreso->categoria = $_REQUEST['categoria'];
        $egreso->concepto = $_REQUEST['concepto'];
        $egreso->comprobante = $_REQUEST['comprobante'];
        $egreso->monto = $_REQUEST['monto'];
        $egreso->forma_pago = $_REQUEST['forma_pago'];   
        $egreso->sucursal = $_REQUEST['sucursal']; 
        $egreso->nro_cheque = $_REQUEST['nro_cheque']; 
        $egreso->plazo = $_REQUEST['plazo'];
      

        $egreso->id > 0 
            ? $this->model->Actualizar($egreso)
            : $this->model->Registrar($egreso);

        if (!isset($_SESSION['user_id'])) {
            session_start();
        }    

        if ($_SESSION['nivel']>1){
            header('Location: index.php?c=egreso&a=extraccion');
        }else{
            header('Location: index.php?c=egreso');
        }
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=egreso');
    }

    public function Anular(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=egreso&a=extraccion');
    }

    public function EliminarPago(){

        $egreso = new egreso();
        $egreso = $this->model->Obtener($_REQUEST['id']);

        $acreedor = new acreedor();
        $acreedor->id=$egreso->id_acreedor;
        $acreedor->monto=$egreso->monto;

        $this->model->Eliminar($_REQUEST['id']);
        $this->acreedor->SumarSaldo($acreedor);
        header('Location: index.php?c=egreso');
    }

}