<?php

use classes\Pedido;
use classes\Producto;
use classes\Ticket;

require_once __DIR__ . '/../php/clases.php';

$tickets = Ticket::getTickets();
$pedidos = Pedido::getPedidos();
$productos = Producto::getProductos();

$productNames = [];
foreach ($productos as $producto) {
    $productNames[$producto->getIdProducto()] = $producto->getNombre();
}

$salesByMonth = [];
foreach ($tickets as $ticket) {
    $fecha = strtotime($ticket->getFechaCreacion());
    if ($fecha === false) {
        continue;
    }
    $mes = date('Y-m', $fecha);
    if (!isset($salesByMonth[$mes])) {
        $salesByMonth[$mes] = 0;
    }
    $salesByMonth[$mes] += floatval($ticket->getTotalVenta());
}
ksort($salesByMonth);
$salesChart = [];
foreach ($salesByMonth as $mes => $total) {
    $salesChart[] = [
        'mes' => $mes,
        'total' => round($total, 2),
    ];
}

$orderStatuses = [];
foreach ($pedidos as $pedido) {
    $estado = trim($pedido->getEstado()) ?: 'Sin estado';
    if (!isset($orderStatuses[$estado])) {
        $orderStatuses[$estado] = 0;
    }
    $orderStatuses[$estado]++;
}
$orderChart = [];
foreach ($orderStatuses as $estado => $count) {
    $orderChart[] = [
        'estado' => $estado,
        'count' => $count,
    ];
}

$soldQuantities = [];
foreach ($tickets as $ticket) {
    foreach ($ticket->getBolsaCompra()->getProductos() as $producto) {
        [$idProducto, $cantidad] = $producto;
        if (!isset($soldQuantities[$idProducto])) {
            $soldQuantities[$idProducto] = 0;
        }
        $soldQuantities[$idProducto] += intval($cantidad);
    }
}

$orderedQuantities = [];
foreach ($pedidos as $pedido) {
    foreach ($pedido->getBolsaCompra()->getProductos() as $producto) {
        [$idProducto, $cantidad] = $producto;
        if (!isset($orderedQuantities[$idProducto])) {
            $orderedQuantities[$idProducto] = 0;
        }
        $orderedQuantities[$idProducto] += intval($cantidad);
    }
}

$productChart = [];
foreach (array_unique(array_merge(array_keys($soldQuantities), array_keys($orderedQuantities))) as $idProducto) {
    $productChart[] = [
        'producto' => $productNames[$idProducto] ?? "Producto {$idProducto}",
        'ventas' => $soldQuantities[$idProducto] ?? 0,
        'pedidos' => $orderedQuantities[$idProducto] ?? 0,
    ];
}

usort($productChart, function ($a, $b) {
    return ($b['ventas'] + $b['pedidos']) <=> ($a['ventas'] + $a['pedidos']);
});
?>
<main class="main">
  <?php include __DIR__ . '/header.php'; ?>
  <section class="container">
    <section class="panel">
      <h2>Reportes Analíticos</h2>
      <p>Gráficos generados con D3.js usando los datos de tickets, pedidos y bolsas de compra.</p>
    </section>

    <section class="charts-grid">
      <!-- <article class="panel chart-panel">
        <h3>Ventas por mes</h3>
        <div id="sales-line-chart" class="chart-canvas"></div>
      </article> -->
      <article class="panel chart-panel">
        <h3>Pedidos por estado</h3>
        <div id="orders-bar-chart" class="chart-canvas"></div>
      </article>
      <article class="panel chart-panel chart-wide">
        <h3>Productos vendidos vs pedidos</h3>
        <div id="product-grouped-chart" class="chart-canvas"></div>
      </article>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Reportes</footer>
  </section>
</main>

<style>
  .charts-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  }
  .chart-panel {
    min-height: 380px;
  }
  .chart-wide {
    grid-column: 1 / -1;
  }
  .chart-canvas {
    width: 100%;
    min-height: 320px;
  }
  .chart-tooltip {
    position: absolute;
    pointer-events: none;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 8px 10px;
    border-radius: 4px;
    font-size: 0.85rem;
    z-index: 20;
    opacity: 0;
    transition: opacity 150ms ease;
  }
</style>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
  const salesData = <?php echo json_encode($salesChart, JSON_UNESCAPED_UNICODE); ?>;
  const orderData = <?php echo json_encode($orderChart, JSON_UNESCAPED_UNICODE); ?>;
  const productData = <?php echo json_encode($productChart, JSON_UNESCAPED_UNICODE); ?>;

  const createTooltip = () => {
    const tooltip = d3.select('body')
      .append('div')
      .attr('class', 'chart-tooltip');
    return tooltip;
  };

  const drawLineChart = (containerId, data) => {
    const container = document.getElementById(containerId);
    if (!container || data.length === 0) return;

    const margin = { top: 20, right: 24, bottom: 40, left: 54 };
    const width = container.clientWidth - margin.left - margin.right;
    const height = container.clientHeight - margin.top - margin.bottom;
    const svg = d3.select(container)
      .append('svg')
      .attr('width', width + margin.left + margin.right)
      .attr('height', height + margin.top + margin.bottom)
      .append('g')
      .attr('transform', `translate(${margin.left},${margin.top})`);

    const x = d3.scalePoint()
      .domain(data.map(d => d.mes))
      .range([0, width])
      .padding(0.5);

    const y = d3.scaleLinear()
      .domain([0, d3.max(data, d => d.total) * 1.1])
      .nice()
      .range([height, 0]);

    const xAxis = d3.axisBottom(x);
    const yAxis = d3.axisLeft(y).ticks(5).tickFormat(d => `€ ${d}`);

    svg.append('g')
      .attr('transform', `translate(0,${height})`)
      .call(xAxis)
      .selectAll('text')
      .attr('transform', 'rotate(-40)')
      .style('text-anchor', 'end');

    svg.append('g')
      .call(yAxis);

    const line = d3.line()
      .x(d => x(d.mes))
      .y(d => y(d.total))
      .curve(d3.curveMonotoneX);

    svg.append('path')
      .datum(data)
      .attr('fill', 'none')
      .attr('stroke', '#3f51b5')
      .attr('stroke-width', 3)
      .attr('d', line);

    const tooltip = createTooltip();

    svg.selectAll('.dot')
      .data(data)
      .enter()
      .append('circle')
      .attr('class', 'dot')
      .attr('cx', d => x(d.mes))
      .attr('cy', d => y(d.total))
      .attr('r', 5)
      .attr('fill', '#3f51b5')
      .on('mousemove', (event, d) => {
        tooltip.style('opacity', 1)
          .html(`<strong>${d.mes}</strong><br>Ventas: € ${d.total.toFixed(2)}`)
          .style('left', `${event.pageX + 14}px`)
          .style('top', `${event.pageY - 28}px`);
      })
      .on('mouseleave', () => tooltip.style('opacity', 0));
  };

  const drawBarChart = (containerId, data) => {
    const container = document.getElementById(containerId);
    if (!container || data.length === 0) return;

    const margin = { top: 20, right: 20, bottom: 60, left: 50 };
    const width = container.clientWidth - margin.left - margin.right;
    const height = container.clientHeight - margin.top - margin.bottom;
    const svg = d3.select(container)
      .append('svg')
      .attr('width', width + margin.left + margin.right)
      .attr('height', height + margin.top + margin.bottom)
      .append('g')
      .attr('transform', `translate(${margin.left},${margin.top})`);

    const x = d3.scaleBand()
      .domain(data.map(d => d.estado))
      .range([0, width])
      .padding(0.3);

    const y = d3.scaleLinear()
      .domain([0, d3.max(data, d => d.count) * 1.2])
      .nice()
      .range([height, 0]);

    svg.append('g')
      .attr('transform', `translate(0,${height})`)
      .call(d3.axisBottom(x))
      .selectAll('text')
      .attr('transform', 'rotate(-35)')
      .style('text-anchor', 'end');

    svg.append('g')
      .call(d3.axisLeft(y).ticks(5).tickFormat(d3.format('d')));

    const tooltip = createTooltip();

    svg.selectAll('.bar')
      .data(data)
      .enter()
      .append('rect')
      .attr('class', 'bar')
      .attr('x', d => x(d.estado))
      .attr('y', d => y(d.count))
      .attr('width', x.bandwidth())
      .attr('height', d => height - y(d.count))
      .attr('fill', '#ff9800')
      .on('mousemove', (event, d) => {
        tooltip.style('opacity', 1)
          .html(`<strong>${d.estado}</strong><br>Cantidad: ${d.count}`)
          .style('left', `${event.pageX + 14}px`)
          .style('top', `${event.pageY - 28}px`);
      })
      .on('mouseleave', () => tooltip.style('opacity', 0));
  };

  const drawGroupedBarChart = (containerId, data) => {
    const container = document.getElementById(containerId);
    if (!container || data.length === 0) return;

    const margin = { top: 24, right: 24, bottom: 90, left: 58 };
    const width = container.clientWidth - margin.left - margin.right;
    const height = container.clientHeight - margin.top - margin.bottom;
    const svg = d3.select(container)
      .append('svg')
      .attr('width', width + margin.left + margin.right)
      .attr('height', height + margin.top + margin.bottom)
      .append('g')
      .attr('transform', `translate(${margin.left},${margin.top})`);

    const subgroups = ['ventas', 'pedidos'];
    const groups = data.map(d => d.producto);

    const x0 = d3.scaleBand()
      .domain(groups)
      .range([0, width])
      .padding(0.2);

    const x1 = d3.scaleBand()
      .domain(subgroups)
      .range([0, x0.bandwidth()])
      .padding(0.05);

    const y = d3.scaleLinear()
      .domain([0, d3.max(data, d => Math.max(d.ventas, d.pedidos)) * 1.2])
      .nice()
      .range([height, 0]);

    const color = d3.scaleOrdinal()
      .domain(subgroups)
      .range(['#1976d2', '#d32f2f']);

    svg.append('g')
      .attr('transform', `translate(0,${height})`)
      .call(d3.axisBottom(x0))
      .selectAll('text')
      .attr('transform', 'rotate(-40)')
      .style('text-anchor', 'end');

    svg.append('g')
      .call(d3.axisLeft(y).ticks(6));

    const tooltip = createTooltip();

    svg.append('g')
      .selectAll('g')
      .data(data)
      .enter()
      .append('g')
      .attr('transform', d => `translate(${x0(d.producto)},0)`)
      .selectAll('rect')
      .data(d => subgroups.map(key => ({ key, value: d[key], producto: d.producto })))
      .enter()
      .append('rect')
      .attr('x', d => x1(d.key))
      .attr('y', d => y(d.value))
      .attr('width', x1.bandwidth())
      .attr('height', d => height - y(d.value))
      .attr('fill', d => color(d.key))
      .on('mousemove', (event, d) => {
        tooltip.style('opacity', 1)
          .html(`<strong>${d.producto}</strong><br>${d.key}: ${d.value}`)
          .style('left', `${event.pageX + 14}px`)
          .style('top', `${event.pageY - 28}px`);
      })
      .on('mouseleave', () => tooltip.style('opacity', 0));

    const legend = svg.append('g')
      .attr('transform', `translate(0, -12)`);

    subgroups.forEach((key, index) => {
      const legendItem = legend.append('g')
        .attr('transform', `translate(${index * 140}, 0)`);

      legendItem.append('rect')
        .attr('width', 14)
        .attr('height', 14)
        .attr('fill', color(key));

      legendItem.append('text')
        .attr('x', 20)
        .attr('y', 12)
        .text(key === 'ventas' ? 'Ventas' : 'Pedidos')
        .attr('fill', '#333')
        .attr('font-size', '0.9rem');
    });
  };

  drawLineChart('sales-line-chart', salesData);
  drawBarChart('orders-bar-chart', orderData);
  drawGroupedBarChart('product-grouped-chart', productData);
</script>
