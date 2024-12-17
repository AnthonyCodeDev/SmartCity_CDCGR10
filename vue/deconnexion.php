<?php

require_once __DIR__ . '/../config/config.php';

session_start();

// delete all session variables
session_unset();

// destroy the session
session_destroy();

header('Location: ' . BASE_URL);
?>
