<main class="main">
    <?php use classes\Cliente;
    use classes\Empleado;
    use classes\Ticket;

    include __DIR__ . '/header.php'; ?>
    <section class="container">
        <section class="panel">
            <h2>Cobros/pagos</h2>
            <p>Esta es una página de ejemplo para la sección de cobros/pagos.  <a href="index.php?page=create_payment"><button>Registrar venta</button></a><a href="index.php?page=create_tiket"><button>Exportar Tickets</button></a></p>

            <?php
                $ventas = Ticket::getTickets();
            ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Nº venta</th>
                    <th>Fecha de venta</th>
                    <th>Total</th>
                    <th>Empleado</th>
                    <th>Cliente</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ventas as $venta) {
                    $empleado = Empleado::getEmpleadoById($venta->getEmpleado());
                    $cliente = Cliente::getClienteById($venta->getCliente());
                    ?>
                <tr>
                    <td><?= $venta->getNumTicket(); ?></td>
                    <td><?= $venta->getFechaCreacion(); ?></td>
                    <td><?= $venta->getTotalVenta(); ?></td>
                    <td><?= $empleado->getNombre(); ?></td>
                    <td><?= $cliente->getNombre(); ?></td>
                    <td>
                        <button class="btn">Ver Info</button>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </section>
        <footer class="footer">© 2026 Flores Nuria · Empleados</footer>
    </section>
</main>
