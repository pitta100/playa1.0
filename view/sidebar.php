<nav id="sidebar">
    <div class="sidebar-header" align="center">
        <img src="assets/img/unosin.png" width="210" align="center">
         <!--<img src="assets/img/25AÑOSVERDEFONGONEGRO.png" width="200" align="center">-->
        <h4>CASA MATRIZ</h4> 
    </div>

    <ul class="list-unstyled components">

        
        <?php if($_SESSION['nivel']==0){ ?>
        <li <?php if(isset($_GET['c']) && $_GET['c'] =='sucursal') echo "class='active'"; ?>>
            <a href="?c=sucursal">Sucursales</a>
        </li>
        <li <?php if($_GET['c']=='usuario') echo "class='active'"; ?>>
            <a href="?c=usuario"> <i class="fa-solid fa-user"></i> Usuarios! </a>
        </li>
        <?php } ?>
        
        <?php if($_SESSION['nivel']==1){ ?>
        <li <?php if (isset($_GET['c']) && $_GET['c'] == 'mesa') echo "class='active'"; ?>>
                        <a href="?c=admin&a=listar">Tablero Principal</a>
        </li>
         
        <li <?php if($_GET['c']=='usuario') echo "class='active'"; ?>>
            <a href="?c=usuario"><i class="fa-solid fa-user"></i> Usuarios </a>
        </li>
         <li <?php if(isset($_GET['c']) && $_GET['c']=='cliente') echo "class='active'"; ?>>
            <a href="?c=cliente"> <i class="fa-solid fa-restroom"></i> Agregar Clientes</a>
        </li> 
        
       
        <li>
            <a href="#productoSubmenu" data-toggle="collapse" aria-expanded="false"> <i class="fa-brands fa-buromobelexperte"></i>CARGAR VEHICULOS<i class="fa-solid fa-forward"></i></a>
            <ul class="collapse list-unstyled 
            <?php 
                if($_GET['c']=='producto' || 
                   $_GET['c']=='categoria'||
                   $_GET['c']=='devolucion'||
                   $_GET['c']=='devolucion_tmp'||
                   $_GET['c']=='inventario') 
                    echo " in"; 
                ?>
            " id="productoSubmenu">
                <li <?php if($_GET['c']=='producto') echo "class='active'"; ?>>
                    <a href="?c=producto">LISTAR VEHICULOS</a>
                </li>
                <li <?php if($_GET['c']=='categoria') echo "class='active'"; ?>>
                    <a href="?c=categoria">Categorías de vehiculos</a>
                </li>
                      <!--<li <?php //if($_GET['c']=='categoria') echo "class='active'"; ?>>
                    <a href="?c=devolucion">Ajustes de stock</a>
                </li>-->
                       <!--<li <?php //if($_GET['c']=='cierre_inventario') echo "class='active'"; ?>>
            <a href="?c=cierre_inventario&a=Cierreinventario">Inventario</a>
        </li>-->
            </ul>
        </li>
        <li>
            <a href="#cajaSubmenu" data-toggle="collapse" aria-expanded="false"> <i class="fa-solid fa-cash-register"></i> Control General <i class="fa-solid fa-forward"></i> </a>
            <ul class="collapse list-unstyled 
                <?php 
                    if($_GET['c']=='egreso' || 
                       $_GET['c']=='ingreso'||
                       $_GET['c']=='deuda'  ||
                       $_GET['c']=='acreedor') 
                        echo " in"; 
                ?>
                " id="cajaSubmenu">
                <li <?php if($_GET['c']=='deuda') echo "class='active'"; ?>>
                    <a href="?c=deuda">lISTA DE COMPROBACION</a>
                </li>
                <li <?php if($_GET['c']=='deuda') echo "class='active'"; ?>>
                    <a href="?c=deuda&a=calendar">Calendario</a>
                </li>
                 <li <?php if($_GET['c']=='ingreso' && !isset($_GET['a'])) echo "class='active'"; ?>>
                    <a href="?c=ingreso">Ingresos</a>
                </li>
                <li <?php if($_GET['c']=='egreso') echo "class='active'"; ?>>
                    <a href="?c=egreso">Egresos</a>
                </li>
                <li <?php if($_GET['c']=='acreedor') echo "class='active'"; ?>>
                    <a href="?c=acreedor">Acreedores</a>
                </li>
                      <!--<li <?php //if(isset($_GET['a']) && $_GET['a']=='balance') echo "class='active'"; ?>>
                    <a href="?c=ingreso&a=balance">Balance</a>
                </li>-->
               <!-- <li <?php //if(isset($_GET['a']) && $_GET['a']=='EstadoResultado') echo "class='active'"; ?>>
                    <a href="?c=venta&a=EstadoResultado">Estado de Resultado</a>
                </li>-->
            </ul>
        </li>
         <li>
            <a href="#compraSubmenu" data-toggle="collapse" aria-expanded="false"><i class="fa-solid fa-cash-register"></i> Compras</a>
            <ul class="collapse list-unstyled 
            <?php 
                if($_GET['c']=='compra' || 
                   $_GET['c']=='compra_tmp') 
                    echo " in"; 
            ?>
            " id="compraSubmenu">
                <li <?php if(!isset($_GET['a']) && $_GET['c']=='compra') echo "class='active'"; ?>>
                    <a href="?c=compra">Compras</a>
                </li>
                <!--<li class='active'><a href="#">Ventas no finalizadas</a></li>-->
                <li <?php if(isset($_GET['a']) && $_GET['a']=='listardia') echo "class='active'"; ?>>
                    <a href="?c=compra&a=listardia">Compras del día</a>
                </li>
                <li <?php if(isset($_GET['c']) && $_GET['c']=='compra_tmp') echo "class='active'"; ?>>
                    <a href="?c=compra_tmp">+ Nueva compra</a></li>
                </ul>
        </li>
        
        <li>
            <a href="#ventaSubmenu" data-toggle="collapse" aria-expanded="false"> <i class="fa-solid fa-sack-dollar"></i> VENTAS<i class="fa-solid fa-forward"></i></a>
            <ul class="collapse list-unstyled
            <?php 
                if($_GET['c']=='venta' || 
                   $_GET['c']=='venta_tmp')
                    echo " in"; 
            ?>
            " id="ventaSubmenu">
                <li <?php if(isset($_GET['c']) && $_GET['c']=='venta') echo "class='active'"; ?>>
                    <a href="?c=venta">Ventas</a>
                </li>
                <!--<li class='active'><a href="#">Ventas no finalizadas</a></li>-->
                <li <?php if(isset($_GET['a']) && $_GET['a']=='listardia') echo "class='active'"; ?>>
                    <a href="?c=venta&a=listardia">Ventas del día</a>
                </li>
                <li <?php if(isset($_GET['c']) && $_GET['c']=='venta_tmp' && !isset($_GET['a'])) echo "class='active'"; ?>>
                    <a href="?c=venta_tmp">+ Nueva venta</a>
                </li>
                 <!--<li <?php// if(isset($_GET['c']) && $_GET['a']=='mayorista') echo "class='active'"; ?>>
                    <a href="?c=venta_tmp&a=mayorista">+ Venta mayorista</a>
                </li>-->
            </ul>
        </li>
         <li>
            <a href="#cierreSubmenu" data-toggle="collapse" aria-expanded="false"> <i class="fa-solid fa-ban"></i> Cierres <i class="fa-solid fa-forward"></i></a>
            <ul class="collapse list-unstyled
            <?php 
                if($_GET['c']=='cierre') 
                    echo " in"; 
            ?>
            " id="cierreSubmenu">
                <li <?php if(isset($_GET['c']) && $_GET['c']=='cierre' && !isset($_GET['a'])) echo "class='active'"; ?>>
                    <a href="?c=cierre">Sesiones</a>
                </li>
                <li <?php if(isset($_GET['a']) && $_GET['a']=='activas') echo "class='active'"; ?>>
                    <a href="?c=cierre&a=activas">Sesiones activas</a>
                </li>
            </ul>
        </li>
        
        </li>-->
        <?php } ?>
        
        

       
                </ul>
        </li>   
        </li>-->
       
       
       
        
        <?php { ?>

       

        
        <?php } ?>
        <?php if($_SESSION['nivel']>1){ ?>
        
         <li <?php if($_GET['c']=='deuda') echo "class='active'"; ?>>
            <a href="?c=deuda">Deudores</a>
        </li>
        <li <?php if(isset($_GET['c']) && $_GET['c']=='venta_tmp') echo "class='active'"; ?>> 
            <a href="?c=venta_tmp" >
                <?php if ($cierre = $this->cierre->Consultar($_SESSION['user_id'])): ?>
                + Nueva venta (F2)
                <?php else: ?>
                Apertura de Caja
                <?php endif ?>
            </a>
        </li>
        <?php } ?>
        
    </ul>

   <ul class="list-unstyled CTAs">
        <li><a href="https://PITTA100.com" class="download">&copy;Pitta-Company, Sistemas Informatico, Electromedicina <?php echo date("Y") ?></a></li>
    </ul>
</nav>
