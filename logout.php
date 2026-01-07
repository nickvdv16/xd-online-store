<?php
session_start();
session_unset();
session_destroy();

header('Location: /online_store/index.php');
exit;