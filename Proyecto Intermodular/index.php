<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Edgar Ariza">
    <meta name="description" content="Portal de noticias y base de datos de jugadores de voleibol.">
    <meta name="keywords" content="voleibol, noticias, jugadores, base de datos, actualidad">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WIKIVOLLEY - Portal Profesional</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

 <header class="linea-superior">
    <div class="logo-y-titulo">
        <a href="index.php">
            <img src="img/logodef.png" alt="Logo WikiVolley" class="logo-principal">
        </a>
        <h2>TU PORTAL DE VOLEIBOL FAVORITO</h2>
    </div>

    <nav class="menu-navegacion">
        <ul>
            <li><a href="#seccion-noticias">NOTICIAS</a></li>
            <li><a href="#seccion-empleo">EMPLEO</a></li>
            <li><a href="#seccion-cursos">CURSOS</a></li>
        </ul>
    </nav>
</header>


<section id="seccion-noticias">
    <div class="cabecera-noticias">
        <img src="img/logo-noticias.png" alt="Noticias Voleibol" class="logo-seccion">
    </div>
    
    
    <div class="contenedor-grid">
        <?php
        $sql = "SELECT * FROM noticias";
        $resultado = $conexion->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
           while($fila = $resultado->fetch_assoc()) {
    echo "<article class='tarjeta-noticia'>";
    echo "  <div class='imagen-wrapper'>";
    echo "    <img src='img/" . $fila['imagen'] . "' alt='Noticia'>";
    echo "  </div>";
    echo "  <div class='contenido-noticia'>";
    echo "    <h3>" . $fila['titulo'] . "</h3>";
    echo "    <p>" . substr($fila['contenido'], 0, 100) . "...</p>"; // Cortamos el texto para que no sea eterno
    
    // Si la noticia tiene un enlace, mostramos el botón
    if (!empty($fila['enlace'])) {
        echo "<a href='" . $fila['enlace'] . "' class='boton-leer-mas' target='_blank'>Leer noticia completa</a>";
    }
    
    echo "  </div>";
    echo "</article>";
}
        } else {
            echo "<p>No hay noticias disponibles.</p>";
        }
        ?>
    </div>
</section>
<section id="seccion-empleo">
    <div class="cabecera-empleo">
        <img src="img/logo-empleo.png" alt="Bolsa de Empleo Voleibol" class="logo-seccion">
    </div>
    <div class="contenedor-lista-empleo">
    <?php
    // Usamos el nombre de tabla que confirmamos: BOLSA_EMPLEO
    $sql_empleo = "SELECT * FROM bolsa_empleo ORDER BY id DESC";
    $resultado_empleo = $conexion->query($sql_empleo);

    if ($resultado_empleo && $resultado_empleo->num_rows > 0) {
        while($fila = $resultado_empleo->fetch_assoc()) {
            // Mapeo de columnas en MAYÚSCULAS
            $puesto = $fila['PUESTO'] ?? 'Puesto no definido';
            $salario_db = $fila['SALARIO'] ?? 0;
            $desc = $fila['DESCRIPCION'] ?? 'Sin descripción';
            $contacto = $fila['CONTACTO'] ?? '#';

            // --- CORRECCIÓN PARA LA MONEDA ---
            // Si el salario es mayor que 0, lo formateamos. Si es 0, ponemos "A convenir"
            if ($salario_db > 0) {
                // Formato: 1.200 € (sin decimales para que sea más limpio, o pon un 2 si quieres céntimos)
                $salario_mostrar = number_format($salario_db, 0, ',', '.') . " €";
            } else {
                $salario_mostrar = "A convenir";
            }
            // ---------------------------------

            // Lógica de contacto (email o link)
            $href = (strpos($contacto, '@') !== false) ? "mailto:$contacto" : $contacto;

            echo "<div class='item-empleo-lista'>";
                // Columna 1: Título y Salario
                echo "<div class='col-info'>";
                    echo "<h3>" . htmlspecialchars($puesto) . "</h3>";
                    echo "<span class='tag-salario'>" . htmlspecialchars($salario_mostrar) . "</span>";
                echo "</div>";

                // Columna 2: Descripción (resumen)
                echo "<div class='col-desc'>";
                    echo "<p>" . htmlspecialchars(substr($desc, 0, 140)) . "...</p>";
                echo "</div>";

                // Columna 3: Acción
                echo "<div class='col-boton'>";
                    echo "<a href='$href' class='btn-lista' target='_blank'>Aplicar</a>";
                echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='mensaje-vacio'>No hay ofertas disponibles en la BOLSA_EMPLEO.</p>";
    }
    ?>
</div>
</section>
<section id="seccion-cursos">
    <div class="overlay-cursos"></div>

    <div class="contenido-seccion-wrapper">
        <div class="cabecera-seccion-logo">
            <img src="img/logo-curso.png" alt="Cursos y Formación" class="logo-seccion">
        </div>

        <div class="contenedor-grid-cursos">
            </div>
    </div>
    <div class="contenedor-grid-cursos">
    <?php
    // Consulta a la tabla CURSOS (Mayúsculas)
    $sql_cursos = "SELECT * FROM cursos ORDER BY id DESC";
    $resultado_cursos = $conexion->query($sql_cursos);

    if ($resultado_cursos && $resultado_cursos->num_rows > 0) {
        while($fila = $resultado_cursos->fetch_assoc()) {
            $nombre = $fila['NOMBRE'] ?? 'Curso sin título';
            $descripcion = $fila['DESCRIPCION'] ?? '';
            $precio_db = $fila['PRECIO'] ?? 0;
            $duracion = $fila['DURACION'] ?? 'Consultar';
            $imagen = $fila['IMAGEN'] ?? 'default-curso.jpg';
            $enlace = $fila['ENLACE'] ?? '#';

            $precio_mostrar = ($precio_db > 0) ? number_format($precio_db, 0, ',', '.') . " €" : "Gratis";
            $href = (strpos($enlace, '@') !== false) ? "mailto:$enlace" : $enlace;

            echo "<div class='tarjeta-curso-compacta'>";
                // Imagen muy pequeña en una esquina o lateral
                echo "<div class='mini-imagen-wrapper'>";
                    echo "<img src='img/" . htmlspecialchars($imagen) . "' alt='Icono Curso'>";
                echo "</div>";

                echo "<div class='contenido-curso-compacto'>";
                    echo "<div class='cabecera-card'>";
                        echo "<h3>" . htmlspecialchars($nombre) . "</h3>";
                        echo "<span class='badge-precio-mini'>$precio_mostrar</span>";
                    echo "</div>";
                    
                    echo "<span class='duracion-txt'>⏱ " . htmlspecialchars($duracion) . "</span>";
                    echo "<p>" . htmlspecialchars(substr($descripcion, 0, 90)) . "...</p>";
                    
                    echo "<a href='$href' class='btn-curso-mini' target='_blank'>Inscribirse</a>";
                echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='mensaje-vacio'>Próximamente nuevas formaciones.</p>";
    }
    ?>
</div>

    
</section>
<footer class="footer">
    <p>&copy; 2026 WIKIVOLLEY by EDGAR ARIZA . Todos los derechos reservados.</p>
</footer>
</body>
</html>
