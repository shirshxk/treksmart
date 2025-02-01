<?php
include 'db.php';


session_destroy();
header('location: ' . HOMEPAGE);
die();