<?php
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'akulikj0_cbr');
define('MYSQL_PASS', 'r4*zHDki');
define('MYSQL_DB_NAME', 'akulikj0_cbr');

$linkDB = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB_NAME) or die ('На сервере ведутся технические работы. The server is carrying out technical work.');
mysqli_query($linkDB,"SET CHARACTER SET 'utf8'");
mysqli_query($linkDB,"SET SESSION collation_connection ='utf8_unicode_ci'");