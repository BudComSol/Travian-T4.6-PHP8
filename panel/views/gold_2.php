<?php



include_once __DIR__ . "/../../application/config.php";
include_once __DIR__ . "/../../application/Database/db_MYSQL.php";









if(isset($_GET['g']))
	{

		echo '<br /><br /><font color="Red"><b>Забрали голд , ебать ты жадный</font></b>';

	}else{

$id = (int)($_POST['id'] ?? 0);



  echo $_POST['gold'];

  $gold = (int)($_POST['gold'] ?? 0);

$database->query(
    "UPDATE users SET gold = gold - :gold WHERE id = :id",
    ['gold' => $gold, 'id' => $id]
);

       header("Location: ../../../panel/index.php?p=player&uid=$id");

    echo '<br /><br /><font color="Red"><b>Забрали голд , ебать ты жадный</font></b>';

}



?>