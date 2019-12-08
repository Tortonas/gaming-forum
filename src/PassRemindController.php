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
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Bandyta prieiti prie puslapio be teisių", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
        $this->getView()->printRemindPass();

        if (isset($_POST['remindPassBtn']))
        {
            $row = $this->getModel()->getDataByString('naudotojai', 'email', $_POST['email']);
            $email = $this->getModel()->secureInput($row['email']);
            if ($this->getModel()->remindPassword($email))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Priminimo laiškas išsiūstas!", $ip);
                $this->printSuccess('Priminimo laiškas išsiūstas!');
            } else {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Klaida siunčiant Priminimą", $ip);
                $this->printDanger('Klaida');
            }
        }
    }

    public function printPageNewPass()
    {
        if($_SESSION['uzblokuotas'] === '1')
        {
            $this->redirect_to_another_page('index.php', 0);
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Vartotojas bando pasiekti užblokuotą vietą", $ip);
        }
        if($_SESSION['role'] >= 1)
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Vartotojas bando pasiekti vietą neturin teisų", $ip);
            $this->redirect_to_another_page('settings.php', 0);
        }
        if(empty($_GET['token']))
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Slaptažodžio tokenas nenurodytas", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
        $token = $this->getModel()->secureInput($_GET['token']);
        $error = 1;
        $data = $this->getModel()->getDataByColumnFirstToken( 'slaptazodziu_priminikliai', 'tokenas', $token);
        if(empty($data))
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Slaptažodžio tokenas nerastas", $ip);
            $this->printDanger('Tokenas Nerastas');
            $error = 0;
        }

        if($error == 1) {
            $this->getView()->printNewPassForm();

            if (isset($_POST['newPassBtn'])) {
                if ($this->getModel()->changeRemindedPass($_POST['newPasswd'], $_POST['repeatNewPasswd'])) {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Slaptažodžio sėkmingai pakeistas", $ip);
                    $this->printSuccess('Sėkmingai pakeista');
                } else {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Tokeo galiojimo data pasibaigė", $ip);
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