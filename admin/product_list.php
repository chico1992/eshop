<?php
    require_once("inc/header.php");
    require_once("inc/functions.php");

    $message = '';

    if($_GET){
        if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['context']) && $_GET['context'] == 'product'){
            
            delete($_GET['id'] , $_GET['context']);
        }else{
            $message= "<div class='alert alert-danger' role='alert'>The delete failed</div>";
        }
        if(isset($_GET['update']) && !empty($_GET['update']) && is_numeric($_GET['update'])){
            if($_GET['update']==1){
                $message= "<div class='alert alert-success' role='alert'>The product was updated successfully</div>";
            }
        }
    }
    $content= "";

    $products_table = $pdo->query("SELECT * FROM product");
    $content .= "<tr>";
    for($i=0; $i < $products_table->columnCount();$i++){ 
        $column = $products_table->getColumnMeta($i); 
        $content .= '<th>'. ucfirst(str_replace('_',' ',$column['name'])) ."</th>";
    }
    $content .= "<th colspan='2'>Actions</th>";
    $content .= "</tr>";
    $content .= "</thead>";
    $content .= "<tbody>";

    $products = $products_table->fetchAll();
    foreach ($products as $product) {
        $content .= '<tr>';
        foreach ($product as $key => $value) {
            if ($key=='picture') {
                $content .= "<td><img style='width:70px;' src='".URL."uploads/img/".$value."'></td>";
            } else {
                $content .= '<td>'.$value."</td>";
            }
            
        }
        $content .= "<td><a href='product_form.php?id=".$product['id_product']."'=><i class='fas fa-edit fa-lg'></i></a></td>";
        $content .= "<td><a data-toggle='modal' data-target='#deleteModal".$product['id_product']."'><i class='fas fa-trash fa-lg text-danger'></i></a></td>";
        deleteModal($product['id_product'] , $product['title'], 'product');
        $content .= '</tr>';
    }

?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Show products</h1>
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