<?php
	require_once '../include/core.inc.php';
	$link=connectToDb();
	$portate=array();
	$date=ottieni_data_serata_attuale();
    if($date <=0){
        $comanda=array('error' => '#error#Errore durante l\'acquisizione della data');
    }
	else{
		if(isset($_POST['tavolo'])  && isset($_POST['indice'])){
			$tavolo=$_POST['tavolo'];
			$indice=$_POST['indice'];
			$query="SELECT *,count(*) as quant 
			FROM programmazioneordini 
			WHERE tavolo=$tavolo AND indice=$indice AND stato < 2 and categoria IN ('primo','secondo','contorno') AND serata='$date'
			GROUP BY portata
			ORDER BY FIELD(categoria, 'primo','secondo','contorno')";
	
			$result = mysqli_query($link, $query) or die("#error#".mysqli_error($link));
			while ($row = mysqli_fetch_assoc($result)) {
				array_push($portate, array('portata' => $row['portata'], 'quantita' => $row['quant']));
			}  
		}
	}
	

	disconnetti_mysql($link, NULL); #visto che non ho un result_set gli passo NULL.. nella funzione in core.in.php ho aggiunto il controllo

	echo json_encode($portate);
	

?>