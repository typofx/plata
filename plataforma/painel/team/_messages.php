<?php
/**
 * Display success and error messages
 * Clears the messages from session after displaying
 */
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>
