<?php
session_start();

require_once('views/Alert.php');
require_once('models/Application.php');
Application::process();
