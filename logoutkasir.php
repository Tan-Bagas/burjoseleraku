<?php
session_start();
session_destroy();
header("Location: login_kasir.php"); 
exit;