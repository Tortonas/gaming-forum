<?php
include_once("src/MainController.php");
foreach (glob("src/*.php") as $filename)
{
    include_once $filename;
}