<?php
	if(!session_id())
		session_start();
	/* Vérifie l'existence du cookie de login automatique */
	if(isset($_COOKIE['user_visa']))
		header("Location:login.php?autoLogin=1");
	
	$user = "";
	if(isset($_GET['user']))
		$user = $_GET['user'];
	if(isset($_GET['error']))
	{
		echo "<font color = 'red'>".$_GET['error']."</font>";
		$_SESSION['userId'] = $user;
	}
	if(isset($_GET['message']))
		echo "<font color = 'green'>".$_GET['message']."</font>";
	
	require_once("fonction.php");
	htmlHeader();
	?>
	<br/>Bonjour, bienvenue sur le site qui vous aide à manager les croissants du vendredi.<br/>
	Si vous êtes une entreprise (par exemple), que vous mangez les croissants ensembles le vendredi et que vous faites un tournus, ce site est fait pour vous.<br/><br/>
    <form action = 'login.php' method = 'post'>
        <table border = '0'>
            <tr>
                <td>login :</td><td><input type = 'text' name = 'login'
				<?php
					echo "value = ".$user;
				?>
				></td>
            </tr>
            <tr>
                <td>password :</td><td><input type = 'password' name = 'password'></td>
            </tr>
            <tr>
                <td><input type = 'checkbox' name = "loginAuto" title="Nécessite d'activer les cookies">login auto</td>
				<td><input type = 'submit' name = 'submit' value = 'Entrer'></td>
            </tr>
			<tr>
				<td colspan="2"><a href = 'createAccount.php'>Créer un compte</a>
			</tr>
			<tr>
				<td colspan="2"><a href = 'resetPassword.php'>J'ai perdu mon password :'(</a>
			</tr>
        </table>
    </form>

</body></html>