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
                    $sql = "INSERT INTO naudotoju_ipai (ip, paskutinis_prisijungimas, fk_naudotojas) 
                            VALUES (".$this->getUserIpAddr().", ".$date.", ".$row['id'].") 
                            ON DUPLICATE KEY UPDATE 
                            ip=VALUES(ip),
                            paskutinis_prisijungimas=VALUES(paskutinis_prisijungimas)";
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

    public function getDataByColumnFirstToken($table, $column, $value)
    {
        $table = $this->secureInput($table);
        $column = $this->secureInput($column);
        $value = $this->secureInput($value);
        $sql = "SELECT * FROM ".$table." WHERE ".$column."='".$value."' ";
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

    public function getLastCreatedTheme()
    {
        $sql = "SELECT id FROM temos ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($sql);
        $id = $result->fetch_assoc();

        return $id['id'];
    }

    public function likeTheme($date, $userId, $themeAnsId)
    {
        $date = $this->secureInput($date);
        $userId = $this->secureInput($userId);
        $themeAnsId = $this->secureInput($themeAnsId);
        $sql = "INSERT INTO temu_pamegimai (sukurimo_data, fk_naudotojas, fk_temos_atsakymas) VALUES ('$date', '$userId', '$themeAnsId')";
        if($this->conn->query($sql))
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Pamėgta tema: $themeAnsId", $ip);
            return true;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Temos pamėgime erroras: ".mysqli_error($this->conn)."", $ip);
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
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Temos ataskymo erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return false;
        }
    }

    public function gallery_insert_img($img_name, $img_path, $img_format, $img_date, $user_id)
    {
        $img_name = $this->secureInput($img_name);
        $img_path = $this->secureInput($img_path);
        $img_format = $this->secureInput($img_format);

        $SQL_insert_request = "INSERT INTO `galerijos_nuotraukos` ( `pavadinimas`, `nuotraukos_kelias`, `formatas`, `sukurimo_data`, `fk_naudotojas`) 
                                    VALUES ( '".$img_name."', '".$img_path."', '".$img_format."', '".$img_date."', '".$user_id."');";

        $SQL_insert_request = $SQL_insert_request."SELECT id FROM galerijos_nuotraukos WHERE nuotraukos_kelias = '".$img_path."'";


        $result = $this->conn->multi_query($SQL_insert_request);

        if (mysqli_next_result($this->conn) > 0)
        {
            $result=mysqli_store_result($this->conn);
            $result=mysqli_fetch_row($result);
            $result = $result[0];
            return $result;
        }else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }

    }

    public function gallery_insert_tag($tag, $date)
    {
        $tag = $this->secureInput($tag);

        $SQL_check_existing = "SELECT IF(EXISTS(SELECT * From galerijos_nuotraukos_etikete WHERE pavadinimas = '".$tag."'),
          (SELECT id From galerijos_nuotraukos_etikete WHERE pavadinimas = '".$tag."' ),-1) as result";

        $result = $this->conn->query($SQL_check_existing);

        if (mysqli_num_rows($result) > 0)
        {
            $result = $result->fetch_assoc();
            $result = $result['result'];
            if ($result == -1)
            {
                $SQL_insert_tag = "INSERT INTO galerijos_nuotraukos_etikete (pavadinimas,sukurimo_data) VALUES ('".$tag."','".$date."');
                                    SELECT id FROM galerijos_nuotraukos_etikete WHERE pavadinimas = '".$tag."'";


                $result = $this->conn->multi_query($SQL_insert_tag);

                if (mysqli_next_result($this->conn) > 0)
                {
                    $result=mysqli_store_result($this->conn);
                    $result=mysqli_fetch_row($result);
                    $result = $result[0];
                    return $result;
                }else
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
                    echo mysqli_error($this->conn);
                    return -1;
                }

            }else
            {
                return $result;
            }

        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }


    }

    public function gallery_assign_tag_to_img($img_id, $tag_id)
    {
        $SQL_assign_tag_to_img = "INSERT INTO `galerijos_nuotrauku_etiketes` (`fk_nuotrauka`, `fk_etikete`) VALUES (".$img_id.", ".$tag_id.")";

        if($this->conn->query($SQL_assign_tag_to_img))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_get_all_imgs()
    {
        $SQL_get_all_imgs = "SELECT gallery.pavadinimas, gallery.nuotraukos_kelias, gallery.sukurimo_data, gallery.fk_naudotojas, likes.id as likes_id, likes.nuotraukos_pamegimas as likes, gallery.id as img_id
                                FROM galerijos_nuotraukos as gallery
                                JOIN galerijos_nuotraukos_pamegimai as likes
                                ON gallery.id = likes.fk_nuotrauka  
                                ORDER BY gallery.sukurimo_data DESC";

        $result = $this->conn->query($SQL_get_all_imgs);

        if ($result->num_rows > 0)
        {
            $images = [];
            while($row = $result->fetch_assoc())
            {
                array_push($images, $row);
            }
            return $images;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }
    }

    public function gallery_assign_likes_to_img($img_id, $date)
    {
        $SQL_assign_likes_to_img = "INSERT INTO `galerijos_nuotraukos_pamegimai` (`sukurimo_data`, `nuotraukos_pamegimas`, `fk_komentaras`, `fk_nuotrauka`) 
                                    VALUES ('".$date."', '0', NULL, '".$img_id."');";
        $SQL_assign_likes_to_img = $SQL_assign_likes_to_img . "SELECT id From galerijos_nuotraukos_pamegimai WHERE fk_nuotrauka = ".$img_id;

        $result = $this->conn->multi_query($SQL_assign_likes_to_img);

        if (mysqli_next_result($this->conn) > 0)
        {
            $result=mysqli_store_result($this->conn);
            $result=mysqli_fetch_row($result);
            $result = $result[0];
            return $result;
        }else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }
    }

    public function gallery_get_image($img_id)
    {
        $SQL_get_image = "SELECT gallery.id as img_id, gallery.pavadinimas as img_pav, gallery.nuotraukos_kelias, gallery.fk_naudotojas, likes.id as like_id, likes.nuotraukos_pamegimas as likes
                            FROM galerijos_nuotraukos as gallery
                            JOIN galerijos_nuotraukos_pamegimai as likes
                                ON gallery.id = likes.fk_nuotrauka
                            WHERE gallery.id = ".$img_id;

        $result = $this->conn->query($SQL_get_image);

        if ($result->num_rows > 0)
        {
            $result = $result->fetch_assoc();
            return $result;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }
    }

    public function gallery_add_image_comment($img_id, $user_id, $text, $date)
    {
        $text = $this->secureInput($text);
        $SQL_add_image_comment = "INSERT INTO `galerijos_nuotrauku_komentarai` (`tekstas`, `sukurimo_data`, `fk_naudotojas`, `fk_galerijos_nuotrauka`) 
                                    VALUES ('".$text."', '".$date."', '".$user_id."', '".$img_id."')";

        if($this->conn->query($SQL_add_image_comment))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_delete_image($img_id)
    {
        $SQL_delete_img = "DELETE FROM `galerijos_nuotraukos` WHERE `galerijos_nuotraukos`.`id` = ".$img_id;

        if($this->conn->query($SQL_delete_img))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_get_all_image_comments($img_id)
    {
        $SQL_get_all_image_comments = "SELECT comment.id, comment.tekstas, comment.sukurimo_data, comment.fk_galerijos_nuotrauka as img_id, naudotojai.id as user_id, naudotojai.slapyvardis as user_name
                                        FROM galerijos_nuotrauku_komentarai as comment
                                        JOIN naudotojai 
                                        ON comment.fk_naudotojas = naudotojai.id
                                        WHERE fk_galerijos_nuotrauka = ".$img_id."
                                        ORDER BY sukurimo_data ASC";

        $result = $this->conn->query($SQL_get_all_image_comments);

        if ($result->num_rows > 0)
        {
            $comments = [];
            while($row = $result->fetch_assoc())
            {
                array_push($comments, $row);
            }
            return $comments;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }


    }

    public function gallery_delete_image_comment($comment_id)
    {
        $SQL_delete_image_comment = "DELETE FROM `galerijos_nuotrauku_komentarai` 
                                        WHERE `galerijos_nuotrauku_komentarai`.`id` = ".$comment_id;

        if($this->conn->query($SQL_delete_image_comment))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_get_image_comment($comment_id)
    {
        $SQL_get_image_comment = "SELECT * 
                                    FROM galerijos_nuotrauku_komentarai
                                    WHERE id = ".$comment_id;

        $result = $this->conn->query($SQL_get_image_comment);

        if ($result->num_rows > 0)
        {
            $result = $result->fetch_assoc();
            return $result;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }
    }

    public function gallery_update_image_comment($comment_id, $comment)
    {
        $SQL_update_image_comment = "UPDATE galerijos_nuotrauku_komentarai 
                                        SET tekstas = '".$comment."' 
                                        WHERE id = ".$comment_id;

        if($this->conn->query($SQL_update_image_comment))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_increase_img_like_count($img_id)
    {
        $SQL_increase_like_count = "UPDATE galerijos_nuotraukos_pamegimai
                                    SET nuotraukos_pamegimas = nuotraukos_pamegimas + 1
                                    WHERE fk_nuotrauka = ".$img_id;

        if($this->conn->query($SQL_increase_like_count))
        {
            return false;
        }
        else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return true;
        }
    }

    public function gallery_get_images_by_name_date_format($img_name, $jpg, $jpeg, $png, $img_date)
    {
        $format = "";
        $img_name = $this->secureInput($img_name);

        if ($jpg == true)
        {
            $format = "'jpg'";
        }
        if ($jpeg == true)
        {
            if(strlen($format) > 0)
            {
                $format = $format.",";
            }
            $format = $format."'jpeg'";
        }
        if($png == true)
        {
            if(strlen($format) > 0)
            {
                $format = $format.",";
            }
            $format = $format."'png'";
        }

        $SQL = "SELECT gallery.id as img_id, gallery.pavadinimas as pavadinimas, gallery.nuotraukos_kelias, gallery.formatas as img_format, gallery.sukurimo_data as img_date, gallery.fk_naudotojas, 
                    ROUND(AVG(likes.nuotraukos_pamegimas),0) as likes, ROUND(AVG(likes.id),0) as like_id ,GROUP_CONCAT(tag.pavadinimas SEPARATOR ';') as tags
                
                FROM galerijos_nuotraukos as gallery
                JOIN galerijos_nuotraukos_pamegimai as likes
                    ON likes.fk_nuotrauka = gallery.id
                
                JOIN galerijos_nuotrauku_etiketes as tags
                    ON gallery.id = tags.fk_nuotrauka
                JOIN galerijos_nuotraukos_etikete as tag
                    ON tags.fk_etikete = tag.id ";

                $SQL = $SQL."WHERE gallery.sukurimo_data >= '".$img_date."' ";

                if ($img_name != false)
                {
                    $SQL = $SQL."AND gallery.pavadinimas LIKE '%".$img_name."%' ";
                }

                if (strlen($format) >= 3)
                {
                    $SQL = $SQL." AND gallery.formatas IN (".$format.") ";
                }

                $SQL = $SQL."GROUP BY gallery.id  
                ORDER BY `img_date` DESC";

        $result = $this->conn->query($SQL);

        if ($result->num_rows > 0)
        {
            $images = [];
            while($row = $result->fetch_assoc())
            {
                array_push($images, $row);
            }
            return $images;
        }
        else
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return -1;
        }
    }

    public function registerUser($username, $email, $password, $passwordRepeat, $country, $address, $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description,
                                 $discID, $faceID, $instaID, $skypeID, $sign, $snapID, $website, $school, $degree)
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
        $instaID = $this->secureInput($instaID);
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
                                $realName, $surname, $birthDate, $city, $favGame, $description, $discID, $faceID, $instaID, $skypeID, $sign, $snapID, $website, $school, $degree);
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
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
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
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
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


    public function changePasswdAdmin($id, $newPasswd, $repeatNewPasswd)
    {
        $conn = $this->conn;
        $id = $this->secureInput($id);
        $newPasswd = $this->secureInput($newPasswd);
        $repeatNewPasswd = $this->secureInput($repeatNewPasswd);

        $sql = "SELECT * FROM naudotojai WHERE id='$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                    if ($newPasswd !== $repeatNewPasswd) {
                        echo("<script>location.href = 'edituser.php?id=1&error=".$newPasswd."';</script>");
                        exit();
                    } else {
                        $sql = "SELECT slapyvardis FROM naudotojai WHERE slapyvardis=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo("<script>location.href = 'edituser.php?id=1&error=sqlerror';</script>");
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $username);
                            mysqli_stmt_execute($stmt);
                            $sql = ("SET CHARACTER SET utf8");
                            $conn->query($sql);
                            $hashedPwd = password_hash($newPasswd, PASSWORD_DEFAULT);
                            $sql = "UPDATE naudotojai SET slaptazodis=? WHERE id=?";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                return false;
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $id);
                                mysqli_stmt_execute($stmt);
                                return true;
                            }
                        }
                    }
            }
        }
    }

    function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function returnConn()
    {
        return $this->conn;
    }

    public function remindPassword($email)
    {
        $errors = [];
        $query = "SELECT email FROM naudotojai WHERE email='$email'";
        $results = mysqli_query($this->conn, $query);

        if (empty($email)) {
            array_push($errors, "Your email is required");
            return false;
        } else if (mysqli_num_rows($results) <= 0) {
            array_push($errors, "Sorry, no user exists on our system with that email");
            return false;
        }
        $token = bin2hex(random_bytes(50));
        $date = date('Y-m-d H:i:s');
        $expireDate = date("Y-m-d H:i:s", strtotime('+1 hours'));

        $query = "SELECT id FROM naudotojai WHERE email='$email'";
        $res = $this->conn->query($query);
        $row = $res->fetch_assoc();
        $id = $row['id'];
        $sql = "INSERT INTO slaptazodziu_priminikliai(tokenas, sukurimo_data, pabaigos_data, fk_naudotojas) VALUES ('$token', '$date', '$expireDate', '$id')";
        if($this->conn->query($sql))
        {

        } else {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
            echo mysqli_error($this->conn);
            return false;
        }

        $to = $email;
        $subject = "Susigrąžinkite slaptažodį ispgame.tk svetainėje";
        $msg = "Paspauskite šią <a href=\"http://ispgame.tk/newpass.php?token=" . $token . "\">nuorodą</a>, kad atnaujintumėte slaptažodį";
        $msg = wordwrap($msg, 70);
        $headers = "From: info@ispgame.tk";
        mail($to, $subject, $msg, $headers);
        header('location: index.php?emailsent=true');
        return true;
    }

    public function changeRemindedPass($pass, $passRepeat)
    {
        $newPass = $this->secureInput($pass);
        $newPassC = $this->secureInput($passRepeat);


        if(!isset($_GET['token']) && empty($_GET['token'])) {
            return false;
        }
        else{
            $token = $this->secureInput($_GET['token']);
            if (empty($newPass) || empty($newPassC)) {
                return false;
            }
            if ($newPass !== $newPassC) {
                return false;
            } else {
                $date = date('Y-m-d H:i:s');
                $sql = "SELECT tokenas, fk_naudotojas FROM slaptazodziu_priminikliai WHERE tokenas='$token' AND pabaigos_data >= '$date' LIMIT 1";
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $dbToken = $row['tokenas'];
                    $usrId = $row['fk_naudotojas'];

                    if ($token === $dbToken) {
                        $hashedPwd = password_hash($newPass, PASSWORD_DEFAULT);
                        $sql = "UPDATE naudotojai SET slaptazodis='$hashedPwd' WHERE id='$usrId'; DELETE slaptazodziu_priminikliai WHERE tokenas='$token' AND pabaigos_data >= '$date' LIMIT 1";
                        $results = mysqli_query($this->conn, $sql);
                        header('location: index.php?changepass=success');
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    public  function getIP()
    {
        $ip = '127.0.0.1';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function updateLog($text, $ip)
    {
        $text = $this->secureInput($text);
        $userId = 1;
        if ($_SESSION['id'] >= 1)
        {
            $userId = $this->secureInput($_SESSION['id']);
        }
        $sql = "INSERT INTO zurnalo_irasai (data, tekstas, ip, fk_naudotojas) VALUE (NOW(), '".$text."', '".$ip."', $userId) ";
        $results = mysqli_query($this->conn, $sql);
        $ip = $this->getModel()->getIP();
        $this->getModel()->updateLog("Duombzės užklausos erroras: ".mysqli_error($this->conn)."", $ip);
        echo mysqli_error($this->conn);
    }
}