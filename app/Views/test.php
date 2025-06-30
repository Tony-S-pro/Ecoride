<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource.");?>

<h1>Test title</h1>

<section>
    <h2>Section title</h2>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat esse repellat doloremque reprehenderit fuga exercitationem, nostrum ea iure autem? Dicta quia aut et mollitia quo corrupti cupiditate molestiae debitis perspiciatis.</p>
</section>

<?php 
    //$objection = App\Core\DatabaseDM::getDmInstance();
    $objection = new App\Models\ObjectionsLogDM(App\Core\DatabaseDM::getDmInstance());
    $cursor = $objection->readDocument(['id_employee' => 5]);

    //dd($cursor);
    
    /*
    foreach($cursor as $doc) {
        $res[]=json_encode($doc); // not necessary if type mapping done in client
    }*/

    //or convert to array, not necessary if type mapping done in client
    //$array = iterator_to_array($cursor); 

    foreach($cursor as $a) {
        echo $a['comment']."</br>";
    }
    dump($cursor);
?>
