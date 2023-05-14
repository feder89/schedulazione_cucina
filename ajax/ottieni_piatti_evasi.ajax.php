<?php
	require_once '../include/core.inc.php';
	$link=connectToDb();
	$portate=array();
	$query="SELECT *,COUNT(id) AS nr FROM programmazioneordini 
			WHERE stato=3 and categoria IN ('primo','secondo','contorno')
			GROUP BY portata, tavolo ,indice, idprogrammazione
			ORDER BY idprogrammazione DESC, tavolo, portata,FIELD(categoria, 'primo','secondo','contorno')";			

		$result = mysqli_query($link, $query) or die("#error#".mysqli_error($link));
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($portate, array(	'portata' => $row['portata'], 
										'quantita' => $row['nr'],
										'tavolo'=> $row['tavolo'],
										'indice' => $row['indice'],
										'ora_produzione' => $row['ora_produzione'],
										'ora_evasione' => $row['ora_evasione'],
										'idprg'=>$row['idprogrammazione']));
		}
	}

	disconnetti_mysql($link, NULL); #visto che non ho un result_set gli passo NULL.. nella funzione in core.in.php ho aggiunto il controllo

	echo json_encode($portate);
	

?>