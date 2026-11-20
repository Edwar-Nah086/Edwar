<?php
declare(strict_types=1);

// Datos de ejemplo para practicar en un repositorio local.
$productos = [
    ['nombre' => 'Aceite de coco virgen', 'categoria' => 'Derivados del coco'],
    ['nombre' => 'Harina de coco', 'categoria' => 'Derivados del coco'],
    ['nombre' => 'Coco en hojuelas naturales', 'categoria' => 'Derivados del coco'],
    ['nombre' => 'Fruta cristalizada', 'categoria' => 'Insumos para alimentos'],
    ['nombre' => 'Polvo para hornear', 'categoria' => 'Insumos para alimentos'],
];

$consulta = $_GET['q'] ?? '';
$busqueda = is_string($consulta) ? trim($consulta) : '';
$resultados = $productos;

if ($busqueda !== '') {
    $patron = '~' . preg_quote($busqueda, '~') . '~iu';
    $resultados = array_filter($productos, static function (array $producto) use ($patron): bool {
        $texto = $producto['nombre'] . ' ' . $producto['categoria'];
        return preg_match($patron, $texto) === 1;
    });
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de práctica</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f2f5ef;
            color: #17291e;
            font: 1rem/1.55 system-ui, sans-serif;
        }

        header {
            padding: 2.5rem 1rem;
            background: #14532d;
            color: #fff;
            border-top: 5px solid #fe6b00;
        }

        header div, main { width: min(100%, 48rem); margin-inline: auto; }
        h1 { margin: 0; font-size: clamp(1.8rem, 5vw, 2.5rem); }
        header p { margin: .35rem 0 0; }
        main { padding: 1.5rem 1rem; }

        section {
            padding: clamp(1rem, 4vw, 1.75rem);
            background: #fff;
            border: 1px solid #dce5dc;
            border-radius: .85rem;
            box-shadow: 0 .3rem 1rem #17291e0d;
        }

        label { display: block; margin-bottom: .35rem; font-weight: 650; }
        .controles { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
        input, button { font: inherit; padding: .6rem .8rem; border-radius: .45rem; }
        input { flex: 1 1 12rem; min-width: 0; border: 1px solid #87998c; }
        button { background: #14532d; color: #fff; border: 1px solid #14532d; cursor: pointer; }
        a { color: #14532d; }
        :focus-visible { outline: 2px solid #a94400; outline-offset: 3px; }
        .resumen { margin-block: 1.25rem .5rem; color: #405748; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .65rem .5rem; border-bottom: 1px solid #e2eae2; }
        th { background: #e9f2e9; }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>Catálogo de práctica</h1>
            <p>Insumos para la industria alimentaria</p>
        </div>
    </header>

    <main>
        <section aria-label="Lista de productos">
            <form method="get" action="index.php">
                <label for="q">Busca por nombre o categoría</label>
                <div class="controles">
                    <input id="q" type="search" name="q" value="<?= escapar($busqueda) ?>" placeholder="Ejemplo: coco">
                    <button type="submit">Buscar</button>
                    <?php if ($busqueda !== ''): ?>
                        <a href="index.php">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($resultados === []): ?>
                <p class="resumen">No encontramos productos para «<?= escapar($busqueda) ?>».</p>
            <?php else: ?>
                <p class="resumen"><?= count($resultados) ?> <?= count($resultados) === 1 ? 'producto' : 'productos' ?></p>
                <table>
                    <thead>
                        <tr><th scope="col">Producto</th><th scope="col">Categoría</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $producto): ?>
                            <tr>
                                <td><?= escapar($producto['nombre']) ?></td>
                                <td><?= escapar($producto['categoria']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
