# Projet Front-End Back-End Restaurant Chef Michant #

Ce fichier décrira les procédures de déploiement du site en local et sur serveur du projet
Ce projet a été réalisé sous Symfony 6.2.* 

<details>
  <summary>Sommaire</summary>
  <ol>
    <li>
      <a href="#déploiement-en-local-du-projet">Déploiement en local du projet</a>
      <ul>
        <li><a href="#cloner-le-projet">Cloner le projet</a></li>
        <li><a href="#base-de-données">Base de données</a></li>
      </ul>
    </li>
    <li>
      <a href="#déploiement-du-projet-sur-serveur-distant">Déploiement du projet sur serveur distant</a>
      <ul>
        <li><a href="#cloner-le-projet-sur-serveur">Cloner le projet sur serveur</a></li>
        <li><a href="#base-de-données-sur-serveur">Base de données sur serveur</a></li>
        <li><a href="#environnement-et-mises-à-jour">Environnement et mises à jour</a></li>
      </ul>
    </li>
  <ol>
</details>

## Déploiement en local du projet ##

### Cloner le projet ###

*Cloner le projet dans le répertoire apps de votre dossier XAMPP (par exemple)
```sh
git clone https://github.com/enLussi/projet-restaurant-michant.git
```
et placer vous sur la branche master

*Dupliquer le fichier .env.local et .env et modifier les données de connexion à une base de données local

*Vérifier que composer est installé sur votre machine (sinon installez-le https://getcomposer.org/) et lancez la commande
```sh
composer install
```
pour installer les dépendances nécessaire à Symfony

### Base de données ###

*Pour créer la base de données du projet dans votre base de données local, lancer la commande 
```sh
symfony console doctrine:database:create
```
ou
```sh
php bin/console doctrine:database:create
```
puis faire les migrations nécessaire pour créer les tables dans la base de données avec les commandes
```sh
symfony console make:migration
```
```sh
symfony console doctrine:migrations:migrate
```
*Le projet contient des Data Fixtures pour les valeurs par défaut de la base de données.
La Fixture AdminFixtures contient les éléments nécessaires à la création d'un utilisateur administrateur par défaut. Vous pouvez modifier ces données à votre convenance.
Pour ce faire, modifier le fichier new_admin.json à la racine du projet avec les données que vous souhaitez (le mot de passe sera hasher par la classe Fixtures).

<strong>Vous ne pouvez pas paramétrer plusieurs comptes administrateurs en même temps.</strong>

*Après avoir paramétré votre compte administrateur lancer les Fixtures pour peupler la base de données des valeurs nécessaires au fonctionnement du site.
```sh
symfony console doctrine:fixtures:load
```
*Pour rajouter un SEUL administrateur, vous pouvez rééditer le fichier new_admin.json avec les nouveaux paramétres.
Puis vous devez utiliser la commande
```sh
symfony console doctrine:fixtures:load --group=AdminFixtures --append
```
Cette commande lancera à nouveaux la création de ligne grâce à la fixtures AdminFixtures en rajoutant dans la base de données (sans purger la base de données).

## Déploiement du projet sur serveur distant ##

### Cloner le projet sur serveur ###

*Cloner le projet dans le répertoire apps de votre dossier XAMPP (par exemple)
```sh
git clone https://github.com/enLussi/projet-restaurant-michant.git
```
et placer vous sur la branche master

*Dupliquer le fichier .env.local et .env et modifier les données de connexion à une base de données local

*Vérifier que composer est installé sur votre serveur sinon installez-le. Pour un hébergeur sous Linux vous pouvez installez composer avec la commande
```sh
curl -sS https://getcomposer.org/installer | usr/bin/php8.1-cli
```
(vous pouvez changer la version de php selon votre version sur le serveur)
ou référer vous à la documentation de votre hébergeur pour installer composer

*Lancez la commande
```sh
composer install
```
pour installer les dépendances nécessaire à Symfony

### Base de données sur serveur ###

Les commandes consoles utilisées sont valable pour un environnement Linux.

*Certains hébergeur permettent de créer une base de données depuis leur interface utilisateur.
Au besoin, vous pouvez créer la base de données avec doctrine
```sh
/usr/bin/php8.1-cli bin/console doctrine:database:create
```

puis faire les migrations nécessaire pour créer les tables dans la base de données avec les commandes
```sh
/usr/bin/php8.1-cli bin/console make:migration
```
```sh
/usr/bin/php8.1-cli bin/console doctrine:migrations:migrate
```
*Le projet contient des Data Fixtures pour les valeurs par défaut de la base de données.
La Fixture AdminFixtures contient les éléments nécessaires à la création d'un utilisateur administrateur par défaut. Vous pouvez modifier ces données à votre convenance.
Pour ce faire, modifier le fichier new_admin.json à la racine du projet avec les données que vous souhaitez (le mot de passe sera hasher par la classe Fixtures).

<strong>Vous ne pouvez pas paramétrer plusieurs comptes administrateurs en même temps.</strong>

*Après avoir paramétré votre compte administrateur lancer les Fixtures pour peupler la base de données des valeurs nécessaires au fonctionnement du site.
```sh
/usr/bin/php8.1-cli bin/console doctrine:fixtures:load
```
*Pour rajouter un SEUL administrateur, vous pouvez rééditer le fichier new_admin.json avec les nouveaux paramétres.
Puis vous devez utiliser la commande
```sh
/usr/bin/php8.1-cli bin/console doctrine:fixtures:load --group=AdminFixtures --append
```
Cette commande lancera à nouveaux la création de ligne grâce à la fixtures AdminFixtures en rajoutant dans la base de données (sans purger la base de données).

### Environnement et mises à jour ###

*N'oubliez pas de passer l'environnement de DEV à PROD dans votre fichier .env

*Si des mises à jours sont à apporter et ne concernent pas la base de données, n'oubliez pas de vider le cache de Symfony
```sh
usr/bin/php8.1-cli bin/console cache:clear
```
