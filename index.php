<?php
session_start();

require_once('views/Alert.php');
require_once('views/Data.php');
require_once('views/Form.php');
require_once('models/Application.php');
Application::process();
