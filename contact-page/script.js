// Lazy loading do Google Maps
let mapsLoaded = false;

/**
 * Carrega o script da API do Google Maps dinamicamente.
 * Verifica se já foi carregado para evitar duplicidade.
 * @returns {void}
 */

function loadGoogleMaps() {
    // Evita múltiplas injeções do script se o observer disparar mais de uma vez
    if (mapsLoaded) return;
    mapsLoaded = true;

    // Criação manual da tag script para controle total do carregamento (Async/Defer)
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' +
        CONFIG.GOOGLE_MAPS_API_KEY +
        '&callback=initMap&loading=async';
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);
}

/**
 * Callback executado automaticamente pela API do Google Maps quando o script termina de carregar.
 * Inicializa o mapa, marcador e eventos usando a API moderna (AdvancedMarkerElement).
 * IMPORTANTE: Função async necessária para usar importLibrary().
 */

async function initMap() {
    // Coordenadas do prédio Workhub (aproximada)
    const location = { lat: 53.33533135354178, lng: -6.2653143722446165 };

    // Seleciona o container específico dentro da tabela
    // NOTE: Se o layout HTML mudar, este seletor precisará ser atualizado.
    const mapContainer = document.querySelector('.map-column table td');

    if (!mapContainer) {
        console.error('[Google Maps] Container .map-column table td não encontrado no DOM.');
        return;
    }

    try {
        // Importa as bibliotecas necessárias da API moderna
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // Inicializa o mapa com controles de UI habilitados para melhor experiência do usuário
        const map = new Map(mapContainer, {
            center: location,
            zoom: 18,
            mapId: "124618566934ee813e326279", // Map ID personalizado
            mapTypeId: 'roadmap',
            // Habilita controles explícitos para garantir usabilidade em desktop e mobile
            mapTypeControl: true,
            streetViewControl: true,
            gestureHandling: 'auto' // Permite scroll na página sem ficar "preso" no mapa
        });

        // Marcação avançada com título
        const marker = new AdvancedMarkerElement({
            map: map,
            position: location,
            title: "77 Camden Street Lower, Saint Kevin's, Dublin 2, D02 XE80"
        });

        /**
         * Adiciona evento de clique para abrir o Street View em nova aba.
         * Usamos a URL oficial com parâmetros 'layer=c' para forçar o modo Street View.
         */

        marker.addListener('click', () => {
            // cbll = coordenadas | cbp = parâmetros da câmera (heading, pitch)
            // heading=295 aponta a câmera para a fachada da loja baseada na sua coordenada
            const streetViewUrl = `https://www.google.com/maps?layer=c&cbll=${location.lat},${location.lng}&cbp=12,295,,0,0`;

            // Abre em nova aba com proteção contra tabnabbing (noopener noreferrer)
            const newWindow = window.open(streetViewUrl, '_blank');
            if (newWindow) {
                newWindow.opener = null;
            }
        });

    } catch (error) {
        // Fallback visual caso a API falhe (ex: erro de chave ou rede)
        console.error('Erro crítico ao inicializar o mapa:', error);
        mapContainer.innerHTML = '<p style="padding: 20px; text-align: center; color: #999;">Mapa indisponível no momento.</p>';
    }
}

// Inicialização e Observadores
document.addEventListener('DOMContentLoaded', function () {

    // --- Lógica de Lazy Loading ---
    const mapContainer = document.querySelector('.map-column');

    if (mapContainer) {
        // O IntersectionObserver melhora a performance inicial da página (LCP),
        // carregando o JS pesado do mapa apenas quando o usuário rola até ele.
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                loadGoogleMaps();
                observer.disconnect(); // Desconecta para não executar novamente
            }
        }, {
            rootMargin: '50px' // Pre-load: inicia o carregamento 50px antes do elemento entrar na tela
        });

        observer.observe(mapContainer);
    } else {
        // Fallback: Se não achar o container, tenta carregar imediatamente para não quebrar a página
        loadGoogleMaps();
    }

    // --- Lógica do Formulário ---
    const form = document.getElementById('contactForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                nome: formData.get('nome'),
                telefone: formData.get('telefone'),
                email: formData.get('email')
            };

            console.log('Envio simulado:', data);
            alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
            form.reset();
        });
    }
});