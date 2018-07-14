<?php
    require_once("inc/header.php");
    require_once("inc/functions.php");
    $message = '';

    if($_GET){
        if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['context']) && $_GET['context'] == 'user'){
            
            delete($_GET['id'] , $_GET['context']);
        }else{
            $message= "<div class='alert alert-danger' role='alert'>The delete failed</div>";
        }
        if(isset($_GET['update']) && !empty($_GET['update']) && is_numeric($_GET['update'])){
            if($_GET['update']==1){
                $message= "<div class='alert alert-success' role='alert'>The user was updated successfully</div>";
            }
        }
    }

    $content= "";

    $users_table = $pdo->query("SELECT * FROM user");
    $content .= "<tr>";
    for($i=0; $i < $users_table->columnCount();$i++){ 
        $column = $users_table->getColumnMeta($i); 
        $content .= '<th>'. ucfirst(str_replace('_',' ',$column['name'])) ."</th>";
    }
    $content .= "<th colspan='2'>Actions</th>";
    $content .= "</tr>";
    $content .= "</thead>";
    $content .= "<tbody>";

    $users = $users_table->fetchAll();
    foreach ($users as $user) {
        $content .= '<tr>';
        foreach ($user as $key => $value) {
            if ($key=='picture') {
                $content .= "<td><img style='width:70px;' src='".URL."uploads/user/".$value."'></td>";
            } else {
                $content .= '<td>'.$value."</td>";
            }
            
        }
        $content .= "<td><a href='".URL."signup.php?id=".$user['id_user']."'=><i class='fas fa-edit fa-lg'></i></a></td>";
        $content .= "<td><a data-toggle='modal' data-target='#deleteModal".$user['id_user']."'><i class='fas fa-trash fa-lg text-danger'></i></a></td>";
        deleteModal($user['id_user'] , $user['pseudo'], 'user');
        $content .= '</tr>';
    }

?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Show Users</h1>
    </div>
    <?= $message ?>
    <table class="table table-striped table-dark">
    <thead>
        <?= $content ?>
        
    </tbody>
    </table>

<?php
    require_once("inc/footer.php");

?>