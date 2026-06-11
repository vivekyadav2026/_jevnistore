<?php
require_once 'includes/db.php'; // defines BASE_URL and starts session
require_once 'includes/functions.php'; // defines redirect()
session_destroy();
redirect(BASE_URL . '/login.php');

