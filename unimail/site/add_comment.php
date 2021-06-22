<?php
session_start();
include 'functions.php';

if(!isset($_SESSION['S_type']))
header('Location: login.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    echo 2;
    $conn = new mysqli('localhost', 'root', '', 'unimaildb');
    $post_id = $conn->real_escape_string($_POST['post_id']);
    $contexts = $conn->real_escape_string($_POST['comment']);
    $owner = $conn->real_escape_string($_SESSION['S_id']);

    try
    {
        $sql = $conn->query("INSERT INTO `comments` (`post`, `owner`, `contexts`, `date`, `approved`) VALUES ('$post_id', '$owner', '$contexts' , current_timestamp(), '0'); ");
        echo $post_id.$owner.$contexts;

    }catch (exception $e)
    {
        alert($e);
    }
    
    header('Location: post.php?post='.$post_id);//put post id as get
}

?>