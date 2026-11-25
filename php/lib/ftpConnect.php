<?php

$ftpServer = "localhost";
$ftpUser = "nscco";
$ftpPass = "gerardo";
$conn = ftp_connect($ftpServer);
ftp_login($conn, $ftpUser, $ftpPass);
ftp_close($conn);

?>