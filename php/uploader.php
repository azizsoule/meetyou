<?php
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["picture"]["name"]);
  $uploadOk = true;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


  if(isset($_POST["changePic"])) {

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

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk) {
      if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
    // if everything is ok, try to upload file
    else {
      echo "Sorry, your file was not uploaded.";
    }

  }
?>
