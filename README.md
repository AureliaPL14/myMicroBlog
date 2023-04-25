# myMicroBlog

Micro-blogging application including following and direct messages systems

## Specifications:
- PHP 8.1
- Symfony 6.2 with webpack-encore
- Composer 2.5
- PostgreSQL 15
- NGINX 1.23.4-alpine
- node-js 19-alpine (npm@latest, jquery 3.6, bootstrap 5 and fontawesome 6)
- mailcatcher
- Versions used to build : docker-20.10.12 / docker-compose-1.29.2

## Installation :
- make install : Build docker images, composer install, npm install and build assets
- make start : Start PHP, NGINX and PostgreSQL images
- make stop : Stop project containers
- make connect / node-connect : Shell CLI in php / NodeJS containers
- make clear : Empty cache
- make composer-update : Update PHP vendors
- make node-install : Install JS vendors
- make node-build : JS and CSS assets compilation

## Easy start :
- Go in project directory and use `make install`
- Then use `make start`
- In your browser go to http://localhost:8180
- Done ! ✅

## Screenshots:
- ![Screenshot of the home page displaying a post with a reply from a different user](/screenshots/screenshot_home.png)
- ![Screenshot of the profile page displaying a basic user informations, profile picture and banner picture](/screenshots/screenshot_profile.png)
- ![Screenshot displaying the direct messaging system with some conversations on the sidebar and messages from the selected conversation in the center](/screenshots/screenshot_messenger.png)