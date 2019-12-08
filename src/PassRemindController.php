<?php


class PassRemindController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    // settings.php
    public function printPageView()
    {
        if($_SESSION['role'] > 0)
        {
            $this->redirect_to_another_page('index.php', 0);
        }
        $this->getView()->printRemindPass();

        if (isset($_POST['remindPassBtn']))
        {
            $row = $this->getModel()->getDataByString('naudotojai', 'email', $_POST['email']);
            $email = $this->getModel()->secureInput($row['email']);
            if ($this->getModel()->remindPassword($email))
            {
                $this->printSuccess('Priminimo laiškas išsiūstas!');
            } else {
                $this->printDanger('Klaida');
            }
        }
    }

    public function printPageNewPass()
    {
        if($_SESSION['uzblokuotas'] === '1')
        {
            $this->redirect_to_another_page('index.php', 0);
        }
        if($_SESSION['role'] >= 1)
        {
            $this->redirect_to_another_page('settings.php', 0);
        }
        if(empty($_GET['token']))
        {
            $this->redirect_to_another_page('index.php', 0);
        }
        $token = $this->getModel()->secureInput($_GET['token']);
        $error = 1;
        $data = $this->getModel()->getDataByColumnFirstToken( 'slaptazodziu_priminikliai', 'tokenas', $token);
        if(empty($data))
        {
            $this->printDanger('Tokenas Nerastas');
            $error = 0;
        }

        if($error == 1) {
            $this->getView()->printNewPassForm();

            if (isset($_POST['newPassBtn'])) {
                if ($this->getModel()->changeRemindedPass($_POST['newPasswd'], $_POST['repeatNewPasswd'])) {
                    $this->printSuccess('Sėkmingai pakeista');
                } else {
                    $this->printDanger('Tokeo galiojimo data pasibaigė');
                }
            }
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Priminti Slaptažodį';
    }
}