<?php
require_once 'model/compra.php';
require_once 'model/compra_tmp.php';
require_once 'model/producto.php';
require_once 'model/egreso.php';
require_once 'model/acreedor.php';
require_once 'model/egreso.php';
require_once 'model/cliente.php';
require_once 'model/cierre.php';

class acreedorController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new acreedor();
        $this->compra_tmp = new compra_tmp();
        $this->producto = new producto();
        $this->cierre = new cierre();
        $this->egreso = new egreso();
        $this->compra = new compra();
        $this->egreso = new egreso();
        $this->cliente = new cliente();
        $this->cierre = new cierre();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/acreedor/acreedor.php';
        require_once 'view/footer.php';
       
    }



    public function Listar(){
        require_once 'view/acreedor/acreedor.php';
    }


    
    public function Crud(){
        $acreedor = new acreedor();
        
        if(isset($_REQUEST['id'])){
            $acreedor = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/acreedor/acreedor-editar.php';
        require_once 'view/footer.php';
    }
    
    public function Obtener(){
        $acreedor = new acreedor();
        
        if(isset($_REQUEST['id'])){
            $acreedor = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/acreedor/acreedor-editar.php';
        
    }

    public function clientepdf(){
        $acreedor = new acreedor();

        require_once 'view/informes/extractoclientepdf.php';
        
    }

    public function PagarModal(){
        $acreedor = new acreedor();
        
        if(isset($_REQUEST['id'])){
            $r = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/acreedor/pagar-form.php';
        
    }
    
    public function Guardar(){
        $acreedor = new acreedor();

        session_start();
        
        $acreedor->id = $_REQUEST['id'];
        $acreedor->id_cliente = $_REQUEST['id_cliente'];
        $acreedor->id_compra = $_REQUEST['id_compra'];
        $acreedor->fecha = $_REQUEST['fecha'];
        $acreedor->concepto = $_REQUEST['concepto'];
        $acreedor->monto = $_REQUEST['monto'];
        $acreedor->saldo = $_REQUEST['saldo'];  
        $acreedor->sucursal = $_SESSION['sucursal'];      

        $acreedor->id > 0 
            ? $this->model->Actualizar($acreedor)
            : $this->model->Registrar($acreedor);
        
        header('Location: index.php?c=acreedor');
    }
    
     public function Pagar(){
        
        session_start();
        $egreso = new egreso();
        
        $egreso->id_cliente  = $_REQUEST['id_cliente'];
        $cierre = $this->cierre->Consultar($_SESSION['user_id']);
        if($_REQUEST['forma_pago']=="Efectivo"){
            $egreso->id_caja = 1;
        }else{
            $egreso->id_caja = 2;
        }
        
        $egreso->id_compra  = $_REQUEST['id_compra'];
        $egreso->id_acreedor  = $_REQUEST['id'];
        $egreso->forma_pago  = $_REQUEST['forma_pago'];
        $egreso->fecha = date("Y-m-d H:i");
        $egreso->categoria = 'Pago';
        $egreso->concepto = "Pago a proveedor ".$_REQUEST['cli'];
        $egreso->comprobante  = $_REQUEST['comprobante'];
        $egreso->monto  = $_REQUEST['mon'];
        $egreso->sucursal = $_SESSION['sucursal'];


        $acreedor = new acreedor();

        $acreedor->id = $_REQUEST['id'];
        $acreedor->monto = $_REQUEST['mon'];
        
        $this->egreso->Registrar($egreso);
        $this->model->Restar($acreedor);
        header('Location: index.php?c=acreedor');
        
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=acreedor');
    }
}