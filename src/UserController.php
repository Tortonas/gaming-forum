<?php


class UserController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    // register.php
    public function printPageView()
    {
        $this->getView()->printRegisterForm();

        if(isset($_POST['registerBtn']))
        {
            echo 'nuspausta';
        }
    }

    public function getTitle()
    {
        echo 'gaming forum - registracija';
    }
}