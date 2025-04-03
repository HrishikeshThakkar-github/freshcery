<?php 


if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    session_start();
    session_unset();
    session_destroy();
    echo "<script>window.location.href='http://freshcery';</script>";
    exit();
}
?>

<script>
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php?confirm=yes";
    } else {
        window.location.href = "http://freshcery/admin-panel/admin.php";
    }
</script>


?>