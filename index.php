<?php
$telefono_whatsapp = "5623888148";

$precios_chilaquiles = [
    'Sencillos'    
          => ['Chico' => 38, 'Grande' => 50],
    'Huevo'              => ['Chico' => 50, 'Grande' => 70],
    'Pollo'              => ['Chico' => 55, 'Grande' => 85],
    'Suadero'            => ['Chico' => 60, 'Grande' => 90],
    'Campechanos'        => ['Chico' => 60, 'Grande' => 90],
    'Pechuga empanizada' => ['Chico' => 65, 'Grande' => 95],
    'Arrachera'          => ['Chico' => 75, 'Grande' => 120]
];

$precios_tortas = [
    'pollo'      => 38,
    'suadero'    => 38,
    'campechana' => 38
];

$precios_extras = [
    'Salsa Macha' => 5,
    'Queso'       => 5,
    'Crema'       => 5,
    'Frijoles'    => 5,
    'Bolillo'     => 5,
    'Huevo'       => 10
];

if (isset($_POST['accion']) && $_POST['accion'] === 'confirmar_pedido') {
    $cliente   = htmlspecialchars($_POST['customer_name'] ?? 'Cliente');
    $num_cliente = htmlspecialchars($_POST['num_customer']?? 'Numero Secundario');
    $direccion = htmlspecialchars($_POST['direccion'] ?? 'No especificada');
    $ordenes   = $_POST['chilaquiles'] ?? [];
    $tortas    = $_POST['tortas'] ?? [];

    $total_pagar = 0;

    $mensaje  = "Nuevo Pedido en Chiquiquiles\n\n";
    $mensaje .= "Cliente: " . $cliente . "\n";
    $mensaje .= "Direccion: " . $direccion . "\n";
    $mensaje .= "-----------------------------\n";
    $mensaje .= "ORDENES DE CHILAQUILES (" . count($ordenes) . "):\n";

    foreach ($ordenes as $i => $chilaquil) {
        $num      = $i + 1;
        $tamano   = htmlspecialchars($chilaquil['tamano'] ?? 'Grande');
        $salsa    = htmlspecialchars($chilaquil['salsa'] ?? 'Verde');
        $queso    = htmlspecialchars($chilaquil['queso'] ?? 'Cotija');
        $proteina = htmlspecialchars($chilaquil['proteina'] ?? 'Sencillos');

        $costo_orden = $precios_chilaquiles[$proteina][$tamano] ?? 0;

        $extras_seleccionados = $chilaquil['extras'] ?? [];
        $costo_extras = 0;
        if (!empty($extras_seleccionados)) {
            foreach ($extras_seleccionados as $extra_nombre) {
                $costo_extras += $precios_extras[$extra_nombre] ?? 0;
            }
            $lista_extras = implode(", ", array_map('htmlspecialchars', $extras_seleccionados));
        } else {
            $lista_extras = "Sin extras";
        }

        $subtotal_orden = $costo_orden + $costo_extras;
        $total_pagar += $subtotal_orden;

        $mensaje .= "Orden #" . $num . ": $" . $subtotal_orden . "\n";
        $mensaje .= " - Tamano: " . $tamano . "\n";
        $mensaje .= " - Salsa: " . $salsa . "\n";
        $mensaje .= " - Queso: " . $queso . "\n";
        $mensaje .= " - Con: " . $proteina . " ($" . $costo_orden . ")\n";
        $mensaje .= " - Extras: " . $lista_extras . " ($" . $costo_extras . ")\n\n";
    }

    $hay_tortas = false;
    $texto_tortas = "TORTAS:\n";
    foreach ($tortas as $tipo => $cant) {
        $cantidad_torta = (int)$cant;
        if ($cantidad_torta > 0) {
            $costo_torta = $cantidad_torta * ($precios_tortas[$tipo] ?? 0);
            $total_pagar += $costo_torta;
            $texto_tortas .= "- " . $cantidad_torta . " de " . ucfirst($tipo) . " ($" . $costo_torta . ")\n";
            $hay_tortas = true;
        }
    }
    if ($hay_tortas) {
        $mensaje .= $texto_tortas . "\n";
    }

    $mensaje .= "-----------------------------\n";
    $mensaje .= "TOTAL ESTIMADO: $" . $total_pagar . "\n\n";
    $mensaje .= "Por favor confirmar costo total y tiempo de entrega.\n";
    $mensaje .= "Numero de contacto secundario: ".$num_cliente;

    header("Location: https://wa.me/" . $telefono_whatsapp . "?text=" . urlencode($mensaje));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiquiquiles</title>
    <link rel="stylesheet" type="img/jpg" href="logo.jpg">
    <style>
        :root {
            --bg-principal: #0d0d0d;
            --bg-tarjeta: #161616;
            --bg-suave: #222222;
            --dorado: #dfb15b;
            --dorado-brillante: #f5cf7a;
            --dorado-oscuro: #8c6b2d;
            --texto-claro: #f4f4f4;
            --texto-secundario: #b3b3b3;
            --borde-dorado: rgba(223, 177, 91, 0.35);
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            margin: 0;
            padding: 0 15px 40px;
            background-color: var(--bg-principal);
            color: var(--texto-claro);
            line-height: 1.6;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        header {
            padding: 20px 0 15px;
            border-bottom: 1px solid var(--borde-dorado);
            text-align: center;
        }

        .logo_picture {
            height: 55px;
            width: 55px;
            vertical-align: middle;
            border-radius: 50%;
            border: 2px solid var(--dorado);
            object-fit: cover;
            box-shadow: 0 0 10px rgba(223, 177, 91, 0.3);
        }

        header h1 {
            color: var(--dorado);
            letter-spacing: 1.5px;
            font-size: 1.8rem;
            text-transform: uppercase;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        header nav a {
            color: var(--dorado-brillante);
            text-decoration: none;
            font-weight: 600;
            padding: 6px 10px;
            display: inline-block;
            transition: color 0.3s, text-shadow 0.3s;
        }

        header nav a:hover {
            color: #ffffff;
            text-shadow: 0 0 8px var(--dorado);
        }

        .banner-hero {
            width: 100%;
            max-height: 380px;
            object-fit: cover;
            border-radius: 14px;
            margin: 20px 0;
            display: block;
            border: 1px solid var(--borde-dorado);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.7);
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .img-seccion {
            width: 100%;
            max-width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            margin: 12px 0 18px 0;
            display: block;
            border: 1px solid var(--borde-dorado);
        }

        main > img:not([src*="foto6.jpeg"]):not([src*="foto3.jpeg"]) {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            border-radius: 10px;
            margin: 18px auto;
            display: block;
            border: 1px solid var(--borde-dorado);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.6);
            clear: both;
        }

        img[src*="foto6.jpeg"],
        img[src*="foto3.jpeg"] {
            float: left;
            width: calc(50% - 6px);
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--dorado);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.7);
            margin: 15px 0;
        }

        img[src*="foto6.jpeg"] {
            margin-right: 12px;
        }

        main > img[src*="foto3.jpeg"] + div,
        main > img[src*="foto3.jpeg"] + section,
        main > img[src*="foto3.jpeg"] + * {
            clear: both;
        }
        .torta_chil{
            width: calc(50% - 6px);
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--dorado);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.7);
            margin: 15px 0;
        }

        img[src=""] {
            display: none !important;
        }

        section {
            background: var(--bg-tarjeta);
            border: 1px solid var(--borde-dorado);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.5);
        }

        h4.menu {
            color: var(--dorado);
            font-size: 1.4rem;
            margin-top: 0;
            margin-bottom: 12px;
            border-bottom: 2px solid var(--dorado-oscuro);
            padding-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 18px;
            width: 100%;
            background: #111;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #333;
        }

        th {
            background: linear-gradient(180deg, #2b2210 0%, #1a150b 100%);
            color: var(--dorado-brillante);
            border-bottom: 2px solid var(--dorado);
            padding: 10px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            border-bottom: 1px solid #222;
            padding: 9px 12px;
            color: var(--texto-claro);
            font-size: 0.95rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: rgba(223, 177, 91, 0.08);
        }

        .make_order {
            background: var(--bg-tarjeta);
            padding: 24px;
            border-radius: 12px;
            max-width: 100%;
            margin: 30px auto;
            border: 1px solid var(--dorado);
            box-shadow: 0 0 20px rgba(223, 177, 91, 0.15);
        }

        .make_order h3 {
            color: var(--dorado);
            font-size: 1.5rem;
            margin-top: 0;
            text-align: center;
            letter-spacing: 1px;
        }

        .bloque-orden {
            background: #1c1c1c;
            border: 1px solid rgba(223, 177, 91, 0.25);
            padding: 16px;
            margin-bottom: 18px;
            border-radius: 8px;
        }

        .bloque-orden h4 {
            color: var(--dorado-brillante);
        }

        .campo {
            margin-bottom: 14px;
        }

        .campo label {
            display: inline-block;
            margin-right: 12px;
            margin-bottom: 4px;
            cursor: pointer;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            background-color: #0b0b0b;
            color: #ffffff;
            border: 1px solid #444;
            border-radius: 6px;
            outline: none;
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--dorado);
            box-shadow: 0 0 8px rgba(223, 177, 91, 0.4);
        }

        button[type="submit"] {
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            filter: brightness(1.1);
        }

        @media (min-width: 768px) {
            header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-align: left;
            }

            header nav {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            header br {
                display: none;
            }

            .img-seccion {
                height: 260px;
            }

            .make_order {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="#"><img src="logo.jpg" alt="Logo Chilaquiles" class="logo_picture"></a>
        <h1 style="display:inline-block; margin-left: 10px;">Chiquiquiles</h1><br><br>
        <a href="#chilaquiles">Chilaquiles</a> |
        <a href="#tortas">Tortas</a> |
        <a href="#extras">Extras</a> |
        <a href="#make_order">Hacer un pedido</a>
    </nav> 
</header>

<img src="mono_chilaquil.png" alt="Chilaquiles recién hechos" class="banner-hero">

<main>
    <div class="card_menu">
        <section id="chilaquiles">
            <h4 class="menu">Chilaquiles</h4>
            <img src="img/foto4.jpeg" alt="Orden de chilaquiles" class="img-seccion">
            <table>
                <thead><tr><th>Salsas</th><th>Quesos</th></tr></thead>
                <tbody>
                    <tr><td>Verde</td><td>Queso Cotija</td></tr>
                    <tr><td>Roja</td><td>Queso Doble Crema</td></tr>                 
                </tbody>
            </table>
            <table>
                <thead><tr><th>Platillo</th><th>Chico</th><th>Grandes</th></tr></thead>
                <tbody>
                    <tr><td>Chilaquiles Sencillos</td><td>$38</td><td>$50</td></tr>
                    <tr><td>Chilaquiles con Huevo</td><td>$50</td><td>$70</td></tr>
                    <tr><td>Chilaquiles con Pollo</td><td>$55</td><td>$85</td></tr>
                    <tr><td>Chilaquiles con Suadero</td><td>$60</td><td>$90</td></tr>
                    <tr><td>Chilaquiles Campechanos</td><td>$60</td><td>$90</td></tr>
                    <tr><td>Chilaquiles con Pechuga</td><td>$65</td><td>$95</td></tr>
                    <tr><td>Chilaquiles con Arrachera</td><td>$75</td><td>$120</td></tr>
                </tbody>
            </table>
        </section>
    </div>
    <img src="img/foto6.jpeg" alt="chilaquiles verdes">
    <img src="img/foto3.jpeg" alt="chilaquiles rojos">

    <div>
        <section id="tortas">
            <h4 class="menu">Tortas de Chilaquiles</h4>
            <img src="img/foto8.jpeg" alt="Torta de chilaquiles" class="torta_chil">
            <table>
                <thead><tr><th>Proteína</th><th>Precio</th></tr></thead>
                <tbody>
                    <tr><td>Pollo</td><td>$38</td></tr>
                    <tr><td>Suadero</td><td>$38</td></tr>
                    <tr><td>Campechana</td><td>$38</td></tr>
                </tbody>
            </table>
        </section>
    </div>
    <div>
        <section id="extras">
            <h4 class="menu">Extras</h4>
            <table>
                <thead><tr><th>Extra</th><th>Costo</th></tr></thead>
                <tbody>
                    <tr><td>Salsa Macha</td><td>$5</td></tr> 
                    <tr><td>Queso</td><td>$5</td></tr>
                    <tr><td>Crema</td><td>$5</td></tr>
                    <tr><td>Frijoles</td><td>$5</td></tr>
                    <tr><td>Bolillo</td><td>$5</td></tr>
                    <tr><td>Huevo</td><td>$10</td></tr>
                </tbody>
            </table>
        </section>
    </div>
<img src="img/foto5.jpeg" alt="chilaquiles preparados">
    <div class="make_order" id="make_order">
        <h3><strong>Realizar pedido</strong></h3>

        <?php if (!isset($_POST['num_chilaquiles'])): ?>
            <form method="POST" action="index.php#make_order">
                <label>Nombre de quien recibe:</label><br>
                <input type="text" name="customer_name" required placeholder="Tu nombre"><br><br>
                <label for="num_customer">Contacto:</label><br>
                <input type="text" name="num_customer" required placeholder="Contacton secundario">


                <label>Dirección de entrega:</label><br>
                <textarea name="direccion" placeholder="Calle, número, colonia..." rows="3" required></textarea><br><br>

                <label>¿Cuántas órdenes de Chiquilaquiles deseas?</label><br>
                <input type="number" name="num_chilaquiles" min="1" max="15" value="1" required><br><br>

                <button type="submit" style="background:#dfb15b; color:#0d0d0d; padding:12px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer; width:100%; font-size:1rem;">
                    Siguiente: Configurar ingredientes
                </button>
            </form>

        <?php else: ?>
            <?php
                $cantidad_pedida = min(15, max(1, (int)($_POST['num_chilaquiles'] ?? 1)));
                $cliente_previo   = htmlspecialchars($_POST['customer_name']);
                $num_cliente = htmlspecialchars($_POST['num_customer']);
                $direccion_previa = htmlspecialchars($_POST['direccion']);
            ?>

            <p>Cliente: <strong><?php echo $cliente_previo; ?></strong> (<?php echo $cantidad_pedida; ?> orden/es)</p>
            <p>Número de contacto: <strong><?php echo $num_cliente; ?></strong></p>
            
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="confirmar_pedido">
                <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($cliente_previo, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="num_customer" value="<?php echo htmlspecialchars($num_cliente, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="direccion" value="<?php echo htmlspecialchars($direccion_previa, ENT_QUOTES, 'UTF-8'); ?>">

                <?php for ($i = 0; $i < $cantidad_pedida; $i++): ?>
                    <div class="bloque-orden">
                        <h4 style="margin-top:0;">Orden #<?php echo $i + 1; ?> de Chilaquiles</h4>
                        
                        <div class="campo">
                            <label>Tamaño:</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][tamano]" value="Chico">Chico</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][tamano]" value="Grande" checked>Grande</label>
                        </div>

                        <div class="campo">
                            <label>Salsa:</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][salsa]" value="Verde" checked>Verde</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][salsa]" value="Roja">Roja</label>
                        </div>

                        <div class="campo">
                            <label>Queso:</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][queso]" value="Cotija" checked>Cotija</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][queso]" value="Doble Crema">Doble Crema</label>
                            <label><input type="radio" name="chilaquiles[<?php echo $i; ?>][queso]" value="Sin Queso">Sin Queso</label>
                        </div>

                        <div class="campo">
                            <label>Acompañamiento:</label>
                            <select name="chilaquiles[<?php echo $i; ?>][proteina]">
                                <option value="Sencillos">Sencillos</option>
                                <option value="Huevo">Huevo</option>
                                <option value="Pollo">Pollo</option>
                                <option value="Suadero">Suadero</option>
                                <option value="Campechanos">Campechanos</option>
                                <option value="Pechuga empanizada">Pechuga empanizada</option>
                                <option value="Arrachera">Arrachera</option>
                            </select>
                        </div>

                        <div class="campo">
                            <label><strong>Extras para esta orden (Opcional):</strong></label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Salsa Macha"> Salsa Macha (+$5)</label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Queso"> Queso (+$5)</label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Crema"> Crema (+$5)</label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Frijoles"> Frijoles (+$5)</label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Bolillo"> Bolillo (+$5)</label><br>
                            <label><input type="checkbox" name="chilaquiles[<?php echo $i; ?>][extras][]" value="Huevo"> Huevo (+$10)</label>
                        </div>
                    </div>
                <?php endfor; ?>

                <h4>Tortas de Chilaquiles (Opcional):</h4>
                <label>Pollo ($38): <input type="number" name="tortas[pollo]" min="0" value="0"></label><br>
                <label>Suadero ($38): <input type="number" name="tortas[suadero]" min="0" value="0"></label><br>
                <label>Campechana ($38): <input type="number" name="tortas[campechana]" min="0" value="0"></label><br><br>

                <button type="submit" style="background:#25D366; color:white; padding:12px 20px; border:none; border-radius:5px; font-weight:bold; cursor:pointer; width:100%; font-size:1rem;">
                    Pedir por WhatsApp
                </button>
            </form>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

