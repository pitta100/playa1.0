<?php
require_once 'model/cliente.php';
require_once 'model/cierre.php';
require_once 'model/cuenta.php';


class clienteController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new cliente();
        $this->cuenta = new cuenta();
        $this->cierre = new cierre();
        $this->cliente = new cliente();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/cliente/cliente.php';
        require_once 'view/footer.php';
       
    }

    public function Cumple(){
        require_once 'view/header.php';
        require_once 'view/cliente/cliente-cumple.php';
        require_once 'view/footer.php';
       
    }


    public function Listar(){
        require_once 'view/cliente/cliente.php';
    }
    public function resumenCliente(){
        require_once 'view/informes/informeClientepdf.php';
    }

    public function Crud(){
        $cliente = new cliente();
        
        if(isset($_REQUEST['id'])){
            $cliente = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/cliente/cliente-editar.php';
        require_once 'view/footer.php';
    }

     public function detalles(){
        $cliente = new cliente();
        
        if(isset($_REQUEST['id'])){
            $cliente = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/cliente/cliente-detalle.php';
        require_once 'view/footer.php';
    }

    public function ListarAJAX(){

        $cliente = new cliente();
        
        $cliente = $this->model->ListarAJAX();
        echo json_encode($cliente, JSON_UNESCAPED_UNICODE);

    }
    
    public function Obtener(){
        $cliente = new cliente();
        
        if(isset($_REQUEST['id'])){
            $cliente = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/cliente/cliente-editar.php';
        
    }

    public function Buscar(){
        $cliente = new cliente();
        
        if(isset($_REQUEST['id'])){
            $cliente = $this->model->Obtener($_REQUEST['id']);
        }
        
        echo json_encode($cliente);
        
    }
    public function Guardar()
{
    $cliente = new cliente();

    // Subida de imagen de perfil
    $cliente->foto_perfil = $_FILES['foto_perfil']['name'] ?? '';
    if (!empty($cliente->foto_perfil)) {
        $tipo = $_FILES['foto_perfil']['type'];
        $tamano = $_FILES['foto_perfil']['size'];
        $temp = $_FILES['foto_perfil']['tmp_name'];

        if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
            echo '<div><b>Error. Solo se permiten archivos .gif, .jpg, .png menores a 2MB.</b></div>';
        } else {
            if (!move_uploaded_file($temp, 'assets/img/' . $cliente->foto_perfil)) {
                echo '<div><b>Error al subir la imagen de perfil.</b></div>';
            } else {
                chmod('assets/img/' . $cliente->foto_perfil, 0777);
            }
        }
    }

    // Datos básicos
    $cliente->id             = $_REQUEST['id'] ?? '';
    $cliente->ruc            = $_REQUEST['ruc'] ?? '';
    $cliente->nombre         = $_REQUEST['nombre'] ?? '';
    $cliente->nick           = $_REQUEST['nick'] ?? '';
    $cliente->correo         = $_REQUEST['correo'] ?? '';
    $cliente->pass           = $_REQUEST['pass'] ?? '';
    $cliente->telefono       = $_REQUEST['telefono'] ?? '';
    $cliente->cumple         = $_REQUEST['cumple'] ?? '';
    $cliente->direccion      = $_REQUEST['direccion'] ?? '';
    $cliente->fecha_registro = date("Y-m-d");
    $cliente->sucursal       = $_REQUEST['sucursal'] ?? '';
    $cliente->puntos         = $_REQUEST['puntos'] ?? '';
    $cliente->gastado        = $_REQUEST['cambiar'] ?? ($_REQUEST['gastado'] ?? '');
    $cliente->mayorista      = $_REQUEST['mayorista'] ?? '';
    $cliente->cliente        = $_REQUEST['cliente'] ?? '';
    $cliente->proveedor      = $_REQUEST['proveedor'] ?? '';
    $cliente->adressWork     = $_REQUEST['adressWork'] ?? '';
    $cliente->residencia_url = $_REQUEST['residencia_url'] ?? '';
    $cliente->phoneWork      = $_REQUEST['phoneWork'] ?? '';

    // Archivos PDF
    $documentos = [
        'comprobanteIngreso',
        'cedulaTributaria',
        'facturasLegalesEmitidas',
        'cedulaIdentidad',
        'estructuraJuridica',
        'beneficiarioFinal',
        'varios'
    ];

    foreach ($documentos as $doc) {
        $actual = $_REQUEST[$doc . '_actual'] ?? '';

        if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === UPLOAD_ERR_OK) {
            $nombre   = uniqid() . '_' . basename($_FILES[$doc]['name']);
            $destino  = 'assets/documentos/clientes/' . $nombre;

            if (!file_exists('assets/documentos/clientes/')) {
                mkdir('assets/documentos/clientes/', 0777, true);
            }

            if (move_uploaded_file($_FILES[$doc]['tmp_name'], $destino)) {
                $cliente->$doc = $nombre;
            } else {
                $cliente->$doc = $actual; // Fallback si falla la subida
            }
        } else {
            $cliente->$doc = $actual;
        }
    }

    // Guardar en BD
    if ($cliente->id > 0) {
        $this->model->Actualizar($cliente);
    } else {
        $this->model->Registrar($cliente);
    }

    // Redirección
    header('Location:' . $_SERVER['HTTP_REFERER']);
}



    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?success=Eliminado&c='.$_REQUEST['c']);
    }
}