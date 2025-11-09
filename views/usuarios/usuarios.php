<!-- Panel Derecho -->
<div class='Contvcc Frm1' style='margin-top: 20px;'>
  <!-- Formulario de Registro -->
  <form action="<?=base_url?>Usuario/save" class='Contvcc Frm2 Brd15' method="post">
    <h4>Regístrate</h4>
    <input type="text" name="id" placeholder="Cédula" required>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="text" name="direccion" placeholder="Dirección" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="password" name="password2" placeholder="Contraseña" required>
    <div class='Conthcj' style='width: 75%; height: 60px;'>
      <input type="submit" value="Registrarse" class="Bt4b">
      <input type="button" value="Cancelar" class="Bt4r"
        onclick="location.href='<?=base_url?>'">
    </div>
  </form>
  <!-- End Formulario de Registro -->
</div>
<!-- End Panel Derecho -->
</body>