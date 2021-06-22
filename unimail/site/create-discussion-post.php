<?php

use function PHPSTORM_META\type;

session_start();
include 'functions.php';
include 'connect.php';
if(!isset($_SESSION['S_type']))
header('Location: login.php');



$valid_title = true;
$valid_image = true;

if($_SERVER['REQUEST_METHOD'] == 'POST' )
{
  $title = trim($_POST['title']);


  if(strlen($title)<3 || strlen($title)>60)
  {
    $valid_title = false;
  }

  else
  {

    $valid_image = check_image($_FILES['image']);
          
    if($valid_image&&$valid_title)
    {
          // move image to folder and send query

          $title = $_POST['title'];
          $ctx = $_POST['post-text'];

          $reg_stmt = $db->prepare('
          INSERT INTO des_posts(title,Contexts,owner) 
          VALUES(?,?,?)');

                    try
                  {
                    $reg_stmt->execute(array($title,$ctx,$_SESSION['S_id']));
                  }
                  catch (Exception $e)
                  {
                    alert($e);
                  }

                  if($_FILES["image"]['tmp_name']!='') {
                    
                  
                    $reg_stmt = $db->prepare('
                    SELECT Max(id) FROM des_posts
                    ');

                                try
                              {
                                $reg_stmt->execute();
                              }
                              catch (Exception $e)
                              {
                                alert($e);
                              
                                  }

                              $id_for_image = $reg_stmt->fetch()[0];

                      move_uploaded_file($_FILES['image']['tmp_name'] , $_SERVER['DOCUMENT_ROOT'].'\site\disc_posts_imgs\\'.$id_for_image.'.'.substr($_FILES['image']['type'], 6));

                      $reg_stmt = $db->prepare('
                      UPDATE des_posts
                      SET image = ? 
                      WHERE id = ?');

                                try
                              {
                                $reg_stmt->execute(array('<img style ="max-height: 800px;" src="disc_posts_imgs/'.$id_for_image.'.'.substr($_FILES['image']['type'], 6).'" class="card-img-top" alt="Post image.">',$id_for_image));
                              }
                              catch (Exception $e)
                              {
                                alert($e);
                              }
                   }

        alert('Posted successfully');


    }
    
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--styles-->
  <link rel="stylesheet" href="styles/bootstrap/bootstrap.css">
  <link rel="stylesheet" href="styles/style.css">
  <!--font poopins -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap">

  <title>Creat discussion post</title>
  <link rel="icon" href="res/unimail_icon.png"> 

</head>

  <body>
    <!-- nav bar -->
  <nav class="navbar fixed-top navbar-expand-lg shadow-sm " id="my-nav-bar">
  <div class="container-fluid">
  
    <a class="navbar-brand" id="my-brand" href="main.php">Uni Mail</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon " id="burger-menu">
      
      <!-- navbar berguer menu icon -->

      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
</svg>
      
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    <li class="nav-item">
         <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="main.php">Announcements</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="discussion.php">Discussion</a>
        </li>
      </ul>


      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        
      <li class="nav-item dropdown ms-auto"><a class="nav-link pe-0" id="userInfo" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span style="font-weight: 550;"><?php  echoName(); echoSurname();?></span></a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userInfo">
              <div class="dropdown-header text-gray-700">
                <h6 class="text-uppercase font-weight-bold"><?php echoName(); echoSurname(); ?></h6><small> <?php groupe(); ?></small>
              </div>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="myposts.php">My posts</a>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="settings.php">Settings</a>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          </li>
      </ul>
    </div>
  </div>
</nav>
<div class="navmarg"></div>
<?php wating_approval(); ?>

<br>
<!-- discussion section-->

<div class="container" >
    <div class="row my-discussion-row justify-content-center">
      <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-header  shadow-sm ps-md-5 px-md-4" id= "cardheader" style="background-color: white; font-weight : 700; font-size: large;    padding-top: 5%; padding-bottom: 5%; ">
                            <div class="card-heading text-primary">Create discussion post</div>
                            
                            </div>
                            <div class="card-body p-md-5">

                              <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label for="exampleInputTextl1" class="form-label">Title *</label>
                                    <input type="text" class="form-control mb-2 <?php isinvalid($valid_title)?>" id="exampleInputTextl1 " aria-describedby="textlHelp" name="title">
                                    <div class="invalid-feedback">
                                      Title must be betwin 3 and 60 characters.
                                    </div>
                                      <label for="post-text" class = "mb-2">Contexts</label>
                                      <textarea class = "form-control mb-2" id="post-text" name="post-text" style="height: 200px" name="contexts"></textarea> 
                                      <div class="row align-items-end">
                                        <div class="col">
                                        <div>
                                            <label for="selectphoto" class="form-label">Select photo</label>
                                            <input type="file" class="form-control <?php isinvalid($valid_image)?>" id="slectphoto" name="image">
                                            <div class="invalid-feedback">
                                               Invalid file format , or image size is over 5MB.
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col text-end">
                                            <button class="btn btn-primary " type="submit">Post</button>
                                        </div>
                                      </div>
                                </div>
                              </form>
                        </div>
                     </div>
     </div>
</div>





<!--footer -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

  </body>
</html>
