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

        echo $_SESSION['slapyvardis'];
    }

    public function getTitle()
    {
        echo 'Gaming forum - prisijungimas';
    }
}