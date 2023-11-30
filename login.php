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

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        if ($row) {
            if (password_verify($clave, $row['clave'])) {
                 
                $_SESSION['email'] = $row['email'];
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['clave'] = $row['clave'];
                header("Location: panel.php"); 
                exit();
            } else {
                $errores[] = "Correo y/o Contraseña incorrectos.";
            }
        } else {
            $errores[] = "Correo y/o Contraseña incorrectos.";
        }
    }
    function limpiarInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
</head>
<body bgcolor="black" text="white">
    <center>
    <h1>webgenerator Germán Juárez</h1>
    
    <form method="post">
        <p>Email</p>
        <p><input type="text" name="email" <?php setInputValue('email'); ?> required><br>
        <p>Contraseña
        <p><input type="password" name="clave" required><br>
        <?php
        foreach ($errores as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
        <p><input type="submit" value="Ingresar">
    </form>

    <p>¿No tienes una cuenta? <a href="register.php">Regístrarse</a></p>

    </center>
    <?php
        function setInputValue($name) {
            if (isset($_POST[$name])) {
                echo 'value="' . htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8') . '"';
            }
        }
    ?>
</body>
</html>