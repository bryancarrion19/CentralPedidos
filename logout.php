<?php
session_start();
session_destroy();
$_SESSION = [];
header("Location: /central2/index.php");
exit();
