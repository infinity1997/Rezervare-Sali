<?php
require "include/header.php";
if (!isset($_SESSION["username"]) || $_SESSION["userRole"] == "guest" || $_SESSION["userRole"] == "teacher") {
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
                <h1>Import orar</h1> <br>
                <div style="font-size: 22px;"> Setarea calendarului anului universitar</div> <br>
                <!-- Adaugare documentului cu orar -->
                <form action="./importOrar.php" method="post" enctype="multipart/form-data">
                    <label for="">Alege semestrul: </label>
                    <div class="checkbox checkbox-primary">
                        <input id="sI" class="styled" type="checkbox">
                        <label for="sI">Semestrul I
                        </label>
                    </div> 
                    <script>  
                        $(document).ready(function(){  
                            $('#sI').click(function(){ 
                                $('#sII').not('#sI').removeAttr('checked');
                                if(document.getElementById("sII").checked == false)
                                {
                                    document.getElementById("sem00").style.display="none";
                                    document.getElementById("sem01").style.display="none";
                                    document.getElementById("sem20").style.display="none";
                                    document.getElementById("sem21").style.display="none";
                                    document.getElementById("sem22").style.display="none";
                                }
                                if(document.getElementById("sI").checked == true)
                                {
                                    document.getElementById("sem00").style.display="block";
                                    document.getElementById("sem01").style.display="block";
                                    document.getElementById("sem10").style.display="block";
                                    document.getElementById("sem11").style.display="block";
                                }
                                else{
                                    document.getElementById("sem00").style.display="none";
                                    document.getElementById("sem01").style.display="none";
                                    document.getElementById("sem10").style.display="none";
                                    document.getElementById("sem11").style.display="none";
                                }
                            });	   
                        });  
                    </script>
                    <div class="checkbox checkbox-primary">
                        <input id="sII" class="styled" type="checkbox">
                        <label for="sII">Semestrul II
                        </label>
                    </div>
                    <br/>
                    <script>  
                        $(document).ready(function(){  
                            $('#sII').click(function(){ 
                                $('#sI').not('#sII').removeAttr('checked');
                                if(document.getElementById("sI").checked == false)
                                {
                                    document.getElementById("sem00").style.display="none";
                                    document.getElementById("sem01").style.display="none";
                                    document.getElementById("sem10").style.display="none";
                                    document.getElementById("sem11").style.display="none";
                                }
                                if(document.getElementById("sII").checked == true)
                                {
                                    document.getElementById("sem00").style.display="block";
                                    document.getElementById("sem01").style.display="block";
                                    document.getElementById("sem20").style.display="block";
                                    document.getElementById("sem21").style.display="block";
                                    document.getElementById("sem22").style.display="block";
                                }
                                else{
                                    document.getElementById("sem00").style.display="none";
                                    document.getElementById("sem01").style.display="none";
                                    document.getElementById("sem20").style.display="none";
                                    document.getElementById("sem21").style.display="none";
                                    document.getElementById("sem22").style.display="none";
                                }
                            });		   
                        });  
                    </script>
                    <div id="sem00" style="display:none;">
                        <label for="">Alege intervalul semestrului: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepS" name="dIncepS" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Data sfârșit:</label>
                        <input type="text" id="dSfS" name="dSfS" placeholder="To Date">
                        <br>
                    </div> 

                    <div id="sem10" style="display:none;">
                        <label for="">Alege interval vacanță de Crăciun: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepVC" name="dIncepVC" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfVC" name="dSfVC" placeholder="To Date">
                        <br>
                    </div>
                    <div id="sem01" style="display:none;">
                        <label for="">Alege intervalul sesiunii de examene: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepE" name="dIncepE" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfE" name="dSfE" placeholder="To Date">
                        <br>
                    </div>
                    <div id="sem11" style="display:none;">
                        <label for="" >Alege interval vacanță de iarnă: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepVI" name="dIncepVI" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfVI" name="dSfVI" placeholder="To Date">
                        <br>
                    </div> 
                    <div id="sem20" style="display:none;">
                        <label for="">Alege interval vacanță de Paști: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepVP" name="dIncepVP" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfVP" name="dSfVP" placeholder="To Date">
                        <br>
                    </div>
                    <div id="sem21" style="display:none;">
                        <label for="">Alege intervalul sesiunii de restanțe: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepSR" name="dIncepSR" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfSR" name="dSfSR" placeholder="To Date">
                        <br>
                    </div>
                    <div id="sem22" style="display:none;">
                        <label for="">Alege interval vacanță de vară: </label> <br>
                        <label for="" style="margin-left: 70px;">Dată început:</label>
                        <input type="text" id="dIncepVV" name="dIncepVV" placeholder="From Date">
                        <br/>
                        <label for="" style="margin-left: 70px;">Dată sfârșit:</label>
                        <input type="text" id="dSfVV" name="dSfVV" placeholder="To Date">
                        <br>
                    </div>
                    <br>
                    <div style="font-size: 22px;"> Încărcare orar </div>
                    <label for="">Selectare orar:</label>
                    <input type="file" name="fileToUpload" id="orar-upload">
                    <br>
                    <input type="submit" value="Upload" name="submit" >
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
                $("#dIncepS").datepicker();  
                $("#dSfS").datepicker();
                $("#dIncepVC").datepicker();  
                $("#dSfVC").datepicker();
                $("#dIncepE").datepicker();  
                $("#dSfE").datepicker();
                $("#dIncepVI").datepicker();  
                $("#dSfVI").datepicker();
                $("#dIncepVP").datepicker();  
                $("#dSfVP").datepicker();
                $("#dIncepSR").datepicker();  
                $("#dSfSR").datepicker();
                $("#dIncepVV").datepicker();  
                $("#dSfVV").datepicker();
           });  
      });  
    </script>
    <?php
    
}
require "include/footer.php";
?>