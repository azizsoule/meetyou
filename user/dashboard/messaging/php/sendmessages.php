<?php

  require '../../../../config/Database.php';
  require '../../../../models/Individu.php';

  session_start();

  $db = Database::connect();

  if (!empty($_POST['message']) && trim($_POST['message'])!=""){

    $id_discussion = $_POST['id_discussion'];
    $id_sender = $_SESSION['user']->id;
    $id_receiver = $_POST['id_receiver'];
    $date = date('Y-m-d H:i:s');
    $message = $_POST['message'];

    $requette = $db->prepare("INSERT INTO message(id_discussion,id_sender,id_receiver,message,date) VALUES (?,?,?,?,?)");
    $requette->execute([$id_discussion,$id_sender,$id_receiver,$message,$date]);

  }

 ?>
