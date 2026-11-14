<?php
session_start();

require_once __DIR__ . '/../models/connection.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController(new UserModel($conn));
$result = $authController->handle();
$error = $result['error'];
$success = $result['success'];

require __DIR__ . '/pages/login.php';
