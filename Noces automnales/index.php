<?php
session_start();
?>
<html><head><title>Noces Automnales</title></head><body>

    <form action = 'login.php' method = 'post'>
        <table border = '0'>
            <tr>
                <td>login :</td><td><input type = 'text' name = 'login'></td>
            </tr>
            <tr>
                <td>password :</td><td><input type = 'password' name = 'password'></td>
            </tr>
            <tr>
                <td></td><td><input type = 'submit' name = 'submit' value = 'Se saoûler'></td>
            </tr>
        </table>
    </form>

</body></html>