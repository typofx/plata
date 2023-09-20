<!DOCTYPE html>


<html lang="en">
<head>
    <title>Submit Project</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function onSubmit(event) {

            var response = grecaptcha.getResponse();
            if (response.length == 0) {

                alert("Please check reCAPTCHA before submitting the form.");
                event.preventDefault();
            }

        }
    </script>
</head>

<body>


    <h1>Submit Project</h1>
    <form action="process_form.php" method="post" onsubmit="onSubmit(event)">
        <label for="project_name">Project Name:</label>
        <input type="text" id="project_name" name="project_name" placeholder="Enter the project name"><br><br>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" placeholder="Enter your link"><br><br>

        <label for="type">Type:</label>
        <select name="type" id="type">
            <option value="Index">Index</option>
            <option value="Bot">Bot</option>
            <option value="Tool">Tool</option>
            <option value="Dex">Dex</option>
            
            <option value="NFT">NFT</option>
            <option value="Audit">Audit</option>
            <option value="CEX">CEX</option>
            <option value="Newspaper">Newspaper</option>
            <option value="KYC">KYC</option>
            <option value="Voting">Voting</option>
            <option value="Funding">Funding</option>
            <option value="Chart">Chart</option>
            <option value="Wallet">Wallet</option>
        </select><br><br>


        <label for="telegram">Telegram:</label>
        <input type="text" id="telegram" name="telegram"><br><br>
        <label for="whatsapp">WhatsApp:</label>
        <input type="text" id="whatsapp" name="whatsapp"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LebDiIoAAAAADxJucIc0n8is7A6L_Yt6DZRZ2R_"></div><br>

        <input type="submit" value="Submit">
    </form>
</body>

</html>