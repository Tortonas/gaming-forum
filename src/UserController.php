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
            $isntaID = $_POST['isntaID'];
            $skypeID = $_POST['skypeID'];
            $sign = $_POST['sign'];
            $snapID = $_POST['snapID'];
            $website = $_POST['website'];
            $school = $_POST['school'];
            $degree = $_POST['degree'];


            $this->getModel()->registerUser($username, $email, $password, $passwordRepeat, $country, $address, $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description,
                $discID, $faceID, $isntaID, $skypeID, $sign, $snapID, $website, $school, $degree);
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Registracija';
    }
}