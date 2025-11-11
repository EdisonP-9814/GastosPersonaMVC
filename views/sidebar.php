<!-- Sidebar -->
<aside class='Contvtc Block_aside'>

<?php if(isset($_SESSION['identity'])): 
    // Usuario Logueado
    $usuario = $_SESSION['identity'];
  ?>
    <div class='Contvtc Frm260'> <h5 style="text-align: center;">¡Bienvenido/a!</h5>
        <h4 style="text-align: center; margin-bottom: 20px;"><?= htmlspecialchars($usuario->nombre_usuario) ?></h4>
        
        <nav class="sidebar-menu">
            <a href="<?=base_url?>Cuenta/index">Mis Cuentas</a>
            <a href="<?=base_url?>Transaccion/crear">Registrar Gasto</a>
            <a href="<?=base_url?>Transaccion/crearIngreso">Registrar Ingreso</a>
            <a href="<?=base_url?>Usuario/logout" class="logout">Cerrar Sesión</a>
        </nav>
    </div>

  <?php else: 
    // Usuario NO Logueado
  ?>
    <div class='Contvtc Frm260'>
      <form class='Contvtc' action="<?=base_url?>Usuario/login" method="POST" style="width: 100%;">
        
        <label for="cedula" style="width: 100%; text-align: left; margin-bottom: 5px;">Cédula</label>
        <input type="text" name='cedula' placeholder='Ingrese su cédula' required style="width: 100%;">

        <label for="clave" style="width: 100%; text-align: left; margin-bottom: 5px; margin-top: 10px;">Contraseña</label>
        <input type="password" name='clave' placeholder='Ingrese su contraseña' required style="width: 100%;">

        <input class="Bt4b" type="submit" value="Ingresar" style="width: 100%; margin-top: 15px;">
        
        <h7 style="margin-top: 15px;">¿No tienes cuenta?</h7>
        <a class="Hovsbr" href="<?=base_url?>Usuario/registro" style="text-align: center; margin-top: 5px;">
          <h5>Regístrate</h5>
        </a>
      </form>
    </div>
  <?php endif; ?>

</aside>
<!-- End Sidebar -->
