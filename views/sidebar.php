<!-- Sidebar -->
<aside class='Contvtc Block_aside'>

<?php if(isset($_SESSION['identity'])): 
    // Usuario Logueado
    $usuario = $_SESSION['identity'];
  ?>
    <div class='Contvtc' style='width: 260px; padding: 20px;'>
        <h5>¡Bienvenido/a!</h5>
        <h4 style="text-align: center; margin-bottom: 20px;"><?= htmlspecialchars($usuario->nombre_usuario) ?></h4>
        
        <div class="Contvtl" style="width: 100%;">
            <a class="Hovsbr" href="<?=base_url?>Cuenta/index">
                <h5>Mis Cuentas</h5>
            </a>
            <a class="Hovsbr" href="<?=base_url?>Transaccion/crear"> <h5>Registrar Gasto</h5>
            </a>
            <a class="Hovsbr" href="<?=base_url?>Transaccion/crearIngreso"> <h5>Registrar Ingreso</h5>
            </a>
            <a class="Hovsbr" href="<?=base_url?>usuario/logout">
                <h5 style="color: #98173a;">Cerrar Sesión</h5>
            </a>
        </div>
    </div>

  <?php else: 
    // Usuario NO Logueado
  ?>
    <div class='Contvtc' style='width: 260px;'>
      <form class='Contvtc Frm260' action="<?=base_url?>usuario/login" method="POST">
        <div class='Conthcl Frm260'>
          <label for="cedula">Cédula</label>
          <input type="text" name='cedula' placeholder='Ingrese su cédula' required>
        </div>
        <div class='Conthcl Frm260'>
          <label for="clave">Contraseña</label>
          <input type="password" name='clave' placeholder='Ingrese su contraseña' required>
        </div>

        <input class="Bt4b" type="submit" value="Ingresar">
        <br><h7>¿No tienes cuenta?</h7>
        <a class="Hovsbr" href="<?=base_url?>usuario/registro">
          <h5>Regístrate</h5>
        </a>
      </form>
    </div>
  <?php endif; ?>

</aside>
<!-- End Sidebar -->
