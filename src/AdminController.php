<?php


class AdminController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        $this->getView()->printAdminPanel();
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
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('adminpanel.php', 0);
            return;
        }

        $content = $this->getModel()->getDataByColumnFirst("naudotojai", 'id', $_GET['id']);
        $this->getView()->printEditUserAsAdmin($content);

    }
}