<?php defined('ROOT_PATH') OR exit("You don't have permission to access this resource."); ?>

<p>Le nouveau compte est prêt.</p>
<p>#<?=$employee['id']?> - <?=$employee['firstname']?> "<?=$employee['pseudo']?>" <?=$employee['name']?></p>
<p><a href="mailto:<?=$employee['email']?>"><?=$employee['email']?></a></p>


<p>Retour à <a href="<?=BASE_URL?>admin">votre espace admin</a>.</p>