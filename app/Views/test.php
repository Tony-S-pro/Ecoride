<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource.");?>

<h1>Test title</h1>

<section>
    <h2>Section title</h2>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat esse repellat doloremque reprehenderit fuga exercitationem, nostrum ea iure autem? Dicta quia aut et mollitia quo corrupti cupiditate molestiae debitis perspiciatis.</p>
</section>

<?php 
/*
    //$objection = App\Core\DatabaseDM::getDmInstance();
    $objection = new App\Models\ObjectionsLogDM(App\Core\DatabaseDM::getDmInstance());
    $cursor = $objection->readDocument(['id_employee' => 5]);

    //dd($cursor);
    
    
    //foreach($cursor as $doc) {
    //    $res[]=json_encode($doc); // not necessary if type mapping done in client
    //}

    //or convert to array, not necessary if type mapping done in client
    //$array = iterator_to_array($cursor); 

    foreach($cursor as $a) {
        echo $a['comment']."</br>";
    }
    dump($cursor);
*/






?>


<h2>Hash Generator</h2>
<p>string -> bcrypt hash</p>
<form action="" method="post">
    <input type="text" name="hash" id="hash">
    <button type="submit">hash</button>
</form>
<p>
    result-><?php
    if(isset($_POST['hash'])) {
        echo password_hash($_POST['hash'] , PASSWORD_BCRYPT);
        unset($_POST['hash']);
    }?> 
    
<?php 

$user_id = $_SESSION['user']['id'];
$cs = new App\Models\View_carpools_status(App\Core\Database::getPDOInstance());
        $carpools_arr = $cs->findByUser_past($user_id);

//dump($carpools_arr);
//dump($carpools_arr[0]['carpool_id']);

$carpools = new App\Models\View_carpool_full(App\Core\Database::getPDOInstance());

//$results =  $carpools->findById($carpools_arr[0]['carpool_id']);
$results=[];

$carpool = new App\Models\Carpool(App\Core\Database::getPDOInstance());
        $price = $carpool->getPrice(4);
        if($price ===null) {
            echo "Une erreur s'est produite, veuillez essayer plus tard.";
            exit();
        }
        $price=$price["price"];

        //get passengers list
        $list=[];
        $uc = new App\Models\User_Carpool(App\Core\Database::getPDOInstance());
        $results_uc = $uc->findUsersByCarpool(4);
        
        if($results_uc===null){
            $list=null;
        }else{
            foreach ($results_uc as $r) {            
                $list[]=$r['user_id'];
            } 
        }

        dd($results_uc, $price, $list);

$arr=[];
$arr1= ['pin'=>123];
$arr2= ['pon'=>456];

$arr[]=$arr1;
$arr[]=$arr2;
dump($arr);
dump($arr[0]);

    
?>
</p>


