<div class='Contvcc Frm1' style='margin-top: 20px;'>
    
    <div style="width: 80%; margin-top: 20px;">
        <h4>Historial de Transacciones</h4>
        <p>
            Aquí puedes ver todos los movimientos que has registrado.
        </p>
        
        <?php if (empty($transacciones)): ?>
            <p class="Txwarning" style="margin-top: 20px;">
                Aún no tienes transacciones registradas.
            </p>
        <?php else: ?>
            <table style='margin-top: 20px;'>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Cuenta</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacciones as $tx): ?>
                        <tr>
                            <td><?=htmlspecialchars($tx['fecha_transaccion'])?></td>
                            <td><?=htmlspecialchars($tx['descripcion_transaccion'])?></td>
                            <td><?=htmlspecialchars($tx['nombre_categoria'])?></td>
                            <td><?=htmlspecialchars($tx['nombre_cuenta'])?></td>
                            <td>
                                <span class="<?=($tx['tipo_transaccion'] == 'GASTO') ? 'alt_red' : 'alt_green'?>">
                                    <?=($tx['tipo_transaccion'] == 'GASTO') ? '-' : '+'?>
                                    $<?=number_format($tx['monto_transaccion'], 2)?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>