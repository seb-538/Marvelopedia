<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
        die(); 
    }
    
    include "../src/ddb_init.php";

    $dtb = (new Database())->get_db_connect();

    $hash = md5("1" . /*your private key here*/ . /*your public key here*/);

    function queryMarvelDatas($offset, $hash, $dtb)
    {
        $curl = curl_init("https://gateway.marvel.com:443/v1/public/characters?offset=".$offset."&limit=100&ts=1&apikey="./*your public key here*/."&hash=".$hash);

        curl_setopt_array($curl, [
            CURLOPT_CAINFO          => __DIR__.DIRECTORY_SEPARATOR . 'certificat.cer',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 10
        ]);
        
        $data = curl_exec($curl);

        if (!$data)
            var_dump(curl_error($curl));
        else{
            $charactersArray = json_decode($data, true);
            $sql = "INSERT INTO characters (name, image_url) VALUES (:name, :image_url)"; 
            $stmt = $dtb->prepare($sql);
            
            foreach ($charactersArray["data"]["results"] as $key => $value){ // a loop to get all elements from the marvel's api
                $stmt->bindValue(':name', $value["name"]);
                $stmt->bindValue(':image_url', $value["thumbnail"]["path"].".".$value["thumbnail"]["extension"]);

                if ($stmt->execute())
                    echo $value["name"]." have been correctly added to the database".PHP_EOL;
                else
                    echo "Something went wrong and ".$value["name"]." has'nt been added to the database".PHP_EOL;
            }
            if ($charactersArray["data"]["offset"] + $charactersArray["data"]["count"] != $charactersArray["data"]["total"])
              queryMarvelDatas($offset + 100, $hash, $dtb);
         }
        curl_close($curl);
    }
    queryMarvelDatas(0, $hash, $dtb);
?> 