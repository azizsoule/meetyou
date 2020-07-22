<?php

  include '../models/Individu.php';
  include '../config/Database.php';

  if(isset($_POST["changePic"])) {

    session_start();

    if (isset($_SESSION['user'])) {

      $user_pic_name = $_SESSION['user']->id;

      $target_dir = "../images/profiles/";
      $target_file = $target_dir.basename($_FILES["picture"]["name"]);
      $uploadOk = true;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $target_file = $target_dir . $user_pic_name . "." . $imageFileType;

      // Allow certain file formats
      if (in_array($imageFileType,array("png","jpeg","jpg"))) {

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if($check) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = true;
        } else {
          echo "File is not an image.";
          $uploadOk = false;
        }

      }
      else {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = false;
      }

      // if everything is ok, try to upload file
      if ($uploadOk) {
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
          $bdd = Database::connect();
          $req = $bdd->prepare("UPDATE individu SET photo=? WHERE id=?");

          if ($req->execute([$user_pic_name.".".$imageFileType,$_SESSION['user']->id])) {
            echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
            header("location: ../user/dashboard");
          } else {
            unlink($target_file);
            header("location: ../user/dashboard");
          }

        } else {
          echo "Sorry, there was an error uploading your file.";
          header("location: ../user/dashboard");
        }
      }
      // Check if $uploadOk is set to false by an error
      else {
        echo "Sorry, your file was not uploaded.";
        header("location: ../user/dashboard");
      }

    } else {

      header('location: ../');

    }

  }

  // Check if file already exists
  /*if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }*/

  // Check file size
  /*if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }*/
?>
