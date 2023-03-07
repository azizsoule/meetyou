<?php

require '../../../../config/Database.php';
require '../../../../models/Individu.php';

session_start();

$db = Database::connect();

$requette = $db->prepare("SELECT * FROM `message` WHERE id_discussion=? ORDER BY date DESC");
$requette->execute([$_GET['dis']]);

while ($message = $requette-> fetch()) {

  if($message['id_sender'] == $_SESSION['user']->id) {
    echo "

    <div class='sender1'>
      <p>{$message['date']}</p>
      <div class='u1 chat'><div class='text'>{$message['message']}</div></div>
    </div>

    ";
  }else {
    echo "

    <div class='sender2'>
      <p>{$message['date']}</p>
      <div class='u2 chat'><div class='text'>{$message['message']}</div></div>
    </div>

    ";
  }

}

 ?>
