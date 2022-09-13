<?php
  require_once '../include/core.inc.php';
  $link = connectToDb();
  $date=ottieni_data_serata_attuale();
  if($date <=0){
      $comanda=array('error' => '#error#Errore durante l\'acquisizione della data');
  }
  else{
      $query="SELECT tavolo, indice, idprogrammazione FROM programmazioneordini 
              WHERE stato=2 and categoria IN ('primo','secondo','contorno') AND serata='$date'
              GROUP BY tavolo, indice, idprogrammazione
          ORDER BY idprogrammazione";
      $tavoli= array();
      if ($result = mysqli_query($link, $query)) {
          while ($row = mysqli_fetch_assoc($result)) {
            $id=$row["tavolo"]."_".$row["indice"];
            array_push($tavoli, array('tavolo' => $row['tavolo'], 'indice'=>$row['indice'], 'idprg' => $row['idprogrammazione']));
          }


          /* free result set */
          mysqli_free_result($result);
          disconnetti_mysql($link, NULL);
          echo json_encode($tavoli);
      }
    }
?>