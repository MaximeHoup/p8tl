# P8_OC-ToDo & Co
Améliorez une application existante

## Environnement de développement
* Symfony 7.0
* Composer 2.7
* WampServer 3.2.6
    * Apache 2.4.51
    * PHP 8.2.12
    * MySQL 5.7.36
 
## Respect des bonnes pratique
Utilisation de [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

Utilisation de [PHPStan](https://phpstan.org/user-guide/getting-started)

Codacy [![Codacy Badge](https://app.codacy.com/project/badge/Grade/d3baafda4a4142d19f2b37a675a6586a)](https://app.codacy.com/gh/MaximeHoup/p8tl/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
## Installation du projet
1.Télécharger ou cloner le repository suivant:
```
https://github.com/MaximeHoup/p8tl.git
```

2.Configurez vos variables d'environnement (connexion à la base de données, serveur SMTP...) à l'aide du fichier
```.env```

3.Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```

4.Créez la base de données grace à la commande:
```
    php bin/console doctrine:database:create
```

5.Créez les différentes tables de la base de données avec la commande :
```
    php bin/console doctrine:migrations:migrate
```
## Contribution
Si vous souhaitez contribuer au projet, merci de lire le fichier [Contribution](https://github.com/MaximeHoup/p8tl/blob/main/Contribution.md)

## Tests
Si vous souhaitez effectuer des test PHPUnit, créez d'abord les fixtures de test:
```
  php bin/console --env=test doctrine:fixtures:load
```

Puis effectuez vos tests de couverture avec la commande:
```
  vendor/bin/phpunit --coverage-html public/test-coverage
```

Accédez aux résultats en passant par le fichier
```
  public/test-coverage/index.html
```
