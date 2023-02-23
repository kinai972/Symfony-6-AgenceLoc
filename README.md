
# Symfony 6 - AgenceLoc

Il s'agit d'un projet simple de site location de voitures qui permet aux membres de réserver des voitures pour la location et aux administrateurs de gérer les véhicules, les membres et les commandes.


## Aperçu

![Aperçu de l'admin pour la gestion des véhicules](https://github.com/kinai972/symfony-location/blob/master/public/images/screenshots/admin_vehicle.png)


## Fonctionnalités

- Inscription et connexion des membres
- Affichage des véhicules disponibles pour la location
- Réservation de véhicules par les membres
- Gestion des membres, des véhicules et des commandes par les administrateurs (CRUD)

## Technologies utilisées

- Symfony 6 (PHP 8 minimum)
- MySQL
- Bootstrap 5
- VichUploader
- ORM-fixtures (Faker PHP)

## Installation

1. Cloner le repository : 
```bash
  git clone https://github.com/votre-repo.git
```

2. Installer les dépendances :
```bash
  composer install
```

3. Configurez la base de données dans le fichier .env à la racine du projet

4. Créer la base de données :
```bash
  php bin/console doctrine:database:create
```

5. Créer les tables :
```bash
  php bin/console doctrine:migrations:migrate
```
Ou alors :
```bash
  php bin/console doctrine:schema:update --force
```

5. Générer les fixtures :
```bash
  php bin/console doctrine:fixtures:load
```

6. Lancer le serveur (Si vous possédez Symfony CLI, utilisez la commande ci-dessous)
```bash
  symfony serve
```



## Documentations utiles

- [FakerPHP](hhttps://fakerphp.github.io/)
- [VichUploader](hhttps://fakerphp.github.io/)
## Accès

**Admin**
- Identifiant : **admin@mail.com**
- Mot de passe : 12345

**Membre**
- Identifiant : **user@mail.com**
- Mot de passe : 12345

Sentez-vous libre de modifier ces informations dans le fichier src/Fixtures/UserFixtures.php
