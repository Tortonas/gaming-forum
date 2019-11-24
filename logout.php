<?php

session_start();
include('src/autoloader.php');

$model = new Model();

$model->logoutMe();
echo '<meta http-equiv="refresh" content="0; url=index.php" />';