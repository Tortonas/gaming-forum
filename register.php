<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    
    <link rel="stylesheet" href="./style/stylesheet.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
      
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class='container'>
            <a class="navbar-brand" href="index.php">Gaming Forumas  </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Namai <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="forum.php">Forumas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">Galerija</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Prisijungti</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="register.php">Registracija</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Nustatymai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Atsijungti</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" href="adminpanel.php">Admin</a>
                    </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
                    <input class="form-control mr-sm-2" type="search" placeholder="Raktažodis paieškai" aria-label="Search">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Ieškoti</button>
                </form>
            </div>
        
        </div>
    </nav>
    <div class='container'>
        <form method='POST' class='mainForm'>
            <div class="form-group">
                <label for="inputFor">Slapyvardis*</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Slapyvardis">
            </div>
            <div class="form-group">
                <label for="inputFor">El. pašto adresas*</label>
                <input type="email" class="form-control" id="inputFor" aria-describedby="emailHelp" placeholder="El. Paštas">
            </div>
            <div class="form-group">
                <label for="inputFor">Slaptažodis*</label>
                <input type="password" class="form-control" id="inputFor" placeholder="Slaptažodis">
            </div>
            <div class="form-group">
                <label for="inputFor">Šalis</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Šalis">
            </div>
            <div class="form-group">
                <label for="inputFor">Adresas</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Adresas">
            </div>
            <div class="form-group">
                <label for="inputFor">Telefono numeris</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Telefono numeris">
            </div>
            <div class="form-group">
                <label for="inputFor">Pavardė</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Pavardė">
            </div>
            <div class="form-group">
                <label for="inputFor">Gimimo data</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Gimimo data">
            </div>
            <div class="form-group">
                <label for="inputFor">Miestas</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Miestas">
            </div>
            <div class="form-group">
                <label for="inputFor">Mėgstamiausias žaidimas</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Mėgstamiausias žaidimas">
            </div>
            <div class="form-group">
                <label for="inputFor">Biografinė žinutė</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Biografinė žinutė">
            </div>
            <div class="form-group">
                <label for="inputFor">Discord ID</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Discord ID">
            </div>
            <div class="form-group">
                <label for="inputFor">Facebook</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Facebook">
            </div>
            <div class="form-group">
                <label for="inputFor">Instagram</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Instagram">
            </div>
            <div class="form-group">
                <label for="inputFor">Skype</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Skype">
            </div>
            <div class="form-group">
                <label for="inputFor">Parašas</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Parašas">
            </div>
            <div class="form-group">
                <label for="inputFor">Tinklalapis</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Tinklalapis">
            </div>
            <div class="form-group">
                <label for="inputFor">Mokykla</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Mokykla">
            </div>
            <div class="form-group">
                <label for="inputFor">Aukštasis išsilavinimas</label>
                <input type="text" class="form-control" id="inputFor" placeholder="Aukštasis išsilavinimas">
            </div>
                <button type="button" class="btn btn-primary">Registruotis</button>
        </form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>