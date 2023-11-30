<?php
    session_start();
    if (isset($_SESSION['email'])) {
        header("Location: panel.php");
        exit();
    }
    require_once '../bd/conexion.php';

    $errores = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = limpiarInput($_POST['email']);
        $clave = $_POST['clave'];
        $confirmar_clave = $_POST['confirmar_clave'];

        $stmtEmail = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmtEmail->execute([$email]);
        $rowEmail = $stmtEmail->fetch();

        if ($rowEmail) {
            $errores[] = "El correo electrónico ya está en uso.";
        }
        if ($clave !== $confirmar_clave) {
            $errores[] = "Las contraseñas no coinciden.";
        }
        if (empty($errores)) {
            $hashedClave = password_hash($clave, PASSWORD_DEFAULT);
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $fecha=date('d-m-Y H:i');
            $stmtInsert = $conn->prepare("INSERT INTO usuarios (email, clave, fecha_registro) VALUES (?, ?, ?)");
            $stmtInsert->execute([$email, $hashedClave, $fecha]);
            echo "<center><p>Registro exitoso</p>";
            echo "<p><a href='login.php'>Iniciar Sesión</a></p></center>";
        }
    }
    function limpiarInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body bgcolor="black" text="white">
    <center>
    <h1>Registrarse es simple</h1>
    <form method="post">
        <p>Email 
        <p><input type="email" name="email" required><br>
        <p>Contraseña 
        <p><input type="password" name="clave" required><br>
        <p>Confirmar contraseña
        <p><input type="password" name="confirmar_clave" required><br>
        <?php
        foreach ($errores as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
        <input type="submit" value="Registrarse">
    </form>
    </center>
</body>
</html>