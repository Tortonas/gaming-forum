<?php
class View {

    function __construct()
    {

    }

    // -- GLOBAL VIEW START --
    public static function printNavbar($location)
    {
        echo '<a class="navbar-brand" href="index.php">Gaming Forumas  </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">';
                self::printNavbarItem("Namai", "index.php", $location);
                self::printNavbarItem("Forumas", "forum.php", $location);
                self::printNavbarItem("Galerija", "gallery.php", $location);
                if($_SESSION['role'] == "0")
                {
                    self::printNavbarItem("Registruotis", "register.php", $location);
                    self::printNavbarItem("Prisijungti", "login.php", $location);
                }
                else
                {
                    if($_SESSION['role'] == 3)
                    {
                        self::printNavbarItem("Admin", "admin.php", $location);
                    }
                    self::printNavbarItem("Nustatymai", "settings.php", $location);
                    self::printNavbarItem("Atsijungti", "logout.php", $location);
                }
                echo '</ul>
            <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
                <input class="form-control mr-sm-2" type="search" placeholder="Raktažodis paieškai" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Ieškoti</button>
            </form>
            </div>
          </nav>
        ';
    }

    private static function printNavbarItem($name, $location, $globalLocation)
    {
        if($globalLocation == $location)
        {
            echo '
                  <li class="nav-item active">
                    <a class="nav-link" href="'.$location.'">'.$name.'</a>
                  </li>';
        }
        else
        {
            echo '
                  <li class="nav-item">
                    <a class="nav-link" href="'.$location.'">'.$name.'</a>
                  </li>';
        }
    }


    function printSuccess($text)
    {
        echo '<div class="alert alert-success" role="alert">'.$text.'</div>';
    }

    function printDanger($text)
    {
        echo '<div class="alert alert-danger" role="alert">'.$text.'</div>';
    }

    // -- GLOBAL VIEW END --

    // -- INDEX PAGE VIEW START --
    function printIndexPage()
    {
        echo '<h1>Sveiki atvykę į forumą!</h1>';
    }
    // -- INDEX PAGE VIEW END --

    // -- LOGIN PAGE VIEW START --

    public function printLoginPage()
    {
        echo '
        <form method="POST" class="mainForm">
            <div class="form-group">
                <label for="inputFor">Slapyvardis</label>
                <input name="username" type="text" class="form-control" id="inputFor" placeholder="Slapyvardis">
            </div>
            <div class="form-group">
                <label for="inputFor">Slaptažodis</label>
                <input name="password" type="password" class="form-control" id="inputFor" placeholder="Slaptažodis">
            </div>
                <button type="submit" name="loginBtn" class="btn btn-primary">Prisijungti</button>
                <a href="remindpass.php">Pamiršai slaptažodį?</a>
        </form>';
    }

    // -- LOGIN PAGE VIEW END --

    // -- ADMIN PAGE VIEW START

    public function printAdminPanel()
    {
        echo '<ul class="list-group">
            <li class="list-group-item">
                HENRIUX420 
                <button type="button" class="btn btn-warning btn-sm">Užtildyti</button>
                <button type="button" class="btn btn-danger btn-sm">Užblokuoti</button>
                <a href="edituser.php"> <button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
            </li>
            <li class="list-group-item">
                VALEEE                
                <button type="button" class="btn btn-warning btn-sm">Užtildyti</button>
                <button type="button" class="btn btn-danger btn-sm">Užblokuoti</button>
                <a href="edituser.php"> <button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
            </li>
            <li class="list-group-item">
                ELYGAAA
                <button type="button" class="btn btn-warning btn-sm">Užtildyti</button>
                <button type="button" class="btn btn-danger btn-sm">Užblokuoti</button>
                <a href="edituser.php"> <button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
            </li>
            <li class="list-group-item">
                RIMV3
                <button type="button" class="btn btn-warning btn-sm">Užtildyti</button>
                <button type="button" class="btn btn-danger btn-sm">Užblokuoti</button>
                <a href="edituser.php"> <button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
            </li>
            <li class="list-group-item">
                Random
                <button type="button" class="btn btn-warning btn-sm">Užtildyti</button>
                <button type="button" class="btn btn-danger btn-sm">Užblokuoti</button>
                <a href="edituser.php"> <button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
            </li>
        </ul>';
    }

    // -- ADMIN PAGE VIEW END

    // -- FORUM PAGE START

    public function printForumFrontPage($catalogs)
    {
        $role = $_SESSION['role'];

        echo '<h1>Forumo kategorijos:</h1>
               <form method="POST">';

        if($catalogs)
        {
            while($row = mysqli_fetch_assoc($catalogs))
            {
                if($role == 3)
                {
                    echo '<h2><a href="themes.php?id='.$row['id'].'">'.$row['pavadinimas'].'</a> <button class="btn btn-danger btn-sm" type="submit" name="deleteButton" value="'.$row['id'].'">Naikinti</button></h2>';
                }
                else
                {
                    echo '<h2><a href="themes.php?id='.$row['id'].'">'.$row['pavadinimas'].'</a></h2>';
                }
            }
        }
        else
        {
            echo '<h2>Nėra nei vienos forumo kategorijos!</h2>';
        }

        echo '</form>';

        if($role == 3)
        {
            echo '
        <form method="POST">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nauja kategorija</span>
                </div>
                <input type="text" name="catalogName" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                <button type="submit" name="createNewCatalog" class="btn btn-primary">Sukurti</button>
            </div>
        </form>';
        }
    }

    public function printForumThemes($themeList, $categoryName)
    {
        echo '        <h1>'.$categoryName['pavadinimas'].' temos:</h1>';

        if($themeList)
        {
            while($row = $themeList->fetch_assoc()) {
                if($_SESSION['role'] >= 3)
                {
                    echo '<h2> <a href="viewtheme.php?id=">'.$row['pavadinimas'].'</a> <button class="btn btn-danger btn-sm">Naikinti</button></h2>';
                }
                else
                {
                    echo '<h2> <a href="viewtheme.php?id=">'.$row['pavadinimas'].'</a></h2>';
                }
            }
        }

        if($_SESSION['role'] > 0)
        {
            echo '<a href="createtheme.php?id='.$_GET['id'].'"> <button type="button" class="btn btn-primary">Sukurti naują temą</button> </a>';
        }
    }

    public function printCreateTheme()
    {
        echo '        <h1>Sukurti naują temą - (kategorijos pavadinimas):</h1>
        <form method="POST">
        <div class="form-group">
            <label for="inputFor">Temos pavadinimas</label>
            <input name="themeName" type="text" class="form-control" id="inputFor" placeholder="Temos pavadinimas">
            <label for="exampleFormControlTextarea3">Turinys</label>
            <textarea name="themeText" class="form-control" id="exampleFormControlTextarea3" rows="7"></textarea>
        </div>

        
            <button type="submit" name="createThemeBtn" class="btn btn-danger">Sukurti naują temą</button>
        </form>';
    }

    // -- FORUM PAGE END
}