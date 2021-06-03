<?php include "config.php";
$sql = "SELECT * FROM ClickGame";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html>
<style>
    body {
        margin: 0;
        font-family: Arial;
    }

    .header {
        text-align: center;
        padding: 32px;
    }

    .column img {
        margin-top: 12px;
        width: 8%;
        alignment: center;
    }

    .row{
        alignment: center;
    }
    .game-info{
        max-width: 1000px;
        margin: 0px auto;
        text-align: center;
    }
    .game-info table{
        width: 100%;
    }
    .delete-btn{
        color: red;
        text-align: center;
        display: block;
    }

</style>
<body>

<!-- Header -->
<div class="header">
    <h1>Image Grid</h1>
</div>

<!-- Photo Grid -->
<div class="row" align="center">
    <div class="column">
        <img id="img1" onclick="findMatch(1)" src="clickme.jpeg">
        <img id="img2" onclick="findMatch(2)" src="clickme.jpeg">
        <img id="img3" onclick="findMatch(3)" src="clickme.jpeg">
        <img id="img4" onclick="findMatch(4)" src="clickme.jpeg">
    </div>
    <div class="column">
        <img id="img5" onclick="findMatch(5)" src="clickme.jpeg">
        <img id="img6" onclick="findMatch(6)" src="clickme.jpeg">
        <img id="img7" onclick="findMatch(7)" src="clickme.jpeg">
        <img id="img8" onclick="findMatch(8)" src="clickme.jpeg">
    </div>
    <div class="column">
        <img id="img9" onclick="findMatch(9)" src="clickme.jpeg">
        <img id="img10" onclick="findMatch(10)" src="clickme.jpeg">
        <img id="img11" onclick="findMatch(11)" src="clickme.jpeg">
        <img id="img12" onclick="findMatch(12)" src="clickme.jpeg">
    </div>
</div>
<div class="header">
    <h1 id="match" hidden>MATCH</h1>
    <h1 id="notmatch" hidden>NOT MATCH</h1>
    <h1 id="gameover" hidden>GAME OVER</h1>

</div>
<a href="javascript:void(0)" onclick="do_delete()" class="delete-btn">DELETE</a>
<div class="game-info">
<h1>Game Info</h1>
<table>
    <thead>
        <tr>
            <th>Player</th>
            <th>Total Image Clicks</th>
            <th>Total Wrong Pair Clicks</th>
            <th>Time [Second]</th>
        </tr>
    </thead>
    <tbody>
        <?php while($obj = $result->fetch_object()){ ?>
            <tr>
                <td><?php echo $obj->id;?></td>
                <td><?php echo $obj->clickCount;?></td>
                <td><?php echo $obj->errorCount;?></td>
                <td><?php 
                $parsed = date_parse($obj->gameTime);
                $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
                echo $seconds;?></td>
            </tr>
        <?php }?>
        
    </tbody>
    
</table>
</div>
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script>
    //STATIC VARIABLE - every function call, variable will continue from previous value
    findMatch.firstClick = 0; //keep the picture id first click by user
    findMatch.secondClick = 0;
    findMatch.matchPic = 0;
    var d = 0;
    var hour = 0;
    var min = 0;
    var sec = 0;
    setInterval(setTime, 1000);
    var clickCounter = 0;
    var errorCounter = 0;
    var array_Match = [1,10,2,12,3,8,4,11,5,6,7,9];
    array_Match = shuffle(array_Match);
    console.log(array_Match);
    function findMatch(x) {
        clickCounter++;
        switch(x){
            case array_Match[0] : case array_Match[1] : findMatch.picName = 'pic1.jpg';break;
            case array_Match[2] : case array_Match[3] : findMatch.picName = 'pic3.jpg';break;
            case array_Match[4] : case array_Match[5] : findMatch.picName = 'pic6.jpg';break;
            case array_Match[6] : case array_Match[7] : findMatch.picName = 'pic5.jpg';break;
            case array_Match[8] : case array_Match[9] : findMatch.picName = 'pic2.jpg';break;
            case array_Match[10] : case array_Match[11] : findMatch.picName = 'pic4.jpg';break;
        }

        if(findMatch.firstClick == 0) {
            document.getElementById('img' + x.toString()).src = findMatch.picName;
            findMatch.firstClick = x;
        }else if(findMatch.secondClick==0){
            document.getElementById('img' + x.toString()).src = findMatch.picName;
            //alert
            findMatch.secondClick = x;
            console.log(findMatch.firstClick);
            console.log(findMatch.secondClick);

            if((findMatch.firstClick==array_Match[0] && findMatch.secondClick==array_Match[1])
                || (findMatch.firstClick==array_Match[2] && findMatch.secondClick==array_Match[3])
                || (findMatch.firstClick==array_Match[4] && findMatch.secondClick==array_Match[5])
                || (findMatch.firstClick==array_Match[6] && findMatch.secondClick==array_Match[7])
                || (findMatch.firstClick==array_Match[8] && findMatch.secondClick==array_Match[9])
                || (findMatch.firstClick==array_Match[10] && findMatch.secondClick==array_Match[11])){
                findMatch.matchPic++;
                document.getElementById('match').hidden = false;
                document.getElementById('notmatch').hidden = true;
            }else{
                errorCounter++;
                document.getElementById('match').hidden = true;
                document.getElementById('notmatch').hidden = false;
                document.getElementById('img' + findMatch.firstClick.toString()).src = 'clickme.jpeg';
                document.getElementById('img' + findMatch.secondClick.toString()).src = 'clickme.jpeg';
            }
            findMatch.firstClick = 0;
            findMatch.secondClick = 0;
        }
        if(findMatch.matchPic == 6){
            alert('CONGRATULATIONS. GAME OVER');
            console.log(clickCounter);
            console.log(errorCounter);
            console.log(get_time());

            insert_db(clickCounter,errorCounter,get_time())
            array_Match = shuffle(array_Match);
            console.log(array_Match);
            reset();
            // for(i=1;i<=12;i++){
            //     document.getElementById('img'+i.toString()).onclick=noRespond;
            // }
        }
    }
    function get_time(){
        if(sec<10){
            secP = "0"+sec;
        }else{
            secP = sec;
        }
        if(min<10){
            minP = "0"+min;
        }else{
            minP = min;
        }
        if(hour<10){
            hourP = "0"+hour;
        }else{
            hourP = hour;
        }

        return hourP+":"+minP+":"+secP;



    }
    function setTime(){
        sec++;
        if(sec===60){
            sec = 0;
            min++;
        }
        if(min===60){
            min = 0;
            hour++;
        }
    }

    function noRespond(){
        document.getElementById('match').hidden = true;
        document.getElementById('notmatch').hidden = true;
        document.getElementById('gameover').hidden = true;
    }
    function reset(){
        for(i=1;i<=12;i++){
            document.getElementById('img' + i).src = 'clickme.jpeg';
        }
        findMatch.firstClick = 0;
        findMatch.secondClick = 0;
        findMatch.matchPic = 0;
        clickCounter = 0;
        errorCounter = 0;
        hour = 0; min = 0; sec = 0;
        noRespond();
        
    }
    function do_delete(){
        var r = confirm("are you sure?");
        if(r){
            window.location.href = "delete.php";
            return true;
        }else{
            return false;
        }
    }

    function shuffle(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }
    function insert_db(clickCounter,errorCounter,playtime){
        $.ajax({
            url: 'update_result.php',
            type: 'POST',
            dataType: 'html',
            data: {
                clickCounter: clickCounter,
                errorCounter: errorCounter,
                playtime: playtime,
            },
        })
        .done(function(result) {
            $(".game-info table tbody").append(result);
           // console.log("success");
        })
        .fail(function() {
           // console.log("error");
        });
        
    }
</script>

</body>
</html>
