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
		   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
           <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      </head>  
      <body>  
       
           <br /><br />  
           <div class="container" style="width:900px;">
		    <h1>Ocupare săli</h1>  
                <label for="">Alege sala:</label>
				<select id="search1" name="search1">
						<option value="">...</option>
                        <option value="AC0-1">AC0-1</option>
                        <option value="AC0-2">AC0-2</option>
                        <option value="AC0-3">AC0-3</option>
                        <option value="AC2-1">AC2-1</option>
                        <option value="AC2-2">AC2-2</option>
						<option value="AC3-2">AC3-2</option>	
						<option value="AC3-3">AC3-3</option>
						<option value="C0-2">C0-2</option>	
						<option value="C0-3">C0-3</option>					
						<option value="C0-4">C0-4</option>	
						<option value="C0-5">C0-5</option>	
						<option value="C0-6">C0-6</option>	
						<option value="C1-3">C1-3</option>	
						<option value="C1-4">C1-4</option>
						<option value="C2-8">C2-8</option>	
						<option value="C2-11">C2-11</option>
						<option value="C2-12">C2-12</option>
						<option value="C2-13">C2-13</option>
						<option value="C2-14">C2-14</option>
						<option value="C3-3">C3-3</option>	
						<option value="C4-2">C4-2</option>	
						<option value="CH1">CH1</option>	
						<option value="E1">E1</option>	
						<option value="E3">E3</option>	
						<option value="M1">M1</option>	
						<option value="M2">M2</option>	
						<option value="T4">T4</option>		
                </select>
					 <input type="text" name="from_date" id="from_date"  placeholder="Dată început" />
                     <input type="text" name="to_date" id="to_date"  placeholder="Dată sfărșit" />
				<div class="col-md-3">  
                     <input type="submit" name="cauta" id="cauta" value="Caută" />  
                </div> 
                <div style="clear:both"></div>                 
                <br />  
	
                <div id="order_table" >
					<?php 
					if(isset($_POST["search1"])){
						?>
                     <table class="table table-bordered">  
                          <tr bgcolor="#6FF">  
                          
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
                          <tr bgcolor="#CFF">  
                                
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
			$.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd',
                prevText: "&#xAB; Luna precedentă",
                nextText: "Luna următoare &#xBB;",
                currentText: "Azi",
                monthNames: [ "Ianuarie","Februarie","Martie","Aprilie","Mai","Iunie",
                    "Iulie","August","Septembrie","Octombrie","Noiembrie","Decembrie" ],
                monthNamesShort: [ "Ian", "Feb", "Mar", "Apr", "Mai", "Iun",
                    "Iul", "Aug", "Sep", "Oct", "Nov", "Dec" ],
                dayNames: [ "Duminică", "Luni", "Marţi", "Miercuri", "Joi", "Vineri", "Sâmbătă" ],
                dayNamesShort: [ "Dum", "Lun", "Mar", "Mie", "Joi", "Vin", "Sâm" ],
                dayNamesMin: [ "Du","Lu","Ma","Mi","Jo","Vi","Sâ" ],
                weekHeader: "Săpt",
                firstDay: 1
           });
			$(function(){  
                $("#from_date").datepicker();
                $("#to_date").datepicker();  
           });  
          
			$('#cauta').click(function(){  
				var from_date = $('#from_date').val();  
                var to_date = $('#to_date').val();  
				var search1 = $('#search1').val();  
				if(search1!=''){
					if(from_date != '' && to_date != '')  
					{  
                     $.ajax({  
                          url:"filter.php",  
                          method:"POST",  
                          data:{from_date:from_date, to_date:to_date, search1:search1},  
                          success:function(data)  
                          {  
                               $('#order_table').html(data);  
                          }  
                     });  
					}  
					else  
					{  
                     alert("Nu ati selectat data!");  
					} 
				}else{
					alert("Nu ati selectat sala!");
				}
                
                  
           }); 

		   
      });  
 </script>

<?php
   include "include/footer.php";
?>
