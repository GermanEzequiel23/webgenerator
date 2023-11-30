nombre_web="$1"

mkdir -p "proyecto/$nombre_web/css/user" \
         "proyecto/$nombre_web/css/admin" \
         "proyecto/$nombre_web/img/avatars" \
         "proyecto/$nombre_web/img/buttons" \
         "proyecto/$nombre_web/img/products" \
         "proyecto/$nombre_web/img/pets" \
         "proyecto/$nombre_web/js/validations" \
         "proyecto/$nombre_web/js/effects" \
         "proyecto/$nombre_web/tpl" \
         "proyecto/$nombre_web/php"

touch "proyecto/$nombre_web/index.php"
touch "proyecto/$nombre_web/css/user/estilo.css"
touch "proyecto/$nombre_web/css/admin/estilo.css"
touch "proyecto/$nombre_web/js/login.js"
touch "proyecto/$nombre_web/js/register.js"
touch "proyecto/$nombre_web/js/effects/panels.js"
touch "proyecto/$nombre_web/tpl/main.tlp"
touch "proyecto/$nombre_web/tpl/login.tlp"
touch "proyecto/$nombre_web/tpl/register.tlp"
touch "proyecto/$nombre_web/tpl/panel.tlp"
touch "proyecto/$nombre_web/tpl/profile.tlp"
touch "proyecto/$nombre_web/tpl/crud.tlp"
touch "proyecto/$nombre_web/php/create.php"
touch "proyecto/$nombre_web/php/read.php"
touch "proyecto/$nombre_web/php/update.php"
touch "proyecto/$nombre_web/php/delete.php"
touch "proyecto/$nombre_web/php/dbconect.php"

echo "<?php echo '<center><h1>$nombre_web</h1></center>'; ?>" > "proyecto/$nombre_web/index.php"

chmod 757 proyecto
echo "Carpetas y archivos para $nombre_web creados con éxito"