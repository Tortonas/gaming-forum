<?php


class AdminController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        if($_SESSION['role'] < 2 || $_SESSION['uzblokuotas'] === '1') {
            $this->redirect_to_another_page('index.php', 0);
        }
        else {

            if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['role']) && !empty($_GET['role']) && $_GET['role'] != '' && $_GET['role'] > 0 && 4 > $_GET['role']) {
                $id = $this->getModel()->secureInput($_GET['id']);
                $role = $this->getModel()->secureInput($_GET['role']);
                $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id", $id);
                if ($data['role'] !== $_GET['role'] && $data['id'] == $_GET['id']) {
                    $this->getModel()->updateDataOneColumn("naudotojai", $_GET['id'], "role", $role);
                    $this->printSuccess("sėkmingai pakeista privilegija");
                }
            } else if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['uztildytas']) && !empty($_GET['uztildytas']) && $_GET['uztildytas'] != '' && $_GET['uztildytas'] >= 0 && 2 > $_GET['uztildytas']) {
                $id = $this->getModel()->secureInput($_GET['id']);
                $uztildytas = $this->getModel()->secureInput($_GET['uztildytas']);
                $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id", $id);
                if ($data['uztildytas'] !== $uztildytas && $id == $id) {
                    $this->getModel()->updateDataOneColumn("naudotojai", $id, "uztildytas", $uztildytas);
                    $this->printSuccess("Sėkmingai užtildytas");
                } else if ($data['uztildytas'] === $_GET['uztildytas'] && $data['id'] == $_GET['id']) {
                    $this->getModel()->updateDataOneColumn("naudotojai", $id, "uztildytas", '0');
                    $this->printSuccess("Vartotojas yra atitildytas");

                }

            } else if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['uzblokuotas']) && !empty($_GET['uzblokuotas']) && $_GET['uzblokuotas'] != '' && $_GET['uzblokuotas'] >= 0 && 2 > $_GET['uzblokuotas']) {
                $id = $this->getModel()->secureInput($_GET['id']);
                $uzblokuotas = $this->getModel()->secureInput($_GET['uzblokuotas']);


                $data = $this->getModel()->getDataByColumnFirst("naudotojai", "id", $id);
                if ($data['uzblokuotas'] !== $uzblokuotas && $data['id'] == $id) {
                    $id = $this->getModel()->secureInput($id);
                    $this->getModel()->updateDataOneColumn("naudotojai", $id, "uzblokuotas", $uzblokuotas);
                    $this->printSuccess("Sėkmingai užblokuotas");
                    if ($id === $_SESSION['id']) {
                        $_SESSION['uzblokuotas'] = '1';
                        $this->redirect_to_another_page('index.php', 0);
                    }
                } else if ($data['uzblokuotas'] === $_GET['uzblokuotas'] && $data['id'] == $id) {
                    $id = $this->getModel()->secureInput($id);
                    $this->getModel()->updateDataOneColumn("naudotojai", $id, "uzblokuotas", '0');
                    $this->printSuccess("Vartotojas yra atblokuotas");
                }
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
        if($_SESSION['role'] < 2 || $_SESSION['uzblokuotas'] === '1')
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
        $id = $this->getModel()->secureInput($_GET['id']);
        foreach ($_POST as $param_name => $param_val) {
            $value = $this->getModel()->secureInput($param_val);
            if(isset($_POST['request']) && $_POST['request'] == "visiDuomenys") {
                if ($param_name !== 'id' && $param_name != "slapyvardis" && $param_name != "request" &&  isset($value) && !empty($value) && $value != '') {
                    $this->getModel()->updateDataOneColumn("naudotojai", $id, $param_name, $value);
                } else if (($param_name == 'email') && ( $param_name != "request" && !isset($value) || empty($value) || $value == '')) {
                    $this->printDanger('Laukai yra tušti');
                    $check = false;
                } else if ($param_name === 'slapyvardis') {
                    $this->printDanger('Laukai yra tušti');
                    $check = false;
                }
                $sum++;
            }
        }

        if(isset($_POST['request']) && $_POST['request'] == "slaptazodisSubmit" ) {

            $password = $this->getModel()->secureInput($_POST['slaptazodis']) ;
            $passwordCheck = $this->getModel()->secureInput($_POST['slaptazodisPakartoti']);
            $id = $this->getModel()->secureInput($_GET['id']);

            if ($this->getModel()->changePasswdAdmin($id, $password, $passwordCheck))
            {
                $this->getView()->printSuccess('Slaptažodis sėkmingai pakeistas');
            } else {
                $this->getView()->printDanger('Klaida');
            }

        }

        if($check === true && $sum > 0)
        {
            $this->printSuccess("Sėkmingai pakeisti duomenys");
        }
        $content = $this->getModel()->getDataByColumnFirst("naudotojai", 'id', $id);
        $this->getView()->printEditUserAsAdmin($content);


    }
}