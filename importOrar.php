<?php
session_start();

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

if( !isset($_SESSION["username"]) || (isset($_SESSION["userRole"]) && $_SESSION["userRole"] != "admin") )		// daca nu suntem logati sau daca suntem logati, dar nu suntem 'admin'
{
	header("Location: index.php");
}
else
{
    function insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $numeGrupa, $sala, $data, $dataSfarsit, $oraStart, $oraSfarsit, $idUser)
    {
    	while(new DateTime($data) < $GLOBALS['startDateSesiune'])
    	{
    		$currentData = new DateTime($data);
    		if(($currentData >= $GLOBALS['startDateVacantaCraciun'] && $currentData <= $GLOBALS['endDateVacantaCraciun']))
    		{
    			$data = calculateNextDate($data, 7);
    			continue;
    		}
    		$coordonatorEveniment = convertToFullNameCoordonatorEveniment($coordonatorEveniment);
	    	$query = "INSERT INTO `rezervari`(`tipEveniment`, `coordonatorEveniment`, `numeObiect`, `numeGrupa`, `sala`, `data`, `dataSfarsit`, `oraStart`, `oraSfarsit`, `idUser`, `timeStamp`) VALUES ('$tipEveniment', '$coordonatorEveniment', '$numeObiect', '$numeGrupa', '$sala', '$data', '$dataSfarsit', '$oraStart', '$oraSfarsit', '$idUser', now())";

		    $result = mysqli_query($GLOBALS['conn'], $query);

			if ($result === TRUE) {
		    	//echo "New record created successfully";
		    	$GLOBALS['nrRezervari']++;
		    	//echo "<br>".$data;
			}
			else {
				echo "Error: " . $query . "<br>" . $conn->error . "<br>";
			}

			$data = calculateNextDate($data, 7);
			$dataSfarsit = $data;
		}
    }

    function calculateNextDate($dateForm, $nr_zile_adaugate)
	{
		$year_form = (int)explode("-", $dateForm)[0];
		$month_form = (int)explode("-", $dateForm)[1];
		$day_form = (int)explode("-", $dateForm)[2];

		switch($month_form)
		{
			case 2:		// luna februarie(28 zile)		
				//verificam daca anul este bisect
				if($year_form % 4 == 0)	
				{
					if($year_form % 100 == 0)
					{
						if($year_form % 400 == 0)
						{
							$leap_year = true;
						}
						else
						{
							$leap_year = false;
						}
					}
					else
					{
						$leap_year = true;
					}
				}
				else	// nu este an bisect
				{
					$leap_year = false;
				}
				
				if($leap_year ==  false)
				{
					if($day_form > (28 - $nr_zile_adaugate))	//daca depasim numarul de zile din luna
					{
						$month_form++;
						$day_form = $day_form + $nr_zile_adaugate - 28;	//recalculam ziua si scadem 28(nr zile) caci am depasit luna
					}
					else
					{
						$day_form = $day_form + $nr_zile_adaugate;	//recalculam ziua din luna curenta
					}
				}
				else
				{
					if($day_form > (29 - $nr_zile_adaugate))	//daca depasim numarul de zile din luna
					{
						$month_form++;
						$day_form = $day_form + $nr_zile_adaugate - 29;	//recalculam ziua si scadem 29(nr zile) caci am depasit luna
					}
					else
					{
						$day_form = $day_form + $nr_zile_adaugate;	//recalculam ziua din luna curenta
					}
				}
				break;
			case 4:		// lunile din an cu 30 zile
			case 6:
			case 9:
			case 11:
				if($day_form > (30 - $nr_zile_adaugate))
				{
					$month_form++;
					$day_form = $day_form + $nr_zile_adaugate - 30;	//recalculam ziua si scadem 30(nr zile) caci am depasit luna
				}
				else
				{
					$day_form = $day_form + $nr_zile_adaugate;	//recalculam ziua din luna curenta
				}
				break;
			default: 	// zilele din an cu 31 zile
				if($day_form > (31 - $nr_zile_adaugate))
				{
					$month_form++;
					$day_form = $day_form + $nr_zile_adaugate - 31;	//recalculam ziua si scadem 31(nr zile) caci am depasit luna
				}
				else
				{
					$day_form = $day_form + $nr_zile_adaugate;	//recalculam ziua din luna curenta
				}
		}
		if($month_form > 12)		// daca depasim numarul de luni din ani, trecem in anul urmator
		{
			$year_form++;
			$month_form = $month_form - 12;  	//scadem 12(luni) caci am depasit numarul de luni din an
		}
		$date = $year_form . "-" . $month_form . "-" . $day_form;

		return $date;		
	}

	function convertToFullNameCoordonatorEveniment($coordonatorEveniment)
	{
		switch($coordonatorEveniment)
		{
			case "AfC":
				$coordonatorEveniment = "Aflori Cristian";
				break;
			case "AlA":
				$coordonatorEveniment = "Alexandrescu Adrian";
				break;
			case "AC":
				$coordonatorEveniment = "Amarandei Cristian";
				break;
			case "AA":
				$coordonatorEveniment = "Archip Alexandru";
				break;
			case "CB":
				$coordonatorEveniment = "Ciobanu Brândușa";
				break;
			case "DT":
				$coordonatorEveniment = "Dumitriu Tiberius";
				break;
			case "SE":
				$coordonatorEveniment = "Șerban Elena";
				break;
			case "HP":
				$coordonatorEveniment = "Herghelegiu Paul";
				break;
			case "HM":
				$coordonatorEveniment = "Hulea Mircea";
				break;
			case "ZO":
				$coordonatorEveniment = "Zvorișteanu Otilia";
				break;
			case "CC":
				$coordonatorEveniment = "Cîmpanu Corina";
				break;
			case "Stru":
			case  "Strugariu":
				$coordonatorEveniment = "Strugariu Răducu";
				break;
			case "BA":
				$coordonatorEveniment = "Bârleanu Alexandru";
				break;
			case "BN":
				$coordonatorEveniment = "Botezatu Nicolae";
				break;
			case "CrB":
				$coordonatorEveniment = "Buțincu Cristian";
				break;
			case "CS":
				$coordonatorEveniment = "Caraiman Simona";
				break;
			case "CP":
				$coordonatorEveniment = "Cașcaval Petru";
				break;
			case "CM":
				$coordonatorEveniment = "Craus Mitică";
				break;
			case "GM":
				$coordonatorEveniment = "Gavrilescu Marius";
				break;
			case "LF":
				$coordonatorEveniment = "Leon Florin";
				break;
			case "LR":
				$coordonatorEveniment = "Lupu Robert";
				break;
			case "MV":
				$coordonatorEveniment = "Manta Vasile";
				break;
			case "MC":
				$coordonatorEveniment = "Monor Călin";
				break;
			case "PF":
				$coordonatorEveniment = "Pantilimonescu Florin";
				break;
			case "PI":
				$coordonatorEveniment = "Petrila Iulian";
				break;
			case "SR":
				$coordonatorEveniment = "Silion R.";
				break;
			case "SA":
				$coordonatorEveniment = "Stan Andrei";
				break;
			case "TM":
				$coordonatorEveniment = "Timiș Mihai";
				break;
			case "UF":
				$coordonatorEveniment = "Ungureanu Florina";
				break;
			case "VG":
				$coordonatorEveniment = "Vieriu George";
				break;
			case "ZM":
				$coordonatorEveniment = "Zaharia Mihai";
				break;
			case "PA":
				$coordonatorEveniment = "Pletea Ariadna";
				break;
			case "DN":
				$coordonatorEveniment = "Dumitriu Narcisa";
				break;
			case "CiG":
			case "Ciobanasu":
				$coordonatorEveniment = "Ciobănașu Georgeta";
				break;
			case "FL":
				$coordonatorEveniment = "Ferariu Lavinia";
				break;
			case "ML":
				$coordonatorEveniment = "Mirea Letiția";
				break;
			case "KM":
				$coordonatorEveniment = "Kloetzer Marius";
				break;
			case "PC":
				$coordonatorEveniment = "Petrescu Camelia";
				break;
			case "CI":
				$coordonatorEveniment = "Cleju Ioan";
				break;
			case "TL":
				$coordonatorEveniment = "Țigăeru Liviu";
				break;
			case "LuF":
				$coordonatorEveniment = "Luca F.";
				break;
			case "MiC":
				$coordonatorEveniment = "Mironeanu Cătălin";
				break;
			case "AS":
				$coordonatorEveniment = "Avram Sorin";
				break;
			case "FS":
				$coordonatorEveniment = "Floria Sabina";
				break;
			case "PT":
				$coordonatorEveniment = "Popovici Tudor";
				break;
			case "VA":
				$coordonatorEveniment = "Valachi Alexandru";
				break;
			case "OM":
				$coordonatorEveniment = "Olaru Marius";
				break;
			case "AvS":
				$coordonatorEveniment = "Avram Sorin";
				break;
			default:
		}
		return $coordonatorEveniment;
	}

    // ************** OBTINERE CURSURI *****************

    function importCursuri($spreadsheet, $worksheetName, $initialDate, $idUser)
    {
    	$tipEveniment = "oră de curs";

	    for($col ='C'; $col <= 'N'; $col++)		//parcurgere coloane
	    {
			echo "<br>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------col = <b style='color:magenta;'>".$col."</b>";
	    	$grupa = $spreadsheet->getActiveSheet()->getCell($col."8")->getValue();
	    	echo " , grupa = <b style='color:Navy'>".$grupa."</b>";

	    	for($row = 9; $row <= 80; $row++)	//parcurgere randuri
		    {	
		    	// calculare data in functie de ziua din saptamana
		    	if($row >= 9 && $row <= 20)
		    	{
		    		// luni
		    		$startDate = $initialDate;
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 24 && $row <= 35)
		    	{
		    		// marti
		    		$startDate = calculateNextDate($initialDate, 1);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 39 && $row <= 50)
		    	{
		    		// miercuri
		    		$startDate = calculateNextDate($initialDate, 2);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 54 && $row <= 65)
		    	{
		    		// joi
		    		$startDate = calculateNextDate($initialDate, 3);
		    		$endDate = $startDate;
		    	}
		    	else
		    	{
		    		// vineri
		    		$startDate = calculateNextDate($initialDate, 4);
		    		$endDate = $startDate;
		    	}

		    	if($row == 21 || $row == 36 || $row == 51 || $row == 66)	// daca am iesit din tabelul cu orarul pe o zi
		    	{
		    		$row = $row + 2;	//marim numarul de randuri ca sa trecem la tabela cu orarul pe ziua urmatoare
		    		continue;
		    	}

		    	// anul 1
			    if($worksheetName == "I_C")
			    {
			    	if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)	//daca am citit ceva pe un rand
				    {
				    	//obtinem culoarea de umplere a celulei
				    	$colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();

				    	//verificam daca avem rand jos de aceeasi culoare si gol
				    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() == ""
				    	)
				    	{
				    		//daca avem inca un rand jos de aceeasi culoare si gol
				    		if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+2))->getFill()->getStartColor()->getARGB()
				    			&& $spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue() == ""
				    		)
				    		{	
						    	// daca urmatoarele 3 coloane sunt de acceasi culoare si daca sunt goale
						    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+3).$row)->getFill()->getStartColor()->getARGB()
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue() != ""
						    	)
						    	{
						    		// avem curs de 3 ore
						    		//preluam numele obiectului din celula
						    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
						    		$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).($row+2))->getValue();
						    		$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue();
						    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    	$oraSfarsit = ($oraStart+3).":00";
							    	$oraStart = $oraStart.":00";
							    	$grupa = NULL;
						    		echo "<br>".$numeObiect;
							    	echo "~~~~~~ =".$row;
							    	echo "  --- <b>".$sala."</b>";
							    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    	echo "<b style='color:red'> **** </b>";
							    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    	//$GLOBALS['nrRezervari']++;
						    		$row = $row + 2;
						    		continue;
						    	}
				    		}
				    		else
				    		{
						    	// daca urmatoarele 3 coloane sunt de acceasi culoare si daca sunt goale
						    	//ord($string) = Convert the first byte of a string to a value between 0 and 255
						    	//chr($bytevalue) = Generate a single-byte from a number
						    	// avem curs de 2 ore
						    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+3).$row)->getFill()->getStartColor()->getARGB()
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue() != ""
						    		&& $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() != "Engleza"
						    	)
						    	{
						    		//preluam numele obiectului din celula
						    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
						    		$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).($row+1))->getValue();
						    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    	$oraSfarsit = ($oraStart+2).":00";
							    	$oraStart = $oraStart.":00";
							    	$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue();
							    	$grupa = NULL;
							    	echo "<br>".$numeObiect;
							    	echo "~~~~~~ =".$row;
							    	echo "  --- <b>".$sala."</b>";
							    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    	echo "<b style='color:red'> **** </b>";
							    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    	//$GLOBALS['nrRezervari']++;
						    		$row++;
						    		continue;
						    	}
				    		}
				    	}

				    	if(		$spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Engleza"
				    		&&	$colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    		&& (	$spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == "impar"
				    			||	$spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == "par"
				    		)
				    	)
				    	{
				    		//avem curs de Engleza
					    	$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    	if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).$row)->getValue() != "")
					    	{
					    		$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).$row)->getValue();
					    	}
					    	else
					    	{
					    		$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).$row)->getValue();
					    	}

					    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()
					    		&& $spreadsheet->getActiveSheet()->getCell($col.($row-1))->getValue() == ""
					    	)
					    	{
					    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
					    	}
					    	else
					    	{
					    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
					    	}
					    	$oraSfarsit = ($oraStart+2).":00";
					    	$oraStart = $oraStart.":00";
					    	$grupa = NULL;
					    	$coordonatorEveniment = NULL;
					    	echo "<br>".$numeObiect;
							echo "~~~~~~ =".$row;
							echo "  --- <b>".$sala."</b>";
					    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
					    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
					    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
					    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
					    	echo "<b style='color:red'> **** </b>";
					    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
				    		$row++;
				    		continue;
				    	}

				    	if(		$spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Desen"
				    		&&	$colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    		&& (	stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue(), "impar") !== false
				    			||	stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue(), "par") !== false
				    		)
				    	)
				    	{
				    		//avem curs de Desen
					    	$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    	$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+3).($row+1))->getValue();
					    	$grupa = NULL;
					    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
					    	$oraSfarsit = ($oraStart+2).":00";
					    	$oraStart = $oraStart.":00";
					    	$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue();
					    	echo "<br>".$numeObiect;
					    	echo "~~~~~~ =".$row;
					    	echo "  --- <b>".$sala."</b>";
					    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
					    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
					    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
					    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
					    	echo "<b style='color:red'> **** </b>";
					    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
				    		$row++;
				    		continue;
				    	}
				    }
				}

				// anul 2
			    if($worksheetName == "II_C")
			    {
			    	if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)	//daca am citit ceva pe un rand
				    {
				    	//obtinem culoarea de umplere a celulei
				    	$colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();

				    	//verificam daca avem rand jos de aceeasi culoare si gol
				    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() == ""
				    	)
				    	{
					    	// daca urmatoarele 3 coloane sunt de acceasi culoare si daca sunt goale
					    	//ord($string) = Convert the first byte of a string to a value between 0 and 255
					    	//chr($bytevalue) = Generate a single-byte from a number
					    	// avem curs de 2 ore
					    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
					    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
					    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
					    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
					    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue() != ""
					    		&& $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() != "Engleza"
					    	)
					    	{
					    		//preluam numele obiectului din celula
					    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    		if(stripos($numeObiect, "TS") !== false)
					    		{
					    			$numeObiect = "TS";
					    		}
					    		//determinare sala
					    		$x = 0;
					    		$c = 1;
					    		while($x < 2)
					    		{
					    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue() != "")
					    			{
					    				if($x == 0)
					    				{
					    					$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
					    				}
					    				if($x == 1)
					    				{
					    					$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
					    				}
					    				$x++;
					    			}
					    			$c++;
					    		}
					    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
						    	$oraSfarsit = ($oraStart+2).":00";
						    	$oraStart = $oraStart.":00";
						    	$grupa = NULL;
					    		echo "<br>".$numeObiect;
						    	echo "~~~~~~ =".$row;
						    	echo "  --- <b>".$sala."</b>";
						    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    			echo "<b style='color:red'> **** </b>";
				    			insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    			//$GLOBALS['nrRezervari']++;
					    		$row++;
					    		continue;
					    	}

					    	if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Engleza") !== false
					    		&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue(), "par") !== false
					    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
					    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() == ""
					    	)
					    	{
					    		//ave curs de Engleza
					    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
						    	$oraSfarsit = ($oraStart+2).":00";
						    	$oraStart = $oraStart.":00";
						    	$coordonatorEveniment = NULL;
						    	$grupa = NULL;
						    	//determinare sala
					    		$x = 0;
					    		$c = 1;
					    		while($x < 2)
					    		{
					    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).$row)->getValue() != "")
					    			{
					    				if($x == 1)
					    				{
					    					$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).$row)->getValue();
					    				}
					    				$x++;
					    			}
					    			$c++;
					    		}
					    		echo "<br>".$numeObiect;
						    	echo "~~~~~~ =".$row;
						    	echo "  --- <b>".$sala."</b>";
						    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    			echo "<b style='color:red'> **** </b>";
				    			insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    			//$GLOBALS['nrRezervari']++;
					    		$row++;
					    		continue;
					    	}
				    	}

				    	if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Engleza") !== false
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row-1))->getValue() == ""
						    && stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue(), "par") !== false
				    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() == ""
				    	)
				    	{
				    		//ave curs de Engleza care are un rand sus gol
				    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
				    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
					    	$oraSfarsit = ($oraStart+2).":00";
					    	$oraStart = $oraStart.":00";
					    	$coordonatorEveniment = NULL;
					    	$grupa = NULL;
					    	//determinare sala
				    		$x = 0;
				    		$c = 1;
				    		while($x < 2)
				    		{
				    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).$row)->getValue() != "")
				    			{
				    				if($x == 1)
				    				{
				    					$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).$row)->getValue();
				    				}
				    				$x++;
				    			}
				    			$c++;
				    		}
				    		echo "<br>".$numeObiect;
					    	echo "~~~~~~ =".$row;
					    	echo "  --- <b>".$sala."</b>";
					    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
			    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
			    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
			    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
			    			echo "<b style='color:red'> **** </b>";
			    			insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
			    			//$GLOBALS['nrRezervari']++;
				    		$row++;
				    		continue;
				    	}
				    }
				}

				// anul 3 si 4
				if($worksheetName == "III_CTI" || $worksheetName == "IV_CTI")
			    {
			    	if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)	//daca am citit ceva pe un rand
				    {
				    	//obtinem culoarea de umplere a celulei
				    	$colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();

				    	//verificam daca avem rand jos de aceeasi culoare
				    	if($colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    	)
				    	{
				    		//daca avem inca un rand jos de aceeasi culoare
				    		if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+2))->getFill()->getStartColor()->getARGB()
				    			&& $spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue() == ""
				    		)
				    		{	
						    	// daca urmatoarele 2 coloane sunt de acceasi culoare si daca sunt goale
						    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
						    	)
						    	{
						    		// avem curs de 3 ore
						    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
						    		$gasit = false;
						    		$c = 0;
						    		while(!$gasit)
						    		{
						    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+2))->getValue() != "")
						    			{
						    				$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+2))->getValue();
						    				$gasit = true;
						    			}
						    			$c++;
						    		}
						    		$gasit = false;
						    		$c = 0;
						    		while(!$gasit)
						    		{
						    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue() != "")
						    			{
						    				$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
						    				$gasit = true;
						    			}
						    			$c++;
						    		}
						    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
					    			$oraSfarsit = ($oraStart+3).":00";
					    			$oraStart = $oraStart.":00";
					    			$grupa = NULL;
						    		echo "<br>".$numeObiect;
					    			echo "~~~~~~ =".$row;
					    			echo "  --- <b>".$sala."</b>";
					    			echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
					    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
					    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
					    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
					    			echo "<b style='color:red'> **** </b>";
							    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    	//$GLOBALS['nrRezervari']++;
						    		$row = $row + 2;
						    		continue;
						    	}
				    		}
				    		else
				    		{
						    	// avem curs de 2 ore
						    	if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
						    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
						    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
						    		&& $colorCell != $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()
						    	)
						    	{
						    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
						    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
					    			$oraSfarsit = ($oraStart+2).":00";
					    			$oraStart = $oraStart.":00";
					    			$x = 0;
						    		$c = -1;	//indice coloana pe care suntem
						    		while($x < 2)
						    		{
						    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue() != "")
						    			{
						    				if($x == 0)
						    				{
						    					$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
						    				}
						    				if($x == 1)
						    				{
						    					$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
						    				}
						    				$x++;
						    			}
						    			$c++;
						    		}
						    		if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "proiect") !== false)
						    		{
						    			$numeObiect ="MS Proiect";
						    		}
						    		if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Proiectare") !== false)
						    		{
						    			$numeObiect = "PSD";
						    		}
						    		if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Tehnologii") !== false)
						    		{
						    			$numeObiect = "TI";
						    		}
						    		if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Inteligenta") !== false)
						    		{
						    			$numeObiect = "IA";
						    		}
						    		$grupa = NULL;
						    		echo "<br>".$numeObiect;
					    			echo "~~~~~~ =".$row;
						    		echo "  --- <b>".$sala."</b>";
						    		echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
					    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
					    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
					    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
					    			echo "<b style='color:red'> **** </b>";
							    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    	//$GLOBALS['nrRezervari']++;
						    		$row++;
						    		continue;
						    	}
				    		}
				    	}
				    }
				}

				// MAster
				if($worksheetName == "Master")
			    {
			    	if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)	//daca am citit ceva pe un rand
				    {
				    	//obtinem culoarea de umplere a celulei
				    	$colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();

				    	//verificam daca avem rand jos de aceeasi culoare
				    	if($colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    	)
				    	{
				    		// avem curs de 2 ore
					    	if( /*   $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
					    		&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
					    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
					    		&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+2).$row)->getValue() == ""
					    		&& $colorCell != $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()*/
					    		   $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
					    		&&(    stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "par") === false
					    			|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Managementul") !== false
					    			|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "PSIOC") !== false
					    		)
					    		&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue(), "par") === false
					    	)
					    	{
					    		$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    		$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    			$oraSfarsit = ($oraStart+2).":00";
				    			$oraStart = $oraStart.":00";
				    			$x = 0;
					    		$c = 0;	//indice coloana pe care suntem
					    		while($x < 2)
					    		{
					    			//echo "<br>col=".(chr(ord($col)+$c))."; row=".($row+1);
					    			if($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue() != ""
					    				&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue(), "par") == false
					    			)
					    			{
					    				if($x == 0)
					    				{
					    					$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
					    				}
					    				if($x == 1)
					    				{
					    					$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+$c).($row+1))->getValue();
					    				}
					    				$x++;	
					    			}
					    			$c++;
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Managementul") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Serviciilor") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "I") !== false
					    			&& (   $col == "E"
					    				|| $col == "F"
					    				|| $col == "G"
					    				|| $col == "H"
					    			)
					    		)
					    		{
					    			$numeObiect ="MSRI";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Managementul") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Serviciilor") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "II") !== false
					    			&& (   $col == "L"
					    				|| $col == "M"
					    				|| $col == "N"
					    				|| $col == "O"
					    			)
					    		)
					    		{
					    			$numeObiect ="MSRII";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Modelare") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Analiza") !== false
					    		)
					    		{
					    			$numeObiect = "MASMA";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Tehnologii") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Limbaje") !== false)
					    		{
					    			$numeObiect = "LTW";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Vizualizare") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Sisteme") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Distribuite") !== false
					    		)
					    		{
					    			$numeObiect = "VSD";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Paradigme") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Proiectare") !== false
					    		)
					    		{
					    			$numeObiect = "PPAD";
					    		}
					    		if($numeObiect == "MASMA")
					    		{
					    			//inversam sala cu nume prof
					    			$aux = $sala;
					    			$sala = $coordonatorEveniment;
					    			$coordonatorEveniment = $aux;
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Calculatoare") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "incorporate") !== false
					    		)
					    		{
					    			$numeObiect = "CI";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Proiect") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "complexe") !== false
					    		)
					    		{
					    			$numeObiect = "PAC";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Retele") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "sis") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "inc") !== false
					    		)
					    		{
					    			$numeObiect = "RSI";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Manag") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "hard") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "soft") !== false
					    		)
					    		{
					    			$numeObiect = "MPHS";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "timp") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "frecv") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "semn") !== false
					    		)
					    		{
					    			$numeObiect = "PTFS";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Sist") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "calc") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "dedicate") !== false
					    		)
					    		{
					    			$numeObiect = "SCD";
					    		}
					    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "om") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "calc") !== false
					    			&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "PSIOC") !== false
					    		)
					    		{
					    			$numeObiect = "PSIOC";
					    		}
					    		//corectare sala(si/sau) coordonatoEveniment
					    		if($numeObiect == "MPHS")
					    		{
					    			$coordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue())[0];
					    			$sala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue())[1];
					    		}
					    		if($numeObiect == "PSIOC")
					    		{
					    			$coordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue())[0];
					    			$sala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue())[0];
					    		}
					    		if($numeObiect == "SCD")
					    		{
					    			$coordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue())[0];
					    			$sala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue())[1];
					    		}
					    		$grupa = NULL;
					    		echo "<br>".$numeObiect;
				    			echo "~~~~~~ =".$row;
					    		echo "  --- <b>".$sala."</b>";
					    		echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    			echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    			echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    			echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    			echo "<b style='color:red'> **** </b>";
						    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
						    	//$GLOBALS['nrRezervari']++;
					    		$row++;
					    		continue;
					    	}
				    	}
				    }
				}
		    }
		}
		echo "<br>nr rezervari = ".$GLOBALS['nrRezervari'];
	}


	// ************** OBTINERE LABORATOARE *****************

	function importLaboratoare($spreadsheet, $worksheetName, $initialDate, $idUser)
	{
		$tipEveniment = "laborator";

		for($col ='C'; $col <= 'O'; $col++)		//parcurgere coloane
	    {
	    	echo "<br>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------col = <b style='color:magenta;'>".$col."</b>";
	    	$grupa = $spreadsheet->getActiveSheet()->getCell($col."8")->getValue();
	    	echo " , grupa = <b style='color:Navy'>".$grupa."</b>";

	    	for($row = 9; $row <= 80; $row++)	//parcurgere randuri
		    {	
		    	// calculare data in functie de ziua din saptamana
		    	if($row >= 9 && $row <= 20)
		    	{
		    		// luni
		    		$startDate = $initialDate;
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 24 && $row <= 35)
		    	{
		    		// marti
		    		$startDate = calculateNextDate($initialDate, 1);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 39 && $row <= 50)
		    	{
		    		// miercuri
		    		$startDate = calculateNextDate($initialDate, 2);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 54 && $row <= 65)
		    	{
		    		// joi
		    		$startDate = calculateNextDate($initialDate, 3);
		    		$endDate = $startDate;
		    	}
		    	else
		    	{
		    		// vineri
		    		$startDate = calculateNextDate($initialDate, 4);
		    		$endDate = $startDate;
		    	}

		    	if($row == 21 || $row == 36 || $row == 51 || $row == 66)	// daca am iesit din tabelul cu orarul pe o zi
		    	{
		    		$row = $row + 2;	//marim numarul de randuri ca sa trecem la tabela cu orarul pe ziua urmatoare
		    		continue;
		    	}

				//daca am citit ceva pe un rand
				if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)
				{
					//obtinem culoarea de umplere a celulei
					$colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();

					//daca suntem pe Shetul "Master"
					if(stripos($worksheetName, "Master") !== false)
					{
						if(   (stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "par") !== false
							|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "impar") !== false
							)
							&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)-1).($row-1))->getFill()->getStartColor()->getARGB()
							&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue(), "FSD") !== false
							&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue(), "proiect") !== false
						)
						{
							//avem laborator de FSD - proiect (Master)
					    	$numeObiect = "FSD Proiect";
			    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue());
			    			$sala = $auxSala[count($auxSala)-1];
			    			$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
				    		$oraSfarsit = ($oraStart+2).":00";
				    		$oraStart = $oraStart.":00";
				    		$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue());
							$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
			    			echo "<br>".$numeObiect;
					    	echo "~~~~~~!! =".$row;
			    			echo "  --- <b>".$sala."</b>";
			    			echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    		echo "<b style='color:red'> **** </b>";
					    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
					    	continue;
						}

						if(   (stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "par") !== false
							|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "impar") !== false
							)
							&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)-1).($row-1))->getFill()->getStartColor()->getARGB()
							&& (   stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue(), "SSD") !== false
								|| stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue(), "FSD") !== false
								|| stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue(), "PAC") !== false
							)
						)
						{
							//avem laborator de SSD sau FSD sau PAC (Master)
							$valCurrentCell = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue();
							$auxNumeObiect = explode(" ", $valCurrentCell);
					    	$numeObiect = $auxNumeObiect[0];
			    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue());
			    			$sala = $auxSala[count($auxSala)-1];
			    			$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
				    		$oraSfarsit = ($oraStart+2).":00";
				    		$oraStart = $oraStart.":00";
				    		$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue());
							$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
			    			echo "<br>".$numeObiect;
					    	echo "~~~~~~ =".$row;
			    			echo "  ---#** <b>".$sala."</b>";
			    			echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    		echo "<b style='color:red'> **** </b>";
					    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
					    	continue;
						}						

						if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "RSI") !== false
							&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "par") !== false
							&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()
						)
						{
							//avem laborator de RSI par
							$valCurrentCell = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
							$auxNumeObiect = explode(" ", $valCurrentCell);
					    	$numeObiect = $auxNumeObiect[0];
			    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
		    				$sala = $auxSala[1];
		    				$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
				    		$oraSfarsit = ($oraStart+2).":00";
				    		$oraStart = $oraStart.":00";
				    		$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue());
							$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-1];
		    				echo "<br>".$numeObiect;
					    	echo "~~~~~~ =".$row;
		    				echo "  --- <b>".$sala."</b>";
		    				echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
				    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
				    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
				    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
				    		echo "<b style='color:red'> **** </b>";
					    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
					    	continue;
						}
					}

					//daca avem rand jos de aceeasi culoare si care sa contina text
					if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    	&& strlen($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue()) > 0
				    )
				    {
						//daca avem inca un rand mai jos umplut cu acceasi culoare si care sa contina tot text diferit de "s impara" si "s para"
						if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+2))->getFill()->getStartColor()->getARGB()
				    		&& strlen($spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue()) > 0
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue() != "s impara"
				    		&& $spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue() != "s para"
				    	)
						{
							//am gasit LABORATOR ************ de 3 ore

							// daca celula pe care ne aflam e intrun curs
							if($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "PF")
							{
								//suntem pe celula unui cursului "SI" din anul IV
								continue;
							}

							if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "complex") !== false
								&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "PAC") !== false
							)
							{
								//am gasit curs de PAC
								$row++;
								continue;
							}
							
							if(    $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "FSD"
								&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
								&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
							)
							{
								//am gasit curs de FSD
								$row++;
								continue;
							}

							if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "timp-frecv") !== false
								&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "PTFS") !== false
							)
							{
								// avem cursuri de "Prel timp frecv. a semnalelor"
								$row++;
								continue;
							}

							if($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "C0-3")
							{
								// am gasit o celula care contine C0-3 (K48 -MASTER)
								continue;
							}

							if($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "AC2-1")
							{
								// am gasit o celula care contine AC2-2 (N48 -MASTER)
								continue;
							}


				    		//preluam numele obiectului din celula pentru a fi introdus in Database
					    	$numeObiect = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
					    	$sala = $spreadsheet->getActiveSheet()->getCell($col.($row+2))->getValue();
					    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
					    	$oraSfarsit = ($oraStart+3).":00";
					    	$oraStart = $oraStart.":00";
					    	$valNextCell = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
							$auxCoordonatorEveniment = explode(" ", $valNextCell);
			    			$coordonatorEveniment = $auxCoordonatorEveniment[0];
					    	echo "<br>".$numeObiect;
					    	echo "~~~~~~~ = ".$row;
					    	echo "  --- <b>".$sala."</b>";  	
					    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
					    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
					    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
					    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
					    	echo "<b style='color:red'> **** </b>";
							insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
					    	//$GLOBALS['nrRezervari']++;
					    	$row = $row + 2;
						}
						else
						{	
							// daca celula pe care ne aflam e "Algebra" sau celula din stanga acesteia este "Algebra"; idem pentru "Analiza"
							if(    $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Algebra"
								|| $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).$row)->getValue() == "Algebra"
								|| $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Analiza"
								|| $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).$row)->getValue() == "Analiza"
							)
							{
								//am gasit seminar de 2 ore
								continue;
							}
							else
							{
								if(    $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "impar"
									|| $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "par"
									|| $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Stru"
									|| $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "copou"
								)
								{
									// Aam gasit seminar de Economie
									//am gasit seminar din ala ciudat a lui Strugariu care arata ca un laboratr in Excel
									$row++;
								}
								else
								{
									//am gasit laborator de 2 ore

									//daca avem laborator de MSP proiect care arata a seminar in Excel
									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "MS") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "proiect") !== false
										&& (   stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "impara") !== false
											|| stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "para") !== false
										)
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
									)
									{
										//am gasit laborator de MS proiect de 2 ore care seamana a seminar si avem continutul(MS Proiect*)
										$valCurrentCell = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
										$auxNumeObiect = explode(" ", $valCurrentCell);
								    	$numeObiect = $auxNumeObiect[0]." Proiect";
								    	$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue());
								    	$sala = $auxSala[count($auxSala)-1];
								    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    		$oraSfarsit = ($oraStart+2).":00";
							    		$oraStart = $oraStart.":00";
							    		$coordonatorEveniment = "CP";
								    	echo "<br>".$numeObiect;
								    	echo " ***here A - ".$row;
							    		echo "  --- <b>".$sala."</b>";
								    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    		echo "<b style='color:red'> **** </b>";
							    		insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    		//$GLOBALS['nrRezervari']++;
								    	$row++;
								    	continue;
									}

									//daca avem laborator de MSP singular
									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "MS") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "P") !== false
										&& (   stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "impara") !== false
											|| stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "para") !== false
										)
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
									)
									{
										//am gasit laborator de MS proiect de 2 ore singural care are continutul(MS Proiect*)
										$valCurrentCell = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
										$auxNumeObiect = explode(" ", $valCurrentCell);
								    	$numeObiect = $auxNumeObiect[0]." Proiect";
								    	$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
								    	$sala = $auxSala[count($auxSala)-1];
								    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    		$oraSfarsit = ($oraStart+2).":00";
							    		$oraStart = $oraStart.":00";
							    		$coordonatorEveniment = "CP";
								    	echo "<br>".$numeObiect;
								    	echo " ***here A(singular) - ".$row;
								    	echo "  --- <b>".$sala."</b>";
								    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    		echo "<b style='color:red'> **** </b>";
							    		insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    		//$GLOBALS['nrRezervari']++;
								    	$row++;
								    	continue;
									}

									//daca celula din stanga este de MS proiect si de aceeasi culoare
									if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)-1).$row)->getFill()->getStartColor()->getARGB()
										&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).$row)->getValue(), "MS Proiect") !== false
									)
									{
										//am gasit laborator de MS Proiect de 2 ore care seamana a seminar in Excel, dar nu avem denumirea obiectului in celula
										//asa ca ne uitam in stanga sa luam denumirea
										$valCurrentCell = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).$row)->getValue();
										$auxNumeObiect = explode(" ", $valCurrentCell);
								    	$numeObiect = $auxNumeObiect[0]." Proiect";
								    	$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
								    	$sala = $auxSala[count($auxSala)-1];
								    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    		$oraSfarsit = ($oraStart+2).":00";
							    		$oraStart = $oraStart.":00";
							    		$coordonatorEveniment = "CP";
								    	echo "<br>".$numeObiect;
								    	echo " ***here B - ".$row;
								    	echo "  --- <b>".$sala."</b>";
								    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    		echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    		echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    		echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    		echo "<b style='color:red'> **** </b>";
							    		insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    		//$GLOBALS['nrRezervari']++;
								    	$row++;
								    	continue;
									}

									// daca avem celule in stanga si in dreapta de aceeasi culoare, dar goale
									if(    $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)-1).$row)->getFill()->getStartColor()->getARGB()
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+2).$row)->getFill()->getStartColor()->getARGB()
										&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).$row)->getValue() == ""
										&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
									)
									{
										//avem curs (exemplu curs de APD din Sheet 3); chiar si cursul de "Modelare si simulare-proiect"
										echo "<br>***here C =".$row;
										$row++;
										continue;
									}

									//daca avem celula care sa contina "Modelare si simulare - proiect(MS Proiect)"
									// aici nu o sa intre niciodata, deoarece cursul de  "Modelare si simulare - Proiect(MS proiect)"" intra in 'if'-ul celulela din stanga si cea din dreapta de aceesi culoare si goale
									if(   (stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Modelare si simulare") !== false
										|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "MS") !== false
										)
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "proiect") !== false
									)
									{
										//avem curs de "Modelare si simulare - proiect(MS Proiect)"
										echo "------here D";
										$row++;
										continue;
									}

									//daca celula Contine "Economie par"
									if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Economie") !== false)
									{
										//avem seminar de economie cu o singura grupa care in Excel seamana cu un laborator
										$row++;
										continue;
									}

									// daca celula contine "Marketing"
									if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Marketing") !== false)
									{
										// avem seminar de Marketing
										$row++;
										continue;
									}							

									// MASTER

									if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Calculatoare incorporate") !== false)
									{
										//avem curs de "Calculatoare incorporate"
							    		$row++;
							    		continue;
									}

									// daca celula e "TPDS"s si in dreapta avem o celula goala de aceeasi culoare
									if(    $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "TPDS"
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
										&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
									)
									{
										// avem curs de TPDS
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Limbaje si tehnologii web") !== false
										|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Managementul serviciilor de retea") !== false
									)
									{
										// avem cursuri de "Limbaje si tehnologii web"
										// avem cursuri de "Managementul serviciilor de retea"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "SSD") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "eng") !== false
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
										&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
									)
									{
										// avem cursuri de "SSD-eng"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "FSD") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "eng") !== false
										&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
										&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
									)
									{
										// avem cursuri de "SSD-eng"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Retele") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "RSI") !== false
									)
									{
										// avem cursuri de "Retele de sisteme incorporate RSI"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Manag") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "MPHS") !== false
									)
									{
										// avem cursuri de "Manag. pr. hard si soft (MPHS)"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "om-calc") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "PSIOC") !== false
									)
									{
										// avem cursuri de "Pr. sist interac. om-calc. (PSIOC)"
										$row++;
										continue;
									}

									if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "sist") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "calcul") !== false
										&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "SCD") !== false
									)
									{
										// avem cursuri de "Pr. sist interac. om-calc. (PSIOC)"
										$row++;
										continue;
									}

									//determinare nume obiect pentru cazurile generale
									$valCurrentCell = $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue();
									$auxNumeObiect = explode(" ", $valCurrentCell);
							    	$numeObiect = $auxNumeObiect[0];

							    	//determinare sala
							    	switch($numeObiect)
							    	{
							    		case "Fizica":
							    			$sala = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
							    			break;
							    		case "Desen":
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$sala = $auxSala[count($auxSala)-1];
							    			break;
							    		case "DEEA":
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
							    			$sala = "";
							    			for($j=1; $j<count($auxSala); $j++)
							    			{
							    				$sala .= $auxSala[$j]." ";
							    			}
							    			$sala = substr($sala, 0, -1);	//scoatem acel spatiu de la sfarsit
							    			break;
							    		case "PAC":
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$sala = $auxSala[count($auxSala)-1];
							    			break;
							    		case "SSD":
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$sala = $auxSala[count($auxSala)-1];
							    			break;
							    		case "FSD":
							    			if(    stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "par") !== false
							    				&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
							    			)
								    		{
								    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
								    			$sala = $auxSala[count($auxSala)-1];
								    			if(stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "proiect") !== false)
								    			{
								    				//avem laborator nu numele FSD - proiect(Master)
								    				$numeObiect = "FSD Proiect";
								    			}
								    		}
								    		else
								    		{
								    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
							    				$sala = $auxSala[count($auxSala)-1];
								    		}
								    		break;
								    	case "PTFS":
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
							    			$sala = $auxSala[1];
								    		break;
								    	case "RSI":
								    			//avem laborator RSI impar
								    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    				$sala = $auxSala[1];
								    		break;
							    		default:
							    			//regula generala pentru determinarea salii
							    			$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
							    			$sala = $auxSala[count($auxSala)-1];
							    	}

							    	//determinare oraStart
							    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
							    	$oraSfarsit = ($oraStart+2).":00";
							    	$oraStart = $oraStart.":00";

							    	//determinare nume coordonatorEveniment
							    	switch($numeObiect)
							    	{
							    		case "Fizica":
							    			$coordonatorEveniment = "CB";
							    			break;
							    		case "ED";
							    			$coordonatorEveniment = "CI";
							    			break;
							    		case "TS":
							    			$coordonatorEveniment = "KM";
							    			break;
							    		case "PAC":
							    			$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
							    			break;
							    		case "SSD":
							    			$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
							    			break;
							    		case "FSD":
								    		if(    stripos($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue(), "par") !== false
								    				&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
								    			)
								    		{
								    			$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
								    			$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
							    			}
							    			else
							    			{
							    				//avem laborator de FSD singur in celula
							    				$valNextCell = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
							    				$auxCoordonatorEveniment = explode(" ", $valNextCell);
							    				$coordonatorEveniment = $auxCoordonatorEveniment[0];
							    			}
							    			break;
							    		case "FSD Proiect":
							    			$auxCoordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue());
							    			$coordonatorEveniment = $auxCoordonatorEveniment[count($auxCoordonatorEveniment)-2];
							    			break;
							    		default:
							    			//regula generala pentru determinare nume coordonatorEveniment
							    			$valNextCell = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
							    			$auxCoordonatorEveniment = explode(" ", $valNextCell);
							    			$coordonatorEveniment = $auxCoordonatorEveniment[0];
							    	}

							    	echo "<br>".$numeObiect;
							    	echo "~~~~~~ =".$row;
							    	echo "  --- <b>".$sala."</b>";
							    	echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
							    	echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
							    	echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
							    	echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
							    	echo "<b style='color:red'> **** </b>";
							    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
							    	//$GLOBALS['nrRezervari']++;
							    	$row++;
						    	}
					    	}
						}
					}
				}
		    }
		}
		echo "<br>nr rezervari = ".$GLOBALS['nrRezervari'];
	}

	// ************** OBTINERE SEMINARII *****************

	function importSeminarii($spreadsheet, $worksheetName, $initialDate, $idUser)
    {
	    $tipEveniment = "seminar";

	    for($col ='C'; $col <= 'N'; $col++)		//parcurgere coloane
	    {
			echo "<br>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------col = <b style='color:magenta;'>".$col."</b>";
	    	$grupa = $spreadsheet->getActiveSheet()->getCell($col."8")->getValue();
	    	echo " , grupa = <b style='color:Navy'>".$grupa."</b>";

	    	for($row = 9; $row <= 80; $row++)	//parcurgere randuri
		    {	
		    	// calculare data in functie de ziua din saptamana
		    	if($row >= 9 && $row <= 20)
		    	{
		    		// luni
		    		$startDate = $initialDate;
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 24 && $row <= 35)
		    	{
		    		// marti
		    		$startDate = calculateNextDate($initialDate, 1);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 39 && $row <= 50)
		    	{
		    		// miercuri
		    		$startDate = calculateNextDate($initialDate, 2);
		    		$endDate = $startDate;
		    	}
		    	elseif($row >= 54 && $row <= 65)
		    	{
		    		// joi
		    		$startDate = calculateNextDate($initialDate, 3);
		    		$endDate = $startDate;
		    	}
		    	else
		    	{
		    		// vineri
		    		$startDate = calculateNextDate($initialDate, 4);
		    		$endDate = $startDate;
		    	}

		    	if($row == 21 || $row == 36 || $row == 51 || $row == 66)	// daca am iesit din tabelul cu orarul pe o zi
		    	{
		    		$row = $row + 2;	//marim numarul de randuri ca sa trecem la tabela cu orarul pe ziua urmatoare
		    		continue;
		    	}

		    	if(strlen($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue()) > 0)	//daca am citit ceva pe un rand
		    	{
		    		//obtinem culoarea de umplere a celulei
				    $colorCell = $spreadsheet->getActiveSheet()->getStyle($col.$row)->getFill()->getStartColor()->getARGB();
				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Algebra") !== false
				    	&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() != ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Algebra"
				    	$numeObiect = "Algebra";
				    	$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue();
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
						$oraSfarsit = ($oraStart+2).":00";
						$oraStart = $oraStart.":00";
						if($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() != "")
						{
							$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
						}
						else
						{
							$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue();
						}
				    	echo "<br>".$numeObiect;
						echo "~~~~~~ =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
				    	$grupa = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1)."8")->getValue();
				    	echo " ----grupa2= <b>".$grupa." ***</b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Analiza") !== false
				    	&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() != ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Analiza"
				    	$numeObiect = "Analiza";
				    	$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue();
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	if($spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() != "")
						{
							$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue();
						}
						else
						{
							$coordonatorEveniment = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue();
						}
				    	echo "<br>".$numeObiect;
						echo "~~~~~~ =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
				    	$grupa = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1)."8")->getValue();
				    	echo " ----grupa2= <b>".$grupa." ***</b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Engleza") !== false
				    	&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() == ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
				    	&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue(), "Engleza") === false
				    	&& stripos($spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue(), "par") === false
				    	&& $worksheetName == "I_C"
				    )
				    {
				    	//avem seminar de Engleza singular
				    	$numeObiect = "Engleza";
				    	$sala = NULL;
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = NULL;
				    	echo "<br>".$numeObiect;
						echo "~~~~~~(sing) =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Economie") !== false
				    	&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() != ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle(chr(ord($col)+1).$row)->getFill()->getStartColor()->getARGB()
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Economie"
				    	$numeObiect = "Economie";
				    	$sala = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).($row+1))->getValue();
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = NULL;
				    	echo "<br>".$numeObiect;
						echo "~~~~~~ =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
				    	$grupa = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1)."8")->getValue();
				    	echo " ----grupa2= <b>".$grupa." ***</b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Economie") !== false
				    	&& stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "par") !== false
				    	&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() != ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Economie par" singular
				    	$numeObiect = "Economie";
				    	$sala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue())[0];
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = NULL;
				    	echo "<br>".$numeObiect;
						echo "~~~~~~(singular) =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue() == "Marketing"
				    	&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() != ""
				    	&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1).$row)->getValue() == ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Marketing"
				    	$numeObiect = "Marketing";
				    	$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
				    	$sala = $auxSala[count($auxSala)-2];
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue())[0];
				    	echo "<br>".$numeObiect;
						echo "~~~~~~ =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
				    	$grupa = $spreadsheet->getActiveSheet()->getCell(chr(ord($col)+1)."8")->getValue();
				    	echo " ----grupa2= <b>".$grupa." ***</b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(    stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Marketing") !== false
				    	&& count(explode(" ", $spreadsheet->getActiveSheet()->getCell($col.$row)->getValue())) > 1	// daca avem mai mult de un text in celula
				    	&& $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue() != ""
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row+1))->getFill()->getStartColor()->getARGB()
					)
				    {
				    	//avem seminar de "Marketin imp" singular
				    	$numeObiect = "Marketing";
				    	$auxSala = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue());
				    	$sala = $auxSala[count($auxSala)-2];
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".$row)->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = explode(" ", $spreadsheet->getActiveSheet()->getCell($col.($row+1))->getValue())[0];
				    	echo "<br>".$numeObiect;
						echo "~~~~~~(sing) =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }

				    if(   (stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "copou") !== false
				    	|| stripos($spreadsheet->getActiveSheet()->getCell($col.$row)->getValue(), "Stru") !== false
				    	)
				    	&& $colorCell == $spreadsheet->getActiveSheet()->getStyle($col.($row-1))->getFill()->getStartColor()->getARGB()
				    	&&(    stripos($spreadsheet->getActiveSheet()->getCell($col.($row-1))->getValue(), "Stru") !== false
				    		|| $spreadsheet->getActiveSheet()->getCell($col.($row-1))->getValue() == ""
				    	)
				    	&& $spreadsheet->getActiveSheet()->getCell(chr(ord($col)-1).($row-1))->getValue() != "SPD"
					)
				    {
				    	//avem seminar de "SPD"
				    	$numeObiect = "SPD";
				    	$sala = NULL;
				    	$oraStart = $spreadsheet->getActiveSheet()->getCell("B".($row-1))->getValue();
				    	$oraSfarsit = ($oraStart+2).":00";
				    	$oraStart = $oraStart.":00";
				    	$coordonatorEveniment = "Strugariu";
				    	echo "<br>".$numeObiect;
						echo "~~~~~~(sing) =".$row;
						echo "  --- <b>".$sala."</b>";
						echo " ---- oraStart = <b style='color:blue;'>".$oraStart."</style></b>";
						echo " ---- oraSfarsit = <b style='color:red;'>".$oraSfarsit."</style></b>";
						echo " --------- prof = <b style='color:green;'>".$coordonatorEveniment."</style></b>";
						echo " ------ data = <b>".$startDate."</b> ; sfarsitData = <b>".$endDate."</b> ";
						echo "<b style='color:red'> **** </b>";
				    	insertQueryRezervare($tipEveniment, $coordonatorEveniment, $numeObiect, $grupa, $sala, $startDate, $endDate, $oraStart, $oraSfarsit, $idUser);
				    	//$GLOBALS['nrRezervari']++;
						$row++;
						continue;
				    }
		    	} 	
		    }
		}
		echo "<br>nr rezervari = ".$GLOBALS['nrRezervari'];
	}
 
	require './include/vendor/autoload.php';
	ini_set('memory_limit', '512M');
	ini_set('max_execution_time', 1000);	// secunde
	
	if(isset($_SESSION["idUser"]))
	{
		$idUser = $_SESSION["idUser"];	// id-ul userului logat
	}
	else
	{
		$idUser = NULL;
	}

	// obtinere informatii din formular
	if(isset($_POST['dIncepS']) && !empty($_POST['dIncepS']))
	{
		$initialDate = $_POST['dIncepS'];	// data de inceput semestru
	}
	else
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dSfS']) && empty($_POST['dSfS']))	//data sfarsit semestru
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dIncepVC']) && !empty($_POST['dIncepVC']))
	{
		$startDateVacantaCraciun = new DateTime($_POST['dIncepVC']);	// data de inceput a vacantei de Craciun
	}
	else
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dSfVC']) && !empty($_POST['dSfVC']))
	{
		$endDateVacantaCraciun = new DateTime($_POST['dSfVC']);		// data de sfarsit a vacantei de Craciun
	}
	else
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dIncepE']) && !empty($_POST['dIncepE']))
	{
		$startDateSesiune = new DateTime($_POST['dIncepE']);	// data de inceput sesiune examene
	}
	else
	{
		exit("Eroare, formularul nu este completat in totalitate.");
		
	}

	if(isset($_POST['dSfE']) && !empty($_POST['dSfE']))
	{
		$endDateSesiune = new DateTime($_POST['dSfE']);		// data de sfarsit sesiune examene
	}
	else
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dIncepVI']) && empty($_POST['dIncepVI']))	//data inceput vacanta de iarna
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	if(isset($_POST['dSfVI']) && empty($_POST['dSfVI']))	//data sfarsit vacanta de iarna
	{
		exit("Eroare, formularul nu este completat in totalitate.");
	}

	$target_dir = "./uploads/";		// directorul unde se va incarca fisierul
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);		// fisierul incarcat
	$uploadOk = 1;
	$uploadFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Eroare, fișierul există deja. ";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 1000000) {
	    echo "Eroare, fișierul este prea mare. Limita permisă este 976.56 Kilobytes. ";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($uploadFileType != "xlsx" && $uploadFileType != "xlsm") {
	    echo "Eroare, doar extensii xlsx, xlsm sunt permise. ";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    exit("Eroare, fișierul nu a fost încărcat. ");
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "Fișierul ". basename( $_FILES["fileToUpload"]["name"]). " a fost încărcat cu succes. ";
	    } else {
	        exit("S-a produs o eroare la încărcarea fișierului.");
	    }
	}

	if($uploadFileType == "xlsm" || $uploadFileType == "xlsx")
	{
		$uploadFileType = "Xlsx";	// cu litera marer, altfel nu functioneaza Reader-ul
	}

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($uploadFileType);
	$worksheetNames = $reader->listWorksheetNames($target_file);

	require './config/db-config.php';
    $conn = connectToDB();
    $result = NULL;

    $nrRezervari = 0;	// numarul total de rezervari facute in baza de date

    for($i = 0; $i < 5; $i++)	// sunt 5 Sheet-uri
	{
		$worksheetName = $worksheetNames[$i];
		echo "<br><br>---------------------------------------------------------------- <b> ".$worksheetName." </b> -------------------------------------------------<br><br>";
		$reader->setLoadSheetsOnly(["Sheet ".($i+1), $worksheetName]);
		$spreadsheet = $reader->load($target_file);
		echo "<b>Laboratoare:</b>";
		importLaboratoare($spreadsheet, $worksheetName, $initialDate, $idUser);
		echo "<br><br><b>Seminarii:</b>";
		importSeminarii($spreadsheet, $worksheetName, $initialDate, $idUser);
		echo "<br><br><b>Cursuri:</b>";
		importCursuri($spreadsheet, $worksheetName, $initialDate, $idUser);
	}
}