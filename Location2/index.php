<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Entre em contato com a TypoFX Limited. Localizado em Dublin 2, oferecemos soluções profissionais. Preencha o formulário ou ligue diretamente.">
    
    <meta http-equiv="Content-Security-Policy" 
        content="default-src 'self'; script-src 'self' https://maps.googleapis.com 'unsafe-inline'; style-src 'self' https://cdnjs.cloudflare.com https://fonts.googleapis.com 'unsafe-inline'; img-src 'self' https://maps.gstatic.com https://maps.googleapis.com data:; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; connect-src 'self' https://maps.googleapis.com; frame-src https://www.google.com;">

    <title>Contato - TypoFX Limited</title>

    <link rel="preconnect" href="https://maps.googleapis.com">
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
    <?php include 'config.php'; ?>
</head>

<body>

    <main class="main-container">
        <header class="contact-header">
            <h1>Contatos</h1>
            <p class="header-desc">
                O escritório da TypoFX Limited está localizado em Dublin. Perto do nosso escritório há um estacionamento conveniente. 
                Você pode entrar em contato conosco e agendar uma reunião no escritório da maneira que for mais conveniente para você.
            </p>
            <p class="sub-title">Meios de comunicação disponíveis:</p>
        </header>

        <section class="contact-methods">
            <div class="methods-group">
                <div class="method-item">
                    <div class="icon-wrapper"><i class="fas fa-phone-alt"></i></div>
                    <div class="method-text">
                        <span class="method-label">Telefones:</span>
                        <span class="method-value">+353 1 234 5678</span>
                    </div>
                </div>

                <div class="method-item">
                    <div class="icon-wrapper"><i class="fab fa-telegram"></i></div>
                    <div class="method-text">
                        <span class="method-label">Telegram:</span>
                        <span class="method-value">t.me/typofx</span>
                    </div>
                </div>

                <div class="method-item">
                    <div class="icon-wrapper"><i class="fas fa-envelope"></i></div>
                    <div class="method-text">
                        <span class="method-label">E-mail:</span>
                        <span class="method-value">support@typofx.ie</span>
                    </div>
                </div>
            </div>

            <div class="addresses-group">
                <p class="map-hint">*(Clique no endereço para centralizar no mapa e no marcador para o Street View)</p>
                <div class="method-item clickable" onclick="panToLocation('dublin')">
                    <div class="icon-wrapper"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="method-text">
                        <span class="method-label">Endereço Dublin:</span>
                        <span class="method-value">77 Camden Street Lower, Dublin 2</span>
                    </div>
                </div>

                <div class="method-item clickable" onclick="panToLocation('santos')">
                    <div class="icon-wrapper"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="method-text">
                        <span class="method-label">Endereço Santos (SP):</span>
                        <span class="method-value">Av. Senador Feijó, 686 cj 621, Santos/SP</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-row">
            <div class="map-column">
                <div id="map-view">
                    <!-- Google Map will be rendered here -->
                </div>
            </div>

            <div class="form-column">
                <div class="form-header">
                    <p>
                        Você pode usar o formulário de contato para comunicação. 
                        Preencha o formulário, envie as informações e um especialista entrará em contato para responder às suas perguntas.
                    </p>
                </div>
                
                <form id="contactForm">
                    <div class="form-group">
                        <label class="form-label" for="question">Sua pergunta</label>
                        <textarea name="question" id="question" class="form-input" placeholder="Como podemos ajudar?"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Seu nome <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Telefone de contato <span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-input">
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-submit">Fazer uma pergunta</button>
                    </div>

                    <p class="privacy-note">
                        Nós não compartilhamos contatos com terceiros, esta informação é necessária para entrar em contato com você.
                    </p>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>

</body>

</html>