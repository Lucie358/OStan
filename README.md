# O'Stan

### Projet d'apotheose pour l'école O'Clock, également présenté lors de la session de janvier 2020 pour le Titre Professionnel développeur Web et Web mobile

O'Stan a été réalisé en équipe en 4 semaines lors de ma période d'Apothéose à l'école O'Clock, puis, je l'ai continué seule une fois cette période terminé.
> Chaque promotion de l'école termine sa formation par l'Apothéose, une période d'un mois en équipe consacré à la réalisation d'un projet mobilisant toutes les compétences des étudiants. 
> Une fois le mois écoulé, chaque groupe doit présenter son projet pendant une démo de projet, retransmise en direct sur Twitch et Facebook. 
>
> La démonstration de O'Stan est visible ici: https://www.youtube.com/watch?v=YZgrCFXXboI&feature=youtu.be&t=4907

<div style="text-align:center">
<img src="public/images/stanlix.png" width="230" height="270"/>
</div>

## O'Stan, c'est quoi ?


O'Stan c'est un site communautaire pour éditeurs, illustrateurs ou auteurs qui souhaitent collaborer ensemble autour d'un projet.
Grâce à O'Stan, un illustrateur pourra trouver quelqu'un pour écrire une histoire par exemple, tandis que lui dessinerait. 

## Mais comment ?

Pour favoriser la mise en relation de ces différents métiers, quelques fonctionnalités ont été mises en place: 
* La publication d'annonces. En effet, n'importe qui (une fois inscris/connecté) pourra déposer une annonce sur le site, visible par toutes personnes connectées qui pourront l'a commenter par exemple.

* La messagerie instantanée: Les utilisateurs connectés peuvent s'envoyer des messages de manière instantanée en cliquant sur l'enveloppe du profil de la personne, ou en cliquant sur "nouveau message" dans la partie messagerie. Un système de "tchat" démarrera alors.

* Une gallerie sur son profil: Chaque utilisateur a la possibilité de partager des images sur son profil et d'ajouter une description dessus. Ainsi, les autres utilisateurs pourront voir le style de la personne concerné par exemple, ce qu'il/elle sait faire etc... d'un simple coup d'oeil.

* Un système de recherche: Pour trouver plus facilement la personne idéale, un système de recherche à été mis en place. Ce dernier fonctionne sur les critères de jobs et tags. 

<div style="text-align:center">
<img src="public/images/stangoku.png" width="230" height="270" style=""/>
</div>

### Installer O'Stan pour avoir un aperçu: 
- Cloner le repository sur sa machine. 
- Composer install
- php bin/console doc:database:create (après avoir modifié le fichier .env)
- php bin/console doc:mig:mig
- php bin/console doc:fix:load

(Le système de mail et de messagerie ne marcheront pas, c'est tout à fait normal puisqu'ils nécéssitent une configuration particlière. Si besoin d'une démonstration de ces derniers, n'hésitez pas à me contacter)

luciebrochet358@gmail.com

