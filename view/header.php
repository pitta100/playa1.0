<?php
require_once 'model/dbconfig.php';
    session_start();
    if($_SESSION["validar"] != "true"){
    
        header("location: login.php");  
        exit();  
    } 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>P&Q</title>
        
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/admin/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/admin/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="assets/admin/js/jquery-ui/jquery-ui.min.css" />
        <link rel="stylesheet" href="assets/admin/css/style.css" />
        <link rel="stylesheet" href="assets/admin/css/sidebar.css" />
        <!--<link  rel="icon"   href="assets/img/francolor.ico" type="ico" />-->
        <link href="plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" rel="stylesheet" />
        <link href="plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" />
        <!--<script src="https://kit.fontawesome.com/e26ae38ed9.js" crossorigin="anonymous"></script>-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <script src="https://kit.fontawesome.com/b880ff62c8.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/estiloPitta/styles.css" />
        
    
        <script src="assets/admin/js/jquery-3.7.1.min.js"></script> 
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="https://cdn.datatables.net/responsive/1.0.2/js/dataTables.responsive.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js" type="text/javascript"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js" type="text/javascript"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            .page-header {
                margin: 0px 0 20px;
            }

        </style>
    </head>
    <body>
    <?php 
        echo (isset($_GET['success']))? "
            <script>
            Swal.fire(
                      '".$_GET['success']."!',
                      'El registro fue ".$_GET['success'].".',
                      'success'
                    );
            </script>
        ":"";
    ?>
        
    <div class="wrapper">
        <?php if($_SESSION['nivel']<3 ){include "sidebar.php";}?>
        <div style="width:100%;">
            <?php include "nav.php";?>
            <div id="content" style="background-color: white">