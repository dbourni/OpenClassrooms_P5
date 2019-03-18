# P5 - Projet OpenClassrooms
Créer un blog en PHP

[![Maintainability](https://api.codeclimate.com/v1/badges/a9963fd0bface3304e48/maintainability)](https://codeclimate.com/github/dbourni/OpenClassrooms_P5/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/84fb41cadbe14eb596fe818f8e60cb9a)](https://www.codacy.com/app/dbourni/OpenClassrooms_P5?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dbourni/OpenClassrooms_P5&amp;utm_campaign=Badge_Grade)

## Informations
Le thème est inspiré du thème [Freelancer](https://startbootstrap.com/themes/freelancer/) de **Start Bootstrap**.

Le code a été validé par deux services : **Codacy** et **Code Climate**. Vous pouvez accéder aux rapports d'analyses en cliquant sur les logos ci-dessus.

Vous trouverez une version auto-hébergée, pour tests, à [cette adresse](http://p5.bournisien.net). Pour l'accès à l'administration : utilisateur = admin | Mot de passe = pass

## Installation
Pour installer l'application, veuillez suivre la procédure suivante :
*  Téléchargez et dézippez les fichiers dans le dossier web de votre serveur (en général www).

*  Créez une base de données et importez le fichier openclassroomsp5.sql afin de créer les tables et les données de démonstration.

*  Editez le fichier *config-dist.php* avec les informations de connexion à votre base de données et renommez-le *config.php*.
    *  DB_HOST = 'mysql:host=AdresseDeLHote;dbname=NomBaseDeDonnées;charset=utf8'
    *  DB_USER = 'UtilisateurBaseDeDonnées'
    *  DB_PASSWORD = 'MotDePasseBaseDeDonnées'
    *  DOMAIN = '<http://NomDeDomaine>' (et dossier racine si besoin)
    
*  Ouvrez le Terminal, entrez dans le dossier de l'application et lancez l'installation via Composer : 
    ``composer install``. Les dépendances seront téléchargées.
    
*  Le blog est fonctionnel !