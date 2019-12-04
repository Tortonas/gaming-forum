<?php


class SettingsController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    // settings.php
    public function printPageView()
    {
        if (isset($_SESSION['slapyvardis'])) {
            $username = $this->getModel()->secureInput($_SESSION['slapyvardis']);
            $row = $this->getModel()->getDataByString('naudotojai', 'slapyvardis', $username);
            $this->getView()->printSettingsForm($row['slapyvardis'], $row['email'], $row['salis'], $row['adresas'], $row['telefono_nr'],
                $row['pavarde'], $row['vardas'], $row['gimimo_data'], $row['miestas'], $row['megstamiausias_zaidimas'],
                $row['biografine_zinute'], $row['discord'], $row['facebook'], $row['instagram'], $row['skype'], $row['parasas'],
                $row['snapchat'], $row['tinklalapis'], $row['mokykla'], $row['aukstasis_issilavinimas']);
            $this->getView()->printChangePasswordForm();
        }

        if (isset($_POST['saveSettingsBtn'])) {
            $email = $this->getModel()->secureInput($_POST['email']);
            $country = $this->getModel()->secureInput($_POST['country']);
            $address = $this->getModel()->secureInput($_POST['address']);
            $phoneNum = $this->getModel()->secureInput($_POST['phoneNum']);
            $realName = $this->getModel()->secureInput($_POST['realName']);
            $surname = $this->getModel()->secureInput($_POST['surname']);
            $birthDate = $this->getModel()->secureInput($_POST['birthDate']);
            $city = $this->getModel()->secureInput($_POST['city']);
            $favGame = $this->getModel()->secureInput($_POST['favGame']);
            $description = $this->getModel()->secureInput($_POST['description']);
            $discID = $this->getModel()->secureInput($_POST['discID']);
            $faceID = $this->getModel()->secureInput($_POST['faceID']);
            $instaID = $this->getModel()->secureInput($_POST['$instaID']);
            $skypeID = $this->getModel()->secureInput($_POST['skypeID']);
            $sign = $this->getModel()->secureInput($_POST['sign']);
            $snapID = $this->getModel()->secureInput($_POST['snapID']);
            $website = $this->getModel()->secureInput($_POST['website']);
            $school = $this->getModel()->secureInput($_POST['school']);
            $degree = $this->getModel()->secureInput($_POST['degree']);
            $this->getModel()->updateUser($username, $email, $country, $address,
                $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description, $discID, $faceID, $instaID,
                $skypeID, $sign, $snapID, $website, $school, $degree);
        }

        if (isset($_POST['changePasswdBtn']))
        {
            $username = $this->getModel()->secureInput($_SESSION['slapyvardis']);
            $oldPasswd = $this->getModel()->secureInput($_POST['oldPasswd']);
            $newPasswd = $this->getModel()->secureInput($_POST['newPasswd']);
            $repeatNewPasswd = $this->getModel()->secureInput($_POST['repeatNewPasswd']);
            $this->getModel()->changePasswd($username, $oldPasswd, $newPasswd, $repeatNewPasswd);
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Nustatymai';
    }
}