<?php

  function age($date) {

    $age = date('Y') - date('Y', strtotime($date));

    if (date('md') < date('md', strtotime($date))) {
      return $age - 1;
    }

    return $age;
  }


  function getMatchs($user){

    $liste_profils = [];
    $liste_profils_compatibles = [];
    $liste_criteres = ['morphologie','teint'];

    $bdd = Database::connect();
    $req = $bdd -> prepare('SELECT * FROM individu WHERE id != :id_user'); // get all individu except $user
    $req -> execute(array('id_user' => $user->id));
    while($data = $req -> fetch()){  // if user exists
      $indiv = new Individu($data);
      $indiv->destroyPassword();
      array_push($liste_profils,$indiv);
    }
    $req -> closeCursor(); // end of processing
    $bdd = Database::disconnect();

    foreach ($liste_profils as $profil) {

      if ($profil->sexe != getCritere($user,'sexe')) {
        //pass
      }else{
        $compteur = 0;
        if ((getCritere($user,'age_deb')<= age($profil->date_naissance)) && (age($profil->date_naissance) <=getCritere($user,'age_fin')))
          $compteur++;
        //
        if ((getCritere($user,'taille_deb')<= getDescription($profil,'taille')) && (getDescription($profil,'taille') <=getCritere($user,'taille_fin')))
          $compteur++;

        if ((string)(getCritere($user,'nationalite')) != null){
          if (getNationalite(getCritere($user,'nationalite')) == getNationalite($profil->nationalite))
            $compteur++;
        }else {
          $compteur++;
        }
        if (getCritere($user,'religion') != null){
          if (getCritere($user,'religion') == $profil->religion)
            $compteur++;
        }else {
          $compteur++;
        }

        foreach ($liste_criteres as $critere) {
          if (getCritere($user,$critere) != null) {

            if(getCritere($user,$critere) == getDescription($profil,$critere))
            $compteur++;

          }else{
            $compteur++;
          }

        }
        // echo ('Compteur'.$profil->nom.' = '.$compteur);

        $taux_critere = ($compteur / 6) * 0.5;

        $liste_ci_user = getCi($user);
		    $liste_ci_profil = getCi($profil);

        $nbre_ci_commun = count(array_intersect($liste_ci_profil,$liste_ci_user));
        // echo ('Nbre_ci_commun'.$profil->nom.' = '.$nbre_ci_commun);

        $taux_ci = ($nbre_ci_commun/count($liste_ci_user))*0.5;

        $taux_compatibilite = ($taux_critere + $taux_ci) * 100;

        $profil->taux = $taux_compatibilite;

        array_push($liste_profils_compatibles,$profil);
    }

  }
  return $liste_profils_compatibles;

}

function getCritere($user,$critere){
  $bdd = Database::connect();
  $req = $bdd -> prepare('SELECT age_deb, age_fin, sexe, teint, taille_deb, taille_fin, morphologie, nationalite, religion FROM critere WHERE id_critere = :id_user');
  $req -> execute(array('id_user' => $user->id_critere));
  $data = $req -> fetch();
  return $data[$critere];

}

function getCi($user){
  $bdd = Database::connect();
  $req = $bdd -> prepare('SELECT ci FROM centre_interet WHERE id_ci = :id_user');
  $req -> execute(array('id_user' => $user->id_ci));
  $data = $req -> fetch();
  $ci = explode(",",$data['ci']);
  return $ci;
}

function getDescription($user,$description){
  $bdd = Database::connect();
  $req = $bdd -> prepare('SELECT taille, teint, morphologie FROM description WHERE id_description = :id_user');
  $req -> execute(array('id_user' => $user->id_description));
  $data = $req -> fetch();
  return $data[$description];

}

function getNationalite($id){
  $bdd = Database::connect();
  $req = $bdd -> prepare('SELECT * FROM pays WHERE id_pays = :id_user');
  $req -> execute(array('id_user' => $id));
  $data = $req -> fetch();
  return $data['nom_pays'];

}
function getVille($id){
  $bdd = Database::connect();
  $req = $bdd -> prepare('SELECT * FROM villes WHERE id_ville = :id_user');
  $req -> execute(array('id_user' => $id));
  $data = $req -> fetch();
  return $data['nom_ville'];

}

function ordonner($liste){
  for ($i=0; $i < count($liste)-1 ; $i++) {
    for ($j=$i; $j <count($liste); $j++) {
      if($liste[$i]->taux < $liste[$j]->taux){
        $temp = $liste[$i];
        $liste[$i] = $liste[$j];
        $liste[$j] = $temp;
      }

    }
  }
}

?>
