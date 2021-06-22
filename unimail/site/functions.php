<?php 

//======================================= acc wating for aprove 

use function PHPSTORM_META\type;

function wating_approval(){
if(isset($_SESSION['S_type']) && $_SESSION['S_type']==4)
{
  echo '
  <div class="alert alert-warning" role="alert" style ="padding : 0; text-align: center;">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
   <p><span class ="text-muted">Account is waiting for approval</span> <span><a href="https://www.facebook.com/aimen.aimenrma" target="_blank">Contact us.</a></span></p>
  </div>
  ';
}}

function echoName() {
    echo $_SESSION['S_name'] . ' ';
  }

  function echoSurname() {
    echo $_SESSION['S_surname'] . ' ';
  }

  function echoGroupe() {
    echo $_SESSION['S_groupe'];
  }

  //===================================== publish bnn state

  function setPubState()
  {
      if($_SESSION['S_type']<=2)
      {
          echo 'active';
      }
      else
      {
          echo 'disabled';
      }
  }

  //===================================== echo alert

  function alert($text)
  {
    echo "<script type='text/javascript'>alert('";
    echo $text;
    echo "');</script>";
  }

  //====================================== return invalid if incorrect field
function isinvalid($state)
{
  if($state ==  false)
  {
    echo 'is-invalid ';
  }
}
 
//======================================= hide groupe if not student
function groupe()
{
  if($_SESSION['S_groupe']==0)
  {
    echo 'Administration';
  }
  else
  {
    echo 'Groupe ' . $_SESSION['S_groupe'];
  }
}
//======================================== echo annonce

function showann($ann_id)
{
  // db connectoin 
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

  // fetch and generate html
  $stmt = $db->prepare
    ("SELECT * 
    FROM posts 
    WHERE id = ?");

    $stmt->execute(array($ann_id));

    $row = $stmt->fetch();

    $stmt = $db->prepare
    ("SELECT name , surname  
    FROM memb 
    WHERE id = ?");

    $stmt->execute(array($_SESSION['S_id']));

    $owner = $stmt->fetch();
    
    $owner_name = $owner['name'] . $owner['surname'];////////////////////////

    $class = '';/////////////////////////////



    //classes colors in hex
$class_color = array(
    "1" => "#2ECC71",
    "2" => "#D35400",
    "3" => "#5DADE2",
    "4" => "#9B59B6",
    "5" => "#34495E",
    "0" => "#0275d8", 
);

// classes names
$class_name = array( 
  "1" => "Develop Mobile",
  "2" => "Web sémantique",
  "3" => "Sécurité informatique",
  "4" => "Intelligence Artificielle",
  "5" => "rédaction scientifique",
  "0" => "General",
);

$groupe = '';

if($row['groupe']!=0)
{
  $groupe = ' | G'.$row['groupe'];
}

$colore = '';
$border = 2;
$text_muted = 'text-muted';
if($row['fixed']==1)
{
  $colore = "text-white bg-secondary fixed";
  $text_muted = '';
  $border = 0;
}

    // generate code
    echo '

    <div class="row">
    <div class="col">
      <!-- card -->
      <div class="card 
      '. $colore .'
      mb-3"
      style = "border: '.$border.'px solid '.$class_color[$row['class']].' !important;">
        <div class="card-body">
            <h5 class="card-title">
            '.$row['title'].'
            </h5>
            <h6 class="card-subtitle mb-2 '.$colore.'  '.$text_muted.'">
            '. $owner['name'] . ' ' . $owner['surname'] .$groupe .'
            </h6>
            <p class="card-text" id="contexts">'. $row['Contexts'] .'</p>
            <div class="row">
            <div class="col"><hr/></div>
            </div>
            <div class="row">
              <div class="col" style="font-weight: 700;">
              '.$class_name[$row['class']].'   
              </div>
              <div class="col"> <h6 class="card-subtitle mb-2 text-end '.$colore.' '.$text_muted.' " id="date">
              '. substr($row['date'], 0, 16) .' 
              </h6> </div>
            </div>
            
        </div>
      </div>
    </div>
  </div>

    ';

}

//=================================================== 
/*
function showdis($ann_id)
{
  // db connectoin 
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

  // fetch and generate html
  $stmt = $db->prepare
    ("SELECT * 
    FROM des_posts 
    WHERE id = ?");

    $stmt->execute(array($ann_id));

    $row = $stmt->fetch();

    // owner info 

    $stmt = $db->prepare
    ("SELECT name , surname , groupe  
    FROM memb 
    WHERE id = ?");

    $stmt->execute(array($_SESSION['S_id']));

    $owner = $stmt->fetch();

        $owner['surname'];
        $owner['name'];
        $owner['groupe'];


}
*/
//============================================== check uploaded file

function check_image ($image)
{
  if($image['size'] == 0) {

    return true;
  }
  
  else
  {
    if($image['size']>5000000)
    {
      return false;
    }
    else
    {
      $check = getimagesize($image["tmp_name"]);
      if($check !== false) {
        return true;
      } else {
        return false;
      }
    }
  }
}