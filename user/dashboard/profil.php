<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';
  include '../../php/functions.php';

  session_start();

  if (!isset($_SESSION['user'])) {
    header("location: ../../");
  }
  $bdd = Database::connect();

  // Recuperation du user connecté
  if (isset($_GET['id']) && isset($_GET['taux'])) {

    $req = $bdd->prepare("SELECT * FROM individu WHERE id=?");
    $req->execute([$_GET['id']]);

    $ind = $req->fetch();

    $user = new Individu($ind);
  }else {
    $user = $_SESSION['user'];
  }
  if (isset($_POST['valider'])) {
    // isset($_POST['nom']) || isset($_POST['prenoms']) || isset($_POST['date_naissance']) || isset($_POST['nationalite'])
    //   || isset($_POST['sexe']) || isset($_POST['profession']) || isset($_POST['religion']) || isset($_POST['telephone']) || isset($_POST['pays']) || isset($_POST['ville'])

    $nom = $_POST['nom'];
    $prenoms = $_POST['prenoms'];
    $date_naissance = $_POST['date_naissance'];

    $nationalite = $_POST['nationalite'];
    $sexe = $_POST['sexe'];
    $profession = $_POST['profession'];
    $religion = $_POST['religion'];
    $telephone = $_POST['tel'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];

    // echo 'nom = '.$nom.' prenom = '.$prenoms.' date = '.$date_naissance.' nation = '.$nationalite.' sexe = '.$sexe.' prof = '.$profession.' religion = '.$religion.' tel = '.$telephone.' pays = '.$pays.' ville = '.$ville;


    $req = $bdd->prepare("UPDATE individu SET nom =?,prenoms =?,telephone =?,sexe =?,date_naissance =?,profession =?,nationalite = ?,religion =?,id_ville = ?,id_pays = ? WHERE id=?");
    $req->execute([$nom,$prenoms,$telephone,$sexe,$date_naissance,$profession,$nationalite,$religion,$ville,$pays,$user->id]);

    echo '<script type="text/javascript">alert("Modification effectuée avec succès!!");window.location = "profil.php";</script>';

  }
  if (isset($_GET['id']) && isset($_GET['taux'])) {

    $req = $bdd->prepare("SELECT * FROM individu WHERE id=?");
    $req->execute([$_GET['id']]);

    $ind = $req->fetch();

    $user = new Individu($ind);
  }else {
    $req = $bdd->prepare("SELECT * FROM individu WHERE id=?");
    $req->execute([$user->id]);
    $ind = $req->fetch();
    $user = new Individu($ind);
  }

  // Recuperation de la description du user
  $req = $bdd->prepare("SELECT * FROM description WHERE id_description=?");
  $req->execute([$user->id_description]);

  $description = $req->fetch();

  $_SESSION['description'] = $description;

  // Recuperation des criteres du user
  $req = $bdd->prepare("SELECT * FROM critere WHERE id_critere=?");
  $req->execute([$user->id_critere]);

  $critere = $req->fetch();
  $_SESSION['critere'] = $critere;
  // Recuperation du pays de residence du user
  $req = $bdd->prepare("SELECT * FROM pays WHERE id_pays=?");
  $req->execute([$user->id_pays]);

  $pays = $req->fetch();
  $_SESSION['pays'] = $pays;
  // Recuperation de la ville de residence du user
  $req = $bdd->prepare("SELECT * FROM villes WHERE id_ville=?");
  $req->execute([$user->id_ville]);

  $ville = $req->fetch();
  $_SESSION['ville'] = $ville;
  // Recuperation de la nationalite du user
  $req = $bdd->prepare("SELECT * FROM pays WHERE id_pays=?");
  $req->execute([$user->nationalite]);

  $nationalite = $req->fetch();
  $_SESSION['nationalite'] = $nationalite;

  if ($critere['nationalite'] == null) {
    $nationaliteMatch = array("nom_pays"=>"Sans importance");
  }else {
    $req = $bdd->prepare("SELECT * FROM pays WHERE id_pays=?");
    $req->execute([$critere['nationalite']]);

    $nationaliteMatch = $req->fetch();
  }
  // echo("Taille user = ".getDescription($user,'taille'));
  // echo("Sexe_critere user = ".getCritere($user,'sexe'));
  // echo("nationalite user = ".getNationalite($user->nationalite));
  $user->matches = getMatchs($user);
  $matchs = $user->matches;
  // ordonner($matchs);

  // echo("Taille = ".count($matchs));
  // foreach ($matchs as $match ) {
  //   echo ("Taux: ".$match->taux);
  // }


    ?>

    <!DOCTYPE html>
    <html lang="fr" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title>

          <?php

              include("../../name.php");
              echo $name." | Profil";

          ?>

        </title>
        <link rel="stylesheet" href="../../css/profil.css">
      </head>

      <body>

        <?php include '../../stuffs/header.php'; ?>

        <div class="body">

          <div class="pp-container">
            <div class="pp">

              <img src="../../images/main3.png" alt="">
              <!-- Mettre le taux de compatibilité ici dans la div de class="icons" -->
              <?php

                if (isset($_GET['id']) && isset($_GET['taux'])) {
                  ?>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-coeur-match-50.png" alt=""><?php echo $_GET['taux']; ?></div><!-- Mettre ici là ou il ya 80 -->
                  </div>

                  <?php
                }

               ?>
              <!-- Nom et age ici  -->
              <div class="name-age">
                <p><?php echo $user->prenoms; ?>,<span>&nbsp<?php echo age($user->date_naissance); ?></span></p>
              </div>
              <!-- Metier et Ville -->
              <div class="pp-title">
                <div class="ic-job"></div>
                <p><?php echo $user->profession; ?></p>
                <div class="ic-lieu"></div>
                <p><?php echo $ville['nom_ville']; ?>&nbsp(<?php echo $pays['nom_pays']; ?>)</p>
              </div>

            </div>

            <?php

            if (isset($_GET['id']) && isset($_GET['taux'])) {
              ?>

                <button class="btn" type="button" name="btn_msg" onclick=""><a style="color:white;" href="../../php/discussion.php?id=<?php echo $_GET['id']; ?>" ><div class="ic-msg"><p>Envoyer un message</p></div></a></button>

              <?php
            }

             ?>


          </div>

          <div class="tab">

            <button class="tablinks" id="defaultOpen" onclick="openTab(event, 'Profil');"><img src="../../images/icons/icons8-utilisateur-50.png" alt=""><p>Profil</p></button>
            <button class="tablinks" onclick="openTab(event, 'CI')"><img src="../../images/icons/icons8-guitare-50.png" alt=""><p>Centres d'intérêt</p></button>
          </div>

          <div id="Profil" class="tabcontent">
            <div class="tabsupp">
              <div class="description">
                <h2><?php echo (isset($_GET['id']) && isset($_GET['taux'])) ? "Description de ".$user->nom : "Ma description personnelle"; ?></h2>
              </div>
              <div class="container">
                <fieldset>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-règle-50.png" alt=""></div>
                    <h4><?php echo $description['taille']; ?> cm</h4>
                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-peau-50.png" alt=""></div>
                    <h4><?php echo $description['teint']; ?></h4>
                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-homme-debout-50.png" alt=""></div>
                    <h4><?php echo $description['morphologie']; ?></h4>
                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-genre-50.png" alt=""></div>
                    <h4><?php echo $user->sexe; ?></h4>
                  </div>
                </fieldset>

                <fieldset>
                  <!-- <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-globe-50.png" alt=""></div>
                    <h4><?php //echo $pays['nom_pays']; ?></h4>
                  </div> -->
                  <!-- <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-bâtiments-de-la-ville-50.png" alt=""></div>
                    <h4><?php //echo $ville['nom_ville']; ?></h4>
                  </div> -->

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-prayer-50.png" alt=""></div>
                    <h4><?php echo $user->religion; ?></h4>
                  </div>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-langue-50.png" alt=""></div>
                    <h4><?php echo $nationalite['nom_pays']?></h4>
                  </div>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-info-50.png" alt=""></div>
                    <textarea rows="3" cols="" placeholder="Informations suplementaires ..." name="infoSup" disabled>
                      <?php echo $description['commentaire']; ?>
                    </textarea>
                  </div>

                </fieldset>
              </div>

            </div>


            <div id = "Prof-rech" class="tabsupp">
              <div class="description">
                <h2>Profil recherché</h2>
              </div>
              <div class="container">
                <fieldset>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-règle-50.png" alt=""></div>
                    <!-- TailleMatch ici -->
                    <h4>Entre <span id="tailleMatch_inf"><?php echo $critere['taille_deb']; ?></span> et <span id="tailleMatch_sup"><?php echo $critere['taille_fin']; ?></span> cm</h4>

                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-peau-50.png" alt=""></div>
                    <h4><?php echo ($critere['teint']==null) ? "Sans importance" : $critere['teint']; ?></h4>
                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-homme-debout-50.png" alt=""></div>
                    <h4><?php echo ($critere['morphologie']==null) ? "Sans importance" : $critere['morphologie']; ?></h4>
                  </div>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-genre-50.png" alt=""></div>
                    <h4><?php echo $critere['sexe']; ?></h4>
                  </div>
                </fieldset>
                <fieldset>
                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-age-50.png" alt=""></div>
                    <!-- ageMatch ici -->
                    <h4>De <span id="age_inf"><?php echo $critere['age_deb']; ?></span> à <span id="age_sup"><?php echo $critere['age_fin']; ?></span> ans</h4>

                  </div>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-prayer-50.png" alt=""></div>
                    <h4><?php echo ($critere['religion']==null) ? "Sans importance" : $critere['religion']; ?></h4>
                  </div>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-langue-50.png" alt=""></div>
                    <h4><?php echo $nationaliteMatch['nom_pays']; ?></h4>
                  </div>

                  <div class="ico-value">
                    <div class="icons"><img src="../../images/icons/icons8-info-50.png" alt=""></div>
                    <textarea rows="3" cols="" placeholder="Informations suplementaires ..." name="infoSup" disabled>
                      <?php echo $critere['commentaire']; ?>
                    </textarea>
                  </div>
                </fieldset>
              </div>
            </div>

            <?php

              if (!(isset($_GET['id']) && isset($_GET['taux']))) {


                ?>
                <script type="text/javascript">

                </script>
                <div  id = "info" class="tabsupp">
                  <div class="description">
                    <h2>Informations personnelles</h2>
                  </div>
                  <div class="info-content">
                    <form name = "infos_perso" action="profil.php" method="post">

                      <input  type="text" name="nom" id="" placeholder="Nom" disabled value="<?php echo $user->nom;?>" onchange="dis();">

                      <input  type="text" name="prenoms" id="" placeholder="Prenoms" disabled value="<?php echo $user->prenoms ;?>"  onchange="dis();">
                      <input  type="date" name="date_naissance" id="" placeholder="Date de naissance" min='1940-01-01' max='2002-12-31' disabled value="<?php echo $user->date_naissance;?>"  onchange="dis();">

                      <select name="nationalite" disabled  onchange="dis();">
                          <option  selected value="<?php echo $user->nationalite;?>"><?php echo getNationalite($user->nationalite) ;?>(Nationalité)</option>

                      </select>

                      <select name="sexe" disabled  onchange="dis();">
                          <option selected value="<?php echo $user->sexe ;?>"><?php echo $user->sexe ;?></option>
                          <option value="Homme">Homme</option>
                          <option value="Femme">Femme</option>
                          <option value="Inconnu">Autre ...</option>
                      </select>

                      <input  type="text"  name="tel" id="" placeholder="Telephone" disabled value="<?php echo $user->telephone ;?>"  onchange="dis();">

                      <select name="pays" disabled onchange="dis();">
                          <option selected value="<?php echo $user->id_pays ;?>" ><?php echo getNationalite($user->id_pays),$user->id_pays ;?>(Pays de Residence)</option>
                          <option value="">Element 1</option>
                          <option value="">Element 2</option>
                          <option value="">Element 3</option>
                      </select>

                      <select name="ville" disabled  onchange="dis();">
                          <option selected value="<?php echo $user->id_ville ;?>"><?php echo getVille($user->id_ville) ;?>(Ville de Residence)</option>
                          <option value="">Element 1</option>
                          <option value="">Element 2</option>
                          <option value="">Element 3</option>
                      </select>

                      <input  type="text" disabled name="profession" id="" placeholder="Profession" value="<?php echo $user->profession ;?>"  onchange="dis();">

                      <select name="religion" disabled  onchange="dis();">
                          <option selected value="<?php echo $user->religion ;?>"><?php echo $user->religion ;?></option>
                          <option value="Judaisme">Judaisme</option>
                          <option value="Christianisme">Christianisme</option>
                          <option value="Islam">Islam</option>
                          <option value="Boudhisme">Boudhisme</option>
                          <option value="Inconnue">Autre..</option>
                      </select>

                      <button type="button" name="modifier"  id="btn_modifier" onclick="enable_info();">Modifier les infos personnelles</button>

                      <button type="submit" name="valider" class="btn_valider" id="btn_valider" value="" disabled >Valider</button>
                      <button type="reset" name="annuler" class="btn_annuler" id="btn_annuler" value="" onclick="disable();">Annuler</button>

                    </form>

                  </div>

                </div>


                <?php
              }

             ?>

          </div>


          <!-- <div id="Photo" class="tabcontent">
            <h2>Photo</h2>
            <p>Paris is the capital of France.</p>
          </div> -->

          <div id="CI" class="tabcontent">
            <div class="tabsupp">
              <div class="description">
                <h2>Centres d'intérêt</h2>
              </div>
              <!-- <div class="container">

                <fieldset id="loisirs" disabled>
                  <legend>Mes loisirs sont:
                    <span  class="topright" onclick="enable('loisirs')">🖊️</span>
                  </legend>
                  <textarea name="loisirs" rows="5" cols="80">Vous n'avez pas encore ajouté vos centres d'intérêt.</textarea>
                </fieldset>

                <fieldset id="musique" disabled>
                  <legend>Mes styles de musque préférés:
                    <span  class="topright" onclick="enable('musique')">🖊️</span>
                  </legend>

                  <textarea name="musique" rows="5" cols="80">Vous n'avez pas encore ajouté vos centres d'intérêt.</textarea>
                </fieldset>

                <fieldset id="sport" disabled>
                  <legend>Mes activités sportives:
                    <span  class="topright" onclick="enable('sport')">🖊️</span>
                  </legend>
                  <textarea name="sport" rows="5" cols="80" >Vous n'avez pas encore ajouté vos centres d'intérêt.</textarea>
                </fieldset>

              </div> -->
              <div class="container_ci">
                <ul class="ci">
                  <?php

                    $req1 = $bdd->prepare("SELECT * FROM centre_interet WHERE id_ci=(SELECT id_ci FROM individu WHERE id=?)");
                    $req1->execute([$user->id]);

                    $ci = $req1->fetch();

                    $ci = explode(',',$ci['ci']);

                    foreach ($ci as $c_i) {
                      echo "<li><input disabled checked type='checkbox'><label>{$c_i}</label></li>";
                    }

                   ?>
                </ul>
            </div>
            </div>

          </div>

        </div>

        <?php include '../../stuffs/footer.php'; ?>

        <script>

          function dis(){document.getElementById('btn_valider').disabled = false}

          function openTab(evt, option) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
              tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
              tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(option).style.display = "block";
            evt.currentTarget.className += " active";
        }
        function disable(){
            var items = document.querySelectorAll('#info input');
            var items2 = document.querySelectorAll('#info select');
            for (var i = 0; i < items.length; i++) {
              items[i].disabled = !items[i].disabled;
            }
            for (var j = 0; j < items2.length; j++) {
              items2[j].disabled = !items2[j].disabled;
            }
            document.getElementById('btn_modifier').style.display="block";
            document.getElementById('btn_valider').style.display="none";
            document.getElementById('btn_annuler').style.display="none";
        }
        function enable_info(){
          var items = document.querySelectorAll('#info input');
          var items2 = document.querySelectorAll('#info select');
          for (var i = 0; i < items.length; i++) {
            items[i].disabled = !items[i].disabled;
          }
          for (var j = 0; j < items2.length; j++) {
            items2[j].disabled = !items2[j].disabled;
          }
          document.getElementById('btn_modifier').style.display="none";
          document.getElementById('btn_valider').style.display="block";
          document.getElementById('btn_annuler').style.display="block";
        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
      </script>
      </body>

    </html>
