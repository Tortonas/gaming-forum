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

    public function getTitle()
    {
        echo 'Gaming Forum - Priminti Slaptažodį';
    }
}