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

        return true;
    }
}