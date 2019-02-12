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
			<h1>Orar</h1>  
               <label for="">Alege anul:</label>
               <select id="searchAn" name="search1" onchange="myFunction(value);">
						<option value="">...</option>
                        <option value="an1">Anul 1</option>
                        <option value="an2">Anul 2</option>
                        <option value="an3">Anul 3</option>
                        <option value="an4">Anul 4</option>
                        <option value="master1">Masterat An 1</option>
                        <option value="master2">Masterat An 2</option>
               </select>
               <script>
                    function myFunction($x) {
                         
                         if($x==="an1")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=13;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                         else if($x==="an2")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=0;i<13;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                              for(i=23;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                         else if($x==="an3")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=0;i<23;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                              for(i=34;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                         else if($x==="an4")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=0;i<34;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                              for(i=43;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                         else if($x==="master1")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=0;i<43;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                              for(i=49;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                         else if($x==="master2")
                         {
                              for(i=0;i<55;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="block";
                              }
                              for(i=0;i<49;i++)
                              {
                                   var z = document.getElementById("search1").options;
                                   z[i].style.display="none";
                              }
                         }
                    }
               </script>
               <br> <br>
               <label for="">Alege grupa:</label>
               <select id="search1" name="search1">
                              <option value="">...</option>
                        <option value="1106A">1106A</option>
                        <option value="1106B">1106B</option>
                        <option value="1107A">1107A</option>
                        <option value="1107B">1107B</option>
                        <option value="1108A">1108A</option>
                        <option value="1108B">1108B</option>
                        <option value="1109A">1109A</option>
                        <option value="1109B">1109B</option>
                        <option value="1110A">1110A</option>
                        <option value="1110B">1110B</option>
                        <option value="1111A">1111A</option>
                        <option value="1111B">1111B</option>
                        <option value="1205A">1205A</option>
                        <option value="1205B">1205B</option>
                        <option value="1206A">1206A</option>
                        <option value="1206B">1206B</option>
                        <option value="1207A">1207A</option>
                        <option value="1208A">1208A</option>
                        <option value="1208B">1208B</option>
                        <option value="1209A">1209A</option>
                        <option value="1209B">1209B</option>
                        <option value="1210A">1210A</option>
                        <option value="1305A">1305A</option>
                        <option value="1306B">1305B</option>
                        <option value="1306A">1306A</option>
                        <option value="1306B">1306B</option>
                        <option value="1307A">1307A</option>
                        <option value="1308A">1308A</option>
                        <option value="1308B">1308B</option>
                        <option value="1309A">1309A</option>
                        <option value="1309B">1309B</option>
                        <option value="1310A">1310A</option>
                        <option value="1310B">1310B</option>
                        <option value="1405A">1405A</option>
                        <option value="1405B">1405B</option>
                        <option value="1406A">1406A</option>
                        <option value="1406B">1406B</option>
                        <option value="1407A">1407A</option>
                        <option value="1407B">1407B</option>
                        <option value="1408A">1408A</option>
                        <option value="1408B">1408B</option>
                        <option value="1409A">1409A</option>
                        <option value="CI 1A">CI 1A</option>
                        <option value="CI 1B">CI 1B</option>
                        <option value="SDTW 1A">SDTW 1A</option>
                        <option value="SDTW 1B">SDTW 1B</option>
                        <option value="DSWT 1A">DSWT 1A</option>
                        <option value="DSWT 1B">DSWT 1B</option>
                        <option value="CI 2A">CI 2A</option>
                        <option value="CI 2B">CI 2B</option>
                        <option value="SDTW 2A">SDTW 2A</option>
                        <option value="SDTW 2B">SDTW 2B</option>
                        <option value="DSWT 2A">DSWT 2A</option>
                        <option value="DSWT 2B">DSWT 2B</option>
						
                    </select>
                    <br> <br>
				<input type="text" name="from_date" id="from_date"  placeholder="Dată început" style="width: 150px;" />
                     <input type="text" name="to_date" id="to_date"  placeholder="Dată sfărșit" style="width: 150px;"/>
                    <br>  
                     <input type="submit" name="cauta" id="cauta" value="Caută"  /> 
                <div style="clear:both"></div>                 
                <br />  
				
                <div id="order_table" >
				

					
					<?php 
					if(isset($_POST["search1"]) && isset($_POST["from_date"]) && isset($_POST["to_date"])){
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
					alert("Nu ati selectat grupa!");
				}
                
                  
           }); 

		 		   
      });  
 </script>

<?php
   include "include/footer.php";
?>
