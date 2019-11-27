<?php

class ForumController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    // forum.php
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
        if(isset($_POST['deleteButton']))
        {
            if($this->getModel()->removeData('katalogai', $_POST['deleteButton']))
            {
                $this->printSuccess('Istrinta!');
                $this->redirect_to_another_page('forum.php', 0);
            }
            else
            {
                $this->printDanger('Ivyko klaida!');
            }
        }
    }

    // themes.php
    public function printPageViewThemes()
    {
        if(!isset($_GET['id']))
        {
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        $themeList = $this->getModel()->getDataByColumn('temos', 'fk_katalogas', $_GET['id']);

        $this->getView()->printForumThemes($themeList, $this->getModel()->getDataByColumnFirst('katalogai', 'id', $_GET['id']));
    }


    // createtheme.php
    public function printCreateThemeView()
    {
        if(!isset($_GET['id']))
        {
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        $this->getView()->printCreateTheme();

        if(isset($_POST['createThemeBtn']))
        {
            if(empty($_POST['themeName']) || empty($_POST['themeText']))
            {
                $this->printDanger('Iveskite kazka!');
                return;
            }

            if($this->getModel()->createNewTheme($_POST['themeName'], $this->getDateTime(), $_SESSION['id'], $_GET['id'], $_POST['themeText']))
            {
                $this->printSuccess('Tema sukurta!');
            }
            else
            {
                $this->printDanger('Nenumatyta klaida!');
            }

        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}