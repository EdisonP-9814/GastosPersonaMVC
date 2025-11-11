<div class='Contvcc Frm1' style='margin-top: 20px;'>
    
    <div class='Conthcj' style='width: 80%;'>
        <h4>Mis Cuentas</h4>
        <a href="<?=base_url?>Cuenta/crear" class="Bt3">
            Crear Nueva Cuenta
        </a>
    </div>

    <?php if (empty($cuentas)): ?>
        <p class="Txwarning" style="margin-top: 20px;">
            Aún no tienes cuentas registradas.
        </p>
    <?php else: ?>
        
        <table style='margin-top: 20px;'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Saldo Inicial</th>
                    <th>Saldo Actual</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cuentas as $c): ?>
                    <tr>
                        <td><?=htmlspecialchars($c['nombre_cuenta'])?></td>
                        <td><?=htmlspecialchars($c['tipo_cuenta'])?></td>
                        <td>$<?=number_format((float)$c['saldo_inicial_cuenta'], 2)?></td>
                        <td>
                            <span class="<?=($c['saldo_actual'] < 0) ? 'alt_red' : 'alt_green'?>">
                                $<?=number_format($c['saldo_actual'], 2)?>
                            </span>
                        </td>
                        <td>
                            <a href="<?=base_url?>Cuenta/editar&id=<?=$c['id_cuenta']?>" class="Bt2">Editar</a>
                            <a href="<?=base_url?>Cuenta/eliminar&id=<?=$c['id_cuenta']?>" class="Bt2r">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
