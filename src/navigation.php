<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
        die(); 
    }

function print_page_number($startnumber, $repetition, $currentPage, $href)
{
    $page_block = "";
    for($i = $startnumber; $i < ($startnumber + $repetition); $i++)
    {
        if ($i == $currentPage)
             $page_block .= "<a id='currentPage'href='".$href."?page=".$i."'>".$i."</a>";
        else
             $page_block .= "<a href='".$href."?page=".$i."'>".$i."</a>";
    }
    return $page_block;
}

function print_navigation($currentPage, $href, $totalPages)
{
    echo "<div id='navigation' align='center'>";
    echo "<a href='".$href."?page=".($currentPage-1)."'><</a>";
    if ($currentPage > 1)
    {
        echo (print_page_number(1, 1, $currentPage, $href));
        if($currentPage - 2 >= 1 && $currentPage < $totalPages)
            echo (print_page_number($currentPage-1, 3, $currentPage, $href));
        else if ($currentPage == $totalPages)
            echo (print_page_number($currentPage-2, 3, $currentPage, $href));
        else
            echo (print_page_number($currentPage, 2, $currentPage, $href));
    }
    else
        echo (print_page_number($currentPage, 3, $currentPage, $href));
    if ($currentPage + 2 <= $totalPages)
            echo (print_page_number($totalPages, 1, $currentPage, $href));          
    echo "<a href='".$href."?page=".($currentPage+1)."'>></a>";
    echo '</div>';
}
?>