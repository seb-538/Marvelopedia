<?php 
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
        die();
    }

    error_reporting(0);

    if(isset($_POST["name"]))
    {
        $hash = md5("1" . /*your public key here*/ . /*your public key here*/);
        $curl = curl_init("https://gateway.marvel.com:443/v1/public/characters?name=".htmlspecialchars($_POST["name"])."&ts=1&apikey="./*your public key here*/."&hash=".$hash);
        curl_setopt_array($curl, [
            CURLOPT_CAINFO          => __DIR__.DIRECTORY_SEPARATOR.'..\scripts\certificat.cer',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 10
        ]);

        if ($data = curl_exec($curl))
        {
            $response = json_decode($data, true)["data"]["results"][0]["description"];
            if ($response)
                echo ($response);
            else
                echo "Aucune description disponible.";
        }
    }    
?>