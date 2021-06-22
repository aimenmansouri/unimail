<?php
include 'connect.php';

	if (isset($_POST['getData'])) {
		$conn = new mysqli('localhost', 'root', '', 'unimaildb');

		    $id = $conn->real_escape_string($_POST['id']);
        $post_type = $conn->real_escape_string($_POST['post_type']);

        if($post_type === '1')
        {
		    $sql = $conn->query("SELECT title,contexts FROM `posts` WHERE id = $id ");
        $response = ' <input type="hidden" name="p_type" value="posts" /> 
                      <input type="hidden" name="id" value="'.$id.'" />';
        }
        else
        {
            $sql = $conn->query("SELECT title,contexts FROM `des_posts` WHERE id = $id ");
            $response = ' <input type="hidden" name="p_type" value="des_posts" /> 
                          <input type="hidden" name="id" value="'.$id.'" />';
        }

		if ($sql->num_rows > 0) {
               
                $data = $sql->fetch_array();

                $response .= '
                <label for="exampleInputTextl1" class="form-label">Tile </label>
                <input type="text" class="form-control mb-2" id="exampleInputTextl1 " aria-describedby="textlHelp" name="title" value="'.$data['title'].'">

                  <label for="post-text" class = "mb-2" >Contexts</label>
                  <textarea class = "form-control mb-2" id="post-text" name="post-text" style="height: 200px">'.$data['contexts'].'</textarea> 

                  <script>
                  function addtocontexts(text)
                  {
                    document.getElementById("post-text").value += text;
                  }
                  </script>
                  <button type="button" class = "btn btn-primary mb-3" onclick="addtocontexts(\'<a href=&quot;put link here&quot;>link text here</a>\');">Insert link</button>

                  <div class="col text-end">
                  <button class="btn btn-danger" type="submit" name ="sub" value="delete">Delete</button>
                  <button class="btn btn-primary" type="submit"name ="sub" value="edit">Save changes</button>
                ';
                exit($response);
		}
        else
        {
			exit('Post not found.');
	    }
    }
?>

			
		