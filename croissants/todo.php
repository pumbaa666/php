<?php
	require_once("fonction.php");
	htmlHeader();
?>
<dl>
	<dt>- rediriger les gens qui ont tenté d'acceder à une page, mais n'étaient pas loggés</dt>
	<dt>- Flux RSS :-p</dt>
	<dt>- email hébdomadaire (mercredi?) avec Top3 et Flop3 des croissanteurs</dt>
	<dt>- Passer la date sélectionnée dans la session, pour la garder tout au long de la navigation</dt>
	<dt>- Tenir compte des préférences utilisateurs</dt>
		<dd>• envoyer flux rss avant chaque vendredi, avec le nom de l'apporteur</dd>
		<dd>• Recevoir le mail de notification</dd>
	<dt>- Voir se qu'il se passe quand on se renvoie un password oublié sur un compte non-validé</dt>
	<dt>- Relation n-n entre table user et settings (voir com' ci-dessous)</dt>
	<dt>- Créer un bouton pour envoyer un flux rss à tout les participants dès qu'on va prendre les croissants</dt>
	<dt>- Débugger le changement de password avec login as</dt>
	<dt>- Ne pas afficher les message d'erreur au login lorsque le user n'a pas coché Login auto et qu'une précédente session de FF est réstaurée</dt>
	<dt>- Ajouter un design quand même une fois (CSS pour voir ?)</dt>
	<dt>- Débugger le calendrier (un warning "notice" apparait quand on sélectionne un dimanche)</dt>
	<dt>- Optimiser les codes d'erreurs dans error.php et dans creatingAccount.php</dt>
	<dt>- Checker toutes les erreurs d'un coup dans creatingAccount.php</dt>
	<dt>- Faire la distinction entre l'erreur du visa à double ou de l'email à double lors de la création d'un compte (Duplicata)</dt>
</dl>
<?php
	htmlFooter();
/* A faire : 
[13:55] Al: ben 3 tables au total
la table user
la table settings
et la table intermediaire
[13:55] Al: genre pour la table settings t'auras ça comme champs
id_setting
option1
option2
page d'accueil
etc..
[13:56] Al: et l'intermédiaire t'auras
fk_id_user
fk_id_setting
valeur
*/

/* Toute la conv' : 
[13:43] Pumbaa: dis-moi, toi qui fait un peu de sql de temps en temps
[13:43] Pumbaa: est-ce que tu éprouves des remords à faire des relations 1-1 entre 2 tables ?
[13:44] Al: alors honnetement oui
dans certains pays c'est considéré comme un crime

mais je suis toujours ouvert à la discussion et si y a une bonne raison pourquoi pas
[13:44] Pumbaa: merde, je me disais aussi
[13:45] Pumbaa: bah en fait j'ai une table user
[13:45] Pumbaa: et une table settings
[13:45] Pumbaa: et chaque user à 1 settings
[13:45] Pumbaa: (c'est pour enregistrer leur préférences : page d'accueil du site, montrer son email aux autres, etc)
[13:45] Pumbaa: mais j'ai hésité longtemps a insérer ces données directement dans la table user
[13:45] Pumbaa: tu penses que ça aurait été préférable ?
[13:46] Al: si je comprends bien. un settings c'est toute une série de champ alors?
[13:46] Pumbaa: oui
[13:46] Al: et tout ces champs sont bollean?
[13:46] Pumbaa: non
[13:46] Al: boolean
[13:46] Pumbaa: y'a du bool, du int et du varchar ^^
[13:47] Pumbaa: j'ai aussi hésité à faire de la table settings une table avec 3 champ : un FK sur le user, une clé, une valeur
[13:47] Pumbaa: dans ce cas ça serait une relation 1-n, nettement plus acceptable ^^
[13:47] Pumbaa: mais moins ordré .)
[13:47] Pumbaa:
[13:48] Al: ouais, bon le truc c'est que j'ai pas tout ça sous les yeux alors c'est difficile de me rendre bien compte

mais tout mettre dans la table user serait préférable
mais après tu peux te retrouver avec une table user de 4'000 champs ce qui est pas terrible non plus
[13:48] Pumbaa: exact !
[13:49] Al: ton idée de table à 3 champs pourquoi pas, mais dans ce cas t'auras une relation n-n si j'ai bien capté
[13:49] Pumbaa: oui, juste
[13:49] Al: car t'auras plusieurs settings pour un seul type
[13:49] Pumbaa: voilà
[13:49] Al: et un meme settings pour plusieurs types
[13:49] Pumbaa: tout à fait
[13:49] Al: enfin une meme clé
[13:49] Al: ouas ok
[13:50] Al: donc il te faudra encore une table intermédiaire si tu fais ça
[13:50] Pumbaa: ah bon ? ^o)
[13:50] Pumbaa: il ne me semble pas
[13:50] Al: ben relation n-n
[13:50] Al: tu viens de me dire "tout à fait" lol
[13:50] Al: qui dit n-n dit table intermédiaire
[13:50] Pumbaa: alors y'a un terme sur lequel on ne doit pas s'entendre
[13:51] Al: c'est bien possible par msn c'est pas facil
[13:51] Pumbaa: car si dans la table settings j'ai le nom du user, je peux tout retrouvé ^^
[13:51] Pumbaa: ouais
[13:51] Al: bon déja ce sera pas le nom mais son id
[13:51] Pumbaa: oui, mais dans mon cas c'est le même
[13:51] Pumbaa: le nom (enfin, le visa) = clé primaire
[13:51] Al: d'acc
[13:51] Al: donc ta table de 3 champs si j'ai bien compris elle aura
[13:52] Al: le fk_id du user
[13:52] Al: une clé (le nom du setting par exemple "page d'accueil")
[13:52] Al: et sa valeur?
[13:52] Pumbaa: oui
[13:52] Al: ok donc tu pourrais avoir par exemple ces enregistrements là :
[13:53] Al: 1. user5 - page d'accueil - www.txt.ch
2. user5 - option à la con - va chier
3.user8 - page d'accueil - www.touchetoi.com
[13:53] Pumbaa: ouaip ^^
[13:54] Al: ouais donc t'as bien une relation n-n
mais tu peux laisser comme ça sans table intermédiaire puisque tes préocupations seront juste de retrouver les options d'un user à la fois

[13:54] Pumbaa: oui ^_^
[13:54] Al: c'est juste pas très conventionnel on dira
[13:55] Pumbaa: ok, c'est quoi le conventionnel alors ?
[13:55] Pumbaa: une table avec le nom des settings ?
[13:55] Al: ben 3 tables au total
la table user
la table settings
et la table intermediaire
[13:55] Al: oui
[13:55] Pumbaa: je vois
[13:55] Al: genre pour la table settings t'auras ça comme champs

id_setting
option1
option2
page d'accueil
etc..
[13:56] Al: et l'intermédiaire t'auras

fk_id_user
fk_id_setting
valeur
[13:56] Pumbaa: ouais, j'y ai pensé après que tu m'ai dit qu'il faudrait 3 tables
[13:56] Pumbaa: ^^
[13:56] Al: ^^
[13:56] Al: ça c'est déjà plus propre
à peine plus chiant pour les requêtes sql au début
mais tu seras content le jour ou il faut ajouter une option
[13:56] Pumbaa: exact
[13:56] Pumbaa: et je vais en rajouter à la pelle xD
[13:56] Pumbaa: je trouve une idée par jour
[13:57] Pumbaa: faudra donc que je me penche sur la question ^^
*/
?>