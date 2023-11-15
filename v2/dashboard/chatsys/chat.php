<?php
session_start();  
if(!isset($_SESSION["username"]))  
{  
      die(header("location:../login.php"));  
}   
include '../../inc/eldb.php';
$ifban = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $ifban)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            if ($row["ban"] == 1){
                die(header("location:oops.php"));
            }
            if ($row["rank"] == 1){
                die('Cette fonction nécessite le Premium.');
            }
        }
    }
}


$ifconfirm = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $ifconfirm)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          if ($row["confirm"] == 0){
            die(header("location: ../confirm.php?usr=".$_SESSION["username"]."&to=".$row["token"]));
          }elseif ($row["confirm"] == 2){
            die(header("location: ../../confirm.php?confirm=false"));
          }
        }
    }
}


function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}  
$idserv = $db->real_escape_string($_GET["serv"]);

$getaccess = "SELECT * FROM users WHERE uname = '".$_SESSION["username"]."'";
if($result = mysqli_query($db, $getaccess)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          if ($row["rank"] == 3){
          }else{
            $ifaccess = "SELECT token FROM servers WHERE token = '".$row["token"]."' AND  id = $idserv";
              if($resultd = mysqli_query($db, $ifaccess)){
                 if(mysqli_num_rows($resultd) > 0){
              }else{
                setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
                $stmt = $db->prepare("INSERT INTO logs (comment, heure, rank, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $comment, $heure, $rank, $status);
                $comment = $_SESSION["username"]." a tenté d'accéder à un chat serveur dont il ne disposait pas l'accès.";
                $heure = strftime('%d %B %Y à %H:%M',strtotime("+6 hours")); 
                $rank = "2";
                $status = "error";
                $stmt->execute();
                $stmt->close();
                die("Bien essayé, les administrateurs ont été prévenu de votre présence sur une page dont vous ne disposez pas l'accès.");
              }
            }
          }
        }
    }
}
$chat = "SELECT * FROM serverchat WHERE serverid = '$idserv' ORDER BY id DESC";
  if($resultc = mysqli_query($db, $chat)){
    if(mysqli_num_rows($resultc) > 0){
      while($rowc = mysqli_fetch_array($resultc)){ ?>

                    <div class="card">
                         <div class="card-body">
                         	<?php
                         	if(startsWith($rowc['content'],"///" )){ ?>
                             <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?> </i> - CHAT ADMIN</h6>  <p class="card-text float-left"><? echo substr($rowc['content'], 3); ?></p>

							<? }elseif(startsWith($rowc['content'],"/ooc" )){ ?>
                             <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?> </i> - MODE OOC</h6>  <p class="card-text float-left"><? echo substr($rowc['content'], 4); ?></p> 

                         	<? }elseif(startsWith($rowc['content'],"//" )){ ?>
							    <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?></i> - MODE OOC</h6>  <p class="card-text float-left"><? echo substr($rowc['content'], 2); ?></p> 

                             <? }elseif(startsWith($rowc['content'],"/name")){ ?>
                             <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?> </i> - CHANGEMENT DE NOM</h6>  <p class="card-text float-left"><? echo 'Changement de nom en' .substr($rowc['content'], 5); ?></p>

                         <? }elseif(startsWith($rowc['content'],"/rpname")){ ?>
                             <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?> </i> - CHANGEMENT DE NOM</h6>  <p class="card-text float-left"><? echo 'Changement de nom en :'.substr($rowc['content'], 7); ?></p>

                              <? }else{ ?>
                             <h6 class="card-subtitle mb-2 text-muted text-left"><a target="_blank" href="https://steamcommunity.com/profiles/'<?php echo $rowc["steamid"]; ?>'"><? echo $rowc['user']; ?></a> - <i><? echo $rowc['plyip']; ?> </i> - <i> <? echo $rowc['plyrank']; ?> </i> - CHAT</h6>  <p class="card-text float-left"><? echo $rowc['content']; ?></p> <? } ?>
                         </div>
                    </div>  
                    <?php } }else{
                    	echo "Aucune donnée n'est disponible. Cela signifie sûrement qu'aucune information n'a été envoyé du serveur au panel.";
                    } } ?>   