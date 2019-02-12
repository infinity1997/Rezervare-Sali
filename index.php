<?php
include "include/header.php";
include "include/view.php";
?>
<div class="container">
    <div class="row">
        <div class="col col-lg-12">
            <div class="butoaneHomeGuest" id="bH1"> 
                <h3>Ocupare săli AC</h3> <br>
                <p>Ocupare săli de curs și laboratoare</p> <br>
                <button class="button" onclick="window.location.href='ocupare.php'"><span>Treci </span></button>
            </div>
            <div class="butoaneHomeGuest" id="bH2"> 
                <h3>Program cadru didactic</h3> <br>
                <p>Vizualizare program cadre didactice</p> <br>
                <button class="button" onclick="window.location.href='program.php'"><span>Treci </span></button>
            </div>
            <div class="butoaneHomeGuest" id="bH3"> 
                <h3>Orar grupe</h3> <br>
                <p>Orarul grupelor</p> <br> <br>
                <button class="button" onclick="window.location.href='orar.php'"><span>Treci </span></button>
            </div>
            
            <?php
                if(isset($_SESSION["username"])&&($_SESSION["userRole"]=="teacher" || $_SESSION["userRole"]=="admin")) {
                    ?>
                    <div class="butoaneHome">
                        <h3>Rezervare</h3> <br>
                        <p>Creează rezervare pentru un eveniment</p><br>
                        <button class="button" onclick="window.location.href='rezervari.php'"><span>Treci </span></button>
                    </div>
                    <script>
                        $(document).ready(function(){
                                $("#bH1").removeClass("butoaneHomeGuest").addClass("butoaneHome");
                                $("#bH2").removeClass("butoaneHomeGuest").addClass("butoaneHome");
                                $("#bH3").removeClass("butoaneHomeGuest").addClass("butoaneHome");
                        });
                    </script>
                    <?php
                }
            ?>
        </div> 
    </div> 
</div>
<?php
include "include/footer.php";
?>
