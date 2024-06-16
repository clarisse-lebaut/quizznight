<!-- file to start a session in one file for all the programm -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
