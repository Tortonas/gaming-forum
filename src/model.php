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
            $username = $this->secureInput($_SESSION['username']);
            $password = $this->secureInput($_SESSION['password']);

            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    if($password == $row['password'])
                    {
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['password'] = $row['password'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['verified'] = $row['verified'];
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
        if(!isset($_SESSION['id']) && empty($_SESSION['id']))
        {
            $_SESSION['id'] = "0";
        }
        if(!isset($_SESSION['username']) && empty($_SESSION['username']))
        {
            $_SESSION['username'] = "0";
        }
        if(!isset($_SESSION['email']) && empty($_SESSION['email']))
        {
            $_SESSION['email'] = "0";
        }
        if(!isset($_SESSION['password']) && empty($_SESSION['password']))
        {
            $_SESSION['password'] = "0";
        }
        if(!isset($_SESSION['first_name']) && empty($_SESSION['first_name']))
        {
            $_SESSION['first_name'] = "0";
        }
        if(!isset($_SESSION['last_name']) && empty($_SESSION['last_name']))
        {
            $_SESSION['last_name'] = "0";
        }
        if(!isset($_SESSION['role']) && empty($_SESSION['role']))
        {
            $_SESSION['role'] = "0";
        }
        if(!isset($_SESSION['verified']) && empty($_SESSION['verified']))
        {
            $_SESSION['verified'] = "0";
        }
    }

    function secureInput($input)
    {
        $input = mysqli_real_escape_string($this->conn, $input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function registerUser($username, $email, $password, $first_name, $last_name)
    {
        $username = $this->secureInput($username);
        $email = $this->secureInput($email);
        $password = $this->secureInput($password);
        $first_name = $this->secureInput($first_name);
        $last_name = $this->secureInput($last_name);
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, role, verified) VALUES ('$username', '$email', '$password', '$first_name', '$last_name', '1', '0');";
        mysqli_query($this->conn, $sql);
    }

    public function loginMe($username, $password)
    {
        $username = $this->secureInput($username);
        $password = $this->secureInput($password);

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if(password_verify($password, $row['password']))
                {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['password'] = $row['password'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['verified'] = $row['verified'];
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
        $_SESSION['username'] = "0";
        $_SESSION['email'] = "0";
        $_SESSION['password'] = "0";
        $_SESSION['first_name'] = "0";
        $_SESSION['last_name'] = "0";
        $_SESSION['role'] = "0";
        $_SESSION['verified'] = "0";
    }

    public function getUserList()
    {
        $sql = "SELECT * FROM users WHERE role=1";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function canIRegisterThisName($username)
    {
    	$username = $this->secureInput($username);
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function changeUserVerification($username)
    {
        $username = $this->secureInput($username);
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if($row['verified'] == 0)
                {
                    $sqlUpdate = "UPDATE users SET verified='1' WHERE username='$username'";
                    $this->conn->query($sqlUpdate);
                }
                else
                {
                    $sqlUpdate = "UPDATE users SET verified='0' WHERE username='$username'";
                    $this->conn->query($sqlUpdate);
                }
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function getSearchJobList($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM ads WHERE fk_user='$id' AND type='1' AND hidden='0' AND valid_till>=NOW()";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getGivingJobList($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM ads WHERE fk_user='$id' AND type='2' AND hidden='0' AND valid_till>=NOW()";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getSearchJobListGlobal()
    {
        $sql = "SELECT * FROM ads WHERE type='1' AND hidden='0' AND valid_till>=NOW()";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getGivingJobListGlobal()
    {
        $sql = "SELECT * FROM ads WHERE type='2' AND hidden='0' AND valid_till>=NOW()";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function createNewAd($title, $type, $description, $text, $salary, $valid_till, $user_id)
    {
        $title = $this->secureInput($title);
        $type = $this->secureInput($type);
        $description = $this->secureInput($description);
        $text = $this->secureInput($text);
        $salary = $this->secureInput($salary);
        $valid_till = $this->secureInput($valid_till);

        $sql = "INSERT INTO ads (title, type, description, text, salary, valid_till, fk_user) VALUES ('$title', '$type', '$description', '$text', '$salary', '$valid_till', '$user_id')";
        return $this->conn->query($sql);
    }

    public function hideAd($id)
    {
        $id = $this->secureInput($id);
        $sql = "UPDATE ads SET hidden='1' WHERE id='$id'";
        $this->conn->query($sql);
    }

    public function checkIfAdExistsById($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM ads WHERE id='$id' AND hidden='0' AND valid_till>=NOW()";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0)
        {
            return true;
        }
        return false;
    }

    public function getAdContentById($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM ads WHERE id='$id'";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getCommentsById($id)
    {
        $id = $this->secureInput($id);
        $sql = "SELECT * FROM `ad_comments`
                JOIN users ON users.id = ad_comments.fk_user
                WHERE fk_ad='$id'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function createNewAdComment($commentText, $userId, $adId)
    {
        //text fk_ad fk_user date
        $commentText = $this->secureInput($commentText);
        $userId = $this->secureInput($userId);
        $adId = $this->secureInput($adId);
        $currentDate = date('Y-m-d');
        $sql = "INSERT INTO ad_comments (text, fk_ad, fk_user, date) VALUES ('$commentText', '$adId', '$userId', '$currentDate')";

        $this->conn->query($sql);
    }

    public function getCountOfAdVisits($adId)
    {
        $sql = "SELECT * FROM ad_views WHERE fk_ad='$adId'";
        $result = $this->conn->query($sql);
        return $result->num_rows;
    }

    public function haveIViewedThisAd($userId, $adId)
    {
        $userId = $this->secureInput($userId);
        $adId = $this->secureInput($adId);
        $sql = "SELECT * FROM ad_views WHERE fk_ad='$adId' AND fk_user='$userId'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function viewThisAd($userId, $adId)
    {
        $sql = "INSERT INTO ad_views (fk_ad, fk_user) VALUES ('$adId', '$userId')";
        $this->conn->query($sql);
    }
}