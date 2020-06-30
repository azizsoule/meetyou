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

  // Modidication adresse email
  if(isset($_POST['btn_mail'])){
    $new_mail = $_POST['mail'];
    $req = $bdd->prepare("UPDATE individu SET email =? WHERE id=?");
    $req->execute([$new_mail,$user->id]);

    echo '<script type="text/javascript">alert("Votre addresse mail a été modifiée avec succès!!");window.location = "settings.php";</script>';
    session_destroy();
  }
// Modidication mot de passe

  if(isset($_POST['btn_mdp'])){
    $new_mdp = $_POST['new_mdp'];
    $req = $bdd->prepare("UPDATE individu SET password =? WHERE id=?");
    $req->execute([$new_mdp,$user->id]);

    echo '<script type="text/javascript">alert("Modification effectuée avec succès!!");window.location = "settings.php";</script>';
    session_destroy();
  }
// Modidication mes preferences

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
                          if ($critere['sexe']=="Femme") {
                            ?>
                            <option value="Homme">Homme</option>
                            <option value="Inconnu">Autre ...</option>
                            <?php
                        }
                        elseif($critere['sexe']=="Homme"){
                          ?>
                          <option value="Femme">Femme</option>
                          <option value="Inconnu">Autre ...</option>
                        <?php
                        }else {
                          ?>
                          <option value="Femme">Femme</option>
                          <option value="Homme">Homme</option>
                          <?php
                        }
                         ?>
                    </select>
                  </div>

                  <div class="line">
                    <!-- Age -->
                    <label>Tranche d'age recherchée :</label>
                    <select id="ageMatch_deb" name="ageMatch_deb" onchange="sendvalue('ageMatch_deb','ageMatch_fin');save(3)">
                      <option value="<?php echo $critere['age_deb']; ?>" selected disabled><?php echo $critere['age_deb']; ?></option>
                    </select>
                    <label for="ageeMatch_fin">&nbsp&nbspà&nbsp&nbsp</label>
                    <select id="ageMatch_fin" name="ageMatch_fin" onchange="save(3)" >
                      <option value="<?php echo $critere['age_fin']; ?>" selected disabled><?php echo $critere['age_fin']; ?></option>
                    </select>
                  </div>
                  <!-- Taille -->
                  <div class="line">
                    <label>Taille (cm) comprise entre :</label>
                    <select  id="tailleMatch_deb" name="tailleMatch_deb" onchange="sendvalue('tailleMatch_deb','tailleMatch_fin');save(3)">
                        <option value="<?php echo $critere['taille_deb']; ?>" selected disabled><?php echo $critere['taille_deb']; ?></option>
                    </select>
                    <label for="tailleMatch_fin">&nbspet&nbsp</label>
                    <select id="tailleMatch_fin" name="tailleMatch_fin" onchange="save(3)">
                        <option value="<?php echo $critere['taille_fin']; ?>" selected disabled><?php echo $critere['taille_fin']; ?></option>
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
                      <option value="<?php echo $critere['teint']; ?>" selected disabled><?php  echo($critere['teint'] == null) ? 'Sans importance' :$critere['teint']; ?></option>
                        <option value="Clair">Clair</option>
                        <option value="Noir">Noir</option>
                        <option value="Bronze">Bronzé</option>
                        <option value="null">Sans importance</option>
                    </select>
                  </div>
                  <!-- Morphologie profil recherché -->
                  <div class="line">
                    <label for="morphMatch">Morphologie</label>
                    <select id="morphMatch" name="morphMatch" onchange="save(3)">
                        <option value="<?php echo $critere['morphologie']; ?>" selected disabled><?php  echo($critere['morphologie'] == null) ? 'Sans importance' :$critere['morphologie']; ?></option>
                        <option value="Mince">Mince</option>
                        <option value="Gros">Gros</option>
                        <option value="null">Sans importance</option>
                    </select>
                  </div>
                  <!-- Nationalité & Réligion -->
                  <div class="description" id="ori">
                    <h2>Nationalité et Religion</h2>
                  </div>
                      <!-- Nationalité -->
                  <div class="line">
                    <label for="">Nationalité:</label>
                    <select class="" name="nationaliteMatch" onchange="save(3);">
                      <option value="<?php echo $critere['nationalite']; ?>" selected disabled><?php  echo($critere['nationalite'] == null) ? 'Sans importance' :getNationalite($critere['nationalite']); ?></option>
                      <option value="null">Sans importance</option>
                      <?php include '../inscription/pays.php'; ?>
                    </select>
                  </div>
                    <!--Réligion -->
                  <div class="line">
                    <label for="religion">Religion:</label>
                    <select name="religionMatch" onchange="save(3);">
                        <option value="<?php echo $critere['religion']; ?>" selected disabled><?php  echo($critere['religion'] == null) ? 'Sans importance' :$critere['religion']; ?></option>
                        <option value="null">Sans importance</option>
                        <option value="Judaisme">Judaisme</option>
                        <option value="Christianisme">Christianisme</option>
                        <option value="Islam">Islam</option>
                        <option value="Boudhisme">Boudhisme</option>
                        <option value="Inconnue">Autre..</option>
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
                <form class="" action="index.html" method="post" name="form_desc" id="from_desc">

                  <!-- Taille User -->
                  <div class="line">

                    <select id="tailleUser" name="tailleUser" onchange="save(3)">
                        <option value="" selected disabled>Votre taille (cm)</option>
                    </select>
                  </div>
                  <!-- Teint User -->
                  <div class="line">

                    <select id="teintUser" name="teintUser" onchange="save(3)">
                        <option value="" selected disabled>Votre teint</option>
                        <option value="Clair">Clair</option>
                        <option value="Noir">Noir</option>
                        <option value="Bronze">Bronzé</option>
                    </select>
                  </div>
                  <!-- Morphologie User -->
                  <div class="line">

                    <select id="morphUser" name="morphUser" onchange="save(3)">
                        <option value="" selected disabled>Votre morphologie</option>
                        <option value="Mince">Mince</option>
                        <option value="Gros">Gros</option>
                        <option value="Autre">Autre ...</option>
                    </select>
                  </div>

                  <!-- Info supplémentaires  User -->
                  <div class="line">
                    <textarea name="name" rows="8" cols="80" placeholder="Informations supllémentaires"></textarea>
                  </div>

                  <div class="line">
                    <input type="submit" name="" value="Sauvegarder" disabled id="btn_pref">
                    <input type="reset" name="" value="Annuler" id="btn_pref_annuler" onclick="javascript:document.getElementById('btn_pref').disabled = true;this.style.display='none';">
                  </div>
                </form>
              </div>
              <!-- Fin Div Ma description personnelle -->

              <!-- Div Mes centres d'intérêts -->
              <div class="content" id="centre_interet">

                <div class="description">
                  <h2>Mes centres d'intérêt</h2>
                </div>
                <form class="" action="index.html" method="post" name="form_ci" id="from_ci">

                  <div class="line">
                    <input type="submit" name="" value="Sauvegarder" disabled id="btn_pref">
                    <input type="reset" name="" value="Annuler" id="btn_pref_annuler" onclick="javascript:document.getElementById('btn_pref').disabled = true;this.style.display='none';">
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
          var y = document.querySelectorAll('#form_pref select,input[type="text"]');
          if (n==1) {
            document.getElementById('btn_mail').disabled = !document.getElementById('btn_mail').disabled;
          }else if(n==2) {
            if (x[0].value != x[1].value) {
              if(x[1].value ==""){
                document.getElementById('btn_mdp').disabled = true;
              }else {
                console.log(x[0].value+','+x[1].value);
                alert("Les deux champs ne correspondent pas!!");
                document.getElementById('btn_mdp').disabled = true;
              }
            }else {
              document.getElementById('btn_mdp').disabled = false;
            }
          }else {
            document.getElementById('btn_pref').disabled = false;
            document.getElementById('btn_pref_annuler').style.display = "block";
          }
        }
      </script>
      </body>
    </html>
