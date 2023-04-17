# myMicroBlog

Application de micro-blogging

## Specifications:
- php 8.1 (à partir de l'image v4-fpm de the-coding-machine)
- symfony 6.2 mode webapp, avec webpack-encore
- composer 2.5
- postgresql 15
- nginx 1.23.4-alpine
- node-js 19-alpine (npm@latest, jquery 3.6, bootstrap 5 et fontawesome 6)
- mailcatcher
- Versions utilisées pour le build : docker-20.10.12 / docker-compose-1.29.2

## Quality tools:
- phpstan 1.10
- squizlabs/php_codesniffer 3.7
- phpunit 9.5

## Utilisation :
- make install : build des images docker, composer install, npm install et build assets
- make start : démarrage des images php, nginx et postgresql
- make stop : arrêt des containers du projet
- make connect / node-connect : shell dans les containers php / nodejs
- make clear : vidage du cache
- make composer-update : mise à jour des vendors php
- make node-install : installation des vendors js
- make node-build : compilation des assets js et scss

- url par défaut en mode dev : http://localhost:8180
