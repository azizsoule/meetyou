<?php

  include '../../config/Database.php';
  include '../../models/Individu.php';

  session_start();

  if (isset($_SESSION['user'])) {

    $user = $_SESSION['user'];


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
              <a href="#infos_perso"><h4>Mes informations personnelles</h4></a>

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

                  <input id="btn_mail" type="submit" name="" value="Enregistrer" disabled>
                </form>

                <!-- Modification mot de passe -->
                <div class="description">
                  <h2>Modification du mot de passe</h2>
                </div>
                <form class="" action="settings.php" method="post" name="form_mdp" id="form_mdp">
                  <div class="line">
                    <label for="new_mdp">Mot de passe:</label>
                    <input type="password" name="new_mdp" value="" placeholder="Entrer votre nouveau mot de passe ici">
                  </div>
                  <div class="line">
                    <label for="conf_new_mdp">Confirmation :</label>
                    <input type="password" name="conf_new_mdp" value="" placeholder="Confirmer le mot de passe" onchange="save(2);">
                  </div>
                  <input id="btn_mdp" type="submit" name="" value="Enregistrer" disabled >
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
                <form class="" action="index.html" method="post" name="form_pref" id="from_pref">
                  <!-- Sexe -->
                  <div class="line">
                    <label>Sexe recherché :</label>
                    <select required name="sexeMatch">
                        <option disabled selected value="">Sexe</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Inconnu">Autre ...</option>
                    </select>
                  </div>

                  <div class="line">
                    <!-- Age -->
                    <label>Tranche d'age recherchée :</label>
                    <select required id="ageMatch_inf" name="ageMatch_deb" onchange="sendvalue('ageMatch_inf','ageMatch_sup');save(3)">
                      <option value="" selected disabled>0</option>
                    </select>
                    <label for="ageMatch_sup">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspà</label>
                    <select required id="ageMatch_sup" name="ageMatch_fin" onchange="save(3)" >
                      <option value="" selected disabled>0</option>
                    </select>
                  </div>
                  <!-- Taille -->
                  <div class="line">
                    <label>Taille (cm) comprise entre :</label>
                    <select required id="tailleMatch_inf" name="tailleMatch_deb" onchange="sendvalue('tailleMatch_inf','tailleMatch_sup');save(3)">
                        <option value="" selected disabled>0</option>
                    </select>
                    <label for="tailleMatch_sup">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspet</label>
                    <select required id="tailleMatch_sup" name="tailleMatch_fin" onchange="save(3)">
                        <option value="" selected disabled>0</option>
                    </select>
                  </div>
                  <!-- Nationalité et Réligion -->
                  <div class="description" id="ori">
                    <h2>Nationalité et Religion</h2>
                  </div>
                      <!-- Nationalité -->
                  <div class="line">
                    <label for="">Nationalité:</label>
                    <select class="" name="nationaliteMatch" onchange="save(3);">
                      <option disabled selected value="">Nationalité</option>
                      <option value="null">Sans importance</option>
                      <?php include 'pays.php'; ?>
                    </select>
                  </div>
                    <!--Réligion -->
                  <div class="line">
                    <label for="religion">Religion:</label>
                    <select name="religionMatch" onchange="save(3);">
                        <option disabled selected value="">Religion</option>
                        <option value="null">Sans importance</option>
                        <option value="Judaisme">Judaisme</option>
                        <option value="Christianisme">Christianisme</option>
                        <option value="Islam">Islam</option>
                        <option value="Boudhisme">Boudhisme</option>
                        <option value="Inconnue">Autre..</option>
                    </select>
                  </div>

                  <div class="line">
                    <input type="submit" name="" value="Sauvegarder" disabled id="btn_pref">
                    <input type="reset" name="" value="Annuler" id="btn_pref_annuler" onclick="javascript:document.getElementById('btn_pref').disabled = true;this.style.display='none';">
                  </div>
                </form>
              </div>
              <!-- Fin Div Vos preferencess -->
            </div>

          </div>
        </div>

        <?php include '../../stuffs/footer.php'; ?>

      <script type="text/javascript">
      setvalue("ageMatch_inf",18,80);
      setvalue("tailleMatch_inf",100,200);


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

    <?php

  } else {

    header('location: ../../');

  }


 ?>
