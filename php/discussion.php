<?php

  include '../config/Database.php';
  include '../models/Individu.php';

  session_start();

  if (isset($_GET['id'])) {

    $bdd = Database::connect();

    $req = $bdd->prepare("SELECT * FROM discussion WHERE (id_individu1=? and id_individu2=?) or (id_individu1=? and id_individu2=?)");
    $req->execute([$_SESSION['user']->id,$_GET['id'],$_GET['id'],$_SESSION['user']->id]);

    if ($data = $req->fetch()) {
      header("location: ../user/dashboard/messages.php?id_discussion={$data['id_discussion']}&id_receiver={$_GET['id']}");
    }else {
      $req = $bdd->prepare("INSERT INTO discussion VALUES(null,?,?)");
      $req->execute([$_SESSION['user']->id,$_GET['id']]);

      $req = $bdd->prepare("SELECT * FROM discussion WHERE id_individu1=? and id_individu2=?");
      $req->execute([$_SESSION['user']->id,$_GET['id']]);

      $data = $req->fetch();

      header("location: ../user/dashboard/messages.php?id_discussion={$data['id_discussion']}&id_receiver={$_GET['id']}");
    }

  }

 ?>
