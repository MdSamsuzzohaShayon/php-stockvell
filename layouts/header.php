<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stockvell</title>
  <link href="/public/css/style.css" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> -->



</head>

<body>
  <!-- Load Facebook SDK for JavaScript start -->
  <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v15.0" nonce="AFRKHuoJ"></script>
  <!-- Load Facebook SDK for JavaScript end -->

  <header class="custom-header bg-primary py-2 m-0">
    <?php require_once($ROOT . '/layouts/navbar.php'); ?>
  </header>