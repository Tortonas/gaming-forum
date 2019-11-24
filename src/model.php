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
}