<!doctype html>

<link rel="stylesheet" href="https://www.plata.ie/pix/pix-style.css">
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>



<html lang="pt-br">
<head>
<title>Plata Token - Gerador de QR Code do PIX</title>
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta charset="utf-8">
<meta name="keywords" content="pix, qrcode pix, qr code, br code, brcode pix, pix copia e cola" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
<script src="https://kit.fontawesome.com/0f8eed42e7.js" crossorigin="anonymous"></script>
<script>
function copiar() {
  var copyText = document.getElementById("brcodepix");
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */
  document.execCommand("copy");
  document.getElementById("clip_btn").innerHTML='<i class="fas fa-clipboard-check"></i>';
}
function reais(v){
    v=v.replace(/\D/g,"");
    v=v/100;
    v=v.toFixed(2);
    return v;
}
function mascara(o,f){
    v_obj=o;
    v_fun=f;
    setTimeout("execmascara()",1);
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value);
}
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E6M96X7Y2Y"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E6M96X7Y2Y');
</script>
    <style>
        .invisibled {
            font-size: 0px;
            text-align: center;
            border-style: none;
            resize:none;
            width: 0px;
            height: 0px;
        }
    </style>
</head>
<body>


<br>

<div id="boxApp" align="center">
<div id="box" class="box">
<center><h3>Comprar Plata com Pix</h3></center>

<div id="forml">
<form method="post" action="0xc298812164bd558268f51cc6e3b8b5daaf0b6341.php" target="_blank">

    <?php
    date_default_timezone_set('UTC');
    $date = date("H:i:s T d/m/Y");
    $Expdate = strtotime(date("H:i:s"))+900;//15*60=900 seconds
    $Expdate = date("H:i:s T d/m/Y",$Expdate);
    $_POST["Expdate"] = $Expdate;
    
    ?>

    <div class="div-label"> <label for="valor">   Valor a Pagar (BRL)</label></div>
    <div>
        <input type="number" id="valorpix" name="valorpix" size="15" autocomplete="off" maxlength="13" step="0.001" min="0.001" value="<?= $_POST["valorpix"];?>" onkeyup="BRLexec()" onkeypress="BRLexec()" onclick="select()" onfocusout="addZeroBRL()" required>
        <script>
                
        </script>
    </div>

    <div class="div-label"> <label for="PLTwanted">   Tokens Plata Previstos (PLT)</label></div>
    <div>
        <input type="number" id="PLTwanted" name="PLTwanted" size="15" step="0.0001" autocomplete="off" maxlength="13" min="0.0001" value="<?= $_POST["PLTwanted"];?>" onkeyup="PLTexec()" onkeypress="PLTexec()" onclick="select()" required>
    </div>

    <div class="div-label"><label for="email">   Email</label></div>
    <div>
        <input type="email" id="emailUser" name="emailUser" size="60" maxlength="90" value="<?= $_POST["emailUser"];?>" onclick="this.select();" required>
    </div>

    <div class="div-label"><label for="email">  Confirmar Email</label></div>
    <div>
        <input type="email" id="confirmemail" name="confirmemail" size="60" maxlength="90" onclick="this.select();" required>
    </div> 
    
    <div>
        <div class="div-label"><label for="email">   Carteira Web3 Polygon(MATIC)</label></div><div>
        <input type="text" id="web3wallet" name="web3wallet" placeholder="0x..." size="60" maxlength="42" value="<?= $_POST["web3wallet"];?>" onclick="this.select();" onfocusout="isValidEtherWallet()" required>
    </div>
    <div>
    <div style="display: none;">
        <label for="identificador">Identificador</label>
        <input type="text" id="identificador" name="identificador" value="<?php echo (rand(100,999));?>" required>
    </div>
    <hr width="95%" class="hrline"/>
    <button id="submitButton" name="submit" class="buttonBuyNow" onclick="checkemail()">Gerar QR Code <i class="fas fa-qrcode"></i></button>
    <hr width="95%" class="hrline"/>
    </form>
    </div></div></div></div>

<?php include '../en/mobile/price.php';?>
<br>
<center><a id="dappVersion">PlataByPix dApp Version 0.1.3 (Beta)</a></center>
<?php
    date_default_timezone_set('UTC');
    echo "<center>" . $Expdate . "</center>";
?>

<script>

    document.getElementById("valorpix").value = "10.00";
    let _PLTBRL = <?php echo number_format($PLTBRL, 8, '.' , ''); ?>;
    console.log("123: "+  _PLTBRL);
    document.getElementById("txtCurrencyEnv").innerText = "(BRL)";
    document.getElementById("txtPAIR").innerText = "<?php echo $PLTBRL?>";
    document.getElementById("tr-price").style.visibility = "collapse";
    
    function BRLexec() {
        let textBRL = Number(document.getElementById("valorpix").value);
        document.getElementById("PLTwanted").value = Number(textBRL/_PLTBRL).toFixed(4);
    } BRLexec();

    function addZeroBRL() {
        document.getElementById("valorpix").value = Number(document.getElementById("valorpix").value).toFixed(2);
    }

    function PLTexec() {
        let textPLT = Number(document.getElementById("PLTwanted").value);
        document.getElementById("valorpix").value = Number(textPLT*_PLTBRL).toFixed(2);
    }
    
    function isValidEtherWallet(){
        let address = document.getElementById("web3wallet").value;
        let result = Web3.utils.isAddress(address);
        if (result!= true) document.getElementById("web3wallet").value = "";
        console.log(result);  // => true?
    }  
    
    function checkemail(){
        if ( document.getElementById("emailUser").value != (document.getElementById("confirmemail").value) ) document.getElementById("confirmemail").value = "";
    }
    
</script>


</body>
</html>
