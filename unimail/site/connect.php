<?php
    $dsn = 'mysql:host=localhost;dbname=unimaildb';
    $user = 'root';
    $pass = '';
    // databese options
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try {
    $db = new PDO($dsn , $user , $pass , $options);
    
    }catch(PDOException $e)
    {
        echo 'Failed ' . $e->getMessage();
    }
    
