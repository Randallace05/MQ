<?php
session_start();
session_destroy(); // Destroy all session data
echo "
<script>
    alert('You have been logged out.');
    window.location.href = '../index.php';
</script>
";
?>