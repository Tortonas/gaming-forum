<?php
class Model {
    private $server;
    private $dbName;
    private $dbUser;
    private $dbPassword;

    private $conn;

    function __construct()
    {
        $this->setDefaultSessions();
        date_default_timezone_set("Europe/Vilnius");
        $dbConfigFile = fopen("./src/database.config", "r") or die("Unable to open file!");
        $dbConfigFileString =  fgets($dbConfigFile);
        $dbConfigLines = explode(":", $dbConfigFileString);
        fclose($dbConfigFile);
        $this->server = $dbConfigLines[0];
        $this->dbUser = $dbConfigLines[1];
        $this->dbPassword = $dbConfigLines[2];
        $this->dbName = $dbConfigLines[3];
        $this->conn = new mysqli($this->server, $this->dbUser, $this->dbPassword, $this->dbName);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->updateLoginStatus();
    }

    public function updateLoginStatus()
    {
        if($_SESSION['id'] != "0")
        {
            $username = $this->secureInput($_SESSION['slapyvardis']);
            $password = $this->secureInput($_SESSION['slaptazodis']);

            $sql = "SELECT * FROM naudotojai WHERE slapyvardis='$username'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if($password == $row['slaptazodis'])
                    {
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['slapyvardis'] = $row['slapyvardis'];
                        $_SESSION['slaptazodis'] = $row['slaptazodis'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['uzblokuotas'] = $row['uzblokuotas'];
                        $_SESSION['uztildytas'] = $row['uztildytas'];
                        return true;
                    }
                    else
                    {
                        $this->logoutMe();
                        return false;
                    }
                }
            }
            else
            {
                $this->logoutMe();
            }
        }
    }

    public function setDefaultSessions()
    {
        // If ID is not set, it means we have to set default sessions, instead of rewriting whole function, I call logout() function. It does the same thing.
        if(!isset($_SESSION['id']) && empty($_SESSION['id']))
        {
            $this->logoutMe();
        }

        return true;
    }

    public function secureInput($input)
    {
        $input = mysqli_real_escape_string($this->conn, $input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function loginMe($username, $password)
    {
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);

        $sql = "SELECT * FROM naudotojai WHERE slapyvardis='$username'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if(password_verify($password, $row['slaptazodis']))
                {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['slapyvardis'] = $row['slapyvardis'];
                    $_SESSION['slaptazodis'] = $row['slaptazodis'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['uzblokuotas'] = $row['uzblokuotas'];
                    $_SESSION['uztildytas'] = $row['uztildytas'];
                    $date = date('Y-m-d H:i:s');
                    $sql = "UPDATE naudotojai SET paskutini_karta_prisijunges='$date' WHERE slapyvardis='$username'";
                    $this->conn->query($sql);
                    $sql = "INSERT INTO naudotoju_ipai (ip, paskutinis_prisijungimas, fk_naudotojas) VALUES (".$this->getUserIpAddr().", ".$date.", ".$row['id'].")";
                    $this->conn->query($sql);
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    public function logoutMe()
    {
        $_SESSION['id'] = "0";
        $_SESSION['slapyvardis'] = "0";
        $_SESSION['slaptazodis'] = "0";
        $_SESSION['email'] = "0";
        $_SESSION['role'] = "0";
        $_SESSION['uzblokuotas'] = "0";
        $_SESSION['uztildytas'] = "0";

        return true;
    }

    public function getData($table)
    {
        $table = $this->secureInput($table);
        $sql = "SELECT * FROM ".$table;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getDataByColumn($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."=".$value;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getDataByColumnFirst($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."=".$value;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                return $row;
            }
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getDataByString($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."='".$value."'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                return $row;
            }
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function updateDataOneColumn($table, $rowId,  $column, $newValue)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $newValue = $this->secureInput($newValue);
        $sql = "UPDATE ".$table." SET ".$column."='".$newValue."' WHERE id=".$rowId;

        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function updateUser($username, $newEmail, $newCountry, $newAddress,
                               $newPhoneNum, $newSurname, $newRealName, $newBirthDate, $newCity, $newFavGame,
                               $newDescription, $newDiscID, $newFaceID, $newInstaID, $newSkypeID, $newSign, $newSnapID,
                               $newWebsite, $newSchool, $newDegree)
    {
        $username = $this->secureInput($username);
        $newEmail = $this->secureInput($newEmail);
        $newCountry = $this->secureInput($newCountry);
        $newAddress = $this->secureInput($newAddress);
        $newPhoneNum = $this->secureInput($newPhoneNum);
        $newSurname = $this->secureInput($newSurname);
        $newRealName = $this->secureInput($newRealName);
        $newBirthDate = $this->secureInput($newBirthDate);
        $newCity = $this->secureInput($newCity);
        $newFavGame = $this->secureInput($newFavGame);
        $newDescription = $this->secureInput($newDescription);
        $newDiscID = $this->secureInput($newDiscID);
        $newFaceID = $this->secureInput($newFaceID);
        $newInstaID = $this->secureInput($newInstaID);
        $newSkypeID = $this->secureInput($newSkypeID);
        $newSign = $this->secureInput($newSign);
        $newSnapID = $this->secureInput($newSnapID);
        $newWebsite = $this->secureInput($newWebsite);
        $newSchool = $this->secureInput($newSchool);
        $newDegree = $this->secureInput($newDegree);

        $sql = "UPDATE naudotojai SET email='$newEmail',
         salis='$newCountry', adresas='$newAddress',
          telefono_nr='$newPhoneNum', pavarde='$newSurname', vardas='$newRealName',
           gimimo_data='$newBirthDate', miestas='$newCity', megstamiausias_zaidimas='$newFavGame',
            biografine_zinute='$newDescription', discord='$newDiscID', facebook='$newFaceID',
             instagram='$newInstaID', skype='$newSkypeID', parasas='$newSign',
              snapchat='$newSnapID', tinklalapis='$newWebsite', mokykla='$newSchool',
               aukstasis_issilavinimas='$newDegree' WHERE slapyvardis='$username'";

        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function removeData($table, $id)
    {
        $table = $this->secureInput($table);
        $id = $this->secureInput($id);
        $sql = "DELETE FROM ".$table." WHERE id=".$id;
        if(mysqli_query($this->conn, $sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function createNewCatalog($name, $date)
    {
        $name = $this->secureInput($name);
        $date = $this->secureInput($date);
        $sql = "INSERT INTO katalogai (pavadinimas, sukurimo_data) VALUES ('$name', '$date')";
        if(mysqli_query($this->conn, $sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function createNewTheme($name, $date, $userId, $catalogId, $text)
    {
        $name = $this->secureInput($name);
        $date = $this->secureInput($date);
        $userId = $this->secureInput($userId);
        $catalogId = $this->secureInput($catalogId);
        $text = $this->secureInput($text);
        $sqlCreateTheme = "INSERT INTO temos (pavadinimas, sukurimo_data, fk_naudotojas, fk_katalogas) VALUES ('$name', '$date', '$userId', '$catalogId')";
        if($this->conn->query($sqlCreateTheme))
        {
            $newThemeId =  $this->conn->insert_id;
            $sqlCreateThemeAnswer = "INSERT INTO temu_atsakymai (tekstas, sukurimo_data, fk_naudotojas, fk_tema) VALUES ('$text', '$date', '$userId', '$newThemeId')";
            if($this->conn->query($sqlCreateThemeAnswer))
            {
                return true;
            }
            else
            {
                echo mysqli_error($this->conn);
                return false;
            }
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function likeTheme($date, $userId, $themeAnsId)
    {
        $date = $this->secureInput($date);
        $userId = $this->secureInput($userId);
        $themeAnsId = $this->secureInput($themeAnsId);
        $sql = "INSERT INTO temu_pamegimai (sukurimo_data, fk_naudotojas, fk_temos_atsakymas) VALUES ('$date', '$userId', '$themeAnsId')";
        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getThemeListJoinedWithUsers($themeId)
    {
        $themeId = $this->secureInput($themeId);
        $sql = "SELECT pavadinimas, slapyvardis, temu_atsakymai.sukurimo_data, tekstas, temu_atsakymai.id FROM temu_atsakymai 
                JOIN naudotojai ON temu_atsakymai.fk_naudotojas = naudotojai.id 
                JOIN temos ON temos.id = temu_atsakymai.fk_tema
                WHERE fk_tema=".$themeId;
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) > 0)
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getLikeCountByThemeAnswerId($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM temu_pamegimai WHERE fk_temos_atsakymas=".$id;
        $result = $this->conn->query($sql);
        return $result->num_rows;
    }

    public function createNewThemeAnswer($text, $date, $userId, $themeId)
    {
        $text = $this->secureInput($text);
        $date = $this->secureInput($date);
        $userId = $this->secureInput($userId);
        $themeId = $this->secureInput($themeId);
        $sql = "INSERT INTO temu_atsakymai (tekstas, sukurimo_data, fk_naudotojas, fk_tema) VALUES ('$text', '$date', '$userId', '$themeId')";
        if($this->conn->query($sql))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function registerUser($username, $email, $password, $passwordRepeat, $country, $address, $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description,
                                 $discID, $faceID, $isntaID, $skypeID, $sign, $snapID, $website, $school, $degree)
    {
        $conn = $this->conn;

        $username = $this->secureInput($username);
        $email = $this->secureInput($email);
        $password = $this->secureInput($password);
        $passwordRepeat = $this->secureInput($passwordRepeat);
        $country = $this->secureInput($country);
        $address = $this->secureInput($address);
        $phoneNum = $this->secureInput($phoneNum);
        $realName = $this->secureInput($realName);
        $surname = $this->secureInput($surname);
        $birthDate = $this->secureInput($birthDate);
        $city = $this->secureInput($city);
        $favGame = $this->secureInput($favGame);
        $description = $this->secureInput($description);
        $discID = $this->secureInput($discID);
        $faceID = $this->secureInput($faceID);
        $isntaID = $this->secureInput($isntaID);
        $skypeID = $this->secureInput($skypeID);
        $sign = $this->secureInput($sign);
        $snapID = $this->secureInput($snapID);
        $website = $this->secureInput($website);
        $school = $this->secureInput($school);
        $degree = $this->secureInput($degree);

        if (empty($username) || empty($password) || empty($passwordRepeat) || empty($email)) {
            return false;
        } else {
            $sql = "SELECT * FROM naudotojai WHERE slapyvardis=? AND slaptazodis=?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                return false;
            } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                return false;
            } else if ($password !== $passwordRepeat) {
                return false;
            } else {
                $sql = "SELECT slapyvardis FROM naudotojai WHERE slapyvardis=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    return false;
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $resultCheck = mysqli_stmt_num_rows($stmt);
                    if ($resultCheck > 0) {
                        return false;
                    } else {
                        $sql = ("SET CHARACTER SET utf8");
                        $conn->query($sql);
                        $sql = "INSERT INTO naudotojai (id, slapyvardis, slaptazodis, email, registracijos_data, avataro_kelias, uzblokuotas,
                                            uztildytas, paskutini_karta_prisijunges, role, salis, adresas, telefono_nr, vardas, pavarde,
                                            gimimo_data, miestas, megstamiausias_zaidimas, biografine_zinute, discord, facebook, instagram,
                                            skype, parasas, snapchat, tinklalapis, mokykla, aukstasis_issilavinimas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            return false;
                        } else {
                            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                            $id = 0;
                            $role = 1;
                            $blocked = 0;
                            $muted = 0;
                            $path = NULL;
                            $date = date('Y-m-d H:i:s');
                            mysqli_stmt_bind_param($stmt, "isssssiisissssssssssssssssss", $id, $username, $hashedPwd, $email, $date, $path, $blocked, $muted, $date, $role, $country, $address, $phoneNum,
                                $realName, $surname, $birthDate, $city, $favGame, $description, $discID, $faceID, $isntaID, $skypeID, $sign, $snapID, $website, $school, $degree);
                            mysqli_stmt_execute($stmt);
                            return true;
                        }
                    }
                }
            }
        }
    }

    public function getCatalogListByPattern($pattern)
    {
        $pattern = $this->secureInput($pattern);
        $sql = "SELECT id, pavadinimas
                FROM katalogai
                WHERE katalogai.pavadinimas LIKE '%$pattern%'";
        if($result = $this->conn->query($sql))
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function getThemeListByPattern($pattern)
    {
        $pattern = $this->secureInput($pattern);
        $sql = "SELECT katalogai.pavadinimas, temos.pavadinimas, temu_atsakymai.tekstas, temu_atsakymai.sukurimo_data, temos.id
                FROM katalogai
                JOIN temos ON temos.fk_katalogas=katalogai.id
                JOIN temu_atsakymai ON temu_atsakymai.fk_tema=temos.id
                WHERE tekstas LIKE '%$pattern%' OR temos.pavadinimas LIKE '%$pattern%'";
        if($result = $this->conn->query($sql))
        {
            return $result;
        }
        else
        {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function checkIfUserHasLikedThisThemeAnswer($userId, $themeAnsId)
    {
        $userId = $this->secureInput($userId);
        $themeAnsId = $this->secureInput($themeAnsId);
        $sql = "SELECT *
                FROM temu_pamegimai
                WHERE temu_pamegimai.fk_naudotojas='$userId' AND temu_pamegimai.fk_temos_atsakymas='$themeAnsId'";

        $result = $this->conn->query($sql);
        if(mysqli_num_rows($result) > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function checkIfICanEditThisTheme($userId, $themeAnsId)
    {
        $userId = $this->secureInput($userId);
        $themeAnsId = $this->secureInput($themeAnsId);
        $sql = "SELECT * FROM
                temu_atsakymai
                WHERE temu_atsakymai.fk_naudotojas = '$userId' AND id = '$themeAnsId'";
        $result = $this->conn->query($sql);
        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function changePasswd($username, $password, $newPasswd, $repeatNewPasswd)
    {
        $conn = $this->conn;
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);
        $newPasswd = $this->secureInput($newPasswd);
        $repeatNewPasswd = $this->secureInput($repeatNewPasswd);

        $sql = "SELECT * FROM naudotojai WHERE slapyvardis='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if(password_verify($password, $row['slaptazodis']))
                {
                    if ($newPasswd !== $repeatNewPasswd) {
                        echo("<script>location.href = 'settings.php?error=passwcheck';</script>");
                        exit();
                    } else {
                        $sql = "SELECT slapyvardis FROM naudotojai WHERE slapyvardis=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo("<script>location.href = 'settings.php?error=sqlerror';</script>");
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $username);
                            mysqli_stmt_execute($stmt);
                            $sql = ("SET CHARACTER SET utf8");
                            $conn->query($sql);
                            $hashedPwd = password_hash($newPasswd, PASSWORD_DEFAULT);
                            $sql = "UPDATE naudotojai SET slaptazodis=? WHERE slapyvardis=?";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                return false;
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $username);
                                mysqli_stmt_execute($stmt);
                                return true;
                            }
                        }
                    }
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }
  
    function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function returnConn()
    {
        return $this->conn;
    }
}