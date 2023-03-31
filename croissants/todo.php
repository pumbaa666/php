<?php
	require_once("fonction.php");
	htmlHeader();
?>
<dl>
	<dt>- rediriger les gens qui ont tent� d'acceder � une page, mais n'�taient pas logg�s</dt>
	<dt>- Flux RSS :-p</dt>
	<dt>- email h�bdomadaire (mercredi?) avec Top3 et Flop3 des croissanteurs</dt>
	<dt>- Passer la date s�lectionn�e dans la session, pour la garder tout au long de la navigation</dt>
	<dt>- Tenir compte des pr�f�rences utilisateurs</dt>
		<dd>� envoyer flux rss avant chaque vendredi, avec le nom de l'apporteur</dd>
		<dd>� Recevoir le mail de notification</dd>
	<dt>- Voir se qu'il se passe quand on se renvoie un password oubli� sur un compte non-valid�</dt>
	<dt>- Relation n-n entre table user et settings (voir com' ci-dessous)</dt>
	<dt>- Cr�er un bouton pour envoyer un flux rss � tout les participants d�s qu'on va prendre les croissants</dt>
	<dt>- D�bugger le changement de password avec login as</dt>
	<dt>- Ne pas afficher les message d'erreur au login lorsque le user n'a pas coch� Login auto et qu'une pr�c�dente session de FF est r�staur�e</dt>
	<dt>- Ajouter un design quand m�me une fois (CSS pour voir ?)</dt>
	<dt>- D�bugger le calendrier (un warning "notice" apparait quand on s�lectionne un dimanche)</dt>
	<dt>- Optimiser les codes d'erreurs dans error.php et dans creatingAccount.php</dt>
	<dt>- Checker toutes les erreurs d'un coup dans creatingAccount.php</dt>
	<dt>- Faire la distinction entre l'erreur du visa � double ou de l'email � double lors de la cr�ation d'un compte (Duplicata)</dt>
</dl>
<?php
	htmlFooter();
/* A faire : 
[13:55] Al: ben 3 tables au total
la table user
la table settings
et la table intermediaire
[13:55] Al: genre pour la table settings t'auras �a comme champs
id_setting
option1
option2
page d'accueil
etc..
[13:56] Al: et l'interm�diaire t'auras
fk_id_user
fk_id_setting
valeur
*/

/* Toute la conv' : 
[13:43] Pumbaa: dis-moi, toi qui fait un peu de sql de temps en temps
[13:43] Pumbaa: est-ce que tu �prouves des remords � faire des relations 1-1 entre 2 tables ?
[13:44] Al: alors honnetement oui
dans certains pays c'est consid�r� comme un crime

mais je suis toujours ouvert � la discussion et si y a une bonne raison pourquoi pas
[13:44] Pumbaa: merde, je me disais aussi
[13:45] Pumbaa: bah en fait j'ai une table user
[13:45] Pumbaa: et une table settings
[13:45] Pumbaa: et chaque user � 1 settings
[13:45] Pumbaa: (c'est pour enregistrer leur pr�f�rences : page d'accueil du site, montrer son email aux autres, etc)
[13:45] Pumbaa: mais j'ai h�sit� longtemps a ins�rer ces donn�es directement dans la table user
[13:45] Pumbaa: tu penses que �a aurait �t� pr�f�rable ?
[13:46] Al: si je comprends bien. un settings c'est toute une s�rie de champ alors?
[13:46] Pumbaa: oui
[13:46] Al: et tout ces champs sont bollean?
[13:46] Pumbaa: non
[13:46] Al: boolean
[13:46] Pumbaa: y'a du bool, du int et du varchar ^^
[13:47] Pumbaa: j'ai aussi h�sit� � faire de la table settings une table avec 3 champ : un FK sur le user, une cl�, une valeur
[13:47] Pumbaa: dans ce cas �a serait une relation 1-n, nettement plus acceptable ^^
[13:47] Pumbaa: mais moins ordr� .)
[13:47] Pumbaa:
[13:48] Al: ouais, bon le truc c'est que j'ai pas tout �a sous les yeux alors c'est difficile de me rendre bien compte

mais tout mettre dans la table user serait pr�f�rable
mais apr�s tu peux te retrouver avec une table user de 4'000 champs ce qui est pas terrible non plus
[13:48] Pumbaa: exact !
[13:49] Al: ton id�e de table � 3 champs pourquoi pas, mais dans ce cas t'auras une relation n-n si j'ai bien capt�
[13:49] Pumbaa: oui, juste
[13:49] Al: car t'auras plusieurs settings pour un seul type
[13:49] Pumbaa: voil�
[13:49] Al: et un meme settings pour plusieurs types
[13:49] Pumbaa: tout � fait
[13:49] Al: enfin une meme cl�
[13:49] Al: ouas ok
[13:50] Al: donc il te faudra encore une table interm�diaire si tu fais �a
[13:50] Pumbaa: ah bon ? ^o)
[13:50] Pumbaa: il ne me semble pas
[13:50] Al: ben relation n-n
[13:50] Al: tu viens de me dire "tout � fait" lol
[13:50] Al: qui dit n-n dit table interm�diaire
[13:50] Pumbaa: alors y'a un terme sur lequel on ne doit pas s'entendre
[13:51] Al: c'est bien possible par msn c'est pas facil
[13:51] Pumbaa: car si dans la table settings j'ai le nom du user, je peux tout retrouv� ^^
[13:51] Pumbaa: ouais
[13:51] Al: bon d�ja ce sera pas le nom mais son id
[13:51] Pumbaa: oui, mais dans mon cas c'est le m�me
[13:51] Pumbaa: le nom (enfin, le visa) = cl� primaire
[13:51] Al: d'acc
[13:51] Al: donc ta table de 3 champs si j'ai bien compris elle aura
[13:52] Al: le fk_id du user
[13:52] Al: une cl� (le nom du setting par exemple "page d'accueil")
[13:52] Al: et sa valeur?
[13:52] Pumbaa: oui
[13:52] Al: ok donc tu pourrais avoir par exemple ces enregistrements l� :
[13:53] Al: 1. user5 - page d'accueil - www.txt.ch
2. user5 - option � la con - va chier
3.user8 - page d'accueil - www.touchetoi.com
[13:53] Pumbaa: ouaip ^^
[13:54] Al: ouais donc t'as bien une relation n-n
mais tu peux laisser comme �a sans table interm�diaire puisque tes pr�ocupations seront juste de retrouver les options d'un user � la fois

[13:54] Pumbaa: oui ^_^
[13:54] Al: c'est juste pas tr�s conventionnel on dira
[13:55] Pumbaa: ok, c'est quoi le conventionnel alors ?
[13:55] Pumbaa: une table avec le nom des settings ?
[13:55] Al: ben 3 tables au total
la table user
la table settings
et la table intermediaire
[13:55] Al: oui
[13:55] Pumbaa: je vois
[13:55] Al: genre pour la table settings t'auras �a comme champs

id_setting
option1
option2
page d'accueil
etc..
[13:56] Al: et l'interm�diaire t'auras

fk_id_user
fk_id_setting
valeur
[13:56] Pumbaa: ouais, j'y ai pens� apr�s que tu m'ai dit qu'il faudrait 3 tables
[13:56] Pumbaa: ^^
[13:56] Al: ^^
[13:56] Al: �a c'est d�j� plus propre
� peine plus chiant pour les requ�tes sql au d�but
mais tu seras content le jour ou il faut ajouter une option
[13:56] Pumbaa: exact
[13:56] Pumbaa: et je vais en rajouter � la pelle xD
[13:56] Pumbaa: je trouve une id�e par jour
[13:57] Pumbaa: faudra donc que je me penche sur la question ^^
*/
?>