<?php
class LoginController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        if($_SESSION['uzblokuotas'] === '1' || $_SESSION['role'] >= 1)
        {
            $this->redirect_to_another_page('index.php', 0);
        }

        $this->getView()->printLoginPage();
        if(isset($_POST['loginBtn']))
        {
            if($this->getModel()->loginMe($_POST['username'], $_POST['password']))
            {
                $this->getView()->printSuccess('Jūs prijungtas prie sistemos!');
                $this->redirect_to_another_page('forum.php', 0);
            }
            else
            {
                $this->getView()->printDanger('Jūs neprijungtas prie sistemos!');
            }
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - prisijungimas';
    }
}