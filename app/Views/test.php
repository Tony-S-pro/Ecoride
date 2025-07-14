<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource.");?>

<h1>Test title</h1>

<section>
    <h2>Section title</h2>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat esse repellat doloremque reprehenderit fuga exercitationem, nostrum ea iure autem? Dicta quia aut et mollitia quo corrupti cupiditate molestiae debitis perspiciatis.</p>
</section>

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





// $user = new App\Models\User(App\Core\Database::getPDOInstance());
//         $result = $user->create([
//                 'email' => '123@123.com',
//                 'password' => '123@123.com',
//                 'pseudo' => '123@123.com',
//                 'name' => '123@123.com',
//                 'firstname' => '123@123.com',
//                 'role' => 'user',
//                 'subscription_date' => date('Y-m-d H:i:s')
//             ]);

// if (isset($_SESSION['old'])) {
//     $_SESSION['errors']=[];
//     $_SESSION['old']=[];
// }


/*
$modelDM = new App\Models\ObjectionsLogDM(App\Core\DatabaseDM::getDmInstance());
*/

/*
$newDoc = [
    'title' => 'Test_01', 
    'content' => 'Bla, bla, bla',
    'creation_date'=> '2025-01-06',
];
$modelDM->createDocument($newDoc);
*/

/*
$docs = $modelDM->readDocument(['blip'=>'bloop'], ['sort' => ['creation_date' => -1]]); //1 ASC, -1 DSC
$results=[];
foreach ($docs as $doc) {
    $results[]=$doc;
}
dump($results);

$results_json = json_encode($results);
dump($results_json);
echo $results_json;
*/

/*
$updtFilter = ['id_employee' => 5];
$updtData = ['creation_date' => date('Y-m-d')];
$modelDM->updateDocument($updtFilter, $updtData);
*/

/*
$delFilter = ['id_employee' => 5];
$modelDM->deleteDocument($delFilter);
*/
/*
$employee_id = $_SESSION['user']['id'];
        $date=date('Y-m-d H:i:s');
        $modelDM = new App\Models\ObjectionsLogDM(App\Core\DatabaseDM::getDmInstance());
        $updtFilter = ['user_id' => $passenger, 'carpool_id' => $carpool_id];
        $updtData = [
            'employee_id' => $employee_id,
            'decision' => 'accepted',
            'objection' => '0',
            'update_date' => $date
        ];
        $modelDM->updateDocument($updtFilter, $updtData);*/

dump(date('Y-m-d'));



$var1= ['pin'=>'17'];
dump($_SESSION);
$var1 = $var1['pin'];
dump($var1);

    
?>

