<?php
session_start();
session_destroy();
header('Location: login_cadastro.php');
exit;