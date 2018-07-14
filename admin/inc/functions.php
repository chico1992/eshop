<?php

    function deleteModal($id , $name ,$context){
        echo "<div class='modal fade' id='deleteModal".$id."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
            echo '<div class="modal-dialog" role="document">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo "<h5 class='modal-title' id='exampleModalLabel'>Delete</h5>";
                        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                            echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                        echo "Are you sure you want to delete the ".$context." ".$name;
                    echo '</div>';
                    echo '<div class="modal-footer">';
                        echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                        echo "<a href='?id=".$id."&context=".$context."' class='btn btn-danger'>Delete</a>";
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    function delete($id , $context){
        global $message;
        global $pdo;
        $req = "SELECT * FROM ".$context." WHERE id_".$context." = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id',$id,PDO::PARAM_INT);
        $result->execute();
        if($result->rowCount()== 1){
            $deleted = $result->fetch();

            $delete_request="DELETE FROM ".$context." WHERE id_".$context."= :id ";

            $delete_result = $pdo->prepare($delete_request);
            $delete_result->bindValue(':id',$id,PDO::PARAM_INT);

            if($delete_result->execute()){
                $picture_path = ROOT_TREE .'uploads/'.$context.'/'.$deleted['picture'];
                if(file_exists($picture_path) && $deleted['picture']!= 'default.jpg'){ // function file_exists()allows us to be sure that we got this picture registered on the server
                    unlink($picture_path); // function unlink() allows us to delete a file from the server
                }
                if($context == 'product'){
                    $message= "<div class='alert alert-success' role='alert'>The ".$context." ".$deleted['title']." was deleted</div>";
                }elseif($context == 'user'){
                    $message= "<div class='alert alert-success' role='alert'>The ".$context." ".$deleted['pseudo']." was deleted</div>";
                }
            }else{
                $message= "<div class='alert alert-danger' role='alert'>The delete failed</div>";
            }
        }else{
            $message= "<div class='alert alert-danger' role='alert'>The delete failed</div>";
        }
    }
