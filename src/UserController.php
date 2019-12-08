<?php


class UserController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    // register.php
    public function printPageView()
    {
        if($_SESSION['uzblokuotas'] === '1' || $_SESSION['role'] > 0)
        {
            $this->redirect_to_another_page('index.php', 0);
        }

        $this->getView()->printRegisterForm();

        if (isset($_POST['registerBtn']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRepeat = $_POST['passwordRepeat'];
            $country = $_POST['country'];
            $address = $_POST['address'];
            $phoneNum = $_POST['phoneNum'];
            $realName = $_POST['realName'];
            $surname = $_POST['surname'];
            $birthDate = $_POST['birthDate'];
            $city = $_POST['city'];
            $favGame = $_POST['favGame'];
            $description = $_POST['description'];
            $discID = $_POST['discID'];
            $faceID = $_POST['faceID'];
            $instaID = $_POST['instaID'];
            $skypeID = $_POST['skypeID'];
            $sign = $_POST['sign'];
            $snapID = $_POST['snapID'];
            $website = $_POST['website'];
            $school = $_POST['school'];
            $degree = $_POST['degree'];


            if($this->getModel()->registerUser($username, $email, $password, $passwordRepeat, $country, $address, $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description,
                $discID, $faceID, $instaID, $skypeID, $sign, $snapID, $website, $school, $degree)) {
                $this->getView()->printSuccess('Registracija sėkminga!');
            } else {
                $this->getView()->printDanger('Klaida');
            }
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Registracija';
    }
}