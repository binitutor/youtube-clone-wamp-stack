<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            include_once "components/header.php";
        ?>
        <title>YouTube Clone</title>
    </head>
    <body>
        <?php 
            include_once "components/nav.php";

            $_SESSION['studio'] = false;
            include_once "components/sidebar-left.php";

            include_once "components/dashboard.php";

            include_once "components/footer.php";
            
            include_once "components/modal-loading.php";
        ?>
    </body>
</html>