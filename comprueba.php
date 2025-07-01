<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /central2/index.php");
    exit();
}
