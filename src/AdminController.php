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
}