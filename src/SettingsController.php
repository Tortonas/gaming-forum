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
            $this->getView()->printSettingsForm($row['slapyvardis'], $row['email'], $row['slaptazodis'], $row['salis'], $row['adresas'], $row['telefono_nr'],
                $row['pavarde'], $row['vardas'], $row['gimimo_data'], $row['miestas'], $row['megstamiausias_zaidimas'],
                $row['biografine_zinute'], $row['discord'], $row['facebook'], $row['instagram'], $row['skype'], $row['parasas'],
                $row['snapchat'], $row['tinklalapis'], $row['mokykla'], $row['aukstasis_issilavinimas']);
            $this->getView()->printChangePasswordForm();
        }

        if (isset($_POST['saveSettingsBtn'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
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
            $instaID = $_POST['$instaID'];
            $skypeID = $_POST['skypeID'];
            $sign = $_POST['sign'];
            $snapID = $_POST['snapID'];
            $website = $_POST['website'];
            $school = $_POST['school'];
            $degree = $_POST['degree'];
            $this->getModel()->updateUser('naudotojai', $username, $email, $password, $country, $address,
                $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description, $discID, $faceID, $instaID,
                $skypeID, $sign, $snapID, $website, $school, $degree);

            /*if ($this->getModel()->updateUser('naudotojai', $username, $email, $password, $country, $address,
                $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description, $discID, $faceID, $instaID,
                $skypeID, $sign, $snapID, $website, $school, $degree))
            {

            }*/
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Nustatymai';
    }
}