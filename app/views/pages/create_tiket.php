
<?php
// Manejar descarga de JSON
use classes\Ticket;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonTickets = Ticket::api_getAllTickets();
    file_put_contents("json/tickets.json", $jsonTickets);
    $tickets = Ticket::ticket_api_decode($jsonTickets);
    ?>
<section class="table-wrap">
    <table class="table">
        <thead>
        <tr>
            <th>Nº de ticket</th>
            <th>Empleado</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Precio Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tickets as $ticket) { ?>
            <tr>
                <td>Nº <?php echo $ticket->getNumTicket(); ?></td>
                <td><?php echo $ticket->getEmpleado(); ?></td>
                <td><?php echo $ticket->getCliente(); ?></td>
                <td><?php echo $ticket->getFechaCreacion(); ?></td>
                <td><?php echo $ticket->getTotalVenta(); ?>€</td>
            </tr>
            <?php } ?>
<?php
}
?>
<form method="POST" style="display:inline;">
    <button type="submit" class="btn">Crear/Actualizar Tickets (JSON)</button>
</form>
