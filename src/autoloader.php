<?php
include_once("src/MainController.php");
include_once("src/BasicControllerInterface.php");
include_once("src/AdminController.php");

foreach (glob("src/*.php") as $filename)
{
    include_once $filename;
}