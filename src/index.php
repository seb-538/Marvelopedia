<!DOCTYPE html>
<html>
    <head>
        <title>Marvel Catalogue</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="../javascript/scripts.js"></script>
    </head>

    <body >
        <div class="container"> <!-- main container with the characters grid -->

          <?php
                include 'ddb_init.php';
                include 'navigation.php';

                $dtb = (new Database())->get_db_connect();
            
                $characterPerPage = 16;
                $sql = "SELECT COUNT(*) AS total FROM characters";
                $totalCharacters = $dtb->query($sql)->fetchColumn();
                $totalPages = ceil($totalCharacters/$characterPerPage);

                if (isset($_GET["page"]) && $_GET["page"] > 0)
                {
                    $currentPage=intval($_GET["page"]);

                    if ($currentPage > $totalPages)
                        $currentPage = $totalPages;
                }
                else
                    $currentPage=1;

                $firstElement = ($currentPage - 1)*$characterPerPage;
                $sql = "SELECT * FROM characters ORDER BY name LIMIT ".$firstElement.", ".$characterPerPage.'';
                foreach ($dtb->query($sql) as $row){
                    echo "<div class=item>".
                    '<img class="zoom" src="'.htmlspecialchars($row['image_url']).'" alt="'.htmlspecialchars($row['name']).'">'.
                    "<p>".htmlspecialchars($row["name"])."</p></div>";

                }

                // modal div, to get more info about a character
                echo "<div class='modal'> 
                <span class='close'>x</span>
                <img class='modalImage'/>
                <p class='modalName'></p></br>
                <p class='modalDescription'></br></p>
                </div>";

                print_navigation($currentPage, explode('?', $_SERVER['REQUEST_URI'])[0], $totalPages); // navigation buttons
            ?>
        </div>
        
        <div class="container" id= "form_block">    <!-- form to add a character -->
                <form id="form" method="post" enctype="multipart/form-data">              
                    <h1>Nouveau personnage</h1><br>
                    <input id="name" type="text" name="name" maxlength="50" required="required" placeholder="Entrer un nom"/><br>
                    <input id="upload" name="image_url" minlength="5" maxlength="50" required="required" type="text" placeholder="Choisir une image"/><br>
                    <input id="secret_upload" name="file" type="file" accept='.jpg,.jpeg,.png,.gif' value="choisir une image"/><br>
                    <input id="submit" type="submit" value="Enregistrer"/>
                    <p id="form_response"></p>
                </form>
        </div>

    </body>
</html>