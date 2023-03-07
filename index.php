<?php

  session_start();

  if (isset($_SESSION['user'])) {

    header('location: user/dashboard/');

  } else {

    ?>

    <!DOCTYPE html>
    <html lang="fr" dir="ltr">
      <head>
        <meta charset="utf-8">
          <title>
              <?php
                  include("name.php");
                  echo "$name | Bienvenue sur $name !";
              ?>
          </title>
        <link rel="stylesheet" href="css/index.css">
      </head>
      <body >

        <script type="text/javascript">
            var isvisible = false;

            function showLogin(){
              var x = document.getElementsByClassName('regform2');
              x[0].style.display = "grid";
            }

            function hideLogin(){
              var x = document.getElementsByClassName('regform2');
              x[0].style.display = "none";
            }


        </script>

        <?php if (isset($_GET['success'])) {
          ?>

            <script>
              alert("Inscrition réussie. Vous pouvez vous connecter à votre compte !");
            </script>

          <?php
        } ?>

        <header>
          <nav>
            <div class="header">
              <p>Y😍u<span>Meet</span></p>
              <button class="btn-ins" type="button" name="connexion" onclick="if(isvisible) {isvisible=false;hideLogin();} else {isvisible=true;showLogin();}">Connectez vous !</button>
            </div>
          </nav>

        </header>

        <main>

          <section class="content">

            <?php if (isset($_GET['exist'])) {
              ?>

                <script>
                  alert("L'email que vous avez saisi est déja enregistré sous un autre compte !");
                </script>

              <?php
            } ?>

            <div class="regform1" id="regform1">
              <form name="reg" action="user/inscription/" method="post">
                <fieldset>
                  <legend id="headText">Inscrivez vous 💞 !</legend>

                  <fieldset class="mail">
                    <legend>Adresse Mail</legend>
                    <input type="email" name="mail_reg" required value="" placeholder="exemple@adresse.com">
                  </fieldset>

                  <fieldset class="pwd">
                    <legend>Mot de passe</legend>
                    <input type="password" required name="pwd_reg" value="" placeholder="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
      title="Doit contenir au moins un chiffre,une lettre majuscule,une lettre miniscule, et au moins 6 caractères">
                  </fieldset>
                  <fieldset class="pwd">
                    <legend>Confirmer le mot de passe</legend>
                    <input type="password" required name="conf_pwd_reg" value="" placeholder="Confirmation.." onchange="confim();">
                  </fieldset>
                  <input type="checkbox" required id="conditions" value="">
                  <label for="conditions">J'accepte les termes et les conditions d'utilisation du site</label>
                </fieldset>

                <input type="submit" name="valider" id = "valider" value="C'est parti !!!">
              </form>
            </div>

            <div class="regform2">
              <form name="login" action="php/connecting.php" method="post">
                <fieldset>
                  <img src="images/user.webp" class="logo" alt="">

                  <fieldset class="mail">
                    <legend style="font-size:17px;">Login:</legend>
                    <input type="email" id="t" required name="mail_log" placeholder="E-mail">
                  </fieldset>
                  <fieldset class="pwd">
                    <legend style="font-size:17px;">Password:</legend>
                    <input type="password" required name="pwd_log" placeholder="Mot de passe">
                  </fieldset>

                  <div class="error">
                    <p>E-mail ou mot de passe incorrect !</p>
                  </div>

                  <input type="submit" value="Se connecter">

                  <?php

                    if(isset($_GET['connexionfailed'])) {
                      ?>

                        <script type="text/javascript">
                          isvisible = true;
                          showLogin();
                          var er = document.getElementsByClassName('error');
                          er[0].style.display = "block";
                        </script>

                      <?php
                    }

                   ?>

                </fieldset>
              </form>
            </div>
          </section>

        </main>

        <!-- footer -->
        <?php include 'stuffs/footer.php'; ?>
        <!-- footer -->
        <script type="text/javascript">

          var valid = false;
          function confim(){
            var valid = true
            var x = document.querySelectorAll('#regform1 input[type="password"]');
            while ((x[0].value != x[1].value) && x[1].value !="") {
                alert("Les deux champs ne correspondent pas veuillez recommencer");
                valid = false;
                document.getElementById('valider').disabled = true;
                break;
            }
            if (valid) {
              document.getElementById('valider').disabled = false;
            }

          }

        </script>

      </body>
    </html>


    <?php

  }

 ?>
