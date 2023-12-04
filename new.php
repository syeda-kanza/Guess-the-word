<?php
session_start();
session_destroy();
header("location: guess_the_word.php");
?>