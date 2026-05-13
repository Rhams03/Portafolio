<?php
session_start();      // Abre la sesión que existe
session_destroy();    // La destruye completamente
header("Location: index.php"); // Manda al index
exit();
?>