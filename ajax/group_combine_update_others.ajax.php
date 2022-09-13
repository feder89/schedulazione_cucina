<?php
	require_once('../include/core.inc.php');
	$link=connectToDb();
	$date=ottieni_data_serata_attuale();
    if($date <=0){
        $comanda=array('error' => '#error#Errore durante l\'acquisizione della data');
    }
    else{
		if(isset($_POST["combines"])){
			$combs = $_POST['combines'];
			mysqli_autocommit($link, FALSE);
				
			$first = (int)current($combs);
			$last = (int)end($combs);
			foreach ($combs as $key => $value) {
				$query = 	"UPDATE programmazioneordini AS prog SET idprogrammazione=$first
						WHERE prog.idprogrammazione=$value AND prog.serata='$date'" ;
						/*WHERE id=(
						SELECT prog.id FROM (select * from programmazioneordini) AS prog
						
						LIMIT 1);*/

				if(!esegui_query($link, $query)){
					mysqli_rollback($link);
					disconnetti_mysql($link, NULL);
					die("#error#".mysqli_error($link));
				}
			}
			
			$diff=$last-$first;
			$queryUpdate_other="UPDATE programmazioneordini AS prog SET idprogrammazione=(idprogrammazione-$diff)
						WHERE prog.idprogrammazione>$last AND prog.serata='$date'";

			if(!esegui_query($link, $queryUpdate_other)){
				mysqli_rollback($link);
				disconnetti_mysql($link, NULL);
				die("#error#".mysqli_error($link));
			}
			if (!mysqli_commit($link)) die("#error#".mysqli_error($link));

			disconnetti_mysql($link, NULL);

			echo "ok";
		}
	}
?>