<?php
session_start();
require_once '../includes/config.php';
unset($_SESSION['admin_id'], $_SESSION['admin_username']);
session_destroy();
redirect(SITE_URL . '/admin/login.php');
