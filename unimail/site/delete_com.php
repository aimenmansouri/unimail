<?php
session_start();
include 'functions.php';

if(!isset($_SESSION['S_type']))
header('Location: login.php');


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['comment_id'])) {
		$conn = new mysqli('localhost', 'root', '', 'unimaildb');
		$comment_id = $conn->real_escape_string($_POST['comment_id']);

        $sql = $conn->query("SELECT * FROM `comments` WHERE id= $comment_id");
        if ($sql->num_rows > 0) {
            $data = $sql->fetch_array();
            if($data['owner']==$_SESSION['S_id'])
            {
                $sql = $conn->query("DELETE FROM `comments` WHERE `comments`.`id` = $comment_id;");  
                exit("Deleted successfully");
            }
            else
            {
                exit("User not authorized");
            }
        }
        else
        {
            exit("Comment not found");
        }
    }
}
?>