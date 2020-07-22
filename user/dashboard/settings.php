<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';
  include '../../php/functions.php';

  session_start();

  if (!isset($_SESSION['user'])) {

    header('location: ../../');
    }

  $user = $_SESSION['user'];
  $bdd = Database::connect();


  // Recuperation des criteres du user
  $req = $bdd->prepare("SELECT * FROM critere WHERE id_critere=?");
  $req->execute([$user->id_critere]);

  $critere = $req->fetch();


  // Recuperation description utilisateur
  $req = $bdd->prepare("SELECT * FROM description WHERE id_description=?");
  $req->execute([$user->id_description]);

  $description = $req->fetch();

  // *********Modidication adresse email*********
  if(isset($_POST['btn_mail'])){
    $new_mail = $_POST['mail'];
    $req = $bdd->prepare("UPDATE individu SET email =? WHERE id=?");
    $req->execute([$new_mail,$user->id]);

    echo '<script type="text/javascript">alert("Votre addresse mail a été modifiée avec succès!!");window.location = "settings.php";</script>';
    session_destroy();
  }

  // *********Modidication mot de passe*********

  if(isset($_POST['btn_mdp'])){
    $new_mdp = $_POST['new_mdp'];
    $req = $bdd->prepare("UPDATE individu SET password =? WHERE id=?");
    $req->execute([$new_mdp,$user->id]);

    echo '<script type="text/javascript">alert("Modification effectuée avec succès!!");window.location = "settings.php";</script>';
    session_destroy();
  }
  // *********Modidication mes preferences*********

  if(isset($_POST['btn_pref'])){

        // Recuperation des infos sur le profil recherché
        $ageMatch_deb = $_POST['ageMatch_deb'];
        $ageMatch_fin = $_POST['ageMatch_fin'];
        $sexeMatch = $_POST['sexeMatch'];
        $teintMatch = $_POST['teintMatch'];
        $tailleMatch_deb = $_POST['tailleMatch_deb'];
        $tailleMatch_fin = $_POST['tailleMatch_fin'];
        $morphologieMatch = $_POST['morphologieMatch'];
        $nationaliteMatch = $_POST['nationaliteMatch'];
        $religionMatch = $_POST['religionMatch'];


        // Verifier si certains criteres n'ont pas d'importance pour le user
        if ($teintMatch == 'null') {
          $teintMatch = null;
        }

        if ($morphologieMatch == 'null') {
          $morphologieMatch = null;
        }

        if ($nationaliteMatch == 'null') {
          $nationaliteMatch = null;
        }

        if ($religionMatch == 'null') {
          $religionMatch = null;
        }

    $req = $bdd->prepare("UPDATE critere SET age_deb =?, age_fin=?, sexe=?, teint=?, taille_deb =?, taille_fin =?, morphologie =?, nationalite =?, religion =? WHERE id_critere=?");
    $req->execute([$ageMatch_deb,$ageMatch_fin,$sexeMatch,
    $teintMatch,$tailleMatch_deb,$tailleMatch_fin,$morphologieMatch,
    $nationaliteMatch,$religionMatch,$user->id_critere]);

    echo '<script type="text/javascript">alert("Modification de vos préférences effectuée avec succès!!");window.location = "settings.php";</script>';
  }

 //*********Modification Ma description Personnelle*********

 if (isset($_POST['btn_desc'])) {
   $taille = $_POST['tailleUser'];
   $teint = $_POST['teintUser'];
   $morphologie = $_POST['morphUser'];
   $commentaire = $_POST['commentaire'];

   $req = $bdd->prepare("UPDATE description SET taille =?, teint =?, morphologie =?, commentaire =? WHERE id_description=?");
   $req->execute([$taille,$teint,$morphologie,$commentaire,$user->id_description]);

   echo '<script type="text/javascript">alert("Modification effectuée avec succès!!");window.location = "settings.php";</script>';
 }

//*********Modification Mes centres d'intérêts*********

if (isset($_POST['btn_ci'])) {
  $ci = implode(',',$_POST['ci']);
  $req = $bdd->prepare("UPDATE centre_interet SET ci =? WHERE id_ci=?");
  $req->execute([$ci,$user->id_ci]);
  echo '<script type="text/javascript">alert("Modification effectuée avec succès!!");window.location = "settings.php";</script>';
}


 ?>

    <!DOCTYPE html>
    <html lang="fr" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="../../css/settings.css">
      </head>
      <body>
        <header>
          <?php include '../../stuffs/header.php'; ?>
        </header>

        <div class="body">
          <div class="container">
            <!-- Div de gauche : liste-->
            <div class="list" id="params">
              <a href="#param-connexion"><h4>Paramètres de connexion</h4></a>
              <a href="#preferences"><h4>Mes préférences</h4></a>
              <a href="#description_personnelle"><h4>Ma description personnelle</h4></a>
              <a href="#centre_interet"><h4>Mes centres d'intérêts</h4></a>

            </div>
            <!-- Div de droite : screen -->
            <div class="screen" id="parametre">

              <!-- Div Paramètres de connexion -->
              <div class="content" id="param-connexion">
                <div class="description">
                  <h2>Paramètres d'emailing</h2>
                </div>
                <form class="" action="#" method="post" name="form_mail">
                  <div class="line">
                    <label for="mail">Adresse email:</label>
                    <input type="text" name="mail" value="<?php echo $user->email; ?>" placeholder="papitou@popito.com" onchange="save(1);">
                  </div>
                  <div class="line">
                    <input type="checkbox" name="mail_pub" value="">
                    <label for="mail">Adresse email publique(Visible sur le profil)</label>
                  </div>
                  <div class="line">
                    <input type="checkbox" name="mail_pub" value="">
                    <label for="mail">Je souhaite recevoir les newsletters du site</label>
                  </div>
                  <div class="line">
                    <input type="checkbox" name="mail_pub" value="">
                    <label for="mail">Je souhaite recevoir mes notifications par email</label>
                  </div>

                  <input id="btn_mail" type="submit" name="btn_mail" value="Enregistrer" disabled>
                </form>

                <!-- Modification mot de passe -->
                <div class="description">
                  <h2>Modification du mot de passe</h2>
                </div>
                <form class="" action="settings.php" method="post" name="form_mdp" id="form_mdp">
                  <div class="line">
                    <label for="new_mdp">Mot de passe:</label>
                    <input type="password" name="new_mdp" value="" placeholder="Entrer votre nouveau mot de passe ici" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                    title="Doit contenir au moins un chiffre,une lettre majuscule,une lettre miniscule, et au moins 6 caractères">
                  </div>
                  <div class="line">
                    <label for="conf_new_mdp">Confirmation :</label>
                    <input type="password" name="conf_new_mdp" value="" placeholder="Confirmer le mot de passe" onchange="save(2);">
                  </div>
                  <input id="btn_mdp" type="submit" name="btn_mdp" value="Enregistrer" disabled >
                </form>

                <!-- Fermer mon compte -->
                <div class="description">
                  <h2>Fermer mon compte</h2>
                </div>

                <div class="line">
                  <p>Une fois votre compte fermé vous ne pourrez plus avoir accès à votre compte, vos profils compatibles
                  <br>ainsi que tous les services proposés sur ce site</p>
                  <br>Cliquez <a href="#" id="fermer_compte" >ici</a> pour fermer définitivement votre compte</p>
                </div>
              </div>
              <!-- Fin Div Paramètre de connexion -->

              <!-- Div Vos préférence -->
              <div class="content" id="preferences">
                <!-- Age et Taille -->
                <div class="description">
                  <h2>Sexe, Age et taille</h2>
                </div>
                <form class="" action="settings.php" method="post" name="form_pref" id="from_pref">
                  <!-- Sexe -->
                  <div class="line">
                    <label>Sexe recherché :</label>
                    <select  name="sexeMatch" onchange="save(3);">
                        <option selected value="<?php echo $critere['sexe']; ?>"><?php echo $critere['sexe']; ?></option>
                        <?php
                          $tab = array('Homme', 'Femme', 'Inconnu');
                          $element = $critere['sexe'];
                          if(in_array($element,$tab)){
                            unset($tab[array_search($element, $tab)]);
                            foreach ($tab as $tab_element) {
                              ?>
                              <option value="<?php echo($tab_element);?>"><?php echo($tab_element=='Inconnu') ? 'Autre...': $tab_element;?></option>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>

                    </select>
                  </div>

                  <div class="line">
                    <!-- Age -->
                    <label>Tranche d'age recherchée :</label>
                    <select id="ageMatch_deb" name="ageMatch_deb" onchange="sendvalue('ageMatch_deb','ageMatch_fin');save(3)">
                      <option value="<?php echo $critere['age_deb']; ?>" selected ><?php echo $critere['age_deb']; ?></option>
                    </select>
                    <label for="ageeMatch_fin">&nbsp&nbspà&nbsp&nbsp</label>
                    <select id="ageMatch_fin" name="ageMatch_fin" onchange="save(3)" >
                      <option value="<?php echo $critere['age_fin']; ?>" selected><?php echo $critere['age_fin']; ?></option>
                    </select>
                  </div>
                  <!-- Taille -->
                  <div class="line">
                    <label>Taille (cm) comprise entre :</label>
                    <select  id="tailleMatch_deb" name="tailleMatch_deb" onchange="sendvalue('tailleMatch_deb','tailleMatch_fin');save(3)">
                        <option value="<?php echo $critere['taille_deb']; ?>" selected><?php echo $critere['taille_deb']; ?></option>
                    </select>
                    <label for="tailleMatch_fin">&nbspet&nbsp</label>
                    <select id="tailleMatch_fin" name="tailleMatch_fin" onchange="save(3)">
                        <option value="<?php echo $critere['taille_fin']; ?>" selected><?php echo $critere['taille_fin']; ?></option>
                    </select>
                  </div>
                  <!-- Teint & Morphologie -->
                  <div class="description" id="ori">
                    <h2>Teint et morphologie</h2>
                  </div>
                  <!-- Teint profil recherché -->
                  <div class="line">
                    <label for="teintMatch">Teint</label>
                    <select id="teintMatch" name="teintMatch" onchange="save(3)">
                      <option value="<?php echo $critere['teint']; ?>" selected><?php  echo($critere['teint'] == null) ? 'Sans importance' :$critere['teint']; ?></option>
                      <?php
                        $tab = array(null, 'Clair', 'Noir','Bronze');
                        $element = $critere['teint'];
                        if(in_array($element,$tab)){
                          unset($tab[array_search($element, $tab)]);
                          foreach ($tab as $tab_element) {
                            ?>
                            <option value="<?php echo($tab_element== null) ? 'null' :$tab_element;?>"><?php echo($tab_element== null) ? 'Sans importance' :$tab_element;?></option>
                          <?php
                          }
                          ?>
                      <?php
                      }
                      ?>

                    </select>
                  </div>
                  <!-- Morphologie profil recherché -->
                  <div class="line">
                    <label for="morphMatch">Morphologie</label>
                    <select id="morphMatch" name="morphMatch" onchange="save(3)">
                        <option value="<?php echo $critere['morphologie']; ?>" selected ><?php  echo($critere['morphologie'] == null) ? 'Sans importance' :$critere['morphologie']; ?></option>
                        <?php
                          $tab = array(null, 'Mince', 'Gros','Autre');
                          $element = $critere['morphologie'];
                          if(in_array($element,$tab)){
                            unset($tab[array_search($element, $tab)]);
                            foreach ($tab as $tab_element) {
                              ?>
                              <option value="<?php echo($tab_element== null) ? 'null' :$tab_element;?>"><?php echo($tab_element== null) ? 'Sans importance' :$tab_element;?></option>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>

                    </select>
                  </div>
                  <!-- Nationalité & Réligion -->
                  <div class="description" id="ori">
                    <h2>Nationalité et Religion</h2>
                  </div>
                      <!-- Nationalité -->
                  <div class="line">
                    <label for="">Nationalité:</label>
                    <select class="" name="nationaliteMatch" onchange="save(3)">
                      <option value="<?php echo ($critere['nationalite'] == null) ? 'null' :$$critere['nationalite']; ?>" selected ><?php  echo($critere['nationalite'] == null) ? 'Sans importance' :getNationalite($critere['nationalite']); ?></option>
                      <?php include '../inscription/pays.php'; ?>
                    </select>
                  </div>
                    <!--Réligion -->
                  <div class="line">
                    <label for="religion">Religion:</label>
                    <select name="religionMatch" onchange="save(3)">
                        <option value="<?php echo $critere['religion']; ?>" selected ><?php  echo($critere['religion'] == null) ? 'Sans importance' :$critere['religion']; ?></option>
                        <?php
                          $tab = array(null, 'Judaisme', 'Christianisme', 'Islam', 'Boudhisme','Inconnue');
                          $element = $critere['religion'];
                          if(in_array($element,$tab)){
                            unset($tab[array_search($element, $tab)]);
                            foreach ($tab as $tab_element) {
                              ?>
                              <option value="<?php echo($tab_element== null) ? 'null' :$tab_element;?>">
                                <?php
                                  if ($tab_element==null)
                                    echo ('Sans Importance');
                                  elseif ($tab_element=='Inconnue')
                                    echo ('Autre...');
                                  else
                                    echo ($tab_element);
                                ?>
                              </option>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>

                    </select>
                  </div>

                  <div class="line">
                    <input type="submit" name="btn_pref" value="Sauvegarder" disabled id="btn_pref">
                    <input type="reset" name="btn_pref_annuler" value="Annuler" id="btn_pref_annuler" onclick="javascript:document.getElementById('btn_pref').disabled = true;this.style.display='none';">
                  </div>
                </form>
              </div>
              <!-- Fin Div Vos preferencess -->

              <!-- Div Ma description personnelle -->
              <div class="content" id="description_personnelle">
                <!-- Age et Taille -->
                <div class="description">
                  <h2>Description Personnelle</h2>
                </div>
                <form class="" action="settings.php" method="post" name="form_desc" id="from_desc">

                  <!-- Taille User -->
                  <div class="line">

                    <select id="tailleUser" name="tailleUser" onchange="save(4);">
                        <option value="<?php echo $description['taille']; ?>" selected><?php echo $description['taille']; ?>  (cm)</option>
                    </select>
                  </div>
                  <!-- Teint User -->
                  <div class="line">

                    <select id="teintUser" name="teintUser" onchange="save(4);">
                        <option value="<?php echo $description['teint']; ?>" selected><?php echo $description['teint']; ?> (Mon teint)</option>
                        <?php
                          $tab = array('Noir', 'Clair', 'Bronze');
                          $element = $description['teint'];
                          if(in_array($element,$tab)){
                            unset($tab[array_search($element, $tab)]);
                            foreach ($tab as $tab_element) {
                              ?>
                              <option value="<?php echo($tab_element);?>"><?php echo($tab_element);?></option>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </select>
                  </div>
                  <!-- Morphologie User -->
                  <div class="line">

                    <select id="morphUser" name="morphUser" onchange="save(4)">
                        <option value="<?php echo $description['morphologie']; ?>" selected ><?php echo $description['morphologie']; ?> (Ma morphologie)</option>
                        <?php
                          $tab = array('Mince', 'Gros', 'Autre');
                          $element = $description['morphologie'];
                          if(in_array($element,$tab)){
                            unset($tab[array_search($element, $tab)]);
                            foreach ($tab as $tab_element) {
                              ?>
                              <option value="<?php echo($tab_element);?>"><?php echo($tab_element=='Autre') ? 'Autre...': $tab_element;?></option>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>

                    </select>
                  </div>

                  <!-- Info supplémentaires  User -->
                  <div class="line">
                    <textarea name="commentaire" rows="8" cols="80" placeholder="Informations supllémentaires" value="<?php echo $description['commentaire']; ?>" onchange="save(4);"><?php echo $description['commentaire']; ?></textarea>
                  </div>

                  <div class="line">
                    <input type="submit" name="btn_desc" value="Sauvegarder" disabled id="btn_desc">
                    <input type="reset" name="" value="Annuler" id="btn_desc_annuler" onclick="javascript:document.getElementById('btn_desc').disabled = true;this.style.display='none';">
                  </div>
                </form>
              </div>
              <!-- Fin Div Ma description personnelle -->

              <!-- Div Mes centres d'intérêts -->
              <div class="content" id="centre_interet">

                <div class="description">
                  <h2>Mes centres d'intérêt</h2>
                </div>
                <form class="" action="settings.php" method="post" name="form_ci" id="form_ci">
                  <div class="container_ci">
                    <ul class="ci" onchange="save(5);">
                      <?php

                        $bdd = Database::connect();
                        $req = $bdd->prepare("SELECT * FROM centre_interet WHERE id_ci=?");
                        $req->execute([$user->id_ci]);

                        $ci_user = $req->fetch();
                        $ci_user = explode(',',$ci_user['ci']);

                        $req1 = $bdd->prepare("SELECT * FROM liste_centre_interet");
                        $req1->execute();

                        $liste_ci = [];
                        while ($data = $req1->fetch()) {
                          array_push($liste_ci,$data['ci']);
                        }

                        foreach ($liste_ci as $element) {
                          if (in_array($element,$ci_user)) {
                              echo "<li><input type='checkbox' name ='ci[]' id='{$element}' value='{$element}' checked><label for='{$element}'>{$element}</label></li>";
                            }else {
                              echo "<li><input type='checkbox' name ='ci[]' id='{$element}' value='{$element}'><label for='{$element}'>{$element}</label></li>";
                            }

                        }

                        $bdd = Database::disconnect();

                       ?>
                    </ul>
                </div>
                  <div class="line">
                    <input type="submit" name="btn_ci" value="Sauvegarder" disabled id="btn_ci">
                    <input type="reset" name="" value="Annuler" id="btn_ci_annuler" onclick="javascript:document.getElementById('btn_ci').disabled = true;this.style.display='none';">
                  </div>
                </form>
              </div>
              <!-- Fin Div Div Mes centres d'intérêts -->
            </div>

          </div>
        </div>

        <?php include '../../stuffs/footer.php'; ?>

      <script type="text/javascript">
      setvalue("ageMatch_deb",18,80);
      setvalue("tailleMatch_deb",100,200);
      setvalue("tailleUser",100,200);


      function setvalue(id,n,m){
        var select = document.getElementById(id);
        for(var i = n; i <= m; i++) {
          var el = document.createElement("option");
          el.textContent = i;
          el.value = i;
          select.appendChild(el);
        }
      }

      function sendvalue(id,id2){
        var select1 = document.getElementById(id); //Le premier select
        var select2 = document.getElementById(id2);
        var opts = select2.getElementsByTagName("option");
        while(opts[1]) {
            select2.removeChild(opts[1]);
        }
        var len = select1.options.length;
        var n = select1.value;//on recupère la valeur courante du select
        var m = select1.options[len-1].value;//on recupère la valeur du dernier élément du premier select
        for(var i = n; i <= m; i++) {
          var el = document.createElement("option");
          el.textContent = i;
          el.value = i;
          select2.appendChild(el);
          }
        }
        function save(n){
          var x = document.querySelectorAll('#form_mdp input[type="password"]');
          switch (n) {
            case 1:
              document.getElementById('btn_mail').disabled = !document.getElementById('btn_mail').disabled;
              break;
            case 2:
              if (x[0].value != x[1].value) {
                if(x[1].value ==""){
                  document.getElementById('btn_mdp').disabled = true;
                }else {
                  console.log(x[0].value+','+x[1].value);
                  alert("Les deux champs ne correspondent pas!!");
                  document.getElementById('btn_mdp').disabled = true;
                }
              }else if(x[0].value ==""){
                document.getElementById('btn_mdp').disabled = true;
              }else {
                document.getElementById('btn_mdp').disabled = false;
              }
              break;
            case 3:
              document.getElementById('btn_pref').disabled = false;
              document.getElementById('btn_pref_annuler').style.display = "block";
              break;
            case 4:
              document.getElementById('btn_desc').disabled = !document.getElementById('btn_desc').disabled;
              document.getElementById('btn_desc_annuler').style.display = "block";
              break;
            default:
            valid = true;
            y = document.querySelectorAll('#form_ci input[type="checkbox"]');
            var j=0;
            for (i = 0; i < y.length ; i++) {
              if (y[i].checked == true) {
                j++;
              }
            }
            if (j < 5) {
              valid = false;
              alert("Veuillez choisir au moins 5(cinq) centres d'intérêt svp!");
            }
            if (valid) {
              document.getElementById('btn_ci').disabled = false;
              document.getElementById('btn_ci_annuler').style.display = "block";
            }else {
              document.getElementById('btn_ci').disabled = true;
            }

          }

        }
      </script>
      </body>
    </html>
