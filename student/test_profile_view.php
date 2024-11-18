<?php
ob_start();
include 'profile_view.php';
$output = ob_get_clean();
file_put_contents('debug_output.txt', $output);
?>
