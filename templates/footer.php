<?php
$current_dir = explode(DIRECTORY_SEPARATOR, getcwd());

// Ensure that them template works in multiple parts of the app.
if (end($current_dir) == "Meshes_Models_CMS") {
    $styles_location = "templates/styles/footer.css";
} else {
    $styles_location = "../templates/styles/footer.css";
}
?>

<html>
<footer>
</footer>

</html>