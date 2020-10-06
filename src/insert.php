<?php 

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
    die(); 
}

include "ddb_init.php";

$dtb=(new Database)->get_db_connect();

$name = $_POST['name'];
$image_url = $_POST['image_url'];

$sql = "INSERT INTO characters (name, image_url) VALUES (:name, :image_url)";
$insert = $dtb->prepare($sql);
$insert->bindValue(':name', ucfirst($name));
$insert->bindValue(':image_url', $image_url);
if ($insert->execute())
    echo "OK";
else
    echo "KO";

?>