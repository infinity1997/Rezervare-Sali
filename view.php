 <?php 
 include "config/db-config.php"; 
 $connect=connectToDB();
 $query = "SELECT * FROM rezervari ORDER BY idRezervare desc";  
 $result = mysqli_query($connect, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
           <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
      </head>  
      <body>  
       
           <br /><br />  
           <div class="container" style="width:900px;">
	   
                <h3 align="center">Caută Eveniment</h3><br />  
                <div class="col-md-3">  
                     <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" />  
                </div>  
                <div class="col-md-3">  
                     <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" />  
                </div>  
                <div class="col-md-5">  
                     <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />  
                </div>  
				<br/><br/><br/>
				<div class="col-md-3">  
                     <input type="button" name="display" id="display" value="DisplayAll" class="btn btn-info" />  
                </div> 
                <div style="clear:both"></div>                 
                <br />  
				
                <div id="order_table" >
					<?php 
					if(isset($_POST["from_date"], $_POST["to_date"]) || isset($_POST["display"]) ){
						?>
                     <table class="table table-bordered">  
                          <tr bgcolor="#6FF">  
                            <th width="20%">ID rezervare</th>  
							<th width="30%">Eveniment</th>  
							<th width="30%">Coordonator</th>  
							<th width="30%">Obiect</th>  
							<th width="30%">Grupa</th>  
							<th width="30%">Sala</th>  
							<th width="30%">Data_start</th> 
							<th width="30%">Data_sfarsit</th>
							<th width="30%">Ora_start</th> 
							<th width="30%">Ora_sfarsit</th>
							<th width="30%">idUser</th> 
							<th width="30%">Time_stamp</th>  
                          </tr>  
                   
					<?php
                     while($row =  $result->fetchAll())  
                     {  
                     ?>  
                          <tr bgcolor="#CFF">  
                               <td><?php echo $row["idRezervare"]; ?></td>  
                                <td><?php echo $row["tipEveniment"]; ?></td>  
                                <td><?php echo $row["coordonatorEveniment"]; ?></td>  
                                <td><?php echo $row["numeObiect"]; ?></td>  
                                <td><?php echo $row["numeGrupa"]; ?></td> 
								<td><?php echo $row["sala"]; ?></td> 
								<td><?php echo $row["data"]; ?></td> 
								<td><?php echo $row["dataSfarsit"]; ?></td>
								<td><?php echo $row["oraStart"]; ?></td> 
								<td><?php echo $row["oraSfarsit"]; ?></td>
								<td><?php echo $row["idUser"]; ?></td> 
								<td><?php echo $row["timeStamp"]; ?></td> 
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
                dateFormat: 'yy-mm-dd'   
           });  
           $(function(){  
                $("#from_date").datepicker();  
                $("#to_date").datepicker();  
           });  
           $('#filter').click(function(){  
                var from_date = $('#from_date').val();  
                var to_date = $('#to_date').val();  
                if(from_date != '' && to_date != '')  
                {  
                     $.ajax({  
                          url:"filter.php",  
                          method:"POST",  
                          data:{from_date:from_date, to_date:to_date},  
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
           }); 
			$('#display').click(function(){  
                var display = $('#display').val();  

               
                     $.ajax({  
                          url:"filter.php",  
                          method:"POST",  
                          data:{display:display},  
                          success:function(data)  
                          {  
                               $('#order_table').html(data);  
                          }  
                     });  
                
                  
           }); 

		 		   
      });
      //
 </script>
