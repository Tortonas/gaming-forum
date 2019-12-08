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
        if($_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė prieiti prie puslapio", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }

        $catalogs = $this->getModel()->getData('katalogai');
        $this->getView()->printForumFrontPage($catalogs);


        if(isset($_POST['createNewCatalog']))
        {
            if(empty($_POST['catalogName']))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Kategorija kurta be pavadinimo", $ip);
                $this->getView()->printDanger('Įveskite pavadinimą!');
            }
            else
            {
                $this->getView()->printSuccess('Sukurta!');
                if($this->getModel()->createNewCatalog($_POST['catalogName'], $this->getDateTime()))
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Sukurta nauja kategorija: ".$_POST['catalogName']." ", $ip);

                    $this->redirect_to_another_page('forum.php', 0);
                }
            }
        }
        if(isset($_POST['deleteButton']))
        {
            if($this->getModel()->removeData('katalogai', $_POST['deleteButton']))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Ištrinta kategorija ", $ip);
                $this->printSuccess('Istrinta!');
                $this->redirect_to_another_page('forum.php', 0);
            }
            else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Kategorijos trynimo klaida", $ip);
                $this->printDanger('Ivyko klaida!');
            }
        }
    }

    // themes.php
    public function printPageViewThemes()
    {
        if(!isset($_GET['id']) || $_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Neisitinas jungimasis prie puslapio", $ip);
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
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Ištrinta tema ", $ip);
                $this->printSuccess('Istrinta!');
                $this->redirect_to_another_page('themes.php?id=' . $_GET['id'], 0);
            }
            else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Temos trynimo klaida", $ip);
                $this->printDanger('Klaida!');
            }
        }
    }


    // createtheme.php
    public function printCreateThemeView()
    {
        if(!isset($_GET['id']) || $_SESSION['uztildytas'] === '1' || $_SESSION['uzbokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Neleistinas bandymas eiti į puslapį", $ip);
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        $this->getView()->printCreateTheme();

        if(isset($_POST['createThemeBtn']))
        {
            if(empty($_POST['themeName']) || empty($_POST['themeText']))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Tamos pavadinimas arba tekstato laukas tuščias", $ip);
                $this->printDanger('Iveskite kazka!');
                return;
            }


            if($this->getModel()->createNewTheme($_POST['themeName'], $this->getDateTime(), $_SESSION['id'], $_GET['id'], $_POST['themeText']))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Sukurta nauja tema: ".$_POST['themeName']." ", $ip);
                $this->printSuccess('Tema sukurta!');
                $newThemeId = $this->getModel()->getLastCreatedTheme();
                $this->redirect_to_another_page('viewtheme.php?id=' . $newThemeId, 1);
            }
            else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nenumatyta klaida Naujos temos sukūrime", $ip);
                $this->printDanger('Nenumatyta klaida!');
            }

        }
    }

    // viewtheme.php
    public function printViewThemeView()
    {
        if(!isset($_GET['id']))
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė panaudoti puslapį", $ip);
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        $themeAnswerList = $this->getModel()->getThemeListJoinedWithUsers($_GET['id']);

        $likeCount = array();
        $likeCountIter = 0;

        if($themeAnswerList == null)
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Tema su tokiu ID neegzistuoja!", $ip);
            $this->printDanger('Tema su tokiu ID neegzistuoja!');
            return;
        }

        while($row = $themeAnswerList->fetch_assoc())
        {
            $likeCount[$likeCountIter++] = $this->getModel()->getLikeCountByThemeAnswerId($row['id']);
        }

        $themeAnswerList = $this->getModel()->getThemeListJoinedWithUsers($_GET['id']);
        $this->getView()->printViewTheme($themeAnswerList, $likeCount);

        if(isset($_POST['commentBtn']) && $_SESSION['uztildytas'] === '0')
        {
            if(empty($_POST['text']))
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Tuščias komentaras", $ip);
                $this->printDanger('Irašykite kažką!');
            }
            else
            {
                if($this->getModel()->createNewThemeAnswer($_POST['text'], $this->getDateTime(), $_SESSION['id'], $_GET['id']))
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Temos atsakymas sukurtas: ".$_POST['text']." ", $ip);
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
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Temos atsakymas išįrintas ", $ip);
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
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Ivyko klaida su id", $ip);
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('forum.php', 0);
            return;
        }

        if(!$this->getModel()->checkIfICanEditThisTheme($_SESSION['id'], $_GET['id']) || !$_SESSION['id'] == 3)
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Neturite teises tvarkyti temos puslapio", $ip);
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
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Komanteras atnaujintas: ".$_POST['text']."", $ip);
                    $this->printSuccess('Komentaras atnaujintas!');
                    $this->redirect_to_another_page('viewtheme.php?id='.$content['fk_tema'], 0);
                }
                else
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Ivyko klaida! Komentaro atnaujiname", $ip);
                    $this->printDanger('Ivyko klaida!');
                }
            }
            else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Ivyko klaida! Negalima pateikti tuščio komentaro!", $ip);
                $this->printDanger('Ivyko klaida! Negalima pateikti tuščio komentaro!');
            }
        }
    }

    // search.php
    public function printSearchContent()
    {
        if($_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė panaudoti puslapįu", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }

        if(isset($_POST['searchText']))
            $this->getView()->printSearchPage($_POST['searchText']);
        else
            $this->getView()->printSearchPage(null);

        if(isset($_POST['searchBtn']))
        {
            $catalogList = $this->getModel()->getCatalogListByPattern($_POST['searchText']);
            $themeList = $this->getModel()->getThemeListByPattern($_POST['searchText']);

            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Atlikta paieška: ".$_POST['searchText']."", $ip);

            $this->getView()->printCatalogSearchResults($catalogList, $themeList);
        }
    }

    public function getTitle()
    {
        echo 'Gaming forum - forumas';
    }
}