<?php
require_once 'inc/utils.php';

unset($_SESSION['admin']);
session_destroy();

header('Location: '.DEF_ROOT_PATH.'/login');