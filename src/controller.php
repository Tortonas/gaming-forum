<?php

class Controller {

    private $model;
    private $view;

    function __construct()
    {
        $this->model = new Model();
        $this->view = new View();
    }

    public function logout()
    {
        $this->model->logoutMe();
    }

    public function printNavBar($location)
    {
        $this->view->printNavbar($location);
    }

    public function redirect_to_another_page($urlDestination, $delay)
    {
        echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$urlDestination.'" />';
    }

    public function printRegisterForm()
    {
        $this->view->printRegisterForm();
    }

    public function handleRegisterButton()
    {
        if(isset($_POST['register_btn']))
        {
            $canIRegister = true;

            if(empty($_POST['username']))
            {
                $this->view->printDanger("Vartotojo vardas neturi būti tuščias!");
                $canIRegister = false;
            }
            else
            {
                if(strlen($_POST['username']) < 5)
                {
                    $this->view->printDanger("Vartotojo vardas turi būti sudarytas iš 5 simbolių!");
                    $canIRegister = false;
                }
            }
            if(empty($_POST['email']))
            {
                $this->view->printDanger("Elektroninis paštas neturi būti tuščias!");
                $canIRegister = false;
            }
            if(empty($_POST['first_name']))
            {
                $this->view->printDanger("Vardas neturi būti tuščias!");
                $canIRegister = false;
            }
            if(empty($_POST['last_name']))
            {
                $this->view->printDanger("Pavardė neturi būti tuščia!");
                $canIRegister = false;
            }
            if(empty($_POST['password']))
            {
                $this->view->printDanger("Slaptažodis neturi būti tuščias!");
                $canIRegister = false;
            }

            if(empty($_POST['password_repeat']))
            {
                $this->view->printDanger("Pakartokite slaptažodį!");
                $canIRegister = false;
            }

            if($_POST['password_repeat'] != $_POST['password'])
            {
                $this->view->printDanger("Slaptažodžiai nesutampa!");
                $canIRegister = false;
            }

            if($this->model->canIRegisterThisName($_POST['username']))
            {
                $this->view->printDanger("Naudotojo vardas užimtas!");
                $canIRegister = false;
            }

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if($canIRegister)
            {
                $this->model->registerUser(strtolower ($_POST['username']), $_POST['email'], $password, $_POST['first_name'], $_POST['last_name']);
                $this->view->printSuccess("Registracija sėkminga!");
                $this->redirect_to_another_page("login.php", 2);
            }
            else
            {
                $this->view->printDanger("Registracija nesėkminga!");
            }
        }
    }

    public function printLoginForm()
    {
        $this->view->printLoginForm();
    }

    public function handleLoginButton()
    {
        if(isset($_POST['login_btn']))
        {
            if($this->model->loginMe($_POST['username'], $_POST['password']))
            {
                $this->view->printSuccess("Prisijungimas sėkmingas!");
                $this->redirect_to_another_page("ads.php", 1);
            }
            else
            {
                $this->view->printDanger("Toks naudotojas su tokiu slapyvardžiu arba slaptažodžiu neegzistuoja!");
            }
        }
    }

    public function canIShowLoginPage()
    {
        if($this->amILoggedIn())
        {
            $this->redirect_to_another_page("index.php", 0);
            return false;
        }
        return true;
    }

    public function canIShowRegisterPage()
    {
        if($this->amILoggedIn())
        {
            $this->redirect_to_another_page("index.php", 0);
            return false;
        }
        return true;
    }

    public function amILoggedIn()
    {
        if($_SESSION['id'] == "0")
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function canIShowUsersPage()
    {
        if($_SESSION['role'] == 3)
        {
            return true;
        }
        else
        {
            $this->redirect_to_another_page("index.php", 0);
            return false;
        }
    }

    public function printUsersPage()
    {
        $userList = $this->model->getUserList();
        $this->view->printUsersPage($userList);
        $this->view->printUsersPageDeleteForm();
    }

    public function handleUsersPageButton()
    {
        if(isset($_POST['verify_btn']))
        {
            if($this->model->changeUserVerification($_POST['username']))
            {
                $this->view->printSuccess("Pakeista!");
                $this->redirect_to_another_page("users.php", 1);
            }
            else
            {
                $this->view->printDanger("Naudotojas nerastas!");
            }
        }
    }

    public function canISeeMyAdsPage()
    {
        if($_SESSION['id'] == "0")
        {
            $this->redirect_to_another_page("index.php", 0);
            return false;
        }
        else
        {
            return true;
        }
    }

    public function canISeeCreateAdWebPage()
    {
        if($_SESSION['verified'] == "0")
        {
            $this->redirect_to_another_page("index.php", 0);
            return false;
        }
        else
        {
            return true;
        }
    }

    public function printMyAdsContent()
    {
        $searchingJobList = $this->model->getSearchJobList($_SESSION['id']);
        $givingJobList = $this->model->getGivingJobList($_SESSION['id']);

        if($_SESSION['verified'] == 1)
        {
            $this->view->printSubmitNewAdButton(true);
        }
        else
        {
            $this->view->printSubmitNewAdButton(false);
        }
        $this->view->printMyAdsContent($searchingJobList, $givingJobList);
    }

    public function printCreateNewAdForm()
    {
        $this->view->printCreateNewAdForm();
    }

    public function handleCreateNewAdButton()
    {
        if(isset($_POST['createad_btn']))
        {
            $canICreateNewAd = true;

            if(empty($_POST['title'])  || empty($_POST['description']) || empty($_POST['text']) || empty($_POST['salary']) || empty($_POST['valid_till']))
            {
                $canICreateNewAd = false;
            }

            if($canICreateNewAd)
            {
                if($this->model->createNewAd($_POST['title'], $_POST['type'], $_POST['description'], $_POST['text'], $_POST['salary'] , $_POST['valid_till'], $_SESSION['id']))
                {
                    $this->view->printSuccess("Skelbimas sėkmingai įkeltas!");
                    $this->redirect_to_another_page("myads.php", 2);
                }
                else
                {
                    $this->view->printDanger("Formų tipai neatitinka!");
                }
            }
            else
            {
                $this->view->printDanger("Yra formos klaidų!");
            }

        }
    }

    public function printGlobalAdsContent()
    {
        $searchJobArr = $this->model->getSearchJobListGlobal();
        $giveJobArr = $this->model->getGivingJobListGlobal();
        $this->view->printGlobalAdsContent($searchJobArr, $giveJobArr);
        if($_SESSION['role'] >= 2)
        {
            $this->view->printGlobalAdsRemoveForm();
            if(isset($_POST['delete_btn']))
            {
                $canIDelete = true;

                if(empty($_POST['ad_id']))
                {
                    $canIDelete = false;
                }

                if($canIDelete)
                {
                    $this->model->hideAd($_POST['ad_id']);
                    $this->view->printSuccess("Jeigu ID egzistuoja, skelbimas ištrintas!");
                    $this->redirect_to_another_page("ads.php", 1);
                }
                else
                {
                    $this->view->printDanger("Validacijos klaida!");
                }
            }
        }
    }

    public function printViewAdContent()
    {
        if(isset($_GET['id']))
        {
            if($this->model->checkIfAdExistsById($_GET['id']))
            {
                if(!$this->model->haveIViewedThisAd($_SESSION['id'], $_GET['id']))
                {
                    $this->model->viewThisAd($_SESSION['id'], $_GET['id']);
                }

                $adContentArr = $this->model->getAdContentById($_GET['id']);
                $this->view->printOneAd($adContentArr);
                $commentArr = $this->model->getCommentsById($_GET['id']);
                $this->view->printAdComment($commentArr);
                if($_SESSION['id'] != 0)
                {
                    $this->view->printAdCommentForm();
                    if(isset($_POST['comment_btn']))
                    {
                        if(!empty($_POST['comment']))
                        {
                            $this->model->createNewAdComment($_POST['comment'], $_SESSION['id'], $_GET['id']);
                            $this->view->printSuccess("Komentaras paskelbtas!");
                            $this->redirect_to_another_page("viewad.php?id=".$_GET['id'],1);
                        }
                        else
                        {
                            $this->view->printDanger("Komenataro laukas tuščias!");
                        }
                    }
                }
            }
            else
            {
                $this->view->printDanger("Toks skelbimas su tokiu ID neegzistuoja. Būsite tuoj perkelti į pagrindinį skelbimų puslapį.");
                $this->redirect_to_another_page("ads.php", 2);
            }
        }
        else
        {
            $this->view->printDanger("Įvyko klaida. ID nenurodytas. Būsite tuoj perkelti į pagrindinį skelbimų puslapį.");
            $this->redirect_to_another_page("ads.php", 2);
        }
    }
}