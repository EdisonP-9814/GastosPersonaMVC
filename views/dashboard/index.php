<div class='Contvcc Frm1' style='margin-top: 20px;'>
    
    <h4>Mi Dashboard</h4>

    <div style="width: 80%; margin-top: 20px;">
        <p>
            ¡Bienvenido, <?= htmlspecialchars($_SESSION['identity']->nombre_usuario) ?>!
        </p>
        <p>
            Desde aquí podrás ver un resumen de tus finanzas, últimos movimientos y accesos directos.
        </p>

        <div class='Conthcs' style='width: 100%; margin-top: 30px;'>
            <a href="<?=base_url?>Cuenta/index" class="Bt4b" style="width: 150px;">Ver mis Cuentas</a>
            <a href="#" class="Bt4b" style="width: 150px; background-color: #41c16b;">Nuevo Gasto</a>
            <a href="#" class="Bt4b" style="width: 150px; background-color: #25739d;">Nuevo Ingreso</a>
        </div>
    </div>
</div>
</body>