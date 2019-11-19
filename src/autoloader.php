<?php
include_once("src/MainController.php");
include_once("src/BasicControllerInterface.php");

foreach (glob("src/*.php") as $filename)
{
    include_once $filename;
}