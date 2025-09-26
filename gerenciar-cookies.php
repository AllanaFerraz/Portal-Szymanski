<?php
$title = "Gerenciar Cookies - Portal Szymanski";
include 'header.php';

// Obter preferências atuais
$prefs = isset($_COOKIE['cookie_preferences']) ? json_decode($_COOKIE['cookie_preferences'], true) : null;
?>

<div class="container">
    <h1>Gerenciar Suas Preferências de Cookies</h1>
    
    <div class="cookie-management">
        <form id="cookie-settings-form">
            <div class="setting-group">
                <h3>Cookies Essenciais <span class="required">(Obrigatórios)</span></h3>
                <p>Estes cookies são necessários para o funcionamento do site e não podem ser desativados.</p>
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" checked disabled>
                        Cookies de Funcionamento do Site
                    </label>
                </div>
            </div>
            
            <div class="setting-group">
                <h3>Cookies de Análise</h3>
                <p>Nos ajudam a entender como os visitantes interagem com o site.</p>
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="analise" <?php echo (!$prefs || $prefs['analise']) ? 'checked' : ''; ?>>
                        Permitir cookies de análise
                    </label>
                </div>
            </div>
            
            <div class="setting-group">
                <h3>Cookies de Preferências</h3>
                <p>Permitem que o site lembre das escolhas que você faz.</p>
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="preferencias" <?php echo (!$prefs || $prefs['preferencias']) ? 'checked' : ''; ?>>
                        Permitir cookies de preferências
                    </label>
                </div>
            </div>
            
            <div class="setting-group">
                <h3>Cookies de Marketing</h3>
                <p>Usados para entregar anúncios mais relevantes para você.</p>
                <div class="cookie-option">
                    <label>
                        <input type="checkbox" name="marketing" <?php echo ($prefs && $prefs['marketing']) ? 'checked' : ''; ?>>
                        Permitir cookies de marketing
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Preferências</button>
                <button type="button" id="accept-all" class="btn-secondary">Aceitar Todos</button>
                <button type="button" id="reject-all" class="btn-secondary">Rejeitar Todos</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('cookie-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const preferences = {
        analise: formData.get('analise') === 'on',
        preferencias: formData.get('preferencias') === 'on',
        marketing: formData.get('marketing') === 'on'
    };
    
    saveCookiePreferences('custom', preferences);
    alert('Preferências salvas com sucesso!');
});

document.getElementById('accept-all').addEventListener('click', function() {
    saveCookiePreferences('all', {});
    alert('Todos os cookies foram aceitos!');
});

document.getElementById('reject-all').addEventListener('click', function() {
    saveCookiePreferences('essential', {});
    alert('Cookies não essenciais foram rejeitados!');
});

function saveCookiePreferences(type, preferences) {
    // Mesma função do banner de cookies
    const prefs = {
        tipo: type,
        data: new Date().toISOString(),
        essenciais: true,
        analise: type === 'all' ? true : (type === 'custom' ? preferences.analise : false),
        preferencias: type === 'all' ? true : (type === 'custom' ? preferences.preferencias : false),
        marketing: type === 'all' ? true : (type === 'custom' ? preferences.marketing : false)
    };
    
    document.cookie = `cookie_preferences=${JSON.stringify(prefs)}; max-age=${365 * 24 * 60 * 60}; path=/; samesite=lax`;
    document.cookie = `cookies_aceitos=true; max-age=${365 * 24 * 60 * 60}; path=/; samesite=lax`;
    
    // Recarregar a página para aplicar as mudanças
    location.reload();
}
</script>

<?php include 'footer.php'; ?>
