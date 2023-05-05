</html><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Website</title>
  <!-- Cargar los estilos de Bootstrap -->

<style>
    @page {
    size: 10.5cm 2.4cm;
    orientation: landscape;
  }
    html {
    box-sizing: border-box;
 }
*,
*:before,
*:after {
 box-sizing: inherit;
 font-family: 'DejaVu Sans', serif;
 margin: 0;
 padding: 0;
}
body{
   width:140px;
   height:8px;

}
.ticket-title h1{
   padding-top:4%;
   font-size:6.5px;
   width:100%;
   margin-left:20px;
   text-align:center;


}
.img-log{
    padding-top:1%;
   width:20px;
   height:20px;
   position:absolute;
    top:0;
    right:53%;

}

.imagen-barras h4{
    width:45%;
    font-size:6px;
    margin-bottom:-2px;
    padding-left:40%;
    text-align:center;
    white-space: nowrap;

}

.imagen-barras img{
    margin-top:7px;
    width:75%;
    padding-left:25%;

}
.ticket-qr{
    padding-top: 10px;
    padding-left:10px;
    position:absolute;
    top:0;
    right:-17;
    width:40%;


}
.imagen-qr{
    width:70px;
    height:70px;
}


</style>

</head>

<body>

<div class="todo">
    <div class="ticket">
                   <div class="">
                        <div class="ticket-title">
                            <h1>Universidad Nacional de San Martin</h1>
                        </div>
                        <div class="">
                            <img class="img-log" src="data:image/jpeg;base64,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" alt="logo" >
                        </div>
                    </div>
                <div class="">
                   <div class="imagen-barras">
                        <?php echo $Br;?>
                        <h4><?php echo $codigo ?></h4>
                        <h4>F.A. <?php echo $fecha ?></h4>
                    </div>
               </div>
    </div>
    <div class="ticket-qr">
                    <div>
                        <?php echo $Qr;?>
                        
                    </div>
    </div>
</div>

</body>

</html>

