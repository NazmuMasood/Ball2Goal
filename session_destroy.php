<?php 
session_start();
session_unset();
session_destroy();
session_start();
echo "Session unset, destroyed and new session created";
?>