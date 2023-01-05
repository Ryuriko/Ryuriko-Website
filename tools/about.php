<?php 
session_start();
require 'function.php';

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ryuriko Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>


  <!-- Container Utama -->
<div class="containers-fluid" id="home">
  <!-- Navbar -->

  <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
      <span class="navbar-brand">Ryuriko</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
        <a class="nav-link" aria-current="page" href="../index.php">Home</a>
        <?php if(isset($_SESSION["login"])) : ?>
        <a class="nav-link" aria-current="page" href="../index.php?hal=comics">Comics</a>
        <a class="nav-link" aria-current="page" href="../index.php?hal=characters">Characters</a>
        <a class="nav-link" aria-current="page" href="../index.php?hal=series">Series</a>
        <?php endif; ?>
        <a class="nav-link active" aria-current="page" href="#home">About</a>
        </div>
      </div>
    </div>
  </nav>


  <div class="row justify-content-center pt-5">
    <div class="col-4">
        <img class="border border-5 border-secondary" src="../img/bio.jpeg" style="width: 95%;">
    </div>
    <div class="col-4 about-text">
        <p class="">Name: Ahmad Khoirul Umam</p>
        <p class="mt-3">Instagram: @ryuriko_</p>
    </div>
  </div>


<!-- Tutup Container Utama -->
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="../library/js/jquery.js"></script>
    <script src="../library/js/script.js"></script>
  </body>
</html>