<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';
  include '../../name.php';

  session_start();

  if (isset($_SESSION['user'])) {

    ?>

    <!DOCTYPE html>
    <html lang="en" >
    <head>
      <meta charset="UTF-8">
      <title><?php echo $name; ?> | Messages !</title>
      <link rel="stylesheet" href="../../css/messages.css">
      <script type="text/javascript" src="messaging/jquery/jquery.js"></script>
      <?php

        if (isset($_GET['dis']) && isset($_GET['rec'])) {
          ?>

          <script type="text/javascript" src="messaging/js/get.js"></script>

          <?php
        }

       ?>
      <script type="text/javascript" src="messaging/js/send.js"></script>
    </head>
    <body>

      <header>
        <?php include '../../stuffs/header.php'; ?>
      </header>

    <div class="main">
      <!--Liste des Messages  -->
      <div class="discussions">
        <div class="header">Discussions</div>
        <div class="msg-list">

          <?php

            $bdd = Database::connect();

            $req = $bdd->prepare("SELECT * FROM discussion WHERE id_individu1=? OR id_individu2=?");
            $req->execute([$_SESSION['user']->id,$_SESSION['user']->id]);

            $id_r = null;

            while ($discussion = $req->fetch()) {

              if ($_SESSION['user']->id == $discussion['id_individu1']) {
                $id_r = $discussion['id_individu2'];
              } else {
                $id_r = $discussion['id_individu1'];
              }

              $req2 = $bdd->prepare("SELECT nom,prenoms,photo FROM individu WHERE id=?");
              $req2->execute([$id_r]);

              $nomPrenom = $req2->fetch();

              if ($nomPrenom['photo'] == null) {
                echo "

                <a href='messages.php?dis={$discussion['id_discussion']}&rec={$id_r}'>
                  <div class='container'>
                    <div class='photo photo-msg'>
                      <img src='../../images/user.webp' alt='img'>
                    </div>
                    <div class='cont-info'>
                      <h4>{$nomPrenom['nom']} {$nomPrenom['prenoms']}</h4>
                      <span>Message</span>
                    </div>
                  </div>
                </a>

                ";
              } else {
                echo "

                <a href='messages.php?dis={$discussion['id_discussion']}&rec={$id_r}'>
                  <div class='container'>
                    <div class='photo photo-msg'>
                      <img src='../../images/profiles/".$nomPrenom['photo']."' alt='img'>
                    </div>
                    <div class='cont-info'>
                      <h4>{$nomPrenom['nom']} {$nomPrenom['prenoms']}</h4>
                      <span>Message</span>
                    </div>
                  </div>
                </a>

                ";
              }

            }

           ?>

        </div>
      </div>
      <!--Fin Liste des Messages  -->

      <!--Début Message-Box  -->
      <?php

        if (isset($_GET['dis']) && isset($_GET['rec'])) {
          ?>

          <div class="msg-box">
            <div class="header">

              <?php

                $req2 = $bdd->prepare("SELECT nom,prenoms FROM individu WHERE id=?");
                $req2->execute([$_GET['rec']]);

                $nomPrenom = $req2->fetch();

                echo "{$nomPrenom['nom']} {$nomPrenom['prenoms']}";

               ?>

            </div><!-- Header : Jerry -->
                <!-- Corps de la discussion -->
              <div class="messages">



              </div>
              <!-- Fin Corps de la discussion -->
            <form class="msg-send" method="post">
              <input class="id_d" type="text" id="id_dis" name="id_discussion" value=<?php echo "{$_GET['dis']}"; ?> hidden>
              <input class="id_r" type="text" name="id_receiver" value=<?php echo "{$_GET['rec']}"; ?> hidden>
              <input class="msg" name="message" type="text" id="msg" placeholder="Ecrivez votre message ..." />
              <button type="submit" id="send"><img src="../../images/send1.png" alt="envoyer"></button>
            </form>
          </div>

          <?php
        } else {
          ?>

          <div class="msg-box">
              <div class="messages">

                <center>

                  <h1 style="direction: ltr; transform: rotate(180deg)">Veuillez choisir une discussion !</h1>

                  <img height="200" width="300" style="direction: ltr; transform: rotate(180deg)" src="../../images/message.png" alt="">

                </center>

              </div>
          </div>

          <?php
        }

       ?>
      <!--Fin Liste des Messages  -->
    </div>

    </body>
    </html>

    <?php

  } else {
    header('location: ../../');
  }

 ?>
