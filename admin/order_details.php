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

    $users_details = $pdo->query("SELECT pseudo, firstname, lastname, email, picture, gender, city, zip_code, address FROM user");
    $orders_details = $pdo->query("SELECT * FROM `order`");
    $order_details_details = $pdo->query("SELECT * FROM order_details");
    $content .= "<tr>";
    for($i=0; $i < $orders_details->columnCount();$i++){ 
        $order_column = $orders_details->getColumnMeta($i); 
        if($order_column['name']=='id_user'){
            for($j=0; $j < $users_details->columnCount();$j++){ 
                $user_column = $users_details->getColumnMeta($j); 
                $content .= '<th>'. ucfirst(str_replace('_',' ',$user_column['name'])) ."</th>";
            }
        }else{
            $content .= '<th>'. ucfirst(str_replace('_',' ',$order_column['name'])) ."</th>";
        }
    }
    for($i=0; $i < $order_details_details->columnCount();$i++){ 
        $order_column = $order_details_details->getColumnMeta($i); 
        if($order_column['name']!='id_order'){
            $content .= '<th>'. ucfirst(str_replace('_',' ',$order_column['name'])) ."</th>";
        }

    }
    $content .= "<th colspan='2'>Actions</th>";
    $content .= "</tr>";
    $content .= "</thead>";
    $content .= "<tbody>";

    $orders = $orders_details->fetchAll();
    foreach ($orders as $order) {
        $req = "SELECT * FROM order_details where id_order =:id_order";
        $result= $pdo->prepare($req);
        $result->bindValue(":id_order",$order['id_order'],PDO::PARAM_INT);
        $result->execute();
        $rowspan=$result->rowCount();
        $content .= "<tr>";
        $content .= "<form>";
        
        foreach ($order as $key => $value) {
            if($key=='id_user'){
                if($value!=NULL){

                    $req = "SELECT pseudo, firstname, lastname, email, picture, gender, city, zip_code, address FROM user WHERE id_user = $value";
                    $user_details = $pdo->query($req);
                    $user = $user_details->fetch();
                    foreach($user as $userKey => $userValue)
                    if ($userKey=='picture') {
                        $content .= "<td rowspan='".$rowspan."'><img style='width:70px;' src='".URL."uploads/user/".$userValue."'></td>";
                    } else {
                        $content .= '<td rowspan="'.$rowspan.'">'.$userValue."</td>";
                    }
                }else{
                    for($i=0;$i<9;$i++){
                        $content .= "<td rowspan='".$rowspan."'>/</td>";
                    }
                }
            }else{
                if($key=='status'){
                    $content.= '<td rowspan="'.$rowspan.'">';
                    $content.='<select name="status" class="form-control">';
                    $content.='<option value="pending"'. ($value=='pending'? 'selected':'') .'>pending</option>';
                    $content.='<option value="pending"'. ($value=='sent'? 'selected':'') .'>sent</option>';
                    $content.='<option value="pending"'. ($value=='cancelled'? 'selected':'') .'>cancelled</option>';
                    $content.='<option value="pending"'. ($value=='delivered'? 'selected':'') .'>delivered</option>';
                    $content.= '</select>';
                    $content.= "</td>";
                }else{
                    $content .= '<td rowspan="'.$rowspan.'">'.$value."</td>";
                }
            }
        }
        
        
        
        $order_details = $result->fetchAll();
        foreach ($order_details as $order_detail) {
            foreach ($order_detail as $key => $value) {
                if($key!='id_order'){
                    $content .= '<td rowspan="1">'.$value."</td>";
                }
            }
            $content .= '</tr>';
            $content .= '<tr>';
        }

        // $content .= "<td><a href='".URL."signup.php?id=".$order['id_user']."'=><i class='fas fa-edit fa-lg'></i></a></td>";
        // $content .= "<td><a data-toggle='modal' data-target='#deleteModal".$order['id_user']."'><i class='fas fa-trash fa-lg text-danger'></i></a></td>";
        // deleteModal($order['id_user'] , $order['pseudo'], 'user');
        $content .= "</form>";
        $content .= '</tr>';
    }

?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Show Users</h1>
    </div>
    <?= $message ?>
    <table class="table table-bordered">
    <thead>
        <?= $content ?>
        
    </tbody>
    </table>

<?php
    require_once("inc/footer.php");

?>