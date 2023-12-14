<body>
    
<?php

$CurrentPageURL = substr($_SERVER['REQUEST_URI'], 1, 2);
//$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            $txtUSD = " (USD)";
            $txtEUR = " (EUR)";
            $txtBRL = " (BRL)";

        if ($CurrentPageURL == "pt") {
            $txtSandMenuProject = 'Projeto';
                $txtSandMenuRoadmap = 'Roteiro';
                $txtSandMenuTokenDistrib = 'Distribuição do Token';
                $txtSandMenuDYOR = 'FSPP';
                $txtSandMenuSketch = 'Esboço';
                $txtSandMenuLitepaper = 'Documentação';
            
            $txtSandMenuProducts = 'Produtos';
                $txtSandMenuBuyPlata = 'Compre Plata';
                $txtSandMenuMerchant = 'Lojinha';

            $txtSandMenuAbout = 'Sobre';
                $txtSandMenuMeetTheTeam = 'Conheça o Time';
                $txtSandMenuSocialMedia = 'Mídia Social';
                $txtSandMenuReportBug = 'Reportar Falha';
                $txtSandMenuEmail = 'Email';
            
            $txtSandMenuTheme = 'Aparência';
                $txtSandMenuThemeDark = 'Modo Escuro';
                $txtSandMenuThemeLight = 'Modo Claro';
                $txtSandMenuAtributteDark = '(Escuro)';
                $txtSandMenuAtributteLight = '(Claro)';

            $txtSandMenuLanguage = 'Linguagem (PT)';
                $txtSandMenuEnglish = 'Inglês (EN)';
                $txtSandMenuPortuguese = 'Português (PT)';
                $txtSandMenuSpanish = 'Espanhol (ES)';
                
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
        $subtileL2 = "o token ERC-20 da Blockchain Polygon.";

        
        $btnContract = "Contrato : 0xc29...b6341";
        
        // Plata Price 
        
        $txtTokenName = "Nome";
        $txtTokenPrice = "Preço";
        $txtTokenMarketcap = "Valor de Mercado :";
        
        // The Project

        $txtTheProject = "O Projeto";
        $txtProjectL1 = "Saques e Depósitos";
        $txtProjectL2 = "Dinheiro extremamente rápido para a Blockchain";
        $txtProjectL3 = "Vamos diminuir o incômodo de ter dinheiro em mãos com a intenção mandá-lo diretamente para a nossa carteira web3, sem uma empresa terceirizada de custódia.";  
        $txtProjectL4 = "Criptomoedas para dinheiro vivo com alguns comandos";  
        $txtProjectL5 = "Nossa ideia é dar a possibilidade de quebrar a barreira do tempo, deixando o seu valioso dinheiro na blockchain decentralizada e sem custódia, sem se preocupar quando você poderá tê-lo como notas vivas."; 
        
        // Listing Places (Português)
        
        $txtPlataToken = "Locais Listados da Plata";  
        $txtAlwaysResearch = "<br>Sempre Faça sua Própria Pesquisa";
        $txtPlataTextL1 = "Apoiamos a auditoria independente e índices para provar que o Plata Token atende sérios critérios de segurança."; 
        $txtPlataTextL2 = "Para facilitar, estamos fornecendo várias fontes onde temos parceria para mostrar que o <html>&#0036</html>PLT está indo muito bem. SFPP."; 

        // Roadmap - Roteiro (Português)
        // Buy Plata Token offchain (Português)
        
            $txtRoadmap = "Roteiro de 2023"; 
            $txtOpenSea = "Perfil OpenSea (NFT)";
            $txtRoadmapPayment = "Aceitamos crédito/débito";
            $txtPIXPayment = "Pagamento com PIX";
            $txtCoingecko = "Estar no Coingecko";
            $txtCoinmarketcapListing = "Listado no Coinmarketcap";
            $txtMerchandising = "Lojinha";
            $txtWorkshopL1 = "1° Workshop";
            $txtPresentation = "1° Produto Apresentado";
            $txtWorkshopL2 = "2° Workshop";
            
        // Initial Split (Portugues)

        $txtInitialSplit = "Divisão Inicial (2022)";
            $txtPlatform = "Operação da Plataforma ( 10% )";
            $txtLegalSupport = "x1";
            $txtDecentralized = "x2";
            $txtExpenses = "x3";
            $txtMarketing = "x4";
            $txtReserve = "x5";
            
        $txtTokenAllocation23 = "Alocação dos Tokens (2023)";
        
        $txtNFTmarketplace = "Negociações de NFT";
            
        // Buy Plata Token offchain (Português)
        
        $txtBuyPlataOffchain = "Compre Tokens Plata<br>com ferramentas Offchain";

        // Wallets (Português)
        
        $txtBestWallets =  "Melhores carteiras para <html>&#0036</html>PLT Plata";
        $txtKeysBlockchain = "Nossas chaves para aplicativos Blockchain";
        $txtExploreBlockchain = "Explore dApps Blockchain. Essas quatro carteiras fornecem a maneira mais simples e segura de se conectar a dApps baseados em Blockchain.<br><br>Você está sempre no controle ao interagir com o novo Descentralizado Financeiro e Web.";

        //Meet The Team (Português)
        
        $mainMeetTheTeam = "Conheça o Time";
        
        $txtAdamPosition = "Fundador Eng. Executivo-Chefe";
        $txtAdamAbout = "Mestrando na Universidade Técnica de Tallaght. Graduado pela Universidade Santa Cecília de Santos. Desenvolvedor Blockchain e de Sistemas Embarcados. Analista Gráfico de Criptoativos.";

        }
        elseif ($CurrentPageURL == "es") {
            $txtSandMenuProject = 'Proyecto';
                $txtSandMenuRoadmap = 'Roadmap';
                $txtSandMenuTokenDistrib = 'Token Distribution';
                $txtSandMenuDYOR = 'DYOR';
                $txtSandMenuSketch = 'Sketch Design';
                $txtSandMenuLitepaper = 'Litepaper';
                
            $txtSandMenuProducts = 'Productos';
                $txtSandMenuBuyPlata = 'Buy Plata';
                $txtSandMenuMerchant = 'Merchant';
            
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
            $txtSandMenuEnglish = 'Inglés (EN)';
            $txtSandMenuPortuguese = 'Portugués (PT)';
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
        $subtileL2 = "the ERC-20 token from Polygon Blockchain.";

       $btnContract = "Contrato : 0xc29...b6341";
       
        $txtTokenName = "Nombre";
        $txtTokenPrice = "Precio";
        $txtTokenMarketcap = "Valor Comercial :";

        $txtTheProject = "El Proyecto";
          $txtProjectL1 = "Saques e Depósitos"; 
          $txtProjectL2 = "Dinero extremadamente rápido para Blockchain";
          $txtProjectL3 = "Nos libraremos de la molestia de tener efectivo disponible con la intención de enviarlo directamente a nuestra billetera web3, sin una empresa de depósito en garantía de terceros."; 
          $txtProjectL4 = "Criptomonedas por dinero en efectivo con unos pocos comandos"; 
          $txtProjectL5 = "Nuestra idea es darle la posibilidad de romper la barrera del tiempo, dejando su valioso dinero en la cadena de bloques descentralizada y sin custodia, sin preocuparse de cuándo podrá tenerlo como notas en vivo.";
          
          $txtPlataToken = "Lugares de Listado de la Plata"; 
           $txtAlwaysResearch = "Siempre Haga su Propia Investigación"; 
           $txtPlataTextL1 = "Apoyamos la auditoría independiente y los índices para demonstrar que Plata Token cumple con los criterios de seguridad."; 
           $txtPlataTextL2 = "Para hacerlo más fácil, proporcionamos muchas fuentes en las que tenemos una asociación para mostrar que <html>&#0036</html> PLT está funcionando muy bien. SHPI.";

        // Roadmap (Español)

          $txtRoadmap = "Hoja de ruta 2023"; 
                 $txtOpenSea = "Perfil OpenSea (NFT)";
                 $txtRoadmapPayment = "Pago de crédito/débito";
                 $txtCoingecko = "Registro Coingecko";
                 $txtCoinmarketcapListing = "Listado de Coinmarketcap";
                 $txtMerchandising = "Comercialización minorista";
                 $txtWorkshopL1 = "1º Taller";
                 $txtPresentation = "1ª Presentación de Producto";
                 $txtWorkshopL2 = "2º Taller";
                 $txtPIXPayment = "Pagos com PIX";

        // Buy Plata Token offchain (Español)
        
        $txtBuyPlataOffchain = "Compre Tokens de Plata<br>con herramientas Offchain";

        // Wallets (Español)
        
        $txtBestWallets =  "Las mejores carteras para <html>&#0036</html>PLT Plata";
        $txtKeysBlockchain = "Nuestras claves para las aplicaciones Blockchain";
        $txtExploreBlockchain = "Explore las dApps de Blockchain. Estas cuatro billeteras brindan la forma más simple y segura de conectarse a dApps basadas en Blockchain.<br><br>Siempre tiene el control cuando interactúa en la nueva Web y finanzas descentralizadas.";

        //Meet The Team (Español)
        
        $mainMeetTheTeam = "Conocé al Equipo";
        
        $txtAdamPosition = "Fundador Diretor-Executivo";
        $txtAdamAbout = "Estudiante de Maestría en la Universidad Técnica de Tallaght. Egresado de la Universidad Santa Cecília de Santos. Desarrollador de Blockchain y Sistemas Embebidos. Analista Gráfico de Criptomonedas.";
        
        $txtThiagoPosition = "CTO & Mechanical Engineer";
        $txtThiagoAbout = "Forex Trader at BM&amp;FBOVESPA. Mechanical Engineer from Anhanguera Rio de Janeiro. Understood that cryptocurrencies have great potential for products and solutions in 2019.";
        
        $txtGustavoPosition = "CTO & Mechanical Engineer";
        $txtGustavoAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";
        
        $txtAdrielPosition = "CTO & Mechanical Engineer";
        $txtAdrielAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";
        
        $txtMilenaPosition = "CTO & Mechanical Engineer";
        $txtMilenaAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";

        }
        
        else {
            $txtSandMenuProject = 'Project';
                $txtSandMenuRoadmap = 'Roadmap';
                $txtSandMenuTokenDistrib = 'Token Distribution';
                $txtSandMenuDYOR = 'DYOR';
                $txtSandMenuSketch = 'Sketch Design';
                $txtSandMenuLitepaper = 'Litepaper';
                
            $txtSandMenuProducts = 'Products';
                $txtSandMenuBuyPlata = 'Buy Plata';
                $txtSandMenuMerchant = 'Merchant';
            
            $txtSandMenuAbout = 'About';
                $txtSandMenuMeetTheTeam = 'Meet The Team';
                $txtSandMenuSocialMedia = 'Social Media';
                $txtSandMenuReportBug = 'Report Bug';
                $txtSandMenuEmail = 'Email';
            
            //$txtSandMenuAtributte
            
            $txtSandMenuTheme = 'Appearance';
                $txtSandMenuThemeDark = 'Dark Mode';
                $txtSandMenuThemeLight = 'Light Mode';
                $txtSandMenuAtributteDark = '(Dark)';
                $txtSandMenuAtributteLight = '(Light)';
                
            //$txtSandMenuLanguage
            
            $txtSandMenuLanguage = 'Language (EN)';
            $txtSandMenuEnglish = 'English (EN)';
            $txtSandMenuPortuguese = 'Portuguese (PT)';
            $txtSandMenuSpanish = 'Spanish (ES)';
            
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
        $subtileL2 = "the ERC-20 token from Polygon Blockchain.";

        $btnContract ="Contract : 0xc29...b6341";
        
        $txtTokenName = "Name";
        $txtTokenPrice = "Price";
        $txtTokenMarketcap = "Market Cap :";
        
        // The Project (English)

        $txtTheProject = "The Project";
        $txtProjectL1 = "Lodgements & Withdraws"; 
        $txtProjectL2 = "Cash to Blockchain directly and extremely fast";
        $txtProjectL3 = "We are going to eradicate the hassle holding cash and would like to send it to our web3 wallet directly without any third-party custodial company."; 
        $txtProjectL4 = "Crypto into the legal tender in a few commands."; 
        $txtProjectL5 = "Our idea is to give the possibility to break the wall of time,leaving valuable money on the decentralized noncustodial blockchain without worrying about when you can have it as cash.";                    

        // Listing Places (English)
        
        $txtPlataToken = "Plata Token Listing Places"; 
        $txtAlwaysResearch = "Always do Your Own Research";     
        $txtPlataTextL1 = "We are supporting the independent audit and indexes to prove Plata Token is meeting the criteria for safety."; 
        $txtPlataTextL2 = "To make it easier, we are providing many sources where we have a partnership to show up<html>&#0036</html> PLT is doing great. DYOR.";

        // Roadmap (English)

        $txtRoadmap = "2023 Roadmap"; 
        $txtOpenSea = "OpenSea (NFT) Profile";
        $txtRoadmapPayment = "Credit/Debit Payment";
        $txtCoingecko = "Coingecko Registration";
        $txtCoinmarketcapListing = "Coinmarketcap Listing";
        $txtMerchandising = "Retail Merchandising";
        $txtWorkshopL1 = "1st Workshop";
        $txtPresentation = "1st Product Presentation";
        $txtWorkshopL2 = "2nd Workshop";
        $txtPIXPayment = "PIX Payment";

        // Initial Split (English)

        $txtInitialSplit = "Initial Split (2022)"; 
            $txtPlatform = "Platform Operational Costs ( 10% )"; 
            $txtLegalSupport = "Legal Support ( 20% )"; 
            $txtDecentralized = "Decentralized Management ( 5% )"; 
            $txtExpenses = "Expenses to Project Mentors ( 5% )"; 
            $txtMarketing = "Marketing and Promotion ( 20% )"; 
            $txtReserve = "Reserve Fund ( 40% )";
        
        $txtTokenAllocation23 = "Token Allocation (2023)";
        
        $txtNFTmarketplace = "NFT Marketplace";


        // Buy Plata Token offchain (English)
        
        $txtBuyPlataOffchain = "Buy Plata Tokens<br>with Offchain tools";
        
        
        // Wallets (English)
        
        $txtBestWallets =  "Best Wallets For <html>&#0036</html>PLT Plata";
        $txtKeysBlockchain = "Our Keys to Blockchain Applications";
        $txtExploreBlockchain = "Explore Blockchain dApps. These four wallets provide the simplest and most secure way to connect to Blockchain-based dApps.<br><br>You are always in control when interacting on the new Decentralized Finance and Web.";

        //Meet The Team (English)
        
        $mainMeetTheTeam = "Meet The Team";
        
        $txtAdamPosition = "Founder CEO & Computer Engineer";
        $txtAdamAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";
        
        $txtThiagoPosition = "CTO & Mechanical Engineer";
        $txtThiagoAbout = "Forex Trader at BM&amp;FBOVESPA. Mechanical Engineer from Anhanguera Rio de Janeiro. Understood that cryptocurrencies have great potential for products and solutions in 2019.";
        
        $txtGustavoPosition = "CTO & Mechanical Engineer";
        $txtGustavoAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";
        
        $txtAdrielPosition = "CTO & Mechanical Engineer";
        $txtAdrielAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";
        
        $txtMilenaPosition = "CTO & Mechanical Engineer";
        $txtMilenaAbout = "Master's Student at the TUD Tallaght. ​ENG from Santa Cecília University. ​C&nbsp;C++ Embedded and Solidity Coder. Crypto Graphic Analyst, DeFi enthusiast.";

        }
        
        

        

        
        
    ?>
</body>