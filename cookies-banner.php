<?php
// Verificar se o usuário já aceitou os cookies
$cookiesAceitos = isset($_COOKIE['cookies_aceitos']) ? $_COOKIE['cookies_aceitos'] : false;
?>

<?php if (!$cookiesAceitos): ?>
<div id="cookies-banner" class="cookies-banner">
    <div class="cookies-content">
        <div class="cookies-text">
            <h3>🍪 Utilizamos Cookies</h3>
            <p>Nosso portal utiliza cookies para melhorar sua experiência, analisar o tráfego e personalizar conteúdo. 
               Ao continuar navegando, você concorda com nossa <a href="politica-privacidade.php" target="_blank">Política de Privacidade</a>.</p>
        </div>
        <div class="cookies-buttons">
            <button id="aceitar-cookies" class="btn-cookie aceitar">Aceitar Todos</button>
            <button id="recusar-cookies" class="btn-cookie recusar">Recusar</button>
            <button id="personalizar-cookies" class="btn-cookie personalizar">Personalizar</button>
        </div>
    </div>
    
    <!-- Modal de Personalização -->
    <div id="modal-cookies" class="modal-cookies">
        <div class="modal-content">
            <h3>Preferências de Cookies</h3>
            <form id="form-cookies">
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="cookies_essenciais" checked disabled>
                        <strong>Cookies Essenciais</strong>
                        <span class="cookie-desc">Necessários para o funcionamento do site</span>
                    </label>
                </div>
                
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="cookies_analise" checked>
                        <strong>Cookies de Análise</strong>
                        <span class="cookie-desc">Nos ajudam a entender como você usa o site</span>
                    </label>
                </div>
                
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="cookies_preferencias" checked>
                        <strong>Cookies de Preferências</strong>
                        <span class="cookie-desc">Lembram suas configurações e escolhas</span>
                    </label>
                </div>
                
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="cookies_marketing">
                        <strong>Cookies de Marketing</strong>
                        <span class="cookie-desc">Mostram conteúdo personalizado</span>
                    </label>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" id="salvar-preferences" class="btn-cookie aceitar">Salvar Preferências</button>
                    <button type="button" id="fechar-modal" class="btn-cookie recusar">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.cookies-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #2c3e50;
    color: white;
    padding: 20px;
    z-index: 1000;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
}

.cookies-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.cookies-text {
    flex: 1;
    min-width: 300px;
}

.cookies-text h3 {
    margin: 0 0 10px 0;
    color: #3498db;
}

.cookies-text a {
    color: #3498db;
    text-decoration: underline;
}

.cookies-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-cookie {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-cookie.aceitar {
    background: #27ae60;
    color: white;
}

.btn-cookie.recusar {
    background: #e74c3c;
    color: white;
}

.btn-cookie.personalizar {
    background: #f39c12;
    color: white;
}

.btn-cookie:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Modal */
.modal-cookies {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 1001;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    color: #333;
    padding: 30px;
    border-radius: 10px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.cookie-option {
    margin: 15px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.cookie-option label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
}

.cookie-option input {
    margin-right: 10px;
    margin-top: 3px;
}

.cookie-desc {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 5px;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .cookies-content {
        flex-direction: column;
        text-align: center;
    }
    
    .cookies-buttons {
        justify-content: center;
    }
    
    .modal-content {
        width: 95%;
        padding: 20px;
    }
    
    .modal-buttons {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('cookies-banner');
    const modal = document.getElementById('modal-cookies');
    const formCookies = document.getElementById('form-cookies');
    
    // Aceitar todos os cookies
    document.getElementById('aceitar-cookies').addEventListener('click', function() {
        setCookiePreference('all', true);
        banner.style.display = 'none';
    });
    
    // Recusar cookies não essenciais
    document.getElementById('recusar-cookies').addEventListener('click', function() {
        setCookiePreference('essential', true);
        banner.style.display = 'none';
    });
    
    // Abrir modal de personalização
    document.getElementById('personalizar-cookies').addEventListener('click', function() {
        modal.style.display = 'block';
    });
    
    // Fechar modal
    document.getElementById('fechar-modal').addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Salvar preferências personalizadas
    document.getElementById('salvar-preferences').addEventListener('click', function() {
        const analise = formCookies.cookies_analise.checked;
        const preferencias = formCookies.cookies_preferencias.checked;
        const marketing = formCookies.cookies_marketing.checked;
        
        setCookiePreference('custom', true, {
            analise: analise,
            preferencias: preferencias,
            marketing: marketing
        });
        
        banner.style.display = 'none';
        modal.style.display = 'none';
    });
    
    // Fechar modal clicando fora
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    function setCookiePreference(type, accepted, preferences = {}) {
        // Cookie de aceitação geral
        document.cookie = `cookies_aceitos=true; max-age=${365 * 24 * 60 * 60}; path=/; samesite=lax`;
        
        // Cookie com preferências
        const prefs = {
            tipo: type,
            data: new Date().toISOString(),
            essenciais: true, // Sempre true
            analise: type === 'all' ? true : (type === 'custom' ? preferences.analise : false),
            preferencias: type === 'all' ? true : (type === 'custom' ? preferences.preferencias : false),
            marketing: type === 'all' ? true : (type === 'custom' ? preferences.marketing : false)
        };
        
        document.cookie = `cookie_preferences=${JSON.stringify(prefs)}; max-age=${365 * 24 * 60 * 60}; path=/; samesite=lax`;
        
        // Inicializar cookies baseado nas preferências
        initializeCookies(prefs);
        
        // Disparar evento personalizado
        document.dispatchEvent(new CustomEvent('cookiesUpdated', { detail: prefs }));
    }
    
    function initializeCookies(prefs) {
        // Cookies essenciais (sempre ativos)
        setEssentialCookies();
        
        // Cookies de análise (Google Analytics, etc.)
        if (prefs.analise) {
            setAnalyticsCookies();
        }
        
        // Cookies de preferências
        if (prefs.preferencias) {
            setPreferenceCookies();
        }
        
        // Cookies de marketing
        if (prefs.marketing) {
            setMarketingCookies();
        }
    }
    
    function setEssentialCookies() {
        // Cookies necessários para o funcionamento do site
        console.log('Cookies essenciais ativados');
    }
    
    function setAnalyticsCookies() {
        // Exemplo: Google Analytics
        console.log('Cookies de análise ativados');
        // window.dataLayer = window.dataLayer || [];
        // function gtag(){dataLayer.push(arguments);}
        // gtag('js', new Date());
        // gtag('config', 'GA_MEASUREMENT_ID');
    }
    
    function setPreferenceCookies() {
        // Cookies de preferências do usuário
        console.log('Cookies de preferências ativados');
    }
    
    function setMarketingCookies() {
        // Cookies de marketing
        console.log('Cookies de marketing ativados');
    }
});
</script>
<?php endif; ?>
