<?php
session_start(); // In every single page we should start our session at the top of our code
// echo $_SESSION['member_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockvell</title>
    <!-- <link href="public/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="custom-header bg-primary py-2 m-0">
      <?php require_once('./layouts/navbar.php'); ?>
    </header>
