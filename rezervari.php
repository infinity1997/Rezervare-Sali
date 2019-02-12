<?php
require "include/header.php";
if (!isset($_SESSION["username"]) || $_SESSION["userRole"] == "guest") {
    echo "<p> Nu ai destule drepturi </p>";
} else {
    ?>
    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
                <h1>Rezervare</h1>
                <!-- adauga rezervare -->
                <form action="rezervari.php" method="post">
                    <label for="tip_eveniment">Tip eveniment:</label>
                    <select id="tip_eveniment" name="tip_eveniment">
                        <option value="oră de curs">oră de curs</option>
                        <option value="laborator">laborator</option>
                        <option value="seminar">seminar</option>
                        <option value="proiect">proiect</option>
                        <option value="ședința">sedinta</option>
                        <option value="prezentare firmă">prezentare firma</option>
                        <option value="alte activitați cu studenții">alte activități cu studenții</option>
                        <option value="alte evenimente">alte evenimente</option>
                    </select>
                    <br/>
                    <label for="coordonatorEveniment">Coordonator eveniment:</label>
                    <input type="text" id="coordonatorEveniment" name="coordonatorEveniment">
                    <br/>
                    

                    <label for="numeObiect">Nume obiect:</label>
                    <input type="text" id="numeObiect" name="numeObiect">
                    <br/>


                    <label for="numeGrupa">Nume grupă:</label>
                    <input type="text" id="numeGrupa" name="numeGrupa">
                    <br/>

                    <label for="sala">Sală:</label>
                    <select id="sala" name="sala">
                        <option>C01</option>
                        <option>C02</option>
                        <option>C03</option>
                        <option>C04</option>
                        <option>AC-01</option>
                        <option>AC-02</option>
                        <option>AC-03</option>
                        <option>AC-04</option>
                    </select>
                    <br/>
                   
                    <label for="data">Data:</label>
                    <input type="text" id="data" name="data" placeholder="From Date">
                    <br/>
                    <label for="">Data sfârșit:</label>
                    <input type="text" id="dataSfarsit" name="dataSfarsit" placeholder="To Date">
                    <br/>
                    <label for="">Ora start:</label>
                    <input type="text" id="ora_start" name="ora_inceput" class="time" placeholder="From Time">
                    <script>
			            $(function() {
				            $('#ora_start').timepicker({ 
                                'timeFormat':'HH:mm',
                                'minTime': '08:00',
                                'maxTime': '19:00',
                                'interval': '30'
                            });
			            });
			        </script>
                    <br/>
                    <label for="ora_sfarsit">Ora sfârșit:</label>
                    <input type="text" id="ora_sfarsit" name="ora_sfarsit" placeholder="To Time">
                    <script>
			            $(function() {
				            $('#ora_sfarsit').timepicker({ 
                                'timeFormat':'HH:mm',
                                'minTime': '09:00',
                                'maxTime': '20:00',
                                'interval': '30'
                            });
			            });
			        </script>
                    <br/>


                    <input type="submit" value="Rezervare" name="rezervare">
                </form>
            </div>
        </div>
    </div>
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
                $("#data").datepicker();  
                $("#dataSfarsit").datepicker();
           });  
      });  
    </script>
    <?php
    require "include/adaugaRezervare.php";
    adaugaRezervare();
}
require "include/footer.php";
?>