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

    // viewtheme.php
    public function printViewThemeView()
    {
        if(!isset($_GET['id']))
        {
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        $themeAnswerList = $this->getModel()->getThemeListJoinedWithUsers($_GET['id']);

        $likeCount = array();
        $likeCountIter = 0;

        while($row = $themeAnswerList->fetch_assoc())
        {
            $likeCount[$likeCountIter++] = $this->getModel()->getLikeCountByThemeAnswerId($row['id']);
        }

        $themeAnswerList = $this->getModel()->getThemeListJoinedWithUsers($_GET['id']);
        $this->getView()->printViewTheme($themeAnswerList, $likeCount);

        if(isset($_POST['commentBtn']))
        {
            if(empty($_POST['text']))
            {
                $this->printDanger('Irašykite kažką!');
            }
            else
            {
                if($this->getModel()->createNewThemeAnswer($_POST['text'], $this->getDateTime(), $_SESSION['id'], $_GET['id']))
                {
                    $this->printSuccess('Temos atsakymas sukurtas!');
                    $this->redirect_to_another_page('viewtheme.php?id='.$_GET['id'], 1);
                }
                else
                {
                    $this->printDanger('Ivyko klaida!');
                }
            }
        }

        if(isset($_POST['deleteBtn']))
        {
            if($this->getModel()->removeData('temu_atsakymai', $_POST['deleteBtn']))
            {
                $this->printSuccess('Istrinta!');
                $this->redirect_to_another_page('viewtheme.php?id='.$_GET['id'], 1);
            }
            else
            {
                $this->printDanger('Ivyko klaida!');
            }
        }

        if(isset($_POST['likeBtn']))
        {
            $this->getModel()->likeTheme($this->getDateTime(), $_SESSION['id'], $_POST['likeBtn']);
            $this->redirect_to_another_page('viewtheme.php?id='.$_GET['id'], 0);
        }
    }


    // editheme.php
    public function printEditThemeView()
    {
        if(!isset($_GET['id']))
        {
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        // TODO: Patikrinti ar as turiu teises redaguoti sita tema.

        $content = $this->getModel()->getDataByColumnFirst('temu_atsakymai', 'id', $_GET['id']);


        $this->getView()->printEditTheme($content['tekstas']);

        if(isset($_POST['editThemeBtn']))
        {
            if(!empty($_POST['text']))
            {
                if($this->getModel()->updateDataOneColumn('temu_atsakymai', $_GET['id'], 'tekstas', $_POST['text']))
                {
                    $this->printSuccess('Komentaras atnaujintas!');
                    $this->redirect_to_another_page('viewtheme.php?id='.$content['fk_tema'], 0);
                }
                else
                {
                    $this->printDanger('Ivyko klaida!');
                }
            }
            else
            {
                $this->printDanger('Ivyko klaida! Negalima pateikti tuščio komentaro!');
            }
        }
    }

    // search.php
    public function printSearchContent()
    {
        if(isset($_POST['searchText']))
            $this->getView()->printSearchPage($_POST['searchText']);
        else
            $this->getView()->printSearchPage(null);

        if(isset($_POST['searchBtn']))
        {
            echo 'soon';
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}