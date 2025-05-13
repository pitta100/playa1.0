<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" id="sidebarCollapse" class="navbar-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <!-- Enlace de usuario -->
                <li><a href='?c=usuario&a=password'><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SESSION['username'] ?></a></li>
                <!-- Enlace de compras -->
                <li><a href="?c=compra_tmp"><i class="fa-solid fa-cash-register"></i> Compras</a></li>
                <!-- Enlace de ventas -->
                <li><a href="?c=venta_tmp"><i class="fa-solid fa-sack-dollar"></i> Ventas</a></li>
                <!-- Enlace de cerrar sesión -->
                <li><a href="login.php">Cerrar sesión</a></li>
                <!-- Versión del sistema alineada a la derecha -->
                <li class="navbar-text" style="margin-left: 20px; font-weight: bold;">
                    <span class="label label-info">Versión 1.2 - 2025</span>
                </li>
            </ul>
        </div>

    </div>
</nav>
