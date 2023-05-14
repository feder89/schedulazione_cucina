<?php
	require_once('../include/core.inc.php');
	$link=connectToDb();
	$date=ottieni_data_serata_attuale();
    if($date <=0){
        $comanda=array('error' => '#error#Errore durante l\'acquisizione della data');
    }
    else{
		if(isset($_POST["piatti"])){
			$portate = $_POST['piatti'];

			$id_prog;
			$query_idprog="SELECT MAX(idprogrammazione) as idprog FROM programmazioneordini";
			$result_prog = mysqli_query($link, $query_idprog) or die("#error#".mysqli_error($link));
			while ($rowp = mysqli_fetch_assoc($result_prog)) {
				$id_prog = $rowp['idprog']+1;
			} 

			mysqli_autocommit($link, FALSE);
			$ret="ciao";
			foreach ($portate as $key=>$p) {
				$i=1;
				while($i<=$p["quant"]){
					$ret = $p["tavolo"]. " ".$p["indice"]." ".$p["portata"];
					$tavolo = $p["tavolo"];
					$indice = $p["indice"];
					$piatto="'".$p["portata"]."'";
					$query = 	"UPDATE programmazioneordini SET stato=2 , idprogrammazione=$id_prog, ora_produzione=CURRENT_TIMESTAMP()
								WHERE id=(
								SELECT prog.id FROM (select * from programmazioneordini) AS prog
								WHERE prog.tavolo=$tavolo
								AND prog.indice=$indice
								AND prog.portata=$piatto AND stato=1 AND prog.serata='$date' LIMIT 1);" ;
					if(!esegui_query($link, $query)){
						mysqli_rollback($link);
						disconnetti_mysql($link, NULL);
						die("#error#".mysqli_error($link));
					}
					$i++;
				}
			}
			if (!mysqli_commit($link)) die("#error#".mysqli_error($link));

			disconnetti_mysql($link, NULL);

			echo "ok";
		}
	}
?>