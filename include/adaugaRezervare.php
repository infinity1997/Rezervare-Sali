<?php
//session_start();
$error=0;

if($_SERVER['REQUEST_METHOD']=='POST')
{
	if( empty($_POST['ora_inceput'])  && empty($_POST['ora_sfarsit']))
	{	
		

		if(empty($_POST['ora_inceput']))
		{
			echo "<br>
			<label class=\"messageError\" style=\"margin-left: 14%; color: red;\">Selectati ORA de Start!</label>";
			$error=1;
		}

		if(empty($_POST['ora_sfarsit']))
		{
			echo "<br>
			<label class=\"messageError\" style=\"margin-left: 14%; color: red;\">Selectati ORA de Sfarsit!</label>";
			$error=1;
		}
		
	}
	else
	{
		$ora_inceput=$_POST['ora_inceput'];
		$ora_sfarsit=$_POST['ora_sfarsit'];
		
		if($ora_inceput<$ora_sfarsit)
		{

		}
		else
		{		
			echo "<br>
			<label>Nu ati ales corect Data desfasurarii evenimentului!</label>";
			$error=1;
		}

	}

	if(empty($_POST['data']))
	{	
		echo "<br>
		<label class=\"messageError\" style=\"margin-left: 14%; color: red;\">Nu ati ales corect DATA desfasurarii evenimentului!</label>";
		$error=1;
	}

	if($_POST['dataSfarsit']<$_POST['data'])
	{
		echo "<br>
		<label class=\"messageError\" style=\"margin-left: 14%; color: red;\">Data de Sfarsit este mai mica decat Data de inceput!</label>";
		$error=1;
	}

	if($_POST['dataSfarsit']!=$_POST['data'])
	{
		echo "<br>
		<label class=\"messageError\" style=\"margin-left: 14%; color: red;\">Rezervarea se face doar pentru o zi!</label>";
		$error=1;
	}


}

$sala="";
$data="";
$dataSfarsit="";
$ora_inceput="";
$ora_sfarsit="";
$tip_eveniment="";
$coordonatorEveniment="";
$numeGrupa="";
$numeObiect="";
function rezerva(){

	if($GLOBALS['error']==0 && $_SERVER['REQUEST_METHOD']=='POST')
	{
		require_once 'config/db-config.php';
		$conn = connectToDB();

		
		$GLOBALS['sala']=$conn->real_escape_string($_POST['sala']);
		$GLOBALS['data']=$conn->real_escape_string($_POST['data']);
		$GLOBALS['dataSfarsit']=$conn->real_escape_string($_POST['dataSfarsit']);
		$GLOBALS['ora_inceput']=$conn->real_escape_string($_POST['ora_inceput']);
		$GLOBALS['ora_sfarsit']=$conn->real_escape_string($_POST['ora_sfarsit']);
		$GLOBALS['tip_eveniment']=$conn->real_escape_string($_POST['tip_eveniment']);
		$GLOBALS['coordonatorEveniment']=$conn->real_escape_string($_POST['coordonatorEveniment']);
		$GLOBALS['numeObiect']=$conn->real_escape_string($_POST['numeObiect']);
		$GLOBALS['numeGrupa']=$conn->real_escape_string($_POST['numeGrupa']);


		$sala1=$GLOBALS['sala'];
		$data1=$GLOBALS['data'];
		$ora_inceput1=$GLOBALS['ora_inceput'];
		$ora_sfarsit1=$GLOBALS['ora_sfarsit'];


		$result = NULL;
		$query = "SELECT * FROM rezervari WHERE Sala=\"$sala1\" AND Data=\"$data1\" ";
		$result = $conn->query($query);
		$conn->close();


		if($result->num_rows > 0)
		{
			
			while($row = $result->fetch_assoc()) 
			{
				$salaAux=$row["sala"];
				$dataAux=$row["data"];

				$ora_inceputAux=$row["oraStart"];
				$ora_sfarsitAux=$row["oraSfarsit"];
				//echo "<br>";
				//print_r($row);

        			//OIA =ora inceput aleasa
        			//OSA= ora sfarsit aleasa
        			//OI = ora inceput existenta($ora_inceputAux)
        			//OS=ora sfarsit existenta($ora_sfarsitAux)
				if($GLOBALS['sala']==$salaAux && $GLOBALS['data']==$dataAux)
				{
        				// OIA<=OI && OSA>=OS
					if($GLOBALS['ora_inceput']<=$ora_inceputAux && $GLOBALS['ora_sfarsit']>=$ora_sfarsitAux)
					{
						//echo "OIA<=OI && OSA>=OS";
						echo "<script>alert(\"In Intervalul Orar ales sala nu este disponibila!\")</script>";
						return false;
					}		
        			
        			//OIA>OI && OSA<OS
					if($GLOBALS['ora_inceput']>$ora_inceputAux && $GLOBALS['ora_sfarsit']<$ora_sfarsitAux){
						//echo "OIA>OI && OSA<OS";
						echo "<script>alert(\"In Intervalul Orar ales sala nu este disponibila!\")</script>";
						return false;
					}
        			//OIA<=OI && OSA<=OS
					
					if($GLOBALS['ora_inceput']<=$ora_inceputAux && $GLOBALS['ora_sfarsit']<=$ora_sfarsitAux && $GLOBALS['ora_sfarsit']>$ora_inceputAux) 
					{
						echo "<script>alert(\"In Intervalul Orar ales sala nu este disponibila!\")</script>";
						//echo "OIA<=OI && OSA<=OS";
						return false;
					}

				/*	if($GLOBALS['ora_inceput']>$ora_inceputAux && $GLOBALS['ora_inceput']<$ora_sfarsitAux){
						echo $GLOBALS['ora_inceput'];
						echo $ora_sfarsitAux;
						echo "cazul final";
						return false;
					}
					*/
				}
				

			}
			return true;

		}
		else
			return true;

	}

}


function adaugaRezervare()
{
	$okRezerva=rezerva();
	require_once 'config/db-config.php';
	$conn = connectToDB();

	$result=1;
	if($okRezerva==true)
	{
		$query="SELECT idRezervare FROM rezervari order by idRezervare desc limit 1"; 
		//The limit 1 clause simply limits the result set to one record - the last one in the table.

		$sala1=$GLOBALS['sala'];
		$data1=$GLOBALS['data'];
		$dataSfarsit1=$GLOBALS['dataSfarsit'];
		$ora_inceput1=$GLOBALS['ora_inceput'];
		$ora_sfarsit1=$GLOBALS['ora_sfarsit'];
		$tip_eveniment1=$GLOBALS['tip_eveniment'];
		$coordonatorEveniment1=$GLOBALS['coordonatorEveniment'];
		$numeObiect1=$GLOBALS['numeObiect'];
		$numeGrupa1=$GLOBALS['numeGrupa'];

		if(isset($_SESSION["idUser"]))
		{
			$idUser=$_SESSION["idUser"];
		}
		else
		{
			$idUser=NULL;
		}


		$sql = "INSERT INTO rezervari ( TipEveniment,coordonatorEveniment,numeGrupa,numeObiect, Sala,Data,dataSfarsit, OraStart,OraSfarsit, idUser)
		VALUES ( '$tip_eveniment1', '$coordonatorEveniment1','$numeGrupa1','$numeObiect1','$sala1','$data1','$dataSfarsit1' ,'$ora_inceput1','$ora_sfarsit1','$idUser')";

		if ($conn->query($sql) === TRUE) {
			echo "<script>alert(\"Cererea a fost trimisa cu succes!\")</script>";
		}
		else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

	}
	else
		if($_SERVER['REQUEST_METHOD']=='POST')
			{
				echo "<script> alert(\"Date nevalide introduse!\") </script>";
			}
}
?>