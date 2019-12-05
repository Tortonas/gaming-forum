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

        if(isset($_POST['deleteThemeBtn']))
        {
            if($this->getModel()->removeData('temos', $_POST['deleteThemeBtn']))
            {
                $this->printSuccess('Istrinta!');
                $this->redirect_to_another_page('themes.php?id=' . $_GET['id'], 0);
            }
            else
            {
                $this->printDanger('Klaida!');
            }
        }
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
                $newThemeId = $this->getModel()->getLastCreatedTheme();
                $this->redirect_to_another_page('viewtheme.php?id=' . $newThemeId, 1);
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

        if($themeAnswerList == null)
        {
            $this->printDanger('Tema su tokiu ID neegzistuoja!');
            return;
        }

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
            if($this->getModel()->checkIfUserHasLikedThisThemeAnswer($_SESSION['id'], $_POST['likeBtn']))
            {
                $this->getModel()->likeTheme($this->getDateTime(), $_SESSION['id'], $_POST['likeBtn']);
            }

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

        if(!$this->getModel()->checkIfICanEditThisTheme($_SESSION['id'], $_GET['id']) || !$_SESSION['id'] == 3)
        {
            $this->printDanger('Neturite teises matyti sio puslapio!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

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
            $catalogList = $this->getModel()->getCatalogListByPattern($_POST['searchText']);
            $themeList = $this->getModel()->getThemeListByPattern($_POST['searchText']);

            $this->getView()->printCatalogSearchResults($catalogList, $themeList);
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}