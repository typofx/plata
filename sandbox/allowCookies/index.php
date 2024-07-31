<body class="dark-background">
    <link rel="stylesheet" href="./style-mobile-cookies.css">
    <script src="cookies.js"></script>

    <table class="cookies-container" id="cookiesTable">
        <tr>
            <th class="cookies-title" colspan="2" height="20px">
            We would like to use cookies to improve our website
            </th>
        </tr>
        <tr>
            <td class="cookies-sub" colspan="2" height="20px">
            <img class="img-cookie" src="cookie.png"> Some cookies enable us to provide enhanced functionality and personalization. Third-party providers might use some cookies to deliver services to you. They cannot be switched off by our systems.
            </td>
        </tr>
        <tr>
            <form method="post" action="">
                <td class="td-button-no" height="20px">
                    <button class="cookies-no" type="button" onclick="hideTable()">No thanks</button>
                </td>
                <td class="td-button-accept" height="20px">
                    <button class="cookies-accept" type="button" onclick="hideTable()">Accept</button>
                </td>
            </form>
        </tr>
    </table>
</body>
