# LYFLY 

#### INSTRUCTIONS TO RUN ON LOCAL COMPUTER
- ##### Clone the project into your System
- ##### Open your apache server (eg. MAMP, XAMPP) 
	- ##### In the settings , change the server root to nik-master(cloned project name)/public directory

- ##### Open terminal
  - ##### cd to nik-master/app
  - ##### run this command 
>   php composer.phar require google/apiclient:"^2.0"
  - ##### this will download vendor folder (which is used for google sign-in) in the app directory  
- ##### Open localhost/phpmyadmin and import sqlfile.sql

- ##### Visit localhost in the browser and the app should pop up

