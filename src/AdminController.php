<?php


class AdminController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        if($_SESSION['role'] < 2) {
            $this->redirect_to_another_page('index.php', 0);
        }

        if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['role']) && !empty($_GET['role']) && $_GET['role'] != '' && $_GET['role'] > 0 && 4 > $_GET['role'])
        {
            $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id",$_GET['id']);
            if( $data['role'] !== $_GET['role'] && $data['id'] == $_GET['id']) {
                $this->getModel()->updateDataOneColumn("naudotojai", $_GET['id'], "role", $_GET['role']);
                $this->printSuccess("sėkmingai pakeista privilegija");
            }
        }
        else if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['uztildytas']) && !empty($_GET['uztildytas']) && $_GET['uztildytas'] != '' && $_GET['uztildytas'] >= 0 && 2 > $_GET['uztildytas']) {

            $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id",$_GET['id']);
            if($data['uztildytas'] !== $_GET['uztildytas'] && $data['id'] == $_GET['id']) {
                $this->getModel()->updateDataOneColumn("naudotojai", $_GET['id'], "uztildytas", $_GET['uztildytas']);
                $this->printSuccess("Sėkmingai užtildyta");
            }
            else if($data['uztildytas'] === $_GET['uztildytas'] && $data['id'] == $_GET['id'])
            {
                $this->printDanger("Vartotojas jau yra užtildytas");
            }
        }
        else if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['uzblokuotas']) && !empty($_GET['uzblokuotas']) && $_GET['uzblokuotas'] != '' && $_GET['uzblokuotas'] >= 0 && 2 > $_GET['uzblokuotas']) {

            $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id",$_GET['id']);
            if($data['uzblokuotas'] !== $_GET['uzblokuotas'] && $data['id'] == $_GET['id']) {
                $this->getModel()->updateDataOneColumn("naudotojai", $_GET['id'], "uzblokuotas", $_GET['uzblokuotas']);
                $this->printSuccess("Sėkmingai užblokuota");
            }
            else if($data['uzblokuotas'] === $_GET['uzblokuotas'] && $data['id'] == $_GET['id'])
            {
                $this->printDanger("Vartotojas jau yra užblokuotas");
            }
        }


        $results = $this->getModel()->getData("naudotojai");
        $this->getView()->printAdminPanel($results);
        // TODO: Implement printPageView() method.
    }

    public function getTitle()
    {
        echo 'pasikeist';
        // TODO: Implement getTitle() method.
    }

    public function printEditUserView()
    {
        if($_SESSION['role'] < 2)
        {
            $this->redirect_to_another_page('index.php', 0);
        }
        if(!isset($_GET['id']))
        {
            //$this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('adminpanel.php', 0);
            return;
        }

        $check = true;
        $sum = 0;
        foreach ($_POST as $param_name => $param_val) {

            if($param_name !== 'id' && $param_name != "slapyvardis" && isset($param_val) && !empty($param_val) && $param_val != '')
            {
                $this->getModel()->updateDataOneColumn("naudotojai", $_GET['id'], $param_name, $param_val);
            }
            else if(($param_name == 'email') && (!isset($param_val) || empty($param_val) || $param_val == ''))
            {
                $this->printDanger('Laukai yra tušti');
                $check = false;
            }
            else if($param_name === 'slapyvardis')
            {
                $this->printDanger('Laukai yra tušti');
                $check = false;
            }
            $sum++;
        }
        if($check === true && $sum > 0)
        {
            $this->printSuccess("Sėkmingai pakeisti duomenys");
        }
        $content = $this->getModel()->getDataByColumnFirst("naudotojai", 'id', $_GET['id']);
        $this->getView()->printEditUserAsAdmin($content);


    }
}