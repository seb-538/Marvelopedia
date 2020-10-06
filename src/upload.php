<?php
    
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
        die();
    }

    $target_dir = "../upload_images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $result_message;

    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        }
        else {
            $result_message =  "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }
    }

    $increment = 0;
    while (file_exists($target_file)) {
        $explodedPath = explode(".", $target_file);
        $filetype = array_pop($explodedPath);
        $explodedPath[array_key_last($explodedPath)] .= $increment++;
        array_push($explodedPath, '.'.$filetype);
        $target_file = "..".implode($explodedPath);
        $uploadOk = 1;
    }

    if ($_FILES["file"]["size"] > 500000) {
    $result_message =  "Votre image ne peut pas depasser 500 Ko.";
    $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $result_message =  "Seul les formats JPG, JPEG, PNG et GIF sont autorisés.";
    $uploadOk = 0;
    }

    if ($uploadOk > 0 && move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $result_message =  "upload OK|".$target_file;
        } 

    echo $result_message;
?>