<?php 
include "../../inc/eldb.php";
  if (!empty($_POST["nompay"] and $_POST["cate"] and $_POST["contenup"])){

    $nomp = $db->real_escape_string($_POST["nompay"]);
    $cate = $db->real_escape_string($_POST["cate"]);

    $getaccess = "SELECT token FROM users WHERE uname = '".$_POST["user"]."'";
    if($result = mysqli_query($db, $getaccess)){
    if(mysqli_num_rows($result) > 0){
        while($rowa = mysqli_fetch_array($result)){

    $sqld = "SELECT * FROM payloadscate WHERE name = '$cate' AND token = '".$rowa["token"]."'";
    if($resultd = mysqli_query($db, $sqld)){
      if(mysqli_num_rows($resultd) > 0){

    $stmt = $db->prepare("INSERT INTO payloads (name, content, byname, category, token) VALUES (?, ?, ?, ?, ?) ");
    $stmt->bind_param("sssss", $name, $content, $byname, $category, $token);
    $name = htmlspecialchars($nomp);
    $content = htmlentities($_POST["contenup"]);
    $byname = htmlspecialchars($_POST["user"]);
    $category = htmlspecialchars($cate);
    $token = $rowa["token"];

    if(mysqli_stmt_execute($stmt)){
      setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
  $stmtl = $db->prepare("INSERT INTO logs (comment, heure, rank, status) VALUES (?, ?, ?, ?)");
  $stmtl->bind_param("ssss", $comment, $heure, $rank, $status);
  $comment = "Nouveau payload : ".$nomp." par ".$_POST["user"].".";
  $heure = strftime('%d %B %Y à %H:%M',strtotime("+6 hours")); 
  $rank = "1";
  $status = "success";
  $stmtl->execute();
  $stmtl->close();
  $stmt->close();
  echo 'done';
  }else{
  echo 'error';
  }
  }else{ 
    echo 'cateno';
  }
  }else{
   echo 'error';
  }
  }
  }
  }
  }else{
    $_POST["error"] = "Un des champs est manquant.";
  }
?>