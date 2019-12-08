<?php
class View
{

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
        if($_SESSION['uzblokuotas'] !== '1') {
            self::printNavbarItem("Forumas", "forum.php", $location);
            self::printNavbarItem("Galerija", "gallery.php", $location);
        }
        if ($_SESSION['role'] == "0") {
            self::printNavbarItem("Registruotis", "register.php", $location);
            self::printNavbarItem("Prisijungti", "login.php", $location);
        } else {
            if ($_SESSION['role'] >= 2 && $_SESSION['uzblokuotas'] !== '1') {
                self::printNavbarItem("Valdymas", "adminpanel.php", $location);
            }
            self::printNavbarItem("Nustatymai", "settings.php", $location);
            self::printNavbarItem("Atsijungti", "logout.php", $location);
        }
        if( $_SESSION['uzblokuotas'] !== '1') {
            echo '</ul>
            <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
                <input class="form-control mr-sm-2" type="search" name="searchText" placeholder="Raktažodis paieškai" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Ieškoti</button>
            </form>
            </div>
          </nav>
        ';
        }

    }

    private static function printNavbarItem($name, $location, $globalLocation)
    {
        if ($globalLocation == $location) {
            echo '
                  <li class="nav-item active">
                    <a class="nav-link" href="' . $location . '">' . $name . '</a>
                  </li>';
        } else {
            echo '
                  <li class="nav-item">
                    <a class="nav-link" href="' . $location . '">' . $name . '</a>
                  </li>';
        }
    }


    function printSuccess($text)
    {
        echo '<div class="alert alert-success" role="alert">' . $text . '</div>';
    }

    function printDanger($text)
    {
        echo '<div class="alert alert-danger" role="alert">' . $text . '</div>';
    }

    function printWarning($text)
    {
        echo '<div class="alert alert-warning" role="alert">' . $text . '</div>';
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

    public function printAdminPanel($users)
    {
        echo '
                <ul class="list-group">';
                 echo '<li class="list-group-item">
                   Prisijungusio valdytojo vardas: '.$_SESSION['slapyvardis'].'
                 </li>';
        if ($users) {

            while ($row = mysqli_fetch_assoc($users)) {
                echo'<li class="list-group-item">
                '.$row['slapyvardis'].' 
                <form class="btn">
                <input type="hidden" name="id" value="'.$row['id'].'">
                <button type="submit" name="uztildytas" value="1" class="btn btn-warning btn-sm">'; if($row['uztildytas'] == '0') { echo 'uztildyti'; } else { echo 'atitildyti'; }  echo '</button>
                </form>';
                if($_SESSION['role'] == 3) {
                    echo '
                <form class="btn">
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" name="uzblokuotas" value="1" class="btn btn-danger btn-sm">'; if($row['uzblokuotas'] == '0') { echo 'uzblokuoti'; } else { echo 'atblokuoti'; }  echo '</button>
                </form>
                <a href="edituser.php?id=' . $row['id'] . '"><button type="button" class="btn btn-primary btn-sm">Redaguoti naudotoją</button> </a>
                <form class="btn">
                <select class="btn btn-light" name="role">
                ';

                    if ($row['role'] == 1) {
                        echo '<option selected value = "1" > Naudotojas</option >
                          <option value = "2" > Moderatorius</option >
                          <option value = "3" > Administratorius</option >
                     </select>
                ';
                    } else if ($row['role'] == 2) {
                        echo '<option value = "1" > Naudotojas</option >
                          <option selected value = "2" > Moderatorius</option >
                          <option value = "3" > Administratorius</option >
                     </select>     
                ';
                    } else if ($row['role'] == 3) {
                        echo '<option value = "1" > Naudotojas</option >
                          <option value = "2" > Moderatorius</option >
                          <option selected value = "3" > Administratorius</option >
                     </select>
                ';
                    }

                    echo '
                
                <input type="hidden" name="id" value="' . $row['id'] . '">
                <button type="submit" class="btn btn-primary btn-sm">Pakeisti rolę</button>
                </form>
             </li>';
                }

            }
        }

        echo '</ul>';
    }

    // -- ADMIN PAGE VIEW END

    // -- FORUM PAGE START

    public function printForumFrontPage($catalogs)
    {
        $role = $_SESSION['role'];

        echo '<h1>Forumo kategorijos:</h1>
               <form method="POST">';

        if ($catalogs) {
            echo '<ul class="list-group">';
            while ($row = mysqli_fetch_assoc($catalogs)) {
                if ($role == 3) {
                    echo '<li class="list-group-item"><h2><a href="themes.php?id=' . $row['id'] . '">' . $row['pavadinimas'] . '</a> <button class="btn btn-danger btn-sm" type="submit" name="deleteButton" value="' . $row['id'] . '">Naikinti</button></h2></li>';
                } else {
                    echo '<li class="list-group-item"><h2><a href="themes.php?id=' . $row['id'] . '">' . $row['pavadinimas'] . '</a></h2></li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<h2>Nėra nei vienos forumo kategorijos!</h2>';
        }

        echo '</form>';

        if ($role == 3 && $_SESSION['uztildytas'] === '0') {
            echo '<br>
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
        echo '        <h1>' . $categoryName['pavadinimas'] . ' temos:</h1>
        <form method="POST">';

        if ($themeList) {
            while ($row = $themeList->fetch_assoc()) {
                echo '<ul class="list-group">';
                if ($_SESSION['role'] >= 2 ) {
                    echo '<li class="list-group-item"><h2> <a href="viewtheme.php?id=' . $row['id'] . '">' . $row['pavadinimas'] . '</a> <button class="btn btn-danger btn-sm" type="submit" name="deleteThemeBtn" value="'.$row['id'].'">Naikinti</button></h2></li>';
                } else {
                    echo '<li class="list-group-item"><h2> <a href="viewtheme.php?id=' . $row['id'] . '">' . $row['pavadinimas'] . '</a></h2></li>';
                }
                echo '</ul>';
            }
        }

        echo '</form>';

        if ($_SESSION['role'] > 0 && $_SESSION['uztildytas'] === '0') {
            echo '<br><a href="createtheme.php?id=' . $_GET['id'] . '"> <button type="button" class="btn btn-primary">Sukurti naują temą</button> </a>';
        }
    }

    public function printCreateTheme()
    {
        echo '        <h1>Sukurti naują temą:</h1>
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

    public function printViewTheme($themeAnswerList, $likeCount)
    {
        $showTitle = true;
        $likeCountIter = 0;
        if ($themeAnswerList) {
            while ($row = $themeAnswerList->fetch_assoc()) {
                if ($showTitle) {
                    echo '<h1>' . $row['pavadinimas'] . '</h1>';
                    $showTitle = false;
                }

                echo '
                <div class="theme-answer">
                    <form method="POST" class="form-group">
                        <h4>' . $row['slapyvardis'] . '</h4>
                        <h6>' . $row['sukurimo_data'] . '</h6>
                        <p>' . $row['tekstas'] . '</p>
                        ';

                if($_SESSION['role'] >= 1)
                {
                    echo '<button type="submit" name="likeBtn" value="' . $row['id'] . '" class="btn btn-primary btn-sm">
                            Pamėgti <span class="badge badge-light">' . $likeCount[$likeCountIter++] . '</span>
                        </button>';
                }
                else
                {
                    echo '<button type="submit" name="likeBtn" value="' . $row['id'] . '" class="btn btn-primary btn-sm" disabled>
                            Pamėgti <span class="badge badge-light">' . $likeCount[$likeCountIter++] . '</span>
                        </button>';
                }

                if ($_SESSION['role'] >= 3 || $_SESSION['slapyvardis'] == $row['slapyvardis']) {
                    echo '
                    <a href="edittheme.php?id=' . $row['id'] . '"> <button type="button" class="btn btn-primary btn-sm">Redaguoti</button> </a>
                        <button type="submit" name="deleteBtn" value=' . $row['id'] . ' class="btn btn-danger btn-sm">Naikinti</button>';
                }

                echo '
                    </form>
                </div>';
            }
        } else {
            echo '<h3>Tema neturi atsakymų! Temos autorius ištrynė savo pranešimą</h3>';
        }


        if ($_SESSION['role'] > 0 && $_SESSION['uztildytas'] === '0') {
            echo '
        <form method="POST">
            <div class="form-group">
                <label for="comment">Komentuoti:</label>
                <textarea class="form-control" name="text" rows="5" id="comment"></textarea>
                <button name="commentBtn" type="submit" class="btn btn-danger">Komentuoti</button>
            </div>
        </form>';
        }

    }

    public function printSearchPage($searchText)
    {
        echo '<form method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Raktažodis paieškai</label>
                <input type="text" class="form-control" id="exampleInputEmail1" value="' . $searchText . '" name="searchText" placeholder="Raktažodis paieškai">
            </div>
            <button type="submit" name="searchBtn" class="btn btn-primary">Ieškoti</button>
        </form>';
    }

    public function printEditTheme($content)
    {

        echo '        <h1>Redaguoti temos atsakymą</h1>
        <form method="POST">
        <div class="form-group">
            <label for="exampleFormControlTextarea3">Turinys</label>
            <textarea class="form-control" name="text" id="exampleFormControlTextarea3" rows="7">' . $content . '</textarea>
        </div>
        <button type="submit" name="editThemeBtn" class="btn btn-danger">Pateikti atnaujintą temą</button>
        </form>';

    }

    public function printEditUserAsAdmin($content)
    {
        echo ' <form method="POST" class="mainForm">
            <h1>Koreguojamas '.$content['slapyvardis'].' profilis</h1>
            <h1>Profilio nustatymai</h1>
            <input type="hidden" name="id" value="'.$content['id'].'">
            <div class="form-group">
                <label for="inputFor">Slapyvardis*</label>
                <input type="text" class="form-control" id="inputFor" value="'.$content['slapyvardis'].'" disabled>
            </div>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas*</label>
                <input type="email" class="form-control" id="inputFor" name="email" aria-describedby="emailHelp" placeholder="El. Paštas" value="'.$content['email'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Šalis</label>
                <input type="text" class="form-control" id="inputFor" name="salis" placeholder="Šalis" value="'.$content['salis'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Adresas</label>
                <input type="text" class="form-control" id="inputFor" name="adresas" placeholder="Adresas" value="'.$content['adresas'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Telefono numeris</label>
                <input type="text" class="form-control" id="inputFor" name="telefono_nr" placeholder="Telefono numeris" value="'.$content['telefono_nr'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Pavardė</label>
                <input type="text" class="form-control" id="inputFor" name="pavarde" placeholder="Pavardė" value="'.$content['pavarde'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Gimimo data</label>
                <input type="text" class="form-control" id="inputFor" name="gimimo_data" placeholder="Gimimo data" value="'.$content['gimimo_data'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Miestas</label>
                <input type="text" class="form-control" id="inputFor" name="miestas" placeholder="Miestas" value="'.$content['miestas'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Mėgstamiausias žaidimas</label>
                <input type="text" class="form-control" id="inputFor" name="megstamiausias_zaidimas" placeholder="Mėgstamiausias žaidimas" value="'.$content['megstamiausias_zaidimas'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Biografinė žinutė</label>
                <input type="text" class="form-control" id="inputFor" name="biografine_zinute" placeholder="Biografinė žinutė" value="'.$content['biografine_zinute'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Discord ID</label>
                <input type="text" class="form-control" id="inputFor" name="discord" placeholder="Discord ID" value="'.$content['discord'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Facebook</label>
                <input type="text" class="form-control" id="inputFor" name="facebook" placeholder="Facebook" value="'.$content['facebook'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Instagram</label>
                <input type="text" class="form-control" id="inputFor" name="instagram" placeholder="Instagram" value="'.$content['instagram'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Skype</label>
                <input type="text" class="form-control" id="inputFor" name="skype" placeholder="Skype" value="'.$content['skype'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Snapchat</label>
                <input type="text" class="form-control" id="inputFor" name="snapchat" placeholder="Snapchat" value="'.$content['snapchat'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Parašas</label>
                <input type="text" class="form-control" id="inputFor" name="parasas" placeholder="Parašas" value="'.$content['parasas'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Tinklalapis</label>
                <input type="text" class="form-control" id="inputFor" name="tinklalapis" placeholder="Tinklalapis" value="'.$content['tinklalapis'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Mokykla</label>
                <input type="text" class="form-control" id="inputFor" name="mokykla" placeholder="Mokykla" value="'.$content['mokykla'].'">
            </div>
            <div class="form-group">
                <label for="inputFor">Aukštasis išsilavinimas</label>
                <input type="text" class="form-control" id="inputFor" name="aukstasis_issilavinimas" placeholder="Aukštasis išsilavinimas" value="'.$content['aukstasis_issilavinimas'].'">
            </div>
                <button type="submit" name="request" value="visiDuomenys" class="btn btn-primary">Išsaugoti nustatymus</button>
        </form>

        <form method="POST" class="mainForm">
            <h1>Slaptažodžio keitimo forma</h1>
            <div class="form-group">
                <label for="inputFor">Naujas slaptažodis</label>
                <input type="password" class="form-control" name="slaptazodis" value="slaptazodis" id="inputFor" name="slaptazodis	" placeholder="Naujas slaptažodis">
            </div>
            <div class="form-group">
                <label for="inputFor">Pakartokite naują slaptažodį</label>
                <input type="password" class="form-control" name="slaptazodisPakartoti" value="slaptazodisPakartoti" id="inputFor" name="slaptazodisPakartoti" placeholder="Naujas slaptažodis">
            </div>
            <button type="submit" name="request" value="slaptazodisSubmit" class="btn btn-danger">Keisti slaptažodį</button>
        </form>';

    }

    public function printCatalogSearchResults($catalogList, $themeAnsList)
    {
        if ($catalogList->num_rows > 0)
        {
            echo '<h1>Atrinkti katalogai:</h1>
            <ul class="list-group">';

            while ($row = $catalogList->fetch_assoc())
            {
                echo '<a href="./themes.php?id='.$row['id'].'"><li class="list-group-item">'.$row['pavadinimas'].'</li></a>';
            }

            echo '</ul>';
        }
        else
        {
            echo '<h2>Rastu katalogu nera!</h2>';
        }

        if ($themeAnsList->num_rows > 0)
        {
            echo '<h1>Atrinktos temos su temu atsakymais:</h1>
            <div class="list-group">';

            while($row = $themeAnsList->fetch_assoc())
            {
                echo '  <a href="./viewtheme.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">'.$row['pavadinimas'].'</h5>
                </div>
                <p class="mb-1">'.$row['tekstas'].'</p>
              </a>';
            }

            echo '</div>';
        }
        else
        {
            echo '<h2>Rastu temu nera!</h2>';
        }

    }

    // -- FORUM PAGE END

    // -- Gallery Page View START

    public function print_Gallery_frontpage()
    {
        echo '<br>
                <form class="form-inline my-2 my-lg-0" method="POST" action="img_search.php">
                <button class="btn btn-primary" type="submit" name="search_img">Ieškoti nuotraukų</button>
            </form>
            <br>
            <br>';

    }

    public function print_Gallery_searchpage()
    {
        echo '<br><br><form method="post">
                    <input class="form-control" type="text" name="img_name" placeholder="Nuotraukos pavadinimas"><br>
                    <input class="form-control" type="text" name="img_tags" placeholder="fortnite;dance;gaming"
                    pattern="^([AaĄąBbCcČčDdEeĘęĖėFfGgHhIiĮįYyJjKkLlMmNnOoPpRrSsŠšTtUuŲųŪūVvZzŽžqQwWxX]{2,};?){0,}" title="Įveskite etiketę nors iš 2 raidžių ir atskirkite etiketes ; symboliu!"><br>
                    
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="jpg" value="1" id="defaultCheck1">
                      <label class="form-check-label" for="defaultCheck1">
                        .jpg
                      </label>
                    </div>
                    
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="jpeg" value="1" id="defaultCheck1">
                      <label class="form-check-label" for="defaultCheck1">
                        .jpeg
                      </label>
                    </div>
                    
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="png" value="1" id="defaultCheck1">
                      <label class="form-check-label" for="defaultCheck1">
                        .png
                      </label>
                    </div><br>
                    
                    <div class="form-group">
                            <label for="data">Nuotraukos įkelimo data</label>
                            <input type="date" max="'.date('Y-m-d').'" class="form-control" name="img_upload_date" required>
                    </div>
                    
                    <button type="submit" name="search_img_submit" value="true" class="btn btn-primary btn-sm">
                            Ieškoti
                        </button>
              </form><br>';
    }

    public function  print_gallery_image_upload()
    {
        echo '<br><h1>Nuotraukos įkėlimas</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="file-upload-form--medium">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Įkelti</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile01" name="photo"
                        aria-describedby="inputGroupFileAddon01" accept="image/gif, image/jpeg, image/png" required>
                        <label class="custom-file-label" for="inputGroupFile01">Pasirinkti nuotrauką</label>
                    </div>
                </div>
                <div class="form-group">
                <br>
                    <label for="inputFor">Etiketės</label>
                    <input type="text" class="form-control" id="inputFor" placeholder="fortnite;dance;gaming" name="tags"
                    pattern="^([AaĄąBbCcČčDdEeĘęĖėFfGgHhIiĮįYyJjKkLlMmNnOoPpRrSsŠšTtUuŲųŪūVvZzŽžqQwWxX]{2,};?){0,}" title="Įveskite etiketę nors iš 2 raidžių ir atskirkite etiketes ; symboliu!">
                </div>
                <div class="form-group">
                    <label for="comment">Pavadinimas:</label>
                    <input type="text" class="form-control" id="inputFor" placeholder="Nuotraukos pavadinimas" name="img_name"
                    pattern="^([AaĄąBbCcČčDdEeĘęĖėFfGgHhIiĮįYyJjKkLlMmNnOoPpRrSsŠšTtUuŲųŪūVvZzŽžqQwWxX][AaĄąBbCcČčDdEeĘęĖėFfGgHhIiĮįYyJjKkLlMmNnOoPpRrSsŠšTtUuŲųŪūVvZzŽžqQwWxX ]{2,})" title="Įveskite etiketę nors iš 2 raidžių ir atskirkite etiketes ; symboliu!" required>
                    <br>
                    <button type="submit" name="submitBtn" class="btn btn-danger">Įkelti</button>
                </div>
            </div>
        </form>';
    }

    public function print_gallery_images($image)
    {
        echo '<figure class="figure">
                <a href="viewphoto.php?img='.$image['img_id'].'"><img src="'.$image['nuotraukos_kelias'].'" id="imageInput" alt="fortnite dance" class="img-thumbnail rounded"></a>
                <figcaption class="figure-caption"><p name="pavadinimas">'.$image['pavadinimas'].'</p></figcaption>
                
                    <form method="post">';

        if ($_SESSION['role'] == 3 || $_SESSION['id'] == $image['fk_naudotojas'])
        {
            echo '<a href="#"><button class="btn btn-danger btn-sm" name="delete_img" type="submit" value="'.$image['img_id'].'">Ištrinti</button></a> '  ;
        }

        if ($_SESSION['role'] > 0)
        {
            echo '<button type="submit" name="like_button" value="'.$image['img_id'].'" class="btn btn-primary btn-sm">
                            Pamėgti <span class="badge badge-light">'.$image['likes'].'</span>
                        </button>';
        }
        if($_SESSION['role'] > 0 && $_SESSION['uztildytas'] !== '1') {
            echo '
                        <a href="viewphoto.php?img=' . $image['img_id'] . '">
                            <button class="btn btn-primary btn-sm" type="button">Komentuoti</button>
                        </a>';
        }

        echo '
                    </form>
            </figure>       ';
    }

    public function print_gallery_comment_section_image($image)
    {
        echo '<figure class="figure">
                <img src="'.$image['nuotraukos_kelias'].'" id="imageInput" alt="fortnite dance" class="img-thumbnail rounded">
                <figcaption class="figure-caption">'.$image['img_pav'].'</figcaption>
                <form method="post">';

        if ($_SESSION['role'] == 3 || $_SESSION['id'] == $image['fk_naudotojas'])
        {
            echo '<a href="#"><button class="btn btn-danger btn-sm" name="delete_img" type="submit" value="'.$image['img_id'].'">Ištrinti</button></a> ';
        }

        if($_SESSION['role'] > 0)
        {
            echo '<button type="submit" name="like_button" value="'.$image['img_id'].'" class="btn btn-primary btn-sm">
                            Pamėgti <span class="badge badge-light">'.$image['likes'].'</span>
                        </button>';
        }

        echo '</form>
            </figure>';
    }

    public function print_gallery_comment_section_comment_form()
    {
        echo '<form method="POST">
                <div class="form-group">
                    <label for="comment">Komentuoti:</label>
                    <textarea class="form-control" rows="5" id="comment" name="comment" required></textarea>
                    <button type="submit" class="btn btn-danger">Komentuoti</button>
                </div>
            </form>';
    }

    public function print_gallery_comment_section_comment($comment)
    {
        echo '<div class="theme-answer">
                <form method="POST" class="form-group">
                    <h4>'.$comment['user_name'].'</h4>
                    <h6>'.$comment['sukurimo_data'].'</h6>
                    <p>'.$comment['tekstas'].'</p>';

        if($_SESSION['role'] == 3 || $_SESSION['id'] == $comment['user_id'])
        {
            echo '<a href="editcomment.php?img='.$comment['img_id'].'&comment_id='.$comment['id'].'"> <button name="edit_comment" type="button" class="btn btn-primary btn-sm">Redaguoti</button> </a>
                        <button type="submit" name="delete_comment" value="'.$comment['id'].'" class="btn btn-danger btn-sm">Naikinti</button>';
        }

        echo '</form>
            </div>';
    }

    public function print_gallery_image_comment_edit($comment)
    {
        echo '<h1>Redaguoti nuotraukos komentarą</h1>
        
        <form method="post">
        
        <div class="form-group">
            <label for="exampleFormControlTextarea3">Turinys</label>
            <textarea class="form-control" id="exampleFormControlTextarea3" name="text" rows="7" required>'.$comment['tekstas'].'</textarea>
        </div>
        
        
            <button type="submit" name="edit_comment" value="'.$comment['id'].'" class="btn btn-danger">Pateikti atnaujintą atsakymą</button>
        </form>';
    }

    // -- Gallery Page View END

    // Rimvydo naudotoju posisteme pradzia

    public function printRegisterForm()
    {
        echo '        <form method=\'POST\' class=\'mainForm\'>
            <div class="form-group">
                <label for="inputFor">Slapyvardis*</label>
                <input type="text" name="username" class="form-control" id="inputFor" placeholder="Slapyvardis">
            </div>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas*</label>
                <input type="email" name="email" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas">
            </div>
            <div class="form-group">
                <label for="inputFor">Slaptažodis*</label>
                <input type="password" name="password" class="form-control" id="inputFor" placeholder="Slaptažodis">
            </div>
            <div class="form-group">
                <label for="inputFor">Pakartoti slaptažodį*</label>
                <input type="password" name="passwordRepeat" class="form-control" id="inputFor" placeholder="Pakartoti slaptažodį">
            </div>
            <div class="form-group">
                <label for="inputFor">Šalis</label>
                <input type="text" name="country" class="form-control" id="inputFor" placeholder="Šalis">
            </div>
            <div class="form-group">
                <label for="inputFor">Adresas</label>
                <input type="text" name="address" class="form-control" id="inputFor" placeholder="Adresas">
            </div>
            <div class="form-group">
                <label for="inputFor">Telefono numeris</label>
                <input type="text" name="phoneNum" class="form-control" id="inputFor" placeholder="Telefono numeris">
            </div>
            <div class="form-group">
                <label for="inputFor">Vardas</label>
                <input type="text" name="realName" class="form-control" id="inputFor" placeholder="Vardas">
            </div>
            <div class="form-group">
                <label for="inputFor">Pavardė</label>
                <input type="text" name="surname" class="form-control" id="inputFor" placeholder="Pavardė">
            </div>
            <div class="form-group">
                <label for="inputFor">Gimimo data</label>
                <input type="text" name="birthDate" class="form-control" id="inputFor" placeholder="Gimimo data">
            </div>
            <div class="form-group">
                <label for="inputFor">Miestas</label>
                <input type="text" name="city" class="form-control" id="inputFor" placeholder="Miestas">
            </div>
            <div class="form-group">
                <label for="inputFor">Mėgstamiausias žaidimas</label>
                <input type="text" name="favGame" class="form-control" id="inputFor" placeholder="Mėgstamiausias žaidimas">
            </div>
            <div class="form-group">
                <label for="inputFor">Biografinė žinutė</label>
                <input type="text" name="description" class="form-control" id="inputFor" placeholder="Biografinė žinutė">
            </div>
            <div class="form-group">
                <label for="inputFor">Discord ID</label>
                <input type="text" name="discID" class="form-control" id="inputFor" placeholder="Discord ID">
            </div>
            <div class="form-group">
                <label for="inputFor">Facebook</label>
                <input type="text" name="faceID" class="form-control" id="inputFor" placeholder="Facebook">
            </div>
            <div class="form-group">
                <label for="inputFor">Instagram</label>
                <input type="text" name="instaID" class="form-control" id="inputFor" placeholder="Instagram">
            </div>
            <div class="form-group">
                <label for="inputFor">Skype</label>
                <input type="text" name="skypeID" class="form-control" id="inputFor" placeholder="Skype">
            </div>
            <div class="form-group">
                <label for="inputFor">Snapchat</label>
                <input type="text" name="snapID" class="form-control" id="inputFor" placeholder="Snapchat">
            </div>
            <div class="form-group">
                <label for="inputFor">Parašas</label>
                <input type="text" name="sign" class="form-control" id="inputFor" placeholder="Parašas">
            </div>
            <div class="form-group">
                <label for="inputFor">Tinklalapis</label>
                <input type="text" name="website" class="form-control" id="inputFor" placeholder="Tinklalapis">
            </div>
            <div class="form-group">
                <label for="inputFor">Mokykla</label>
                <input type="text" name="school" class="form-control" id="inputFor" placeholder="Mokykla">
            </div>
            <div class="form-group">
                <label for="inputFor">Aukštasis išsilavinimas</label>
                <input type="text" name="degree" class="form-control" id="inputFor" placeholder="Aukštasis išsilavinimas">
            </div>
                <button type="submit" name="registerBtn" class="btn btn-primary">Registruotis</button>
        </form>';
    }

    public function printSettingsForm($username, $email, $country, $address, $phoneNum, $surname, $realName, $birthDate, $city, $favGame, $description,
                                      $discID, $faceID, $instaID, $skypeID, $sign, $snapID, $website, $school, $degree)
    {
        echo '
            <form method=\'POST\' class=\'mainForm\'>
                <h1>Profilio nustatymai</h1>
                <div class="form-group">
                    <label for="inputFor">Slapyvardis*</label>
                    <input type="text" class="form-control" id="inputFor" value="'.$username.'" disabled>
                </div>
                <div class="form-group">
                    <label for="inputFor">El. pašto adresas*</label>
                    <input type="email" name="email" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas" value="'.$email.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Šalis</label>
                    <input type="text" name="country" class="form-control" id="inputFor" placeholder="Šalis" value="'.$country.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Adresas</label>
                    <input type="text" name="address" class="form-control" id="inputFor" placeholder="Adresas" value="'.$address.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Telefono numeris</label>
                    <input type="text" name="phoneNum" class="form-control" id="inputFor" placeholder="Telefono numeris" value="'.$phoneNum.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Vardas</label>
                    <input type="text" name="realName" class="form-control" id="inputFor" placeholder="Vardas" value="'.$realName.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Pavardė</label>
                    <input type="text" name="surname" class="form-control" id="inputFor" placeholder="Pavardė" value="'.$surname.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Gimimo data</label>
                    <input type="text" name="birthDate" class="form-control" id="inputFor" placeholder="Gimimo data" value="'.$birthDate.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Miestas</label>
                    <input type="text" name="city" class="form-control" id="inputFor" placeholder="Miestas" value="'.$city.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Mėgstamiausias žaidimas</label>
                    <input type="text" name="favGame" class="form-control" id="inputFor" placeholder="Mėgstamiausias žaidimas" value="'.$favGame.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Biografinė žinutė</label>
                    <input type="text" name="description" class="form-control" id="inputFor" placeholder="Biografinė žinutė" value="'.$description.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Discord ID</label>
                    <input type="text" name="discID" class="form-control" id="inputFor" placeholder="Discord ID" value="'.$discID.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Facebook</label>
                    <input type="text" name="faceID" class="form-control" id="inputFor" placeholder="Facebook" value="'.$faceID.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Instagram</label>
                    <input type="text" name="$instaID" class="form-control" id="inputFor" placeholder="Instagram" value="'.$instaID.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Skype</label>
                    <input type="text" name="skypeID" class="form-control" id="inputFor" placeholder="Skype" value="'.$skypeID.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Snapchat</label>
                    <input type="text" name="snapID" class="form-control" id="inputFor" placeholder="Snapchat" value="'.$snapID.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Parašas</label>
                    <input type="text" name="sign" class="form-control" id="inputFor" placeholder="Parašas" value="'.$sign.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Tinklalapis</label>
                    <input type="text" name="website" class="form-control" id="inputFor" placeholder="Tinklalapis" value="'.$website.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Mokykla</label>
                    <input type="text" name="school" class="form-control" id="inputFor" placeholder="Mokykla" value="'.$school.'">
                </div>
                <div class="form-group">
                    <label for="inputFor">Aukštasis išsilavinimas</label>
                    <input type="text" name="degree" class="form-control" id="inputFor" placeholder="Aukštasis išsilavinimas" value="'.$degree.'">
                </div>
                    <button type="submit" name="saveSettingsBtn" class="btn btn-primary">Išsaugoti nustatymus</button>
            </form>';
    }

    public function printChangePasswordForm()
    {
        echo '<form method=\'POST\' class=\'mainForm\'>
                <h1>Slaptažodžio keitimo forma</h1>
                <div class="form-group">
                    <label for="inputFor">Dabartinis slaptažodis</label>
                    <input type="password" name="oldPasswd" class="form-control" id="inputFor" placeholder="Senas slaptažodis">
                </div>
                <div class="form-group">
                    <label for="inputFor">Naujas slaptažodis</label>
                    <input type="password" name="newPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <div class="form-group">
                    <label for="inputFor">Pakartokite naują slaptažodį</label>
                    <input type="password"  name="repeatNewPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <button type="submit" name="changePasswdBtn" class="btn btn-danger">Keisti slaptažodį</button>
            </form>';
    }

    public function printProfPic($picPath)
    {
        echo '<form method=\'POST\' class=\'mainForm\' enctype="multipart/form-data">
                <h1>Profilio nuotrauka</h1>
                <div class=\'profile-picture--200px\'>
                    <img src="'.$picPath.'" alt="default profile picture" class="img-thumbnail">
                    <div class="file-upload-form--small">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" name="uploadProfPic" class="input-group-text" id="inputGroupFileAddon01">Įkelti</button>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="profPicLoc" class="custom-file-input" id="inputGroupFile01"
                                aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Pasirinkti nuotrauką</label>
                            </div>
                        </div>
                    </div>
                </div>
              </form>';
    }

    public function printRemindPass()
    {
        echo '<form method=\'POST\' class=\'mainForm\'>
            <h1>Slaptažodžio priminimas!</h1>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas</label>
                <input type="email" name="email" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas">
            </div>
                <button type="submit" name="remindPassBtn" class="btn btn-primary">Siųsti priminimą</button>
        </form>';
    }

    public function printNewPassForm()
    {
        echo '<form method=\'POST\' class=\'mainForm\'>
                <h1>Slaptažodžio keitimo forma</h1>
                <div class="form-group">
                    <label for="inputFor">Naujas slaptažodis</label>
                    <input type="password" name="newPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <div class="form-group">
                    <label for="inputFor">Pakartokite naują slaptažodį</label>
                    <input type="password"  name="repeatNewPasswd" class="form-control" id="inputFor" placeholder="Naujas slaptažodis">
                </div>
                <button type="submit" name="newPassBtn" class="btn btn-danger">Keisti slaptažodį</button>
            </form>';
    }
    // Pabaiga


}