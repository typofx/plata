# Página de Contato - TypoFX Limited

Página de contato profissional com integração ao Google Maps, desenvolvida com HTML5, CSS3 e JavaScript puro.

## 📋 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Estrutura de Arquivos](#estrutura-de-arquivos)
- [Configuração](#configuração)
- [Funcionalidades](#funcionalidades)
- [Segurança](#segurança)
- [Responsividade](#responsividade)
- [Como Usar](#como-usar)
- [Manutenção](#manutenção)

## 🎯 Sobre o Projeto

Página de contato desenvolvida para a TypoFX Limited, localizada em Dublin, Irlanda. O projeto apresenta um mapa interativo do Google Maps com marcador personalizado, informações de contato e formulário.

**Endereço:** 77 Camden Street Lower, Saint Kevin's, Dublin 2, D02 XE80  
**Coordenadas:** 53.33533135354178, -6.2653143722446165

## 🚀 Tecnologias Utilizadas

- **HTML5** - Estrutura semântica com tabelas (sem uso de divs)
- **CSS3** - Estilização e responsividade
- **JavaScript (ES6+)** - Lógica de interação e integração com APIs
- **Google Maps JavaScript API v3.56+** - Mapa interativo
- **AdvancedMarkerElement** - Marcadores modernos no mapa
- **Font Awesome 6.0.0** - Ícones de interface

## 📁 Estrutura de Arquivos

```
contact-page/
│
├── index.html          # Estrutura HTML principal
├── style.css           # Estilos e responsividade
├── script.js           # Lógica JavaScript
├── config.example.js   # Template de configuração (versionar)
├── config.js           # Sua configuração local (não versionar!)
├── .gitignore          # Arquivos ignorados pelo Git
└── README.md           # Esta documentação
```

## ⚙️ Configuração

### 1. Requisitos

- Conta no [Google Cloud Platform](https://console.cloud.google.com/)
- Chave de API do Google Maps JavaScript API
- Map ID configurado no Google Cloud Console
- Servidor web para hospedar os arquivos

### 2. Configurar API do Google Maps

#### a) Criar Chave de API

1. Acesse [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. Crie um novo projeto (se necessário)
3. Vá em **APIs & Services > Credentials**
4. Clique em **+ CREATE CREDENTIALS > API key**
5. Copie a chave gerada

#### b) Restringir a Chave (IMPORTANTE!)

1. Clique na chave criada
2. Em **Application restrictions**, selecione **HTTP referrers (web sites)**
3. Adicione seus domínios:
   - `seu-dominio.com/*`
   - `*.seu-dominio.com/*`
4. Em **API restrictions**, selecione **Restrict key**
5. Marque apenas **Maps JavaScript API**
6. Clique em **SAVE**

#### c) Criar Map ID

1. Acesse [Maps Management](https://console.cloud.google.com/google/maps-apis/studio/maps)
2. Clique em **CREATE NEW MAP ID**
3. Defina um nome (ex: `typofx-contato`)
4. Escolha o tipo **JavaScript**
5. Clique em **SAVE**
6. Copie o Map ID gerado

### 3. Configurar o Projeto

#### Configuração Local (após clonar o repositório)

1. **Copie o arquivo de exemplo:**
   ```bash
   cp config.example.js config.js
   ```

2. **Edite o arquivo `config.js`** com sua chave real:
   ```javascript
   const CONFIG = {
       GOOGLE_MAPS_API_KEY: 'sua_chave_real_aqui'
   };
   ```

3. **Edite o arquivo `script.js`** (linha ~58) com seu Map ID:
   ```javascript
   mapId: "SEU_MAP_ID_AQUI", // Substitua pelo Map ID real
   ```

⚠️ **Importante:** O arquivo `config.js` está no `.gitignore` e não será commitado

## 🎨 Funcionalidades

### Mapa Interativo

- **Visualização:** Mapa centralizado no endereço da empresa
- **Marcador Avançado:** Pino com título do endereço
- **Street View:** Clique no marcador para abrir o Google Street View em nova aba
- **Lazy Loading:** Mapa carrega apenas quando visível na tela (economia de banda)

### Informações de Contato

- **Telefone:** 000-000-00-00
- **Telegram:** t.me/typofx
- **E-mail:** support@typofx.ie
- **Endereço:** Completo com ícones Font Awesome

### Formulário de Contato

- **Campos:**
  - Nome (obrigatório)
  - Telefone (obrigatório, validação de formato)
  - E-mail (obrigatório, validação HTML5)
- **Validação:** HTML5 + pattern matching
- **Privacidade:** Aviso sobre não compartilhamento com terceiros

> **Nota:** O formulário atualmente apenas simula o envio (alert + console.log). 

## 🔒 Segurança

### Medidas Implementadas

1. **Content Security Policy (CSP)**
   - Bloqueia scripts maliciosos (XSS)
   - Restringe fontes de recursos
   - Configurado via meta tag no `<head>`

2. **Subresource Integrity (SRI)**
   - Valida integridade do Font Awesome CDN
   - Previne ataques de CDN comprometido
   - Hash SHA-512 no link do stylesheet

3. **Proteção contra Tabnabbing**
   - `window.opener = null` ao abrir Street View
   - Impede que páginas externas acessem a origem

4. **Restrições da API**
   - Chave do Google Maps restrita por domínio
   - Apenas Maps JavaScript API habilitada
   - Previne uso não autorizado e cobranças inesperadas

### Boas Práticas

- ❌ **NÃO versione** o arquivo `config.js` no Git
- ✅ Use `.gitignore` para proteger configurações sensíveis
- ✅ Monitore o uso da API no Google Cloud Console
- ✅ Configure alertas de cobrança no GCP

## 📱 Responsividade

O design se adapta a dois tamanhos principais:

### Tablets (≤ 768px)

- Layout em coluna vertical
- Informações de contato centralizadas
- Mapa com altura reduzida (300px)
- Fontes ajustadas para legibilidade

### Celulares (≤ 480px)

- Layout compacto
- Mapa com altura menor (250px)
- Botão de envio ocupa largura total
- Padding reduzido para aproveitar espaço

## 💻 Como Usar

### Desenvolvimento Local

1. Clone ou baixe os arquivos
2. Configure `config.js` com sua chave de API
3. Abra `index.html` em um navegador moderno

### Produção

1. **Valide a configuração:**
   - Chave de API com restrições de domínio
   - Map ID personalizado (não use `DEMO_MAP_ID`)
   - CSP ajustado para seu ambiente

2. **Hospede os arquivos:**
   - Servidor HTTP/HTTPS (Apache, Nginx, IIS)
   - Serviços de hospedagem (GitHub Pages, Netlify, Vercel)

3. **Teste:**
   - Verifique o mapa em diferentes navegadores
   - Teste responsividade em dispositivos móveis
   - Valide o formulário de contato
   - Confirme que o Street View abre corretamente

## 🛠️ Manutenção

### Pontos de Falha Comuns

| Problema | Causa Provável | Solução |
|----------|----------------|---------|
| Mapa não carrega | Chave de API inválida/expirada | Verificar configuração no GCP |
| Marcador não aparece | Map ID incorreto ou demo | Criar Map ID personalizado |
| Erro CORS | CSP bloqueando recursos | Ajustar meta tag CSP |

### Monitoramento

**Google Cloud Console:**
- Acesse [Quotas & System Limits](https://console.cloud.google.com/apis/api/maps-backend.googleapis.com/quotas)
- Monitore requisições diárias
- Configure alertas de cobrança

**Performance:**
- Lighthouse Score (alvo: >90)
- Core Web Vitals (LCP, FID, CLS)
- Tempo de carregamento do mapa

## 🚀 Features Futuras (Roadmap)

#### 1. Hyperlinks Clicáveis nos Contatos
**Status:** Pendente  
**Descrição:** Tornar as informações de contato interativas
- **Telefone:** `tel:+353-XXX-XXX-XX-XX` - Abre o discador no mobile
- **E-mail:** `mailto:support@typofx.ie` - Abre cliente de e-mail
- **Telegram:** Link direto para `https://t.me/typofx` 
- **Endereço:** Link para Google Maps/Apple Maps com direções

**Benefício:** Melhora a UX e reduz fricção para contato

#### 2. Download de vCard (.vcf)
**Status:** Pendente  
**Descrição:** Botão para adicionar TypoFX aos contatos do usuário
- Gera arquivo vCard com todos os dados
- Compatible com iPhone, Android e desktop
- Um clique para salvar nos contatos

**Benefício:** Facilita salvar informações de contato

#### 3. Dark Mode
**Status:** Pendente  
**Descrição:** Tema escuro alternativo
- Toggle switch no header
- Salvar preferência no localStorage 
- Detectar preferência do sistema (`prefers-color-scheme`)
- Mapa com estilo escuro personalizado (Google Maps Styling)

**Benefício:** Melhor experiência visual e acessibilidade

#### 4. Internacionalização (i18n)
**Status:** Pendente  
**Descrição:** Suporte a múltiplos idiomas
- Português (PT/BR)
- Inglês (EN)
- Espanhol (ES)
- Detecção automática do idioma do navegador
- Seletor de idioma no header

**Benefício:** Alcança público internacional

#### 5. Chat ao Vivo
**Status:** Pendente  
**Descrição:** Widget de chat integrado
- Tawk.to, Intercom ou solução própria
- Horário de atendimento configurável
- Mensagens offline

**Benefício:** Suporte em tempo real

#### 6. FAQ / Perguntas Frequentes
**Status:** Pendente  
**Descrição:** Seção de perguntas comuns
- Accordion com respostas
- Busca de perguntas
- Link para páginas de ajuda

**Benefício:** Reduz volume de contatos repetitivos

#### 7. Direções e Transporte Público
**Status:** Pendente  
**Descrição:** Informações de como chegar
- Botão "Como Chegar" abre rota no Google Maps
- Linhas de ônibus próximas
- Estações de metrô/trem próximas
- Informações de estacionamento

**Benefício:** Facilita visitas presenciais

#### 8. Horário de Funcionamento
**Status:** Pendente  
**Descrição:** Widget com horários
- Dias e horários de atendimento
- Status "Aberto Agora" / "Fechado"
- Horários especiais (feriados)

**Benefício:** Expectativa clara de quando obter resposta

---

**Desenvolvido com HTML, CSS e JavaScript puro** | Sem frameworks, sem dependências pesadas
