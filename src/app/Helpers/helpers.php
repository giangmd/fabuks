<?php
if (function_exists('is_email')) {
    function is_email($email) {
        if (empty($email)) {
            return false;
        }

        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Validate e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}