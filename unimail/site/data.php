<?php
include 'connect.php';
	if (isset($_POST['getData'])) {
		$conn = new mysqli('localhost', 'root', '', 'unimaildb');

		$start = $conn->real_escape_string($_POST['start']);
		$limit = $conn->real_escape_string($_POST['limit']);

		$sql = $conn->query("SELECT * FROM `des_posts` ORDER BY id DESC LIMIT $start , $limit ");

		if ($sql->num_rows > 0) {

			$response = "";

			while($data = $sql->fetch_array()) {

                $id = $conn->real_escape_string($data['id']);

                $stmt = $db->prepare
                ("SELECT name , surname 
                FROM memb 
                WHERE id = ?");
            
                $stmt->execute(array($data['owner']));
                
                $owner = $stmt->fetch();


                $sql_com = $conn->query("SELECT COUNT(*) as count FROM `comments` WHERE post = $id ;");
                $comment = $sql_com->fetch_array();
                $comment = $comment['count'];

                $is_aprv = 'hidden';
                $sql_aprv = $conn->query("SELECT COUNT(*) as count FROM `comments` WHERE post = $id AND approved = 1;");
                $sql_aprv = $sql_aprv->fetch_array();
                if ($sql_aprv['count']>0)
                {
                  $is_aprv = '';
                }
                

				$response .= '
        <div class="row my-discussion-row justify-content-center">

      <div class="col-lg-7 mb-3 "> 
        <div class="card shadow-sm">
        <a href="post.php?post='.$data['id'].'">
        '.$data['image'].'
            <div class="card-body">


            <div class="row">
            <div class="col-sm-12 col-md-6">
                      <h5 class="card-title"><a href="post.php?post='.$data['id'].'" class="text-decoration-none">'.$data['title'].'</a></h5>
            </div>
            <div class="col-sm-12 col-md-6 text-end" '.$is_aprv.'>
                      <h5><span class="badge bg-success" >Solved</span></h5> 
                      </div>
            </div>
          

              <div class="contaner">
                <div class="row">

                  <div class="col">
                  <p class="card-subtitle mb-2 text-muted ">'.$owner['name']. ' ' .$owner['surname'].'</p>
                  </div>
                  
                </div>
              </div>

              <p class="card-text">'.$data['Contexts'].'</p>

            <div class="contaner">
              <div class="row">

                <div class="col">
                <h6 class="card-subtitle mb-2 text-muted">'.$comment.' Comments</h6>
                </div>

                <div class="col">
                <div class="col"> <h6 class="card-subtitle mb-2 text-end text-muted" id="date">'.substr($data['date'], 0, 16).'</h6> </div>
                </div>
                
              </div>
            </div>
            
            </div>
          </div>
      </div>
</div>
				';
			}

			exit($response);
		} else
			exit('reachedMax');
	}
?>