<?php
/**
 * Admin Logout Handler
 * Destroys session and redirects to login
 * 
 * @version 1.0
 * @date 2026
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

initSession();

// Destroy session
$_SESSION = [];
session_destroy();

// Redirect to login
redirect('/EnglishNewsApp/admin/login.php');
