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
    } 
    
$carpool = new App\Models\Carpool(App\Core\Database::getPDOInstance());


    
?>
</p>


