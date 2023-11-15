<?php
include "../../inc/eldb.php";
if (isset($_POST["pname"])){
  $searchpay = "SELECT id FROM payloads WHERE name = '".$_POST["pname"]."'";
      if($resultd = mysqli_query($db, $searchpay)){
        if(mysqli_num_rows($resultd) > 0){
              while($rowd = mysqli_fetch_array($resultd)){ 
                $stmtd = $db->prepare("UPDATE servers SET payload = ? WHERE id = ?");
                $stmtd->bind_param("ss", $payload, $id);
            $payload = $rowd["id"];
            $id = $_POST["idserv"];
            $stmtd->execute();
            $stmtd->close();
            echo 'done';
          }
        }else{
          echo 'oof';
        }
    }

}
?>