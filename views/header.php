<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gastos Personales</title>
    <link rel="stylesheet" href="<?=base_url?>assets/css/style.css">
    <link rel="shortcut icon" href="<?=base_url?>assets/images/logo.png">
</head>
<body>
    <!-- Cabecera -->
    <header class='Conthcl' style='height: 60px; background: #73a0db;'>
        <img src="<?=base_url?>assets/images/logo.png" alt="Logo">
        <p>Gastos Personales</p>
    </header>

    <!-- Menu -->
     <nav id='menu' class='Conthcc' style='height: 40px; background: #d4dff3;'>
        <ul>
            <li><a href="<?=base_url?>">Inicio</a></li>
            <li><a href="<?=base_url?>Cuenta/index">Cuentas</a></li>
            <li><a href="<?=base_url?>usuario/registro">Usuarios</a></li>
            <li><a href="<?=base_url?>Transaccion/index">Transacciones</a></li>
            <li><a href="#">Reportes</a></li>

            <?php if(isset($_SESSION['identity'])): ?>
                <li><a href="<?=base_url?>usuario/logout">Cerrar Sesión (<?= $_SESSION['identity']->nombre_usuario ?>)</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class='Contptl'>  <div class='Contptl'>
