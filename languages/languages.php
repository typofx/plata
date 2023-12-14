<body>






    <?php



    $CurrentPageURL = substr($_SERVER['REQUEST_URI'], 1, 2);

    //$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);



    $txtUSD = " (USD)";

    $txtEUR = " (EUR)";

    $txtBRL = " (BRL)";



    if ($CurrentPageURL == "pt") {

        //Cookies (Portuguese)

        $txtTitleCookies = 'Cookies';

        $txtCookies = 'Esse site utiliza cookies para garantir a melhor experiência do usuário.';

        $txtCookieAccept = 'Aceitar';

        $txtCookieDecline = 'Recusar';

        //Menu bar (Portuguese)

        $txtSandMenuProject = 'Projeto';

        $txtSandMenuRoadmap = 'Roteiro';

        $txtSandMenuTokenDistrib = 'Distribuição do Token';

        $txtSandMenuDYOR = 'FSPP';

        $txtSandMenuSketch = 'Esboço';

        $txtSandMenuLitepaper = 'Documentação';



        $txtSandMenuProducts = 'Produtos';

        $txtSandMenuBuyPlata = 'Compre Plata';

        $txtSandMenuMerchant = 'Lojinha';

        $txtSandMenuListing = 'Listagem';



        $txtSandMenuAbout = 'Sobre';

        $txtSandMenuMeetTheTeam = 'Conheça o Time';

        $txtSandMenuSocialMedia = 'Mídia Social';

        $txtSandMenuReportBug = 'Reportar Falha';

        $txtSandMenuEmail = 'Email';



        $txtSandMenuTheme = 'Aparência:';

        $txtSandMenuThemeDark = 'Modo Escuro';

        $txtSandMenuThemeLight = 'Modo Claro';

        $txtSandMenuAtributteDark = 'Escuro';

        $txtSandMenuAtributteLight = 'Claro';



        $txtSandMenuLanguage = 'Idioma (PT)';

        $txtSandMenuEnglish = 'English (EN)';

        $txtSandMenuPortuguese = 'Português (PT)';

        $txtSandMenuSpanish = 'Español (ES)';



        $txtSandMenuCurrency = "Moeda";

        $txtSandMenuBRL = 'Real (BRL)';

        $txtSandMenuUSD = 'Dólar (USD)';

        $txtSandMenuEUR = 'Euro (EUR)';



        $mainTextL1 = "Token do Projeto ACTM,";

        $mainTextL2 = "Caixa Automático";

        $mainTextL3 = "de Criptomoedas.";

        $mainTextL4 = "Web3 para Notas";

        $mainTextL5 = "e Vice-Versa";

        $mainTextL6 = "em Nossos Quiosques.";



        $subtileL1 = "Faça parte da rede da pura pepita de prata";

        $subtileL1D = "Faça parte da comunidade da pura pepita de prata";

        $subtileL2 = "o token ERC-20 da Blockchain Polygon.";





        $btnContract = "Contrato : 0xc29...b6341";



        // Plata Price 



        $txtTokenName = "Nome";

        $txtTokenPrice = "Preço";

        $txtTokenMarketcap = "Valor de Mercado :";



        // The Project



        $txtTheProject = "O Projeto";

        $txtProjectL1 = "Depósitos";

        $txtProjectL2 = "Dinheiro extremamente rápido para a Blockchain";

        $txtProjectL3 = "Vamos diminuir o incômodo de ter dinheiro em mãos com a intenção mandá-lo diretamente para a nossa carteira web3, sem uma empresa terceirizada de custódia.";

        $txtProjectWithdraw = "Saques";

        $txtProjectL4 = "Criptomoedas para dinheiro vivo com alguns comandos.";

        $txtProjectL5 = "Nossa ideia é dar a possibilidade de quebrar a barreira do tempo, deixando o seu valioso dinheiro na blockchain decentralizada e sem custódia, sem se preocupar quando você poderá tê-lo como notas vivas.";



        // Listing Places (Português)

        $PLT = 'Plata Token';

        $txtPlataToken = "Locais Listados da Plata";

        $txtAffiliatePlatform = "30 plataformas afiliadas anexadas";

        $txtAlwaysResearch = "Sempre Faça sua Própria Pesquisa";

        $txtPlataText = "Apoiamos a auditoria independente e índices para provar que o Plata Token atende sérios critérios de segurança. Para facilitar, estamos fornecendo várias fontes onde temos parceria para mostrar que o $PLT está indo muito bem.";



        // More information



        $txtMoreInformation = "Quer saber mais?";





        // Roadmap - Roteiro (Português)

        // Buy Plata Token offchain (Português)



        $txtRoadmap = "Roteiro de 2023";
        $txtFirststage = "Primeira etapa";


        $txtStageProjectAnnouncement = " Anuncio do projeto";

        $txtStageSocialMedia = "Midia Social";

        $txtStagePolygonTeamAproval = "Aprovação do time Polygon";

        $txtStageAirdrop = "Airdrop";

        $txtStagePlata = "https://www.plata.ie";

        $txtStageSketch = "Sketch Project";

        $txtStageLitepaper = "Litepaper";

        $txtStageGiveaway = "Giveaway";

        $txtStageWalletsPlata = "1k Carteiras com Token Plata";

        $txtStagePrototype = "50% do Protótipo concluído (UX)";

        $txtStageSoftcap = "Softcap alcançado";

        $txtStageLiquidity = "DEX Fundos de liquidez";

        //Split

        $txtTokenDistribution = "Distribuição de Tokens";

        $txtCirculatingSupply = "Fornecimento Circulante: 11,299,000,992 PLT";


        // Initial Split (Portuguese)



        $txtInitialSplit = "Divisão Inicial (2022)";

        $txtPlatform = "Operação da Plataforma ( 10% )";

        $txtLegalSupport = "Suporte Legal ( 20% )";

        $txtDecentralized = "Gerenciamento descentralizado ( 5% )";

        $txtExpenses = "Mentores do Projeto ( 5% ) ";

        $txtMarketing = "Marketing e Promoções ( 20% )";

        $txtReserve = "Fundo de Reserva ( 40% )";



        // Token Allocation 2023 (Portuguese)

        $txtTokenAllocation23 = "Alocação dos Tokens (2023)";

        $txtNullAddress = "Null Address: 0x00...dEaD ( 49% )";

        $txtTypoFx = "Typo FX: Carteiras ( 26% )";

        $txtUniswap = "Uniswap V3 ( 5% )";

        $txtQuickswap = "Quickswap DEX ( 5% )";

        $txtSushiSwap = "SushiSwap V2 ( 5% )";

        $txtPromotionalGiveaway = "Giveaway Promocional ( 5% )";

        $txtAirDrop = "AirDrop dApp ( 4% )";



        // NFT Marketplace (Portuguese)

        $txtNFTmarketplace = "Negociações de NFT";

        $txtArtistsCollaboration = "Colaboração dos Artistas ( 20% )";

        $txtLiquidityACTM = "Liquidez para o Projeto ACTM ( 30% )";

        $txtRainfallVictims = "Vitimas de Enchentes no Brasil ( 50% )";



        // Buy Plata Token offchain (Português)



        $txtBuyPlataOffchain = "Compre Tokens Plata<br>com ferramentas Offchain";



        // Wallets (Português)



        $txtBestWallets =  "Melhores carteiras para <html>&#0036</html>PLT Plata";

        $txtKeysBlockchain = "Nossas chaves para aplicativos Blockchain";

        $txtExploreBlockchain = "Explore dApps Blockchain. Essas quatro carteiras fornecem a maneira mais simples e segura de se conectar a dApps baseados em Blockchain.<br><br>Você está sempre no controle ao interagir com o novo Descentralizado Financeiro e Web.";



        //Meet The Team (Português)



        $mainMeetTheTeam = "Conheça o Time";


        $txtAdamPosition = "CEO & ENGENHEIRO DA COMPUTAÇÃO";

        $txtAdamName = "Adam Soares";



        $txtThiagoPosition = "CTO & ENGENHEIRO MECÂNICO";

        $txtThiagoName = "Thiago Bezerra";



        $txtGustavoPosition = "GERENTE DE PARTICIPAÇÃO E MARKETING";

        $txtGustavoName = "Gustavo Salles";



        $txtAdrielPosition = "DESENVOLVEDOR WEB3";

        $txtAdrielName = "Adriel Dias";



        $txtLucasPosition = "DESENVOLVEDOR WEB3 TRAINEE";

        $txtLucasName = "Lucas Carvalho";

        //FOOTER (Portuguese)

        $txtCompany = "An Computer Science Company";

        $txtTermsConditions = "Termos & Condições";

        $txtPrivacy = "Privacy";

        $txtRights = "© 2023 Typo FX | Todos os direitos reservados";

        $txtAbout = "SOBRE";

        $txtCaseStudies = "Estudos de caso";

        $txtProducts = "PRODUTOS";

        $txtWallets = "Carteiras";

        $txtContact = "CONTATO";

        $txtReportBug = "Reportar Bug";

        $txtSupport = "Suporte";
    } elseif ($CurrentPageURL == "es") {

        //Cookies (Español)
        $PLT = 'Plata Token';
        $txtTitleCookies = 'Nosotros usamos cookies';

        $txtCookies = 'Este sitio web utiliza cookies para garantizar la mejor experiencia de usuario.';

        $txtCookieAccept = 'Aceptar';

        $txtCookieDecline = 'Rechazar';


        //Menu bar (Español)

        $txtSandMenuProject = 'Proyecto';

        $txtSandMenuRoadmap = 'Roadmap';

        $txtSandMenuTokenDistrib = 'Token Distribution';

        $txtSandMenuDYOR = 'DYOR';

        $txtSandMenuSketch = 'Sketch Design';

        $txtSandMenuLitepaper = 'Litepaper';



        $txtSandMenuProducts = 'Productos';

        $txtSandMenuBuyPlata = 'Comprar Plata';

        $txtSandMenuMerchant = 'Merchant';

        $txtSandMenuListing = 'Listado';



        $txtSandMenuAbout = 'Acerca';

        $txtSandMenuMeetTheTeam = 'Meet The Team';

        $txtSandMenuSocialMedia = 'Social Media';

        $txtSandMenuReportBug = 'Report Bug';

        $txtSandMenuEmail = 'Email';



        $txtSandMenuTheme = 'Aspecto';

        $txtSandMenuThemeDark = 'Tema Oscuro';

        $txtSandMenuThemeLight = 'Tema Claro';

        $txtSandMenuAtributteDark = '(Oscuro)';

        $txtSandMenuAtributteLight = '(Claro)';



        $txtSandMenuLanguage = 'Idioma (ES)';

        $txtSandMenuEnglish = 'English (EN)';

        $txtSandMenuPortuguese = 'Português (PT)';

        $txtSandMenuSpanish = 'Español (ES)';





        $txtSandMenuCurrency = 'Moneda';



        $txtSandMenuBRL = 'Real Brasileño (BRL)';

        $txtSandMenuUSD = 'Dólar (USD)';

        $txtSandMenuEUR = 'Euro (EUR)';



        $mainTextL1 = "Token do proyecto ACTM,";

        $mainTextL2 = "Cajero Automático";

        $mainTextL3 = "de Criptomonedas.";

        $mainTextL4 = "Web3 para Notas";

        $mainTextL5 = "y Viceversa";

        $mainTextL6 = "en Nuestros Kioscos.";



        $subtileL1 = "Úne a la comunidad de pepitas de Plata pura";

        $subtileL1D = $subtileL1;

        $subtileL2 = "el token ERC-20 de Polygon Blockchain.";



        $btnContract = "Contrato : 0xc29...b6341";



        $txtTokenName = "Nombre";

        $txtTokenPrice = "Precio";

        $txtTokenMarketcap = "Valor Comercial :";



        $txtTheProject = "El Proyecto";

        $txtProjectL1 = "Retiros e Depósitos";

        $txtProjectL2 = "Dinero extremadamente rápido para Blockchain";

        $txtProjectL3 = "Nos libraremos de la molestia de tener efectivo disponible con la intención de enviarlo directamente a nuestra billetera web3, sin una empresa de depósito en garantía de terceros.";

        $txtProjectWithdraw = "Retiros";

        $txtProjectL4 = "Criptomonedas por dinero en efectivo con unos pocos comandos.";

        $txtProjectL5 = "Nuestra idea es darle la posibilidad de romper la barrera del tiempo, dejando su valioso dinero en la cadena de bloques descentralizada y sin custodia, sin preocuparse de cuándo podrá tenerlo como notas en vivo.";


        //Listing Places (Español)


        $txtPlataToken = "Lugares de Listado de la Plata";

        $txtAffiliatePlatform = "30 plataformas afiliadas adjuntas";

        $txtAlwaysResearch = "Siempre Haga su Propia Investigación";

        $txtPlataText = "Apoyamos la auditoría independiente y los índices para demostrar que Plata Token cumple con los criterios de seguridad. Para que sea más fácil para usted, el usuario, proporcionamos todos los enlaces, donde tenemos una asociación para mostrar que $PLT está funcionando muy bien.";


        // More Infomation (Español)



        $txtMoreInformation = "¿Necesitas más información?";



        // Roadmap (Español)

        $txtFirststage = "Primera etapa";

        $txtRoadmap = "Hoja de ruta 2023";



        $txtStageProjectAnnouncement = " Anuncio de proyecto";

        $txtStageSocialMedia = "Social Media";

        $txtStagePolygonTeamAproval = "Aprobación del equipo Polygon";

        $txtStageAirdrop = "Airdrop";

        $txtStagePlata = "https://www.plata.ie";

        $txtStageSketch = "Sketch Project";

        $txtStageLitepaper = "Litepaper";

        $txtStageGiveaway = "Giveaway";

        $txtStageWalletsPlata = "1k Carteras con Token de Plata";

        $txtStagePrototype = "50% Prototype hecho (UX)";

        $txtStageSoftcap = "Softcap alcanzado";

        $txtStageLiquidity = "DEX Fondos de liquidez";


        //Split

        $txtTokenDistribution = "Distribuición de Tokens";

        $txtCirculatingSupply = "Suministro Circulante: 11,299,000,992 PLT";


        // Initial Split (Español)

        $txtInitialSplit = "División inicial (2022)";

        $txtPlatform = "Operación de la plataforma ( 10% )";

        $txtLegalSupport = "Apoyo Legal ( 20% )";

        $txtDecentralized = "Gestión Descentralizada ( 5% )";

        $txtExpenses = "Mentores de Proyectos ( 5%)";

        $txtMarketing = "Marketing y Promoción ( 20% )";

        $txtReserve = "Fondo de Reserva ( 40% )";



        // Token Allocation 2023 (Español)

        $txtTokenAllocation23 = "Asignación de fichas (2023)";

        $txtNullAddress = "Null Address: 0x00...dEaD ( 49% )";

        $txtTypoFx = "Typo FX: Carteras ( 26% )";

        $txtUniswap = "Uniswap V3 ( 5% )";

        $txtQuickswap = "Quickswap DEX ( 5% )";

        $txtSushiSwap = "SushiSwap V2 ( 5% )";

        $txtPromotionalGiveaway = "Giveaway Promocional ( 5% )";

        $txtAirDrop = "AirDrop dApp ( 4% )";



        // NFT Marketplace (Español)

        $txtNFTmarketplace = "Mercado de NFT";

        $txtArtistsCollaboration = "Colaboración de artistas ( 20% )";

        $txtLiquidityACTM = "Liquidez para Proyecto ACTM ( 30% )";

        $txtRainfallVictims = "Víctimas de lluvias en Brasil ( 50% )";



        $txtBuyPlataOffchain = "Compre Tokens de Plata<br>con herramientas Offchain";



        // Wallets (Español)



        $txtBestWallets =  "Las mejores carteras para <html>&#0036</html>PLT Plata";

        $txtKeysBlockchain = "Nuestras claves para las aplicaciones Blockchain";

        $txtExploreBlockchain = "Explore las dApps de Blockchain. Estas cuatro billeteras brindan la forma más simple y segura de conectarse a dApps basadas en Blockchain.<br><br>Siempre tiene el control cuando interactúa en la nueva Web y finanzas descentralizadas.";



        //Meet The Team (Español)



        $mainMeetTheTeam = "Conocé al Equipo";



        $txtAdamPosition = "CEO FUNDADOR & INGENIERO INFORMATICO";

        $txtAdamName = "Adam Soares";



        $txtThiagoPosition = "CTO & INGENIERO MECÁNICO";

        $txtThiagoName = "Thiago Bezerra";



        $txtGustavoPosition = "GERENTE DE PARTICIPACIÓN DE MARKETING";

        $txtGustavoName = "Gustavo Salles";



        $txtAdrielPosition = "DESARROLLADOR WEB3";

        $txtAdrielName = "Adriel Dias";



        $txtLucasPosition = "DESARROLLADOR WEB3 APRENDIZ";

        $txtLucasName = "Lucas Carvalho";

        // Footer (Español)

        $txtCompany = "An Computer Science Company";

        $txtTermsConditions = "Términos & Condiciones";

        $txtPrivacy = "Privacidad";

        $txtRights = "© 2023 Typo FX |  Reservados todos los derechos";

        $txtAbout = "ACERCA";

        $txtCaseStudies = "Estudios de caso";

        $txtProducts = "PRODUCTOS";

        $txtWallets = "Tarjeta";

        $txtContact = "CONTACTO";

        $txtReportBug = "Reportar un erro";

        $txtSupport = "Soporte";
    } else {

        //Cookies (Portuguese)

        $txtTitleCookies = 'Cookies';

        $txtCookies = 'This website uses cookies to ensure the best user experience.';

        $txtCookieAccept = 'Accept';

        $txtCookieDecline = 'Decline';

        //Header

        $txtSandMenuProject = 'Project';

        $txtSandMenuRoadmap = 'Roadmap';

        $txtSandMenuTokenDistrib = 'Token Distribution';

        $txtSandMenuDYOR = 'DYOR';

        $txtSandMenuSketch = 'Sketch Design';

        $txtSandMenuLitepaper = 'Litepaper';



        $txtSandMenuProducts = 'Products';

        $txtSandMenuBuyPlata = 'Buy Plata';

        $txtSandMenuMerchant = 'Merchant';

        $txtSandMenuListing = 'Listing Places';



        $txtSandMenuAbout = 'About';

        $txtSandMenuMeetTheTeam = 'Meet The Team';

        $txtSandMenuSocialMedia = 'Social Media';

        $txtSandMenuReportBug = 'Report Bug';

        $txtSandMenuEmail = 'Email';



        //$txtSandMenuAtributte



        $txtSandMenuTheme = 'Theme';

        $txtSandMenuThemeDark = 'Dark Mode';

        $txtSandMenuThemeLight = 'Light Mode';

        $txtSandMenuAtributteDark = '(Dark)';

        $txtSandMenuAtributteLight = '(Light)';



        //$txtSandMenuLanguage



        $txtSandMenuLanguage = 'Language (EN)';

        $txtSandMenuEnglish = 'English (EN)';

        $txtSandMenuPortuguese = 'Português (PT)';

        $txtSandMenuSpanish = 'Español (ES)';



        //$txtSandMenuCurrency



        $txtSandMenuCurrency = 'Currency';



        $txtSandMenuBRL = 'Brazilian Real (BRL)';

        $txtSandMenuUSD = 'US Dollar (USD)';

        $txtSandMenuEUR = 'Euro (EUR)';



        $mainTextL1 = "Token for ACTM Project,";

        $mainTextL2 = "Automated";

        $mainTextL3 = "Crypto Teller Machine.";

        $mainTextL4 = "Web3 to Cash";

        $mainTextL5 = "and Vice Versa";

        $mainTextL6 = "From Our Kiosks.";



        $subtileL1 = "Join the pure silver nugget community";

        $subtileL1D = $subtileL1;

        $subtileL2 = "the ERC-20 token from Polygon Blockchain.";



        $btnContract = "Contract : 0xc29...b6341";



        $txtTokenName = "Name";

        $txtTokenPrice = "Price";

        $txtTokenMarketcap = "Market Cap :";



        // The Project (English)



        $txtTheProject = "The Project";

        $txtProjectL1 = "Lodgements";

        $txtProjectL2 = "Cash to Blockchain directly and extremely fast";

        $txtProjectL3 = "We are going to eradicate the hassle holding cash and would like to send it to our web3 wallet directly without any third-party custodial company.";

        $txtProjectWithdraw = "Withdraws";

        $txtProjectL4 = "Crypto into the legal tender in a few commands.";

        $txtProjectL5 = "Our idea is to give the possibility to break the wall of time,leaving valuable money on the decentralized noncustodial blockchain without worrying about when you can have it as cash.";



        // Listing Places (English)

        $PLT = '$PLT';

        $txtPlataToken = "Plata Token Listing Places";

        $txtAffiliatePlatform = "30 attached affiliate platforms";

        $txtAlwaysResearch = "Always do Your Own Research";

        $txtPlataText = "We are supporting the independent audit and indexes to prove Plata Token is meeting the criteria for safety. To make it easier, for you, the user, we are providing all links, where we have a partnership to show up $PLT is doing great. DYOR.";


        // More Information (English)

        $txtMoreInformation = "Do you need more information?";



        // Roadmap (English)



        $txtRoadmap = "2023's Roadmap";



        $txtFirststage = "First stage";

        $txtStageProjectAnnouncement = "Project Announcement";

        $txtStageSocialMedia = "Social Media";

        $txtStagePolygonTeamAproval = "Polygon Team Approval";

        $txtStageAirdrop = "Airdrop";

        $txtStagePlata = "https://www.plata.ie";

        $txtStageSketch = "Sketch Project";

        $txtStageLitepaper = "Litepaper";

        $txtStageGiveaway = "Giveaway";

        $txtStageWalletsPlata = "1k Wallets holding Plata Token";

        $txtStagePrototype = "50% Prototype Done (UX)";

        $txtStageSoftcap = "Softcap Reached";

        $txtStageLiquidity = "DEX Liquidity Pools";


        //Split

        $txtTokenDistribution = "Plata Tokenomics";

        $txtCirculatingSupply = "Circulating Supply: 11,299,000,992 PLT";


        // Initial Split (English)


        $txtInitialSplit = "Initial Split (2022)";

        $txtPlatform = "Platform Operational Costs ( 10% )";

        $txtLegalSupport = "Legal Support ( 20% )";

        $txtDecentralized = "Decentralized Management ( 5% )";

        $txtExpenses = "Expenses to Project Mentors ( 5% )";

        $txtMarketing = "Marketing and Promotion ( 20% )";

        $txtReserve = "Reserve Fund ( 40% )";



        // Token Allocation 2023 (English)

        $txtTokenAllocation23 = "Token Allocation (2023)";

        $txtNullAddress = "Null Address: 0x00...dEaD ( 49% )";

        $txtTypoFx = "Typo FX: Wallets ( 26% )";

        $txtUniswap = "Uniswap V3 ( 5% )";

        $txtQuickswap = "Quickswap DEX ( 5% )";

        $txtSushiSwap = "SushiSwap V2 ( 5% )";

        $txtPromotionalGiveaway = "Promotional Giveaway ( 5% )";

        $txtAirDrop = "AirDrop dApp ( 4% )";



        // NFT Marketplace

        $txtNFTmarketplace = "NFT Marketplace";

        $txtArtistsCollaboration = "Artists who collaborated ( 20% )";

        $txtLiquidityACTM = "Liquidity for ACTM Project ( 30% )";

        $txtRainfallVictims = "Rainfall's Victims in Brazil ( 50% )";





        // Buy Plata Token offchain (English)



        $txtBuyPlataOffchain = "Buy Plata Tokens<br>with Offchain tools";





        // Wallets (English)



        $txtBestWallets =  "Best Wallets For <html>&#0036</html>PLT Plata";

        $txtKeysBlockchain = "Our Keys to Blockchain Applications";

        $txtExploreBlockchain = "Explore Blockchain dApps. These four wallets provide the simplest and most secure way to connect to Blockchain-based dApps.<br><br>You are always in control when interacting on the new Decentralized Finance and Web.";



        //Meet The Team (English)



        $mainMeetTheTeam = "Meet The Team";


        $txtAdamPosition = "FOUNDER CEO & COMPUTER ENGINEER";

        $txtAdamName = "Adam Soares";



        $txtThiagoPosition = "CTO & MECHANICAL ENGINEER";

        $txtThiagoName = "Thiago Bezerra";



        $txtGustavoPosition = "MARKETING ENGAGEMENT MANAGER";

        $txtGustavoName = "Gustavo Salles";



        $txtAdrielPosition = "WEB3 DEVELOPER";

        $txtAdrielName = "Adriel Dias";



        $txtLucasPosition = "WEB3 DEVELOPER TRAINEE";

        $txtLucasName = "Lucas Carvalho";


        //FOOTER (English)

        $txtCompany = "An Computer Science Company";

        $txtTermsConditions = "Terms & Conditions";

        $txtPrivacy = "Privacy";

        $txtRights = "© 2023 Typo FX | All rights reserved";

        $txtAbout = "ABOUT";

        $txtCaseStudies = "Case Studies";

        $txtProducts = "PRODUCTS";

        $txtWallets = "Wallets";

        $txtContact = "CONTACT";

        $txtReportBug = "Report Bug";

        $txtSupport = "Support";
    }





















    ?>





</body>