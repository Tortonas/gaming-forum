<?php


class AdminController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
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