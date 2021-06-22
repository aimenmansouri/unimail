<?php
session_start();
include 'functions.php';
include 'connect.php';

if(!isset($_SESSION['S_type']))
header('Location: login.php');

if($_SESSION['S_type']>2)
header('Location: main.php');

$valid_title = true;

if($_SERVER['REQUEST_METHOD'] == 'POST' )
{

    $title = trim($_POST['title']);
  
    if(strlen($title)<3 || strlen($title)>60)
    {
      $valid_title = false;
    }

  else
  {

    $ctx = $_POST['post-text'];

    $fixed;
    if(isset($_POST['fixed']) )
    {
      $fixed = 1;
    }
    else
    {
      $fixed = 0;
    }

    $groupe;
    if($_SESSION['S_type']>1)
    {
      $groupe = $_SESSION['S_groupe'];
    }
    else
    {
      $groupe = $_POST['groupe'];
    }

    $class = $_POST['class'];
    $owner = $_SESSION['S_id'];

    $reg_stmt = $db->prepare('
    INSERT INTO posts(title,Contexts,fixed,groupe,class,owner) 
    VALUES(?,?,?,?,?,?)');

    try
    {
      $reg_stmt->execute(array($title,$ctx,$fixed,$groupe,$class,$owner));
    }
    catch (Exception $e)
    {
      alert($e);
      header('Location: create-post.php');
    }
    
    alert('Posted successfully');
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

  <title>Creat post</title>
  <link rel="icon" href="res/unimail_icon.png"> 

</head>

  <body>
    <!-- nav bar -->
  <nav class="navbar fixed-top navbar-expand-lg shadow-sm" id="my-nav-bar">
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
               <h6 class="text-uppercase font-weight-bold"><?php  echoName(); echoSurname();?></h6><small><?php groupe(); ?></small>
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
                            <div class="card-heading text-primary">Create post</div>
                            
                            </div>
                            <div class="card-body p-md-5">

                              <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <div class="mb-3">
                                    <label for="exampleInputTextl1" class="form-label">Title *</label>
                                    <input type="text" class="form-control mb-2 <?php isinvalid($valid_title)?>" id="exampleInputTextl1 " aria-describedby="textlHelp" name="title">
                                    <div class="invalid-feedback">
                                    Title must be betwin 3 and 60 characters.
                                    </div>
                                      <label for="post-text" class = "mb-2" >Contexts</label>
                                      <textarea class = "form-control mb-2" id="post-text" name="post-text" style="height: 200px" name="ctx"></textarea> 
                                      <div class="text-end">
                                      <script>
                                      function addtocontexts(text)
                                      {
                                        document.getElementById("post-text").value += text;
                                      }
                                      </script>

                                      <button type="button" class = "btn btn-primary" onclick="addtocontexts('<a href=&quot;put link here&quot;>link text here</a>');">Insert link</button>

                                      </div>
                                      <div class="row align-items-end">
                                        <div class="col">
                                        <div class="form-check">
                                    <input class="form-check-input " type="checkbox"  id="flexCheck" name="fixed" <?php if ($_SESSION['S_type']==2) echo  'hidden';?>>
                                    <label class="form-check-label mb-2" for="flexCheck" <?php if ($_SESSION['S_type']==2) echo  'hidden';?>>
                                      Fixed
                                    </label>
                                  </div>
                                    <div class="input-group mb-3">

                                            <label class="input-group-text" for="inputGroupSelect01" <?php if ($_SESSION['S_type']>1) echo 'hidden';?>>Groupe</label>
                                            <select class="form-select " id="inputGroupSelect01" name="groupe"<?php if ($_SESSION['S_type']>1) echo 'hidden';?>>
                                                <option value="0">All</option>
                                                <option value="1">G1</option>
                                                <option value="2">G2</option>
                                                <option value="3">G3</option>
                                                <option value="4">G4</option>
                                                <option value="4">G5</option>
                                                <option value="6">G6</option>
                                                <option value="7">G7</option>
                                                <option value="8">G8</option>
                                                <option value="9">G9</option>
                                                <option value="10">G10</option>
                                            </select>
                                        </div>

                                        <div class="input-group">

                                            <label class="input-group-text " for="inputGroupSelect01">Class</label>
                                            <select class="form-select " id="inputGroupSelect01" name="class">
                                                <option value="0">General</option>
                                                <option value="1">Develop Mobile</option>
                                                <option value="2">Web sémantique</option>
                                                <option value="3">Sécurité informatique</option>
                                                <option value="4">Intelligence Artificielle</option>
                                                <option value="5">rédaction scientifique</option>
                                            </select>

                                        </div>


                                        </div>
                                        
                                        <div class="col text-end">
                                        
                                            <button class="btn btn-primary" type="submit">Post</button>
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
