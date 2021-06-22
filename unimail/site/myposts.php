<?php
session_start();
include 'functions.php';

if(!isset($_SESSION['S_type']))
header('Location: login.php');

$valid_title = true;

if($_SERVER['REQUEST_METHOD'] == 'POST' ){

  if(is_numeric($_POST['id']))
  {
    if($_POST['p_type']=='posts'||$_POST['p_type']=='des_posts')
    {
      $conn = new mysqli('localhost', 'root', '', 'unimaildb');
          $id = $conn->real_escape_string($_POST['id']);
          $p_type = $conn->real_escape_string($_POST['p_type']);
          $owner =  $conn->real_escape_string($_SESSION['S_id']);

          if($_POST['sub'] == 'edit')
          {
            $title = trim($_POST['title']);
            if(strlen($title)<3 || strlen($title)>60)
            {
              alert("Title must be betwin 3 and 60 characters.");
            }
            else
            {               
              $title = $conn->real_escape_string($title);
              $contexts = $conn->real_escape_string($_POST['post-text']);
              $sql = $conn->query("UPDATE `$p_type` SET `Contexts` = '$contexts', `title` = '$title' WHERE `$p_type`.`id` = $id && owner = $owner; ");
                          if($sql==1)
                          alert("Post has been successfully updated");
                          else
                          alert("Not authorized to perform operation");
            }
          }
          else if($_POST['sub'] == 'delete')
          {
            $sql = $conn->query("DELETE FROM $p_type
                                WHERE id = $id && owner = $owner;");
                          if($sql==1){
                          $sql = $conn->query("DELETE FROM `comments` WHERE `comments`.`post` = $id;");
                          alert("Deleted successfully.");
                          }
                          else{
                          alert("Not authorized to perform operation");
                          }
          }
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

  <title>My posts</title>
  <link rel="icon" href="res/unimail_icon.png"> 


</head>

  <body style="margin-bottom: 50px;">
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
                <h6 class="text-uppercase font-weight-bold"><?php  echoName(); echoSurname();?></h6><small> <?php groupe(); ?></small>
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
<!-- myposts section-->



<div class="container">

    <?php

    if($_SESSION['S_type']<3)
    {
      $conn = new mysqli('localhost', 'root', '', 'unimaildb');
      $owner = $conn->real_escape_string($_SESSION['S_id']);
      $posts = $conn->query("SELECT title , date , id FROM `posts` WHERE owner = $owner ");
      echo '<div class="row mb-3"><div class="col">';
        if ($posts->num_rows > 0) {
            echo '
            <h5 class="text-muted">Announcements posts :</h5>
            <div class="table-responsive">
            <table class="table">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 5%">#</th>
                        <th scope="col" style="width: 80%">Poste title</th>
                        <th scope="col"style="width: 15%">publish date</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    
                    <tbody>
                  ';
          $count = 1;
          while($data = $posts->fetch_array()) {
            echo '
              <tr>
              <th scope="row">'.$count.'</th>
              <td><p>'.$data['title'].'</p></td>
              <td>'.substr($data['date'], 0, 16).'</td>
              <td class="text-end"> <button type="button" class = "btn btn-success" id="'.$data['id'].'" data-toggle="modal" data-target="#edit-post" value="1" onclick="getpost(this)">Edit</button></td>
            </tr>
            ';
            $count++;
          }
          echo '
                </tbody>

                </table></div>
  
              ';


          
          echo '</div></div>';
        }
        else
        {
          echo '<h2 class="text-muted text-center">You have no announcement posts.</h2> </div> </div>';
          echo'<hr>';
        }
//======================================================================================================================================================================

$conn = new mysqli('localhost', 'root', '', 'unimaildb');
$owner = $conn->real_escape_string($_SESSION['S_id']);
$posts = $conn->query("SELECT title , date , id FROM `des_posts` WHERE owner = $owner ");
echo '<div class="row"><div class="col">';
  if ($posts->num_rows > 0) {
      echo '
      <h5 class="text-muted">Discussion posts :</h5>
      <div class="table-responsive">
      <table class="table">
              <thead>
                <tr>
                  <th scope="col" style="width: 5%">#</th>
                  <th scope="col" style="width: 80%">Poste title</th>
                  <th scope="col"style="width: 15%">publish date</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              
              <tbody>
            ';
    $count = 1;
    while($data = $posts->fetch_array()) {
      echo '
        <tr>
        <th scope="row">'.$count.'</th>
        <td><p>'.$data['title'].'</p></td>
        <td>'.substr($data['date'], 0, 16).'</td>
        <td class="text-end"> <button type="button" class = "btn btn-success" id="'.$data['id'].'" data-toggle="modal" data-target="#edit-post" value="0" onclick="getpost(this)">Edit</button> </td>
      </tr>
      ';
      $count++;
    }
    echo '
          </tbody>

          </table></div>

        ';


    
    echo '</div></div>';
  }
  else
  {
    echo '<h2 class="text-muted text-center">You have no discussion posts.</h2> </div> </div>';
  }

}


//===================================================================================================================================================


    else
    {
$conn = new mysqli('localhost', 'root', '', 'unimaildb');
$owner = $conn->real_escape_string($_SESSION['S_id']);
$posts = $conn->query("SELECT title , date , id FROM `des_posts` WHERE owner = $owner ");
echo '<div class="row"><div class="col">';
  if ($posts->num_rows > 0) {
      echo '
      <h5 class="text-muted">Discussion posts :</h5>
      <div class="table-responsive">
      <table class="table">
              <thead>
                <tr>
                  <th scope="col" style="width: 5%">#</th>
                  <th scope="col" style="width: 80%">Poste title</th>
                  <th scope="col"style="width: 15%">publish date</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              
              <tbody>
            ';
    $count = 1;
    while($data = $posts->fetch_array()) {
      echo '
        <tr>
        <th scope="row">'.$count.'</th>
        <td><p>'.$data['title'].'</p></td>
        <td>'.substr($data['date'], 0, 16).'</td>
        <td class="text-end"> <button type="button" class = "btn btn-success" id="'.$data['id'].'" data-toggle="modal" data-target="#edit-post" value="0" onclick="getpost(this)">Edit</button> </td>
      </tr>
      ';
      $count++;
    }
    echo '
          </tbody>

          </table></div>

        ';


    
    echo '</div></div>';
  }
  else
  {
    echo '<h2 class="text-muted text-center">You have no discussion posts.</h2> </div> </div>';
  }
    }

    ?>

</div>





<!-- Modal -->
<div class="modal fade " id="edit-post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <h5 class="modal-title" id="exampleModalLabel2">Edit post</h5>
        </button>
      </div>
      <div class="modal-body">
      <!--modelbody-->
    
                              <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                  <div class="mb-3">  
                                      <div class="container post"></div>
                                          <div class="text-center" id = "post_spinner">
                                            <div class="spinner-border" role="status">
                                            </div>
                                      </div>
                                  </div>
                              </form>
        </div>
      </div>
<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> model close btn-->
    </div>
  </div>
</div>



<!--footer -->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript">
    function getpost(btn){

      var post_type = btn.value;
      var id  = btn.id;

            $(document).ready(function () {
               getData();
            });

            function getData() {

                $.ajax({
                    url: 'getpostdata.php',
                    method: 'POST',
                    dataType: 'text',
                   data: {
                       getData: 1,
                       id: id,
                       post_type: post_type,
                   },
                   success: function(response) {
                      $(".post").html("");
                      $(".post").append(response);
                      document.getElementById("post_spinner").hidden = true;
                    }
                });
            }
          }
        </script>
  </body>
</html>
