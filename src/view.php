<?php
class View {
    private $model;
    function __construct()
    {
        $this->model = new Model();
    }

    public function printNavbar($location)
    {
        echo '
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
              <a class="navbar-brand" href="index.php">Darbo skelbimai</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">';
                $this->printNavbarItem("Namai", "index.php", $location);
                $this->printNavbarItem("Skelbimai", "ads.php", $location);
                if($_SESSION['role'] == "0")
                {
                    $this->printNavbarItem("Registruotis", "register.php", $location);
                    $this->printNavbarItem("Prisijungti", "login.php", $location);
                }
                else
                {
                    if($_SESSION['role'] == 3)
                    {
                        $this->printNavbarItem("Narių sąrašas", "users.php", $location);
                    }
                    $this->printNavbarItem("Mano skelbimai", "myads.php", $location);
                    $this->printNavbarItem("Atsijungti", "logout.php", $location);
                }
                echo '</ul>
              </div>
            </div>
          </nav>
        ';
    }

    function printNavbarItem($name, $location, $globalLocation)
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

    function printRegisterForm()
    {
    	//value="'.$username.'"
    	if(isset($_POST['username']))
    		$username = htmlentities($_POST["username"]);
    	else
    		$username = "";

    	if(isset($_POST['email']))
    		$email = htmlentities($_POST["email"]);
    	else
    		$email = "";

    	if(isset($_POST['first_name']))
    		$first_name = htmlentities($_POST["first_name"]);
    	else
    		$first_name = "";

    	if(isset($_POST['last_name']))
    		$last_name = htmlentities($_POST["last_name"]);
    	else
    		$last_name = "";
        echo '
              <div class="main-content--small-margin">
        <form method="POST">
          <div class="form-group">
              <label for="inputEmail">Naudotojo vardas</label>
              <input name="username" type="text" class="form-control" id="inputEmail" placeholder="Naudotojo vardas" value="'.$username.'">
          </div>
          <div class="form-group">
              <label for="inputEmail">Elektroninis paštas</label>
              <input name="email" type="text" class="form-control" id="inputEmail" placeholder="El. Paštas" value="'.$email.'">
          </div>
          <div class="form-group">
              <label for="inputEmail">Vardas</label>
              <input name="first_name" type="text" class="form-control" id="inputEmail" placeholder="Vardas" value="'.$first_name.'">
          </div>
          <div class="form-group">
              <label for="inputEmail">Pavardė</label>
              <input name="last_name" type="text" class="form-control" id="inputEmail" placeholder="Pavardė" value="'.$last_name.'">
          </div>
          <div class="form-group">
              <label for="inputPassword">Slaptažodis</label>
              <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Slaptažodis">
          </div>
          <div class="form-group">
              <label for="inputPassword">Pakartokite slaptažodį</label>
              <input name="password_repeat" type="password" class="form-control" id="inputPassword" placeholder="Pakartokite slaptažodį">
          </div>
          <button type="submit" name="register_btn" class="btn btn-primary">Registruotis</button>
      </form>
    </div>
        ';
    }

    function printLoginForm()
    {
    	//value="'.$username.'"
    	if(isset($_POST['username']))
    		$username = htmlentities($_POST["username"]);
    	else
    		$username = "";
        echo '      <div class="main-content--small-margin">
        <form method="POST">
          <div class="form-group">
              <label for="inputEmail">Naudotojo vardas</label>
              <input type="text" class="form-control" id="inputEmail" name="username" placeholder="Naudotojo vardas" value="'.$username.'">
          </div>
          <div class="form-group">
              <label for="inputPassword">Slaptažodis</label>
              <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Slaptažodis">
          </div>
          <button type="submit" name="login_btn" class="btn btn-primary">Prisijungti</button>
      </form>
    </div>';
    }

    function printSuccess($text)
    {
        echo '<div class="alert alert-success" role="alert">'.$text.'</div>';
    }

    function printDanger($text)
    {
        echo '<div class="alert alert-danger" role="alert">'.$text.'</div>';
    }

    function printUsersPage($array)
    {
        echo '        <div class="main-content--small-margin">
            <h1>Naudotojų sąrašas:</h1>
            <ul class="list-group">';

        if ($array->num_rows > 0)
        {
            // output data of each row
            while($row = $array->fetch_assoc())
            {
                if($row['verified'] == "0")
                {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">'.$row['username'].'<span class="badge badge-danger badge-pill">Nepatvirtintas</span></li>';
                }
                else
                {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">'.$row['username'].'<span class="badge badge-primary badge-pill">Patvirtintas</span></li>';
                }
            }
        }
        else
        {
            $this->printSuccess("Sistemoje nėra paprasto tipo naudotojų!");
        }
        echo '</ul>
        </div>';
    }

    function printUsersPageDeleteForm()
    {
        echo '
        <form method="POST" class="main-content--small-margin">
          <div class="form-group">
              <label for="inputEmail">Įveskite naudotojo vardą statusą norite pakeisti.</label>
              <input type="text" class="form-control" id="inputEmail" name="username" placeholder="Naudotojo vardas">
          </div>
          <button type="submit" name="verify_btn" class="btn btn-primary">Pakeisti</button>
      </form>';
    }

    function printMyAdsContent($searchJobArr, $giveJobArr)
    {
        echo '      <div class="main-content--small-margin">
      <div class="list-group">
          <h1>"Ieškau darbo" - skelbimai</h1>';

        if ($searchJobArr->num_rows > 0)
        {
            while($row = $searchJobArr->fetch_assoc())
            {
               echo '          <a href="viewad.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">'.$row['title'].'</h5>
              <small>Galioja iki '.$row['valid_till'].'</small>
            </div>
            <p class="mb-1">'.$row['description'].'</p>
            <small>Alga '.$row['salary'].' eurų</small>
          </a>';
            }
        }
        else
        {
            echo "<h5>Neturite tokio tipo skelbimų.</h5>";
        }

        echo '<div class="list-group">
            <h1>"Siūlau darbą" - skelbimai</h1>';

        if ($giveJobArr->num_rows > 0)
        {
            while($row = $giveJobArr->fetch_assoc())
            {
                echo '          <a href="viewad.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">'.$row['title'].'</h5>
              <small>Galioja iki '.$row['valid_till'].'</small>
            </div>
            <p class="mb-1">'.$row['description'].'</p>
            <small>Alga '.$row['salary'].' eurų</small>
          </a>';
            }
        }
        else
        {
            echo "<h5>Neturite tokio tipo skelbimų.</h5>";
        }

        echo  '</div>
        </div>
      </div>';

    }

    function printSubmitNewAdButton($isActiveButton)
    {
        if($isActiveButton)
        {
            echo '<a href="createad.php"> <button type="submit" class="btn btn-primary main-content--small-margin">Sukurti naują skelbimą</button> </a>';
        }
        else
        {
            echo ' <button type="submit" class="btn btn-primary main-content--small-margin disabled">Sukurti naują skelbimą</button> <h5 style="color:red">Kaip administratorius patvirtins jūsų paskyrą, galėsite kelti skelbimus.</h5>';
        }
    }

    function printCreateNewAdForm()
    {
        //title, short description, text, salary, validtill, type

		//value="'.$username.'"
    	if(isset($_POST['title']))
    		$title = htmlentities($_POST["title"]);
    	else
    		$title = "";

    	if(isset($_POST['description']))
    		$description = htmlentities($_POST["description"]);
    	else
    		$description = "";

    	if(isset($_POST['text']))
    		$text = htmlentities($_POST["text"]);
    	else
    		$text = "";

    	if(isset($_POST['salary']))
    		$salary = htmlentities($_POST["salary"]);
    	else
    		$salary = "";

    	if(isset($_POST['valid_till']))
    		$valid_till = htmlentities($_POST["valid_till"]);
    	else
    		$valid_till = "";

        echo '<form method="POST" class="main-content--small-margin">
              <div class="form-group">
                <label for="exampleFormControlInput1">Vardas pavardė arba firmos pavadinimas</label>
                <input type="text" name="title" class="form-control" id="exampleFormControlInput1" placeholder="Petras Petraitis arba UAB `UAB` " value="'.$title.'">
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1">Skelbimo tipas</label>
                <select name="type" class="form-control" id="exampleFormControlSelect1">
                  <option value="1">Ieškau darbo</option>
                  <option value="2">Siūlau darbą</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Trumpas pristatymas</label>
                <input name="description" type="text" class="form-control" id="exampleFormControlInput1" placeholder="UAB `UAB` ieško darbuotojų. " value="'.$description.'">
              </div>
              <div class="form-group">
                <label for="exampleFormControlTextarea1">Pilnas skelbimo tekstas</label>
                <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3" >'.$text.'</textarea>
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Alga</label>
                <input name="salary" type="text" class="form-control" id="exampleFormControlInput1" placeholder="555" value="'.$salary.'">
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Iki kada galios skelbimas</label>
                <input name="valid_till" type="text" class="form-control" id="exampleFormControlInput1" placeholder="2019-12-01" value="'.$valid_till.'">
              </div>
              <button type="submit" name="createad_btn" class="btn btn-primary">Sukurti naują skelbimą</button>
            </form>';
    }

    function printGlobalAdsContent($searchJobArr, $giveJobArr)
    {
        echo '      <div class="main-content--small-margin">
      <div class="list-group">
          <h1>"Ieškau darbo" - skelbimai</h1>';

        if ($searchJobArr->num_rows > 0)
        {
            while($row = $searchJobArr->fetch_assoc())
            {
                echo '          <a href="viewad.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">';

                $viewCount = $this->model->getCountOfAdVisits($row['id']);
                if($_SESSION['role'] >= 2)
                {
                    echo '<h5 class="mb-1">'.$row['title'].' (id: '.$row['id'].') (Peržiūros: '.$viewCount.')</h5>';
                }
                else
                {
                    echo '<h5 class="mb-1">'.$row['title'].'</h5>';
                }

            echo '<small>Galioja iki '.$row['valid_till'].'</small>
            </div>
            <p class="mb-1">'.$row['description'].'</p>
            <small>Alga '.$row['salary'].' eurų</small>
          </a>';
            }
        }
        else
        {
            echo "<h5>Nėra tokio tipo skelbimų.</h5>";
        }

        echo '<div class="list-group">
            <h1>"Siūlau darbą" - skelbimai</h1>';

        if ($giveJobArr->num_rows > 0)
        {
            while($row = $giveJobArr->fetch_assoc())
            {
                $viewCount = $this->model->getCountOfAdVisits($row['id']);
                echo '          <a href="viewad.php?id='.$row['id'].'" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">';

                if($_SESSION['role'] >= 2)
                {
                    echo '<h5 class="mb-1">'.$row['title'].' (id: '.$row['id'].') (Peržiūros: '.$viewCount.')</h5>';
                }
                else
                {
                    echo '<h5 class="mb-1">'.$row['title'].'</h5>';
                }

                echo '<small>Galioja iki '.$row['valid_till'].'</small>
            </div>
            <p class="mb-1">'.$row['description'].'</p>
            <small>Alga '.$row['salary'].' eurų</small>
          </a>';
            }
        }
        else
        {
            echo "<h5>Nėra tokio tipo skelbimų.</h5>";
        }

        echo  '</div>
        </div>
      </div>';
    }

    function printGlobalAdsRemoveForm()
    {
        echo '
        <form method="POST" class="main-content--small-margin">
          <div class="form-group">
              <label for="inputEmail">Įveskite skelbimo ID kurį norite ištrinti.</label>
              <input type="text" class="form-control" id="inputEmail" name="ad_id" placeholder="Skelbimo ID">
          </div>
          <button type="submit" name="delete_btn" class="btn btn-danger">Ištrinti</button>
      </form>';
    }

    function printOneAd($adArr)
    {
        while($row = $adArr->fetch_assoc())
        {
            //Title, description, full text, salary, valid_till, tipas
            $type = null;
            if($row['type'] == 1)
                $type = "Ieškau darbo";
            else
                $type = "Siūlau darbą";

            $title = $row['title'];
            $description = $row['description'];
            $text = $row['text'];
            $valid_till = $row['valid_till'];
            $salary = $row['salary'];
            echo '<div class="main-content--small-margin">
                      <dl class="row">
                      <dt class="col-sm-3">Skelbimo tipas</dt>
                      <dd class="col-sm-9">'.$type.'</dd>
                    
                      <dt class="col-sm-3">Pranešėjas</dt>
                      <dd class="col-sm-9">'.$title.'</dd>
                      
                      <dt class="col-sm-3">Santrauka</dt>
                      <dd class="col-sm-9">'.$description.'</dd>
                    
                      <dt class="col-sm-3">Pilnas skelbimas</dt>
                      <dd class="col-sm-9">
                        <p>'.$text.'</p>
                      </dd>
                    
                      <dt class="col-sm-3">Alga</dt>
                      <dd class="col-sm-9">'.$salary.' eur</dd>

                      <dt class="col-sm-3">Skelbimas aktyvus iki</dt>
                      <dd class="col-sm-9">'.$valid_till.'</dd>
                </div>
            ';
        }
    }

    function printAdCommentForm()
    {
        echo '<form method="POST">
              <div class="form-group">
                <label for="exampleFormControlInput1">Jūsų vartotojo vardas</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" value="'.$_SESSION['username'].'" disabled>
              </div>


              <div class="form-group">
                <label for="exampleFormControlTextarea1">Komentaras</label>
                <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
              <button name="comment_btn" class="btn btn-primary">Komentuoti</button>
            </form>';
    }

    function printAdComment($commentArr)
    {
        if ($commentArr->num_rows > 0)
        {
            echo '<h3>Komentarai</h3>';
            while($row = $commentArr->fetch_assoc())
            {
                echo '<div class="list-group" style="margin-bottom: 20px">
                      <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                          <h5 class="mb-1">'.$row['first_name'].' '.$row['last_name'].'</h5>
                          <small class="text-muted">'.$row['date'].'</small>
                        </div>
                        <p class="mb-1">'.$row['text'].'</p>
                        <small class="text-muted">'.$row['email'].'</small>
                      </a>
                    </div>';
            }
        }
    }
}