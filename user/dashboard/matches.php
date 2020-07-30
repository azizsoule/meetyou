<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';
  include '../../php/functions.php';

  session_start();

  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user->matches = getMatchs($user);
    // ordonner($user->matches);
    $matchs = $user->matches;
    // echo("Taille = ".count($matchs));
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>
          <?php
              include("../../name.php");
              echo $name." | Bienvenue chez vous !";
          ?>
      </title>
      <link rel="stylesheet" href="../../css/settings.css">
  </head>
  <body>

    <header>
      <?php include '../../stuffs/header.php'; ?>
    </header>

    <div class="body" id="prof_comp">
      <!-- Div préférences -->

      <?php

        $connection = Database::connect();

        $requette = $connection->prepare("SELECT * FROM critere WHERE id_critere=(SELECT id_critere FROM individu WHERE id=?)");
        $requette->execute([$user->id]);

        $prefs = $requette->fetch();

       ?>

      <div class="list" id="pref">
        <h3>Vos préférences</h3>

        <h3>Sexe</h3>
        <span><?php echo $prefs['sexe']; ?></span><br>

        <!-- Age ici -->
        <h3>Age</h3>
        <span>De <span id="age_inf"><?php echo $prefs['age_deb']; ?></span> à <span id="age_sup"><?php echo $prefs['age_fin']; ?></span> ans</span>

        <!-- Taille ici -->
        <h3>Taille</h3>
        <span>Entre <span id="tailleMatch_inf"><?php echo $prefs['taille_deb']; ?></span> et <span id="tailleMatch_sup"><?php echo $prefs['taille_fin']; ?></span>  cm</span>

        <h3>Religion</h3>
        <span><?php echo ($prefs['religion'] == null) ? "Sans importance" : $prefs['religion']; ?></span><br>

        <h3>Teint</h3>
        <span><?php echo ($prefs['teint'] == null) ? "Sans importance" : $prefs['teint']; ?></span><br>

        <h3>Morphologie</h3>
        <span><?php echo ($prefs['morphologie'] == null) ? "Sans importance" : $prefs['morphologie']; ?></span><br>

        <h3>Nationalité</h3>
        <span><?php echo ($prefs['nationalite'] == null) ? "Sans importance" : $prefs['nationalite']; ?></span><br>

        <!-- religion ici -->
        <a href="settings.php#preferences">
          <button class="" type="button" name="button">Modifier</button>
        </a>
      </div>
      <!-- Div overview match -->
      <div class="screen" id="profils">


        <?php

          foreach ($matchs as $match) {
              $taux =round($match->taux, 0);
              $age = age($match->date_naissance);
            if ($match->photo == null) {
              echo'

              <div class="content profil" >
                <div class="container">
                  <div class="photo">
                    <!-- Photo matches -->
                    <img src="../../images/user.webp">
                  </div>
                  <div class="info_match">
                    <!-- Nom matches -->
                    <h2>'.$match->nom.',&nbsp'.$age.'&nbsp&nbsp<span>taux = '.$taux.'</span></h2>
                    <div class="line">
                      <div class="ic-job"></div>
                      <!-- Emploi matches -->
                      <label class="label-10">'.$match->profession.'</label>
                    </div>
                    <br>
                    <div class="line">
                      <div class="ic-lieu"></div>
                      <!-- Lieu de residence -->
                      <label class="label-10">'.getNationalite($match->id_pays).','.getVille($match->id_ville).'</label>
                    </div>
                    <div class="line">
                      <button class="btn" type="button" name="btn_msg" onclick=""><a style="color:white;" href="../../php/discussion.php?id='.$match->id.'" ><div class="ic-msg"><p>Envoyer un message</p></div></a></button>
                      <button class="btn blue" type="button" name="btn_profil" onclick="" ><a style="color:white;" href=profil.php?id='.$match->id.'&taux='.$taux.'><div class="ic-profil"><p>Voir le profil</p></div></a></button>
                    </div>

                  </div>
                  <!--<div class="photo taux">
                    <img src="../../images/gif/heart2.gif">
                  </div> -->
                </div>
              </div>

              ';
            } else {
              echo'

              <div class="content profil" >
                <div class="container">
                  <div class="photo">
                    <!-- Photo matches -->
                    <img src="../../images/profiles/'.$match->photo.'">
                  </div>
                  <div class="info_match">
                    <!-- Nom matches -->
                    <h2>'.$match->nom.',&nbsp'.$age.'&nbsp&nbsp<span>taux = '.$taux.'</span></h2>
                    <div class="line">
                      <div class="ic-job"></div>
                      <!-- Emploi matches -->
                      <label class="label-10">'.$match->profession.'</label>
                    </div>
                    <br>
                    <div class="line">
                      <div class="ic-lieu"></div>
                      <!-- Lieu de residence -->
                      <label class="label-10">'.getNationalite($match->id_pays).','.getVille($match->id_ville).'</label>
                    </div>
                    <div class="line">
                      <button class="btn" type="button" name="btn_msg" onclick=""><a style="color:white;" href="../../php/discussion.php?id='.$match->id.'" ><div class="ic-msg"><p>Envoyer un message</p></div></a></button>
                      <button class="btn blue" type="button" name="btn_profil" onclick="" ><a style="color:white;" href=profil.php?id='.$match->id.'&taux='.$taux.'><div class="ic-profil"><p>Voir le profil</p></div></a></button>
                    </div>

                  </div>
                  <!--<div class="photo taux">
                    <img src="../../images/gif/heart2.gif">
                  </div> -->
                </div>
              </div>

              ';
            }
        }
        ?>


        <!-- <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>condition

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>

        <div class="content profil" >
          <div class="container">
            <div class="photo">
              <img src="../../images/user.webp">
            </div>
            <div class="info_match">

              <h2>Elodie,&nbsp<span>24</span></h2>
              <div class="line">
                <div class="ic-job"></div>
                <label class="label-10">Profession</label>
              </div>
              <br>
              <div class="line">
                <div class="ic-lieu"></div>
                <label class="label-10">Abidjan,cocody</label>
              </div>
              <div class="line">
                <button class="btn" type="button" name="btn_msg"><div class="ic-msg"><p>Envoyer un message</p></div></button>
                <button class="btn blue" type="button" name="btn_profil" ><div class="ic-profil"><p>Voir le profil</p></div></button>
              </div>

            </div>
          </div>
        </div>
   -->


      </div>
      </div>


  </body>
  </html>
  <?php

  }else {
    header("location: ../../");
  }

?>
