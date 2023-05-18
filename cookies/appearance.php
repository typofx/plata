<?php
if(isset($_COOKIE['appearance'])) {
    $appearance = $_COOKIE['appearance'];
} else {
    $appearance = 'off'; // Definir valor padrão
}

if(isset($_POST['change_appearance'])) {
    if($appearance == 'off') {
        $appearance = 'on';
    } else {
        $appearance = 'off';
    }

    setcookie('appearance', $appearance, time() + (86400 * 30), '/'); // Definir cookie por 30 dias
}

function setCookieValue($value) {
    setcookie('appearance', $value, time() + (86400 * 30), '/'); // Definir cookie por 30 dias
    $_COOKIE['appearance'] = $value; // Atualizar o valor do cookie na variável $_COOKIE
}
?>

<script>
window.addEventListener('DOMContentLoaded', function() {
    const elem = document.getElementById("info");
    const body = document.body;

    if (elem.innerText == "on") {
        body.style.backgroundColor = "#000000"; // Fundo escuro
        body.style.color = "#ffffff"; // Texto branco
    } else {
        body.style.backgroundColor = "#ffffff"; // Fundo claro
        body.style.color = "#000000"; // Texto preto
    }
});

function setCookie(value) {
    // Enviar uma solicitação para atualizar o cookie usando PHP
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("change_appearance=1");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Atualizar o valor do cookie na página sem recarregar
                setCookieValue(value);
            }
        }
    };
}

function changeAppearance() {
    const elem = document.getElementById("info");
    const body = document.body;

    if (elem.innerText == "on") {
        elem.innerText = "off";
        body.style.backgroundColor = "#ffffff"; // Mudar para fundo claro
        body.style.color = "#000000"; // Mudar para cor do texto preta
        setCookie("off");
    } else {
        elem.innerText = "on";
        body.style.backgroundColor = "#000000"; // Mudar para fundo escuro
        body.style.color = "#ffffff"; // Mudar para cor do texto branca
        setCookie("on");
    }
}
</script>

<body>
<h1 id="info"><?php echo $appearance; ?></h1>
<button type="button" onclick="changeAppearance()">Button</button>
</body>
