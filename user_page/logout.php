<?php
require_once '../endpoint/session_config.php';

session_destroy(); // Destroy all session data
echo "
<script>
    alert('You have been logged out.');
    window.location.href = '../index.php';
</script>
";
?>
