<?php
include 'includes/header.php';
?>


<main class="flex-grow-1">
<?php
$vista = isset($_GET["view"]) ? $_GET["view"] : "inicio";

if (file_exists("views/" . $vista . ".php")) {
    include "views/" . $vista . ".php";
} else {
    echo "<div class=container py-5><h1>Página no encontrada</h1></div>";
}

?>
</main>

<?php
include 'includes/footer.php';
?>

