<html>
<head>
<style>
	table {
		cellpadding=30px;
	}
	
	th, td {
		padding: 15px;
		text-align: center;
}
</style>
</head>
</html>
<?php  
 include "config/db-config.php"; 
 $connect=connectToDB();
  
 if(isset($_POST["display"])) 
 {  
      $output = '';  
      $query = "SELECT * FROM rezervari ORDER BY data ASC, oraStart ASC";  
      $result = mysqli_query($connect, $query); 
		
      $output .= '  
           <table class="table table-bordered" >  
                <tr bgcolor="#FF9">  
                      
                    <th width="30%">Eveniment</th>  
					<th width="30%">Coordonator</th>  
					<th width="30%">Obiect</th>  
					<th width="30%">Grupă</th>  
                    <th width="30%">Sală</th>  
					<th width="30%">Dată start</th> 
					<th width="30%">Dată sfârșit</th>
					<th width="30%">Oră start</th> 
					<th width="30%">Oră sfârșit</th>
                </tr>  
      ';  
      if(mysqli_num_rows($result) > 0 )
      {  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '  
                     <tr bgcolor="#FFC">  
                          <td>'. $row["tipEveniment"] .'</td>  
                          <td>'. $row["coordonatorEveniment"] .'</td>   
                          <td>'. $row["numeObiect"] .'</td>  
						  <td>'. $row["numeGrupa"] .'</td> 
						  <td>'. $row["sala"] .'</td> 
						  <td>'. $row["data"] .'</td> 
						  <td>'. $row["dataSfarsit"] .'</td> 
						  <td>'. $row["oraStart"] .'</td> 
						  <td>'. $row["oraSfarsit"] .'</td> 
						  
                     </tr>  
                ';  
           }  
      }  
      else  
      {  
           $output .= '  
                <tr>  
                     <td colspan="5">No found!</td>  
                </tr>  
           ';  
      }  
      $output .= '</table>';  
      echo $output;  
 }  
 
  if(isset($_POST["from_date"], $_POST["to_date"],$_POST["search1"]))  
 {  
	 $_var=$_POST["search1"];
      $output = '';  
      $query = "  
           SELECT * FROM rezervari  
           WHERE data BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND (numeGrupa='$_var' OR sala='$_var') ORDER BY data ASC, oraStart ASC
      ";  
      $result = mysqli_query($connect, $query);  
      $output .= '  
           <table class="table table-bordered">  
                <tr bgcolor="#FF9">  
                     
                    <th width="30%">Eveniment</th>  
					<th width="30%">Coordonator</th>  
					<th width="30%">Obiect</th>  
					<th width="30%">Grupă</th>  
                    <th width="30%">Sală</th>  
					<th width="30%">Dată start</th> 
					<th width="30%">Dată sfârșit</th>
					<th width="30%">Oră start</th> 
					<th width="30%">Oră sfârșit</th>
		
                </tr>  
      ';  
      if(mysqli_num_rows($result) > 0)  
      {  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '  
                     <tr bgcolor="#FFC">  
                         
                          <td>'. $row["tipEveniment"] .'</td>  
                          <td>'. $row["coordonatorEveniment"] .'</td>   
                          <td>'. $row["numeObiect"] .'</td>  
						  <td>'. $row["numeGrupa"] .'</td> 
						  <td>'. $row["sala"] .'</td> 
						  <td>'. $row["data"] .'</td> 
						  <td>'. $row["dataSfarsit"] .'</td> 
						  <td>'. $row["oraStart"] .'</td> 
						  <td>'. $row["oraSfarsit"] .'</td> 
						  
                     </tr>  
                ';  
           }  
      }  
      else  
      {  
           $output .= '  
                <tr>  
                     <td colspan="5">No found!</td>  
                </tr>  
           ';  
      }  
      $output .= '</table>';  
      echo $output;  
 } 


 if(isset($_POST["search"])) 
 {  
	  $_var=$_POST["search"];
      $output = '';  
      $query = "SELECT * FROM rezervari WHERE sala='$_var' OR coordonatorEveniment='$_var' OR numeGrupa='$_var' ORDER BY data ASC, oraStart ASC";  
      $result = mysqli_query($connect, $query); 
		
      $output .= '  
           <table class="table table-bordered">  
                <tr bgcolor="#FF9">  
        
                    <th width="30%">Eveniment</th>  
					<th width="30%">Coordonator</th>  
					<th width="30%">Obiect</th>  
					<th width="30%">Grupă</th>  
                    <th width="30%">Sală</th>  
					<th width="30%">Dată start</th> 
					<th width="30%">Dată sfârșit</th> 
					<th width="30%">Oră start</th> 
					<th width="30%">Oră sfârșit</th> 
			
                </tr>  
      ';  
      if(mysqli_num_rows($result) > 0 )
      {  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '  
                     <tr bgcolor="#FFC">  
                          
                          <td>'. $row["tipEveniment"] .'</td>  
                          <td>'. $row["coordonatorEveniment"] .'</td>   
                          <td>'. $row["numeObiect"] .'</td>  
						  <td>'. $row["numeGrupa"] .'</td> 
						  <td>'. $row["sala"] .'</td> 
						  <td>'. $row["data"] .'</td> 
						  <td>'. $row["dataSfarsit"] .'</td> 
						  <td>'. $row["oraStart"] .'</td> 
						  <td>'. $row["oraSfarsit"] .'</td> 
						  
                     </tr>  
                ';  
           }  
      }  
      else  
      {  
           $output .= '  
                <tr>  
                     <td colspan="5">No found!</td>  
                </tr>  
           ';  
      }  
      $output .= '</table>';  
      echo $output;  
 }  
 
 
 



 ?>
