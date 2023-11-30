<?php
if (isset($_GET['dominio'])) {
    $dominio = $_GET['dominio'];
    $carpetaWeb = "proyecto/$dominio";
    $zip = "proyecto/$dominio.zip";

    if (file_exists($carpetaWeb)) {
        shell_exec("zip -r $zip $carpetaWeb");

        if (file_exists($zip)) {
 
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $dominio . '.zip"');
            header('Content-Length: ' . filesize($zip));

            readfile($zip);
            exit();
        } else {
            echo "No se pudo crear el archivo ZIP";
        }
    } else {
        echo "La carpeta de la web no existe";
    }
}
?>