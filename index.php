<!-- PHP -->
<?php
require 'tools/function.php';

if(isset($_COOKIE["id"]) && isset($_COOKIE["key"])){
  $id = $_COOKIE["id"];
  $key = $_COOKIE["key"];

  $result = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
  $row = mysqli_fetch_assoc($result);

  if($key === hash("sha256", $row["username"])){
    $_SESSION["login"] = true;
  }
  
}

$ts = '1';
$publicKey = "fb51fa3f134a5857db5cdccfc05d3c1b";
$privateKey = '0c9944b25ae68104588b6c0d9b091ebf1136b59b';
$hash = hash('md5', $ts.$privateKey.$publicKey);
$hal = "comics";
$url = "http://gateway.marvel.com/v1/public/".$hal."?ts=".$ts."&apikey=".$publicKey."&hash=".$hash;

$getAPI = file_get_contents($url);
$result = json_decode($getAPI, true);
$resultForPolicy = json_decode($getAPI, true);
$result = $result["data"]["results"];
$name = "name";

if(isset($_GET["hal"]) ){
  if($_GET["hal"] == "comics" ){
    $getAPI = file_get_contents($url);
    $result = json_decode($getAPI, true);
    $result = $result["data"]["results"];
    $name = "title";
   
  } else if($_GET["hal"] == "characters" ){
    $hal = "characters";
    $url = "http://gateway.marvel.com/v1/public/".$hal."?ts=".$ts."&apikey=".$publicKey."&hash=".$hash;
    $getAPI = file_get_contents($url);
    $result = json_decode($getAPI, true);
    $result = $result["data"]["results"];
    $name = "name";
    
  } else if($_GET["hal"] == "series" ){
    $hal = "series";
    $url = "http://gateway.marvel.com/v1/public/".$hal."?ts=".$ts."&apikey=".$publicKey."&hash=".$hash;
    $getAPI = file_get_contents($url);
    $result = json_decode($getAPI, true);
    $result = $result["data"]["results"];
    $name = "title";
    
  }
}

// apabila user mencari somethings
if(isset($_POST["button"]) ){
  $search = urlencode($_POST["input"]);
  $getAPI = file_get_contents($url."&name=".$search);
  $result = json_decode($getAPI, true);
  $result = $result["data"]["results"];
};

if(isset($_POST["submit-sign"])){
  if(signin($_POST) > 0 ){
    echo "
      <script>
        alert('Sign-in Success!');
      </script>
    ";
    header("Location: index.php");
  }
  else{
    echo mysqli_error($conn);
  }
}
if(isset($_POST["submit-log"])){
  if(login($_POST) > 0 ){
    header("Location: index.php");
  }
  else{
    echo mysqli_error($conn);
  }
}

?>

<!-- HTML -->

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ryuriko Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/logo.png">
  </head>
  <body>

<div class="containers-fluid bg-dark">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
      <span class="navbar-brand">Ryuriko</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <?php if(!isset($_SESSION["login"])) : ?>
          <a class="nav-link" href="?log=login">Log-in</a>
          <a class="nav-link" href="?log=signin">Sign-in</a>
          <?php else: ?>
            <?php if(isset($_GET["hal"]) && isset($_SESSION["login"])) : ?>
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
            <!-- Comics -->
            <?php if($_GET["hal"]=="comics" && isset($_SESSION["login"])){ ?>
              <a class="nav-link active" href="?hal=comics">Comics</a>
            <?php } else{ ?>
              <a class="nav-link" href="?hal=comics">Comics</a>
            <?php } ?>
            <!-- Characters -->
            <?php if($_GET["hal"]=="characters" && isset($_SESSION["login"])){ ?>
              <a class="nav-link active" href="?hal=characters">Characters</a>
            <?php } else{ ?>
              <a class="nav-link" href="?hal=characters">Characters</a>
            <?php } ?>
            <!-- Series -->
            <?php if($_GET["hal"]=="series" && isset($_SESSION["login"])){ ?>
              <a class="nav-link active" href="?hal=series">Series</a>
            <?php } else{ ?>
              <a class="nav-link" href="?hal=series">Series</a>
            <?php } ?>
            <?php else: ?>
              <a class="nav-link" href="?logout=true">logout</a>
              <?php endif; ?>
              <?php endif; ?>
              <a class="nav-link" href="tools/about.php">About</a>
        </div>
      </div>
    </div>
  </nav>


<?php if(isset($_GET["hal"]) && isset($_SESSION["login"]) ) : ?>
  <!-- Halaman Items -->

  <!-- Search Input -->
  <div class="container-fluid">

  <div class="input-group mb-3 mt-3 ms-3">
    <form action="" method="post">
      <div class="d-flex flex-row">
        <input type="text" class="form-control" placeholder="Type Here" name="input" autocomplete="off">
        <button class="btn btn-secondary ms-1" type="submit" name="button">Cari</button>
      </div>
    </form>
  </div>

  <!-- Judul Hal -->
  <hr class="border border-3 border-secondary">
  <p class="fs-1 text-light text-center judul"><?= strtoupper($_GET["hal"]); ?></p>

  <!-- Card -->
  <div class="container">
    <div class="row mb-1">
      <?php foreach($result as $data) : ?>
        <div class="col-md-3 mb-2">
          <div class="card mt-4 me-1 border border-3 border-secondary" style="height: 95%;">
            <img class="card-img-top border-bottom border-3 border-secondary" src="<?= $data["thumbnail"]["path"].".".$data["thumbnail"]["extension"]; ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $data[$name]; ?></h5>
              <button type="button" class="btn btn-secondary detail"  data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?= $data["id"] ?>" data-hal="<?= $_GET["hal"] ?>">Detail</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  </div>
<?php elseif(isset($_GET["log"]) && !isset($_SESSION["login"]) ) : ?>
  <div class="containers-fluid text-center" id="log">
    <div class="card" id="log-form">
      <form class="fw-bold text-bg-secondary" action="" method="post" style="font-family: cursive;">
        <label class="mt-3" for="username">Username : </label>
        <input type="text" name="username" id="username" autocorrect="off" autofocus required> <br>
        <label class="mt-2" for="password">Password : </label>
        <input type="password" name="password" id="password" required>
      <?php if($_GET["log"] == "signin") : ?>
        <label class="mt-2" for="password1">Confirmation Password : </label>
        <input type="password" name="password1" id="password1" required> <br>
        <input type="checkbox" name="cookie" id="cookie">
        <label class="mt-2" for="cookie">Remember Me</label>
        <br>
        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit-sign">Signin</button>
      <?php elseif($_GET["log"] == "login") : ?>
        <br>
        <input type="checkbox" name="cookie" id="cookie">
        <label class="mt-2" for="cookie">Remember Me</label>
        <br>
        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit-log">Login</button>
        <?php endif; ?>
      </form>
    </div>  
  </div>
<?php elseif(isset($_GET["logout"])) : ?>
    <?php
      $_SESSION = [];
      session_unset();
      session_destroy();

      // hapus cookie
      setcookie("id", "", time() - 3600);
      setcookie("key", "", time() - 3600);

      header("Location: index.php");
      exit;
      ?>
<?php else : ?>
  <!-- Landing Page-->
  <div class="container-fluid" id="background">
    <div class="btn btn-info" id="letsgo">
      <?php if(isset($_SESSION["login"])){ ?>
        <a href="?hal=comics" class="fw-bold text-light text-decoration-none">Lets Go!</a>
      <?php } else{ ?>
          <a href="?log=login" class="fw-bold text-light text-decoration-none">Lets Go!</a>
      <?php } ?>
    </div>
  </div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
  </div>
    <div class="card-body">
      <blockquote class="blockquote mb-0 text-center text-bg-dark pt-2 pb-2">
        <p class="text-secondary fs-6"><?= $resultForPolicy["attributionText"]; ?></p>
        <!-- <footer class="blockquote-footer"><p title="Source Title">A well-known quote, contained in a blockquote element.</p></footer> -->
      </blockquote>
    </div>
  </div>

  
  
  
<!-- Tutup Container Utama -->
</div> 

  
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="library/js/jquery.js"></script>
    <script src="library/js/script.js"></script>
  </body>
</html>