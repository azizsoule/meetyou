
<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';

  session_start();

  if (isset($_SESSION['user'])) {
    $bdd = Database::connect();
    $req = $bdd->prepare("SELECT * FROM individu WHERE id=?");
    $req->execute([$_SESSION['user']->id]);
    $ind = $req->fetch();
    $user = new Individu($ind);

    ?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title>
          <?php
              include("../../name.php");
              echo $name." | Welcome !";
          ?>
        </title>

        <link rel="stylesheet" href="../../css/welcome.css">
      </head>
      <body>
        <?php include '../../stuffs/header.php'; ?>
        <div class="body">
          <div class="bg-image">
            <!-- Nom de l'utilisateur ici -->
            <div class="name">
              <p>Bienvenue,&nbsp<span><?php echo $user->nom; ?></span></p>
            </div>
            <!-- Photo user -->
            <div class="photo">
              <img src="../../images/a5b832097e960f42da8d6b1063187ea0.png" alt="photoUser">
            </div>
          </div>

          <div class="main">
            <!-- Overview profis compatibles -->
            <div class="msg-box" id="prof_comp">
              <div class="header">Profils compatibles</div>
                <!-- Photos profils compatibles -->
              <div class="container">
                <div class="photo photo-match">
                  <img src="../../images/main3.png" alt="">
                  <div class="info_match">
                    <p>Lucie,&nbsp<span>19</span></p>
                  </div>
                </div>
                <div class="photo photo-match">

                </div>
                <div class="photo photo-match">

                </div>
              </div>
              <div class="consul"><a href="matches.php">Consulter vos profils compatibles</a></div>
            </div>
            <!--Fin Overview profis compatibles -->

            <!-- Overview Messages -->
            <div class="msg-box" id="messages">
              <div class="header">Discussions</div>
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

                  $req2 = $bdd->prepare("SELECT nom,prenoms FROM individu WHERE id=?");
                  $req2->execute([$id_r]);

                  $nomPrenom = $req2->fetch();

                  echo "

                  <a href='messages.php?id_discussion={$discussion['id_discussion']}&id_receiver={$id_r}'>
                    <div class='container'>
                      <div class='photo photo-msg'>
                        <img src='../../images/main3.png' alt='img'>
                      </div>
                      <div class='cont-info'>
                        <h4>{$nomPrenom['nom']} {$nomPrenom['prenoms']}</h4>
                        <span>Message</span>
                      </div>
                    </div>
                  </a>

                  ";
                }

               ?>
            <!--Fin Overview Messages -->
          </div>
        </div>
      </body>

      <?php include '../../stuffs/footer.php'; ?>

    </html>


    <?php

  } else {
    header('location: ../..');
  }

 ?>
