<?php
class LoginController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        $this->getView()->printLoginPage();
        if(isset($_POST['loginBtn']))
        {
            //login logic
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - prisijungimas';
    }
}