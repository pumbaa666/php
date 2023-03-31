<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	echo "Bienvenue sur le site qui n'accorde aucune pitié aux croissants ! Chaque vendredi nous allons - ensemble - en éradiquer le plus possible !";
	echo "<br/><br/>Pour participer à ce joyeux massacre (joyeux pour les papilles, mais moins pour le tour de taille...) cliquez sur <a href = 'mainFrame.php?page=liste'>Liste</a>, dans le menu en haut, et allez vous inscrire.";
	echo "<br/><br/><b>Note : Le site est en version beta, si vous trouvez des bugs <a href = 'mailto:loic.correvon@elca.ch'>contactez-moi</a></b>";
	

?>

<?php
	htmlFooter();
?>