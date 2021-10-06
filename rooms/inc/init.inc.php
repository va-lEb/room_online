<?php

// connexion à la base de donnée

$host = 'mysql:host=localhost;dbname=rooms';
$login = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // gestion  des erreurs
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
);
$pdo = new PDO($host, $login, $password, $options);


// ouverture de session
session_start();

// Déclaration de constantes
    // Constante pour URL absolue
    define('URL', 'http://localhost/DIW60/php/rooms/');

    // chemin racine serveur pour l'enregistrement des photos
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

    // Chemin depuis notre serveur vers le dossier du projet
    define('PROJECT_PATH', '/DIW60/php/rooms/');

    // mail de confirmation de l'inscription
    define('welcometext', 'Félicitations ! Votre inscription est enregistrée !');

    // adresse destinatrice des msg de la page contact.php
    define('EMAIL_ADMIN', 'ebengue.valery@gmail.com');
    

// var destinée a afficher les msg utilisateur. Cette var est appelée en dessous du titre de nos pages
$msg = '';