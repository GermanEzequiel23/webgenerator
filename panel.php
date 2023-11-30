<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
require_once '../bd/conexion.php';

$idUsuario = $_SESSION['id_usuario'];
$esAdmin = ($_SESSION['email'] === 'admin@server.com' && password_verify('serveradmin', $_SESSION['clave']));

if (isset($_POST['eliminar_dominio'])) {
    $dominioEliminar = $_POST['eliminar_dominio'];
    $carpetaEliminar = "proyecto/$dominioEliminar";
    if (file_exists($carpetaEliminar)) {
        shell_exec("rm -r $carpetaEliminar");
        $stmtDelete = $conn->prepare("DELETE FROM webs WHERE dominio = ?");
        $stmtDelete->execute([$dominioEliminar]);
        header("Location: panel.php");  
    }
    else {
        echo "<center>No se encontró la carpeta $carpetaEliminar</center>";
    }
} elseif (isset($_POST['nombre_web'])) {
    $nombreWeb = limpiarInput($_POST['nombre_web']);
    shell_exec("./wix.sh $nombreWeb");
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $fecha=date('d-m-Y H:i');
    $stmtInsertWeb = $conn->prepare("INSERT INTO webs (id_usuario, dominio, fecha_creacion) VALUES (?, ?, ?)");
    $stmtInsertWeb->execute([$idUsuario, $nombreWeb, $fecha]);
    header("Location: panel.php");
}
$stmtWebs = $esAdmin ? $conn->query("SELECT * FROM webs") : $conn->prepare("SELECT * FROM webs WHERE id_usuario = ?");
if (!$esAdmin) {
    $stmtWebs->execute([$idUsuario]);
}
$webs = $stmtWebs->fetchAll();

function limpiarInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inicio</title>
</head>
<body bgcolor="black" text="white">
    <center>
    <h2>Bienvenido a tu panel</h2>
    <?php
    echo "<p><a href='logout.php'>Cerrar sesión de " . $_SESSION['email'] . "</a></p>";
    if(!$esAdmin) {
    echo "<form method='post'>
        <p>Generar Web de:</p>
        <input type='text' name='nombre_web' placeholder='Nombre de la nueva web' required>
        <input type='submit' value='Crear web'>
    </form>";
    }   
    ?>
    <h3>Tus Webs:</h3>
    <ul>
        <?php
        foreach ($webs as $web) {
            $dominio = $web['dominio'];
            echo "<li>";
            echo "<a href='proyecto/$dominio/index.php'>$dominio</a>";
            if (!$esAdmin) {
                echo " | <a href='download.php?dominio=$dominio'>Descargar web</a>";
                echo " | <form method='post' style='display:inline;'>";
                echo "<input type='hidden' name='eliminar_dominio' value='$dominio'>";
                echo "<input type='submit' value='Eliminar'>";
                echo "</form>";
            }
            echo "</li>";
        }
        ?>
    </ul>
    </center>
</body>
</html>