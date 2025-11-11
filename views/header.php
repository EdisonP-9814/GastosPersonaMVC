<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gastos Personales</title>
    <link rel="stylesheet" href="<?=base_url?>assets/css/style.css">
    <link rel="shortcut icon" href="<?=base_url?>assets/images/logo.png">
</head>
<body>

    <header id='cabecera-principal'>
        <div class="logo">
            <img src="<?=base_url?>assets/images/logo.png" alt="Logo">
            <p>Gastos Personales</p>
        </div>
        
        <nav>
            <ul>
                <li><a href="<?=base_url?>">Inicio</a></li>

                <?php if(isset($_SESSION['identity'])): ?>
                    <li><a href="<?=base_url?>Cuenta/index">Cuentas</a></li>
                    <li><a href="<?=base_url?>Transaccion/index">Transacciones</a></li>
                    <li><a href="<?=base_url?>Reporte/index">Reportes</a></li>
                    <li><a href="<?=base_url?>Usuario/logout">Cerrar Sesión (<?= $_SESSION['identity']->nombre_usuario ?>)</a></li>
                
                <?php else: ?>
                    <li><a href="<?=base_url?>Usuario/registro">Regístrate</a></li>
                <?php endif; ?>

            </ul>
        </nav>
    </header>

    <?php 
        if (class_exists('Utils')) {
            Utils::showSessionMessage('msgsuccess', 'success');
            Utils::showSessionMessage('msgerror', 'error');
        }
    ?>
    <div id="main-container">

        <div class='Contptl'>