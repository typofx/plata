# Send Email - Documentação Completa

## 📋 Visão Geral

Sistema simplificado de envio de emails de confirmação de registro usando PHPMailer. 

**Funcionalidades:**
- Formulário de registro de membros
- Upload de foto de perfil
- Envio de email com confirmação
- Campos de redes sociais
- Feedback visual de sucesso/erro

---

## 📁 Estrutura de Arquivos

```
send_email/
├── add.php                          # Formulário HTML + feedback de mensagens
├── insert.php                       # Processamento do formulário + envio de email
├── config.php                       # Carregamento de configurações SMTP do .env
├── .env                            # Credenciais SMTP (não commitar)
├── .env.example                    # Template de configuração
├── composer.json                   # Dependências PHP
├── composer.lock                   # Versões fixas das dependências
├── README.md                       # Instruções rápidas
├── DOCUMENTATION.md                # Este arquivo
└── src/
    └── EmailNotificationService.php # Classe que envia os emails
```

---

## 🚀 Configuração Inicial

### Passo 1: Copiar Configuração
```bash
copy .env.example .env
```

### Passo 2: Preencher Credenciais SMTP

Edite o arquivo `.env`:

```dotenv
SMTP_HOST=smtp.gmail.com              # Host do servidor de email
SMTP_PORT=587                         # Porta (587 para TLS)
SMTP_ENCRYPTION=tls                   # Tipo de criptografia
SMTP_USERNAME=seu-email@gmail.com     # Seu email
SMTP_PASSWORD=sua-senha-app           # Senha específica do app
SMTP_FROM_EMAIL=seu-email@gmail.com   # Email remetente
SMTP_FROM_NAME=Team Platform          # Nome do remetente
```

### Passo 3: Instalar Dependências
```bash
composer install
```

### Passo 4: Iniciar Servidor
```bash
# Ou manualmente
php -S localhost:8000
```

Acesse: `http://localhost:8000/add.php`

---

## 📧 Configuração de Provedores

### Gmail
```
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_USERNAME=seu-email@gmail.com
SMTP_PASSWORD=senha-app-especifica
```

**Como gerar senha de app:**
1. Acesse: https://myaccount.google.com/apppasswords
2. Selecione "Mail" e "Windows Computer"
3. Copie a senha gerada
4. Cole em `SMTP_PASSWORD` no `.env`

### Outlook/Microsoft 365
```
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_USERNAME=seu-email@outlook.com
SMTP_PASSWORD=sua-senha
```

### Servidor Customizado
```
SMTP_HOST=seu-servidor.com.br
SMTP_PORT=587 ou 465
SMTP_ENCRYPTION=tls ou ssl
SMTP_USERNAME=seu-usuario
SMTP_PASSWORD=sua-senha
```

---

## 🔄 Fluxo de Funcionamento

```
User acessa add.php
    ↓
Preenche formulário
    ↓
Clica "Submit"
    ↓
insert.php recebe dados
    ↓
Valida campos obrigatórios
    ↓
Valida formatos de email e senha
    ↓
Processa upload de imagem
    ↓
Envia email via EmailNotificationService
    ↓
Feedback ao usuário (sucesso ou erro)
    ↓
Redireciona para add.php
```

---

## 📝 Campos do Formulário

### Seção: Platform Data (Obrigatórios)
- **First Name**: Nome do usuário
- **Last Name**: Sobrenome do usuário
- **User Email**: Email para login na plataforma
- **Password**: Senha (mínimo 6 caracteres)
- **Confirm Password**: Confirmação de senha

### Seção: Member Data
- **Profile Picture**: Foto de perfil (obrigatória)
  - Formatos: JPG, PNG, GIF, WebP
  - Tamanho máximo: 5MB
- **Position**: Cargo do membro (ex: INTERN)

### Seção: Social Media (Opcionais)
- WhatsApp, Instagram, Telegram, Facebook
- GitHub, Twitter (X), LinkedIn, Twitch, Medium
- Email Social (Email público para contato)

### Seção: Email Recipient
- **Send confirmation email to**: Email que receberá a confirmação

---

## 🔐 Validações

### Servidor (insert.php)
- ✅ Método HTTP deve ser POST
- ✅ Todos os campos obrigatórios preenchidos
- ✅ Emails com formato válido
- ✅ Senhas coincidem
- ✅ Senha com mínimo 6 caracteres
- ✅ Arquivo de imagem com tipo válido
- ✅ Tamanho de imagem ≤ 5MB

### Cliente (add.php - JavaScript)
- ✅ Senhas coincidem em tempo real

---

## 📧 Classe EmailNotificationService

Localização: `src/EmailNotificationService.php`

### Método: `send()`

```php
$emailService = new EmailNotificationService();
$emailService->send($formData, $fileUploadData, $socialMedia);
```

**Parâmetros:**
- `$formData` (array): name, last_name, email, password, position, recipient_email
- `$fileUploadData` (array|null): originalName, tmpPath
- `$socialMedia` (array): whatsapp, instagram, telegram, etc.

**O que faz:**
1. Configura SMTP usando credenciais do `.env`
2. Monta o email HTML com dados do formulário
3. Anexa a foto de perfil
4. Envia para o email do recipient
5. Lança exceção se houver erro

---

## 📬 Formato do Email

O email enviado contém:
- ✉️ Dados básicos (nome, email, cargo)
- 🖼️ Foto de perfil anexada
- 📱 Redes sociais preenchidas
- 🔐 Senha de plataforma (em texto)

---

## 🐛 Tratamento de Erros

Erros são:
1. Registrados em `error_log` do PHP
2. Armazenados em `$_SESSION['error']`
3. Exibidos na página em banner vermelho

**Exemplos de erros:**
- "Please fill in all required fields."
- "Invalid platform email format."
- "Passwords do not match!"
- "File size exceeds 5MB limit"
- Erros de conexão SMTP

---

## ✅ Mensagens de Sucesso/Erro

Aparecem em banner colorido no topo da página:

**Sucesso (Verde):**
```
✓ Team member registered successfully!
```

**Erro (Vermelho):**
```
✗ Error: [descrição do erro]
```

As mensagens são exibidas uma vez e limpas após o recarregamento.

---

## 🔧 Troubleshooting

### "SMTP Connect failed"
- Verifique `SMTP_HOST` e `SMTP_PORT` no `.env`
- Verifique se o servidor SMTP está acessível
- Tente porta 465 com SSL ao invés de 587

### "Username and Password not accepted"
- Verifique `SMTP_USERNAME` e `SMTP_PASSWORD`
- Para Gmail, use senha de app (não a senha da conta)
- Verifique se "Less secure app access" está habilitado (Gmail)

### "File size exceeds 5MB"
- A imagem é muito grande
- Comprima a imagem antes de fazer upload

### Nenhum email recebido
- Verifique se email foi para spam
- Verifique logs do PHP para erros
- Teste com um email de teste primeiro

---

## 🛡️ Segurança

**Práticas implementadas:**
- ✅ Sanitização de inputs com `filter_var()`, `trim()`, `htmlspecialchars()`
- ✅ Validação de tipo de arquivo
- ✅ Limite de tamanho de arquivo
- ✅ Senhas em texto plano no email 
- ✅ `.env` na lista `.gitignore` (credenciais não são commitadas)

---

## 📦 Dependências

```json
{
    "require": {
        "phpmailer/phpmailer": "^7.0"
    }
}
```

PHPMailer versão 7.0+ instalada pelo Composer.

---

## 🎯 Próximos Passos

1. ✅ Configurar `.env` com suas credenciais
2. ✅ Testar envio de email
3. ✅ Customizar template do email (em EmailNotificationService.php)
4. ✅ Implementar armazenamento de dados em banco de dados
5. ✅ Adicionar autenticação na página de upload
6. ✅ Implementar validação de email (confirmação por link)

---

## 📞 Suporte

Para problemas:
1. Verifique este documento
2. Verifique logs do PHP
3. Teste credenciais SMTP com ferramenta externa
4. Consulte documentação do PHPMailer: https://github.com/PHPMailer/PHPMailer

---

**Última atualização:** Janeiro 2026
