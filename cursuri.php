<?php
$bdd = new PDO('mysql:host=localhost; dbname=ticalendarac;charset=utf8', 'adminCalendar', 'vfvfFN1JNVgMFpAp');
$sql = "SELECT * FROM rezervari WHERE tipEveniment !='laborator'";
$req = $bdd->prepare($sql);
$req->execute();
$rezervari = $req->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='css/fullcalendar.css' rel='stylesheet' />
</head>
<body>
            
<div class="container">       
    <div class="row">
        <div class="col-lg-12 text-center">
            <div id="calendar" class="col-centered">
            </div>
        </div>
    </div>

</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src='js/moment.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='js/ro.js'></script>

<script>

    $(document).ready(function() {
        $('#calendar').fullCalendar({
		
            header: {
                left: 'prev,next today',
				
                center: 'title',
               //right: 'month,basicWeek,basicDay'
				right: 'month,basicWeek,agendaDay'
				
            },
            eventLimit: true,
            selectHelper: true,
			defaultView: 'agendaDay',
			minTime:"08:00:00",
			maxTime:"20:00:00",
            rezervari: [
                <?php foreach($rezervari as $event):
                ?>
                {
                    title: '<?php echo $event['numeObiect'];?>'+'\n'+'<?php echo $event['sala'];?>'+'\n'+'<?php echo $event['numeGrupa'];?>',
                    start: '<?php echo implode(" ",array($event['data'],$event['oraStart']))?>',
                    end: '<?php echo  implode(" ",array($event['dataSfarsit'],$event['oraSfarsit']))?>',
					color: '#40E0D0',
					<?php if($event['sala']=='AC0-1'){?>
					color:'#FFCC00',
					<?php
					}else if($event['sala']=='AC0-2'){
					?>
					color:'#FF9966'
					<?php
					}else if($event['sala']=='AC0-3'){
					?>
					color:'#FF66FF'
					<?php
					}else if($event['sala']=='AC2-1'){
					?>
					color:'#FF6699'
					<?php
					}else if($event['sala']=='AC2-2'){
					?>
					color:'#FF33FF'
					<?php
					}else if($event['sala']=='AC3-2'){
					?>
					color:'#FF3366'
					<?php
					}else if($event['sala']=='AC3-3'){
					?>
					color:'#66FFCC'
					<?php
					}else if($event['sala']=='C0-2'){
					?>
					color:'#6699CC'
					<?php
					}else if($event['sala']=='C0-3'){
					?>
					color:'#66CC33'
					<?php
					}else if($event['sala']=='C0-4'){
					?>
					color:'#669900'
					<?php
					}else if($event['sala']=='C0-5'){
					?>
					color:'#66CCFF'
					<?php
					}else if($event['sala']=='C0-6'){
					?>
					color:'#CC66FF'
					<?php
					}else if($event['sala']=='C1-3'){
					?>
					color:'#CCFF00'
					<?php
					}else if($event['sala']=='C1-4'){
					?>
					color:'#CC33CC'
					<?php
					}else if($event['sala']=='C2-8'){
					?>
					color:'#CC6600'
					<?php
					}else if($event['sala']=='C2-11'){
					?>
					color:'#CC9999'
					<?php
					}else if($event['sala']=='C2-12'){
					?>
					color:'#CCCC00'
					<?php
					}else if($event['sala']=='C2-13'){
					?>
					color:'#33FFCC'
					<?php
					}else if($event['sala']=='C2-14'){
					?>
					color:'#33FF00'
					<?php
					}else if($event['sala']=='C3-3'){
					?>
					color:'#3399CC'
					<?php
					}else if($event['sala']=='C4-2'){
					?>
					color:'#FF3366'
					<?php
					}else if($event['sala']=='CH1'){
					?>
					color:'#00CCCC'
					<?php
					}else if($event['sala']=='E1'){
					?>
					color:'#FFFF66'
					<?php
					}else if($event['sala']=='E3'){
					?>
					color:'#FFFF66'
					<?php
					}else if($event['sala']=='M1'){
					?>
					color:'#CC6600'
					<?php
					}else if($event['sala']=='M2'){
					?>
					color:'#CC6600'
					<?php
					}else if($event['sala']=='T4'){
					?>
					color:'#00CCFF'
					<?php
					}
					?>
                },
                <?php endforeach; ?>
            ]
        });
    });

</script>

</body>

</html>
