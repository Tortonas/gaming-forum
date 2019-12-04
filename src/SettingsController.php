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
        if (isset($_SESSION['slapyvardis']))
        {
            $username = $this->getModel()->secureInput($_SESSION['slapyvardis']);
            $row = $this->getModel()->getDataByString('naudotojai', 'slapyvardis', $username);
            if (($picPath = $row['avataro_kelias']) === NULL)
            {
                $picPath = "img/profile pictures/default.png";
            }
            $this->getView()->printProfPic($picPath);

            if(isset($_POST['uploadProfPic']))
            {
                $controller = new model();
                $conn = $controller->returnConn();
                $username = $controller->secureInput($_SESSION['slapyvardis']);
                $targetDir = "img/profile pictures/";
                $temp = explode(".", $_FILES["profPicLoc"]["name"]);
                $fileName = $username . "_picture" . '.' . end($temp);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                if (!empty($_FILES["profPicLoc"]["name"]))
                {
                    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                    if (in_array($fileType, $allowTypes))
                    {
                        if (move_uploaded_file($_FILES["profPicLoc"]["tmp_name"], $targetFilePath))
                        {
                            $sql = "UPDATE naudotojai SET avataro_kelias=? WHERE slapyvardis=?";
                            $stmt = mysqli_stmt_init($conn);
                            if (mysqli_stmt_prepare($stmt, $sql))
                            {
                                mysqli_stmt_bind_param($stmt, "ss", $targetFilePath, $username);
                                mysqli_stmt_execute($stmt);
                                echo("<script>location.href = 'settings.php?picchange=success';</script>");
                                $this->printSuccess('Nuotrauka sėkmingai pakeista!');
                            } else {
                                $this->printDanger('Klaida');
                            }
                        } else {
                            $this->printDanger('Klaida');
                        }
                    } else {
                        $this->printDanger('Klaida');
                    }
                } else {
                    $this->printDanger('Klaida');
                }
            }


            $this->getView()->printSettingsForm($row['slapyvardis'], $row['email'], $row['salis'], $row['adresas'], $row['telefono_nr'],
                $row['pavarde'], $row['vardas'], $row['gimimo_data'], $row['miestas'], $row['megstamiausias_zaidimas'],
                $row['biografine_zinute'], $row['discord'], $row['facebook'], $row['instagram'], $row['skype'], $row['parasas'],
                $row['snapchat'], $row['tinklalapis'], $row['mokykla'], $row['aukstasis_issilavinimas']);
            $this->getView()->printChangePasswordForm();
        }

        if (isset($_POST['saveSettingsBtn']))
        {
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
            if ($this->getModel()->updateUser($username, $email, $country, $address,
                $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description, $discID, $faceID, $instaID,
                $skypeID, $sign, $snapID, $website, $school, $degree))
            {
                $this->getView()->printSuccess('Pakeitimai išsaugoti');
                $this->redirect_to_another_page('settings.php', 1);
            } else {
                $this->getView()->printDanger('Klaida');
            }
        }

        if (isset($_POST['changePasswdBtn']))
        {
            $username = $this->getModel()->secureInput($_SESSION['slapyvardis']);
            $oldPasswd = $this->getModel()->secureInput($_POST['oldPasswd']);
            $newPasswd = $this->getModel()->secureInput($_POST['newPasswd']);
            $repeatNewPasswd = $this->getModel()->secureInput($_POST['repeatNewPasswd']);
            if ($this->getModel()->changePasswd($username, $oldPasswd, $newPasswd, $repeatNewPasswd))
            {
                $this->getView()->printSuccess('Slaptažodis sėkmingai pakeistas');
            } else {
                $this->getView()->printDanger('Klaida');
            }
        }
    }

    public function getTitle()
    {
        echo 'Gaming Forum - Nustatymai';
    }
}