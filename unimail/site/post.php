<?php
session_start();
include 'functions.php';

if(!isset($_SESSION['S_type']))
header('Location: login.php');

if($_SERVER['REQUEST_METHOD'] == 'GET' && is_numeric($_GET['post']))
{
  //fetch for the post 
  $conn = new mysqli('localhost', 'root', '', 'unimaildb');
  $id = $conn->real_escape_string($_GET['post']);
  $sql = $conn->query("SELECT title , contexts , date , owner , image FROM `des_posts` WHERE id = $id ");

  if ($sql->num_rows == 0) 
    header('Location: 404.php');
  
  $data = $sql->fetch_array();

  $title = $data['title'];
  $contexts = $data['contexts'];
  $date = substr($data['date'], 0, 16);
  $owner = $conn->real_escape_string($data['owner']);
  $owner_id = $owner;
  $image = $data['image'];
  
  $sql = $conn->query("SELECT name , surname FROM `memb` WHERE id = $owner ");
  $data = $sql->fetch_array();
  
  $owner = $data['name'].' '.$data['surname'];
  $owner_t = $owner;

  $sql = $conn->query("SELECT COUNT(*) as count FROM `comments` WHERE post = $id ;");
  $data = $sql->fetch_array();
  $comments = $data['count'];

  $html_post = '

  <!-- post card without image-->
  <div class="col-lg-7 mb-2 "> 
    <div class="card shadow-sm">
    '.$image.'
        <div class="card-body">
          <h5 class="card-title mb-3">'.$title.'</h5>

          <div class="contaner">
            <div class="row">

              <div class="col">
              <h6 class="card-subtitle mb-2 text-muted">'.$owner.'</h6>
              </div>
              
            </div>
          </div>

          <p class="card-text">'.$contexts.'</p>

          <div class="contaner">
              <div class="row">

              <div class="col">
              <h6 class="card-subtitle mb-2 text-muted">'.$comments.'  Comments</h6>
              </div>

              <div class="col">
              <div class="col"> <h6 class="card-subtitle mb-2 text-end text-muted" id="date">'.$date.'</h6> </div>
              </div>
              
              </div>
          </div>
      <hr>
          <div class="form-floating mb-2">
              
              <form class="row g-3" method="POST" action="add_comment.php">
                  <textarea name ="comment" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px"></textarea>
                  <input type="hidden" id="post_id_hidden_holder" name="post_id" value="'.$id.'"> <!--///////////////////post id here //////////////////////-->
                  <div class="col-12 text-end">
                  <button class="btn btn-primary " type="submit">Comment</button>
                  </div>
              </form>

          </div>

  ';


  // fetch for comments

    $conn = new mysqli('localhost', 'root', '', 'unimaildb');
		$sql = $conn->query("SELECT * FROM `comments` WHERE post = '$id' ORDER BY approved DESC,id DESC;");

		if ($sql->num_rows > 0) {
			$response2 = "";

      while($data = mysqli_fetch_assoc($sql)) 
      { 

      $owner = $conn->real_escape_string($data['owner']);

      if($owner==$_SESSION['S_id'])
      $isowner = '';
      else
      $isowner = 'hidden';

      $sql_owner = $conn->query("SELECT `name` , `surname` FROM `memb` WHERE id = '$owner' ;");
      $owner = $sql_owner->fetch_array();
      $owner = $owner['name'].' '.$owner['surname'];

      $approved = '';

      if($data['approved']==1)
      {
        $approved = 'btn btn-outline-success btn-sm'; 
        $border = 'border-success';
      }
      else
      {
        $approved = 'btn btn-outline-secondary btn-sm';
        $border = ''; 
      }
      
      if($owner_id == $_SESSION['S_id'])
      {
        $approve_perm = '';
      }
      else
      {
        $approve_perm = 'hidden';
      }

        $response2 .= '    
            <div class="card mb-2 '.$border.'">
            <div class="card-body ">
                <div class="row mb-2">

                <div class="col">
                    <p class="card-title text-primary">'.$owner.'</p>    
                </div> 


                <div class="col text-end" '.$approve_perm.'>
                <button type="button" class="'.$approved.' approved"  value="'.$data['id'].'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                </svg>    
                </button>
                </div>


                  <div class="col-auto text-end " '.$isowner.'>
                     <button type="button" class="btn btn-danger btn-sm del_btn" value="'.$data['id'].'">Delete</button>
                  </div>
                
                </div>

                <p class="card-text">'.$data['contexts'].'</p>
            </div>               
            <h6 class="card-subtitle mb-2 text-end text-muted" id="date" style="margin-right: 10px;"> '.substr($data['date'], 0, 16).' </h6> 
        </div>  
        ';
        
        }
      
    }
    else
    {
      $response2 = '<h2 class="text-muted text-center">No comments.</h2>';
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

  <title><?php echo $owner_t; ?> post</title>
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
<!-- post and replies section section-->

<div class="container" >
    <div class="row my-discussion-row justify-content-center">

                <!--HERE POST HTML -->
                <?php
                echo $html_post;
                ?>
                <hr>

                <!-- herer to put comments -->
                <?php 
                echo $response2;
                ?>
              </div>
            </div>
        </div>
  </div>
</div>

<!--footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
    
    
    $(".del_btn").click(function(e) {
          e.preventDefault();
          $.ajax({
              type: "POST",
              url: "delete_com.php",
              data: { 

                comment_id : $(this).val()
              },
              success: function(result) {
                alert(result);
                window.location.href = 'post.php?post=<?php echo $id?>';
              },
              error: function(result) {
                  alert('error');
              }
          });
      });

    </script>

    <script>

$(".approved").click(function(e) {
          e.preventDefault();
          $.ajax({
              type: "POST",
              url: "approve_com.php",
              data: { 
                comment_id : $(this).val()
              },
              success: function(result) {
                alert(result);
                window.location.href = 'post.php?post=<?php echo $id?>';
              },
              error: function(result) {
                  alert('error');
              }
          });
      });

    </script>
  </body>
</html>
