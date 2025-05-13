<?php
require_once 'model/venta.php';
require_once 'model/usuario.php';
require_once 'model/compra.php';
require_once 'model/venta_tmp.php';
require_once 'model/producto.php';
require_once 'model/ingreso.php';
require_once 'model/deuda.php';
require_once 'model/acreedor.php';
require_once 'model/egreso.php';
require_once 'model/cierre.php';
require_once 'model/caja.php';
require_once 'model/cliente.php';
require_once 'model/pago_tmp.php';
require_once 'model/gift_card.php';
require_once 'model/metodo.php';


class ventaController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new venta();
        $this->venta = new venta();
        $this->usuario = new usuario();
        $this->compra = new compra();
        $this->venta_tmp = new venta_tmp();
        $this->producto = new producto();
        $this->ingreso = new ingreso();
        $this->deuda = new deuda();
        $this->acreedor = new acreedor();
        $this->egreso = new egreso();
        $this->cierre = new cierre();
        $this->caja = new caja();
        $this->cliente = new cliente();
        $this->pago_tmp = new pago_tmp();
        $this->gift_card = new gift_card();
        $this->metodo = new metodo();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/venta/venta.php';
        require_once 'view/footer.php';
       
    }

    public function Sesion(){
        require_once 'view/header.php';
        if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])) {          
            require_once 'view/venta/venta-sesion.php';
        }else{
            echo "<h1>Debe hacer apertura de caja</h1>";
        }
        require_once 'view/footer.php';
    }

    public function NuevaVenta(){
        require_once 'view/header.php';
        require_once 'view/venta/nueva-venta.php';
        require_once 'view/footer.php';
       
    }


    public function Listar(){
        require_once 'view/venta/venta.php';
    }
    
    public function EstadoResultado(){
        require_once 'view/header.php';
        require_once 'view/informes/estado_resultado.php';
        require_once 'view/footer.php';
       
    }
    
    public function ListarCliente(){
        require_once 'view/header.php';
        require_once 'view/venta/ventacliente.php';
        require_once 'view/footer.php';
    }
    
    public function ListarUsuario(){
        require_once 'view/header.php';
        require_once 'view/venta/ventausuario.php';
        require_once 'view/footer.php';
    }
    
    public function ListarProducto(){
        require_once 'view/header.php';
        require_once 'view/venta/ventaproducto.php';
        require_once 'view/footer.php';
    }
    
    public function ListarProductoCat(){
        require_once 'view/header.php';
        require_once 'view/venta/ventaproductocat.php';
        require_once 'view/footer.php';
    }

    public function detalles(){
        require_once 'view/venta/venta_detalles.php';
    }
    
    
    public function ListarDia(){
        
        require_once 'view/header.php';
        require_once 'view/venta/ventadia.php';
        require_once 'view/footer.php';
    }
    
     public function ListarAjax(){

        $venta = $this->model->Listar(0);
        echo json_encode($venta, JSON_UNESCAPED_UNICODE);
    }


     public function ListarAjaxventa(){

        $desde = ($_REQUEST["desde"]);
        $hasta = ($_REQUEST["hasta"]);


        $venta = $this->model->Listarventa01($desde,$hasta);
        echo json_encode($venta, JSON_UNESCAPED_UNICODE);
    }



    public function ListarFiltros(){
        
        $desde = ($_REQUEST["desde"]);
        $hasta = ($_REQUEST["hasta"]);
        
        $venta = $this->model->ListarFiltros($desde,$hasta);
        echo json_encode($venta, JSON_UNESCAPED_UNICODE);
    }
    

    public function Cambiar(){

        $venta = new venta();
        
        $id_item = $_REQUEST['id_item'];
        $id_venta = $_REQUEST['id_venta'];
        $cantidad = $_REQUEST['cantidad'];
        $codigo = $_REQUEST['codigo'];
        $cantidad_ant = $_REQUEST['cantidad_ant'];
        
        $cant = $cantidad_ant - $cantidad;

        if($cantidad>0){
            $venta = $this->model->Cantidad($id_item, $id_venta, $cantidad);
            
            if($venta->contado=='Cuota')
                $deuda = $this->deuda->EditarMonto($id_venta, $venta->total_venta);

            if($venta->contado=='Contado')
                $deuda = $this->ingreso->EditarMonto($id_venta, $venta->total_venta);

            
            $this->producto->Sumar($codigo, $cant);

        }
        
        echo json_encode($venta);
    }

    public function Cancelar(){
        
        $id_item = $_REQUEST['id_item'];
        $id_venta = $_REQUEST['id_venta'];
        $codigo = $_REQUEST['codigo'];
        $cantidad = $_REQUEST['cantidad_item'];


        $venta = $this->model->Cantidad($id_item, $id_venta, 0);
            
        if($venta->contado=='Cuota')
            $deuda = $this->deuda->EditarMonto($id_venta, $venta->total_venta);

        if($venta->contado=='Contado')
            $deuda = $this->ingreso->EditarMonto($id_venta, $venta->total_venta);

        $venta = $this->model->CancelarItem($id_item);
        $this->producto->Sumar($codigo, $cantidad);
        header('location: ?c=venta_tmp&a=editar&id='.$id_venta);
    }


    
    public function Crud(){
        $venta = new venta();
        
        if(isset($_REQUEST['id'])){
            $venta = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/header.php';
        require_once 'view/venta/venta-editar.php';
        require_once 'view/footer.php';
    }

    public function Cierre(){
        
        require_once 'view/informes/cierrepdf.php';
        
    }
    
    public function InformeDiario(){
        
        require_once 'view/informes/ventadiapdf.php';
        
    }
    
    public function InformeRango(){
        
        require_once 'view/informes/ventarangopdf.php';
        
    }
    
    public function InformeUsados(){
        
        require_once 'view/informes/productosusadospdf.php';
        
    }
    
    public function CierreMes(){
       
        require_once 'view/informes/cierremesnewpdf.php';
        
    }
        
    public function Factura(){
        
        require_once 'view/informes/facturapdf.php';
        
    }
    public function specificInformation(){
        
        require_once 'view/informes/specificInformationPdf.php';
        
    }
    
    public function Ticket(){
        
        require_once 'view/informes/ticketpdf.php';
        
    }
    
    public function Obtener(){
        $venta = new venta();
        
        if(isset($_REQUEST['id'])){
            $venta = $this->model->Obtener($_REQUEST['id']);
        }
        
        require_once 'view/venta/venta-editar.php';
        
    }

    public function ObtenerProducto(){
        $venta = new venta();
        
        $venta = $this->model->ObtenerProducto($_REQUEST['id_venta'], $_REQUEST['id_producto']);
        
        echo json_encode($venta);
        
    }

    public function GuardarUno(){
         

        $venta = new venta();

        $costo = $_REQUEST['precio_costo']*$_REQUEST['cantidad'];
        $p_venta = $_REQUEST['precio_venta']*$_REQUEST['cantidad'];
        
        $venta->id = 0;
        $venta->id_venta = $_REQUEST['id_venta'];
        $venta->id_cliente = $_REQUEST['id_cliente'];
        $venta->id_vendedor = $_REQUEST['id_venta'];
        $venta->id_producto = $_REQUEST['id_producto'];
        $venta->id_res = 0;
        $venta->precio_costo = $_REQUEST['precio_costo'];
        $venta->precio_venta = $_REQUEST['precio_venta'];
        $venta->subtotal = $p_venta;
        $venta->descuento = 0;
        $venta->iva = 0;
        $venta->total = $p_venta;
        $venta->comprobante = $_REQUEST['comprobante'];
        $venta->nro_comprobante = $_REQUEST['nro_comprobante'];
        $venta->cantidad = $_REQUEST['cantidad'];
        $venta->margen_ganancia = round(((($p_venta - $costo)*100)/$costo),2);
        $venta->fecha_venta = $_REQUEST['fecha_venta'];
        $venta->metodo = $_REQUEST['metodo'];
        $venta->banco = $_REQUEST['banco'];
        $venta->contado = $_REQUEST['contado'];


        $this->producto->Restar($venta);
        

        $venta->id > 0 
            ? $this->model->Actualizar($venta)
            : $this->model->Registrar($venta);



        header('Location: index.php?c=venta_tmp&a=editar&id='.$venta->id_venta);
    }
    public function Guardar()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $ven = $this->model->Ultimo();
        $nuevo_id_venta = $ven->id_venta + 1;
        $sumaTotal = 0;

        foreach ($this->venta_tmp->Listar() as $v) {
            $venta = new venta();

            $venta->id = 0;
            $venta->id_venta = $nuevo_id_venta;
            $venta->id_cliente = $_REQUEST['id_cliente'] ?? null;
            $venta->id_vendedor = $v->id_vendedor;
            $venta->vendedor_salon = 0;
            $venta->id_producto = $v->id_producto;
            $venta->precio_costo = $v->precio_costo;
            $venta->precio_venta = $v->precio_venta;
            $venta->cantidad = $v->cantidad;
            $venta->subtotal = $venta->precio_venta * $venta->cantidad;
            $venta->descuento = $v->descuento;
            $venta->iva = $_REQUEST['ivaval'] ?? 0;
            $venta->total = $venta->subtotal - ($venta->subtotal * ($venta->descuento / 100));
            $venta->margen_ganancia = (($venta->total - $venta->precio_costo) / $venta->precio_costo) * 100;
            $venta->comprobante = $_REQUEST['comprobante'] ?? '';
            $venta->nro_comprobante = $_REQUEST['nro_comprobante'] ?? '';
            $venta->fecha_venta = $_REQUEST['fecha_venta'] ?? date("Y-m-d H:i");
            $venta->metodo = $_REQUEST['forma_pago'] ?? '';
            $venta->contado = $_REQUEST['contado'] ?? '';
            $venta->banco = $_REQUEST['banco'] ?? '';
            $venta->fecha = $venta->fecha_venta;
            $venta->categoria = "Venta";

            // Producto para el concepto
            $producto = $this->producto->Obtener($v->id_producto);
            $venta->concepto = "{$v->cantidad} Kg - {$producto->producto}";
            $venta->monto = $venta->total;

            $venta->fecha_emision = $venta->fecha_venta;
            $venta->fecha_vencimiento = date("Y-m-d", strtotime("+30 days")); // Automático 30 días

            // ID Gift opcional
            if (!empty($_REQUEST['id_gift'])) {
                $venta->id_gift = $_REQUEST['id_gift'];
            }

            // Guardar venta
            $this->model->Registrar($venta);

            // Si es Gift Card, actualizar su estado
            if (!empty($venta->id_gift)) {
                $this->gift_card->Retirado($venta->id_gift);
            }

            // Restar stock
            $this->producto->Restar($venta);

            // Sumar al total de la venta
            $sumaTotal += $venta->total;
        }

        // Si la venta es a crédito
        if ($venta->contado == 'Credito') {
            $deuda = new deuda();
            $deuda->id_cliente = $venta->id_cliente;
            $deuda->id_venta = $venta->id_venta;
            $deuda->fecha = date("Y-m-d H:i");
            //$deuda->vencimiento = $_REQUEST['vencimiento'] ?? date("Y-m-d", strtotime("+30 days"));
            $deuda->vencimiento = $_REQUEST['vencimiento'] ?? date("Y-m-d");  // Sin los 30 días
            $deuda->concepto = 'Venta a crédito';
            $deuda->monto = $sumaTotal;
            $deuda->saldo = $sumaTotal - ($_REQUEST['entrega'] ?? 0);
            $deuda->sucursal = $_SESSION['sucursal'] ?? 0;
            
            $deuda->tipo_entrega = $_REQUEST['tipo_entrega'] ?? 0;
            $deuda->entrega_inicial = $_REQUEST['entrega_inicial'] ?? 0;
            $deuda->entregas_restantes = $_REQUEST['entregas_restantes'] ?? 0;
            $deuda->monto_estimado = $_REQUEST['monto_estimado'] ?? 0;
            $deuda->venci_entrega_restante = $_REQUEST['venci_entrega_restante'] ?? 0;
            $deuda->totalEntrega = $_REQUEST['entrega'] ?? 0;
            $deuda->frecuencia_pagos = $_REQUEST['frecuencia_pagos'] ?? 0;


            $deuda->cuotas = $_REQUEST['cuo'] ?? 0;
            $deuda->montoRefuerzo = $_REQUEST['montoRefuerzo'] ?? 0;
            $deuda->cantidadRefuerzo = $_REQUEST['cantidadRefuerzo'] ?? 0;
            $deuda->fecha_refuerzo = $_REQUEST['fecha_refuerzo'] ?? null;
            $deuda->fecha_pago_cuota = $_REQUEST['vencimiento']; // Iniciamos con el vencimiento para la


            $this->deuda->Registrar($deuda);

            // Si hay una entrega inicial
            if (!empty($_REQUEST['entrega']) && $_REQUEST['entrega'] > 0) {
                $de = $this->deuda->Ultimo();
                $cli = $this->cliente->Obtener($venta->id_cliente);

                $ingreso = new ingreso();
                $ingreso->id_cliente = $venta->id_cliente;
                $ingreso->id_venta = $venta->id_venta;
                $ingreso->id_deuda = $de->id;
                $ingreso->fecha = date("Y-m-d H:i");
                $ingreso->categoria = 'Entrega';
                $ingreso->concepto = 'Venta a crédito a ' . $cli->nombre;
                $ingreso->comprobante = $venta->comprobante;
                $ingreso->monto = $_REQUEST['entrega'];
                $ingreso->forma_pago = $venta->metodo;
                $ingreso->sucursal = 0;

                // Caja según forma de pago
                $ingreso->id_caja = ($venta->metodo == "Efectivo") ? 3 : 2;

                $this->ingreso->Registrar($ingreso);
            }
        } else {
            // Venta al contado
            foreach ($this->pago_tmp->Listar() as $r) {
                $ingreso = new ingreso();

                $ingreso->id_cliente = $venta->id_cliente;
                $ingreso->id_venta = $nuevo_id_venta;
                $ingreso->fecha = date("Y-m-d H:i");
                $ingreso->categoria = 'Venta';
                $ingreso->concepto = 'Venta al contado';
                $ingreso->comprobante = ($_REQUEST['comprobante'] ?? '') . ' N° ' . ($_REQUEST['nro_comprobante'] ?? '');
                $ingreso->forma_pago = $r->pago;
                $ingreso->monto = $r->monto;
                $ingreso->saldo = $r->monto;
                $ingreso->sucursal = 0;
                $ingreso->id_caja = ($venta->metodo == "Efectivo") ? 3 : 2;

                $this->ingreso->Registrar($ingreso);
            }
        }

        // Vaciar tablas temporales
        $this->venta_tmp->Vaciar();
        $this->pago_tmp->Vaciar();

        // Redirección según comprobante
        if ($_REQUEST['comprobante'] == "Ticket") {
            header("Location: index.php?c=venta&a=ticket&id={$nuevo_id_venta}");
        } elseif ($_REQUEST['comprobante'] == "Factura") {
            header("Location: index.php?c=venta&a=factura&id={$nuevo_id_venta}");
        } else {
            header("Location: index.php?c=venta_tmp");
        }
    }
      
    
  
    public function Eliminar(){
        
        foreach($this->model->Listar($_REQUEST['id']) as $v){
            $venta = new venta();
            $venta->id_producto = $v->codigo;
            $venta->cantidad = $v->cantidad;
            $this->producto->Sumar($venta);
        }
        $this->ingreso->EliminarVenta($_REQUEST['id']);
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php?c=venta');
        
    }

    public function Anular(){
        
        foreach($this->model->Listar($_REQUEST['id']) as $v){
            $venta = new venta();
            $venta->id_producto = $v->codigo;
            $venta->cantidad = $v->cantidad;
            $this->producto->Sumar($venta);
        }
        
        $this->ingreso->AnularVenta($_REQUEST['id']);
        $this->deuda->AnularVenta($_REQUEST['id']);
        $this->model->Anular($_REQUEST['id']);
        header('Location: index.php?c=venta');
    }
}