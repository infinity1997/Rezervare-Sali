<?php
 include "include/header.php";
 if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}
 include "config/db-config.php"; 
 $connect=connectToDB();
 $query = "SELECT * FROM rezervari ORDER BY idRezervare desc";  
 $result = mysqli_query($connect, $query);   
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">
			<h1>Program cadru didactic</h1>  
               <label for="">Alege cadru didactic:</label>
               <select id="search" name="search">
						<option value="">...</option>
                        <option value="Aflori Cristian">Aflori Cristian</option>
                        <option value="Alexandrescu Adrian">Alexandrescu Adrian</option>
                        <option value="Amarandei Cristian">Amarandei Cristian</option>
                        <option value="Archip Alexandru">Archip Alexandru</option>
                        <option value="Avram Sorin">Avram Sorin</option>
						<option value="Bârleanu Alexandru">Bârleanu Alexandru</option>
						<option value="Botezatu Nicolae">Botezatu Nicolae</option>
						<option value="Buțincu Cristian">Buțincu Cristian</option>
						<option value="Butnaru Gheorghiță">Butnaru Gheorghiță</option>
						<option value="Caraiman Simona">Caraiman Simona</option>
						<option value="Cașcaval Petru">Cașcaval Petru</option>
						<option value="Cîmpanu Corina">Cîmpanu Corina</option>
						<option value="Ciobănașu Georgeta">Ciobănașu Georgeta</option>
						<option value="Ciobanu Brândușa">Ciobanu Brândușa</option>
						<option value="Cleju Ioan">Cleju Ioan</option>
						<option value="Craus Mitică">Craus Mitică</option>
						<option value="Dumitriu Narcisa">Dumitriu Narcisa</option>
						<option value="Dumitriu Tiberius">Dumitriu Tiberius</option>
						<option value="Ferariu Lavinia">Ferariu Lavinia</option>
						<option value="Floria Sabina">Floria Sabina</option>
						<option value="Gavrilă Ionuț">Gavrilă Ionuț</option>
						<option value="Gavrilescu Marius">Gavrilescu Marius</option>
						<option value="Herghelegiu Paul">Herghelegiu Paul</option>
						<option value="Hrițcu Roxana Otilia Sonia">Hrițcu Roxana Otilia Sonia</option>
						<option value="Hulea Mircea">Hulea Mircea</option>
						<option value="Kloetzer Marius">Kloetzer Marius</option>
						<option value="Leon Florin">Leon Florin</option>
						<option value="Lupu Robert">Lupu Robert</option>
						<option value="Manta Vasile">Manta Vasile</option>
						<option value="Mirea Letiția">Mirea Letiția</option>
						<option value="Mironeanu Cătălin">Mironeanu Cătălin</option>
						<option value="Monor Călin">Monor Călin</option>
						<option value="Olaru Marius">Olaru Marius</option>
						<option value="Pantilimonescu Florin">Pantilimonescu Florin</option>
						<option value="Pavăl Silviu">Pavăl Silviu</option>
						<option value="Petrescu Camelia">Petrescu Camelia</option>
						<option value="Petrila Iulian">Petrila Iulian</option>
						<option value="Pletea Ariadna">Pletea Ariadna</option>
						<option value="Popovici Tudor">Popovici Tudor</option>
						<option value="Rusan Andrei">Rusan Andrei</option>
						<option value="Șerban Elena">Șerban Elena</option>
						<option value="Smochină Cristian">Smochină Cristian</option>
						<option value="Stan Andrei">Stan Andrei</option>
						<option value="Strugariu Răducu">Strugariu Răducu</option>
						<option value="Tibeică Marius Nicolae">Tibeică Marius Nicolae</option>
						<option value="Țigăeru Liviu">Țigăeru Liviu</option>
						<option value="Timiș Mihai">Timiș Mihai</option>
						<option value="Ungureanu Florina">Ungureanu Florina</option>
						<option value="Valachi Alexandru">Valachi Alexandru</option>
						<option value="Vieriu George">Vieriu George</option>
						<option value="Zaharia Mihai">Zaharia Mihai</option>
						<option value="Zbranca Elena">Zbranca Elena</option>
						<option value="Zvorișteanu Otilia">Zvorișteanu Otilia</option>
						<option value=""></option>
						<option value=""></option>
                    </select>
				<br/>
				<div class="col-md-3">  
                     <input type="submit" name="cauta" id="cauta" value="Caută"/>
                </div> 
                <div style="clear:both"></div>                 
                <br />  
				
                <div id="order_table" >
					<?php 
					if(isset($_POST["search"])){
						?>
                     <table class="table table-bordered">  
                          <tr bgcolor="#FFC">  
                             
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
                   
					<?php
                     while($row = mysqli_fetch_array($result))  
                     {  
                     ?>  
                          <tr bgcolor="#FFC">  
                          
                                <td><?php echo $row["tipEveniment"]; ?></td>  
                                <td><?php echo $row["coordonatorEveniment"]; ?></td>  
                                <td><?php echo $row["numeObiect"]; ?></td>  
                                <td><?php echo $row["numeGrupa"]; ?></td> 
								<td><?php echo $row["sala"]; ?></td> 
								<td><?php echo $row["data"]; ?></td> 
								<td><?php echo $row["dataSfarsit"]; ?></td> 
								<td><?php echo $row["oraStart"]; ?></td> 
								<td><?php echo $row["oraSfarsit"]; ?></td> 
								 
                          </tr>  
                     <?php  
                     } 
					}		 
                     ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
      $(document).ready(function(){  
          
			$('#cauta').click(function(){  
                var search = $('#search').val();  

               
                     $.ajax({  
                          url:"filter.php",  
                          method:"POST",  
                          data:{search:search},  
                          success:function(data)  
                          {  
                               $('#order_table').html(data);  
                          }  
                     });    
           });		   
      });  
 </script>

<?php
   include "include/footer.php";
?>
