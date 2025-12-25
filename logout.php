<?php
/**
 * PPDB SMK - Logout
 */

require_once 'config/session.php';
require_once 'config/config.php';

Session::logout();
header('Location: ' . SITE_URL . '/login.php');
exit;
