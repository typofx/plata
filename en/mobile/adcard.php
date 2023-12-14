
<style>
    
/*td,tr,th {
    border: 1px solid black;
}            
*/
    
.card-tools-table {
    text-align: center;
    width: 100%;
}

.div-card-centered {
  position: absolute;
  top: 70%;
  left: 50%;
  transform: translate(-50%, -50%);
  width : 100%;
}

.img-card {
   width : 100%;
}

.container {
    position: relative;
    text-align: center;
    width : 100%;
}

</style>

<section id="offchain tools">
        <div class="container">
        <table class="card-tools-table">
            <tr><th>
            <img id="ad-card" class="img-card" src="<?php echo $adCard?>">
            <div class="div-card-centered"><h2><?php echo $txtBuyPlataOffchain ?></h2></div>
            </th></tr>
        </table>
        </div>
</section>