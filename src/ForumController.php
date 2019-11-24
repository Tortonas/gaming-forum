<?php

class ForumController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function printPageView()
    {
        $catalogs = $this->getModel()->getData('katalogai');
        $this->getView()->printForumFrontPage($catalogs);


        if(isset($_POST['createNewCatalog']))
        {
            if(empty($_POST['catalogName']))
            {
                $this->getView()->printDanger('Įveskite pavadinimą!');
            }
            else
            {
                $this->getView()->printSuccess('Sukurta!');
                if($this->getModel()->createNewCatalog($_POST['catalogName'], $this->getDateTime()))
                {
                    $this->redirect_to_another_page('forum.php', 0);
                }
            }
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}