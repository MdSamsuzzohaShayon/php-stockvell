<?php
session_start(); // Need to start a seassion in order to destroy a session
session_unset();
session_destroy();

header("Location: ../index.php?error=none");

 ?>
