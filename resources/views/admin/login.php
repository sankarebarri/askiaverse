<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">🚀 Askiaverse Admin</h1>
                <p class="login-subtitle">Connexion à l'interface d'administration</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <span class="error-icon">⚠️</span>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login" class="login-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Entrez votre nom d'utilisateur">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Entrez votre mot de passe">
                </div>

                <button type="submit" class="login-btn">
                    <span class="btn-icon">🔐</span>
                    Se connecter
                </button>
            </form>

            <div class="login-footer">
                <a href="/" class="back-link">← Retour à l'accueil</a>
            </div>
        </div>
    </div>

    <style>
        .admin-login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f1419, #1a2332, #0f1419);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background: #253549e6;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            font-family: Poppins, sans-serif;
            margin: 0 0 0.5rem 0;
        }

        .login-subtitle {
            color: #ffffffb3;
            font-size: 1rem;
            margin: 0;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ff6b6b;
        }

        .error-icon {
            font-size: 1.2rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group input {
            padding: 0.75rem 1rem;
            background: #171f2acc;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74,144,226,.1);
        }

        .form-group input::placeholder {
            color: #ffffff66;
        }

        .login-btn {
            background: linear-gradient(135deg, #4a90e2, #2a4d69);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74,144,226,.4);
        }

        .btn-icon {
            font-size: 1.1rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }

        .back-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .back-link:hover {
            color: #fff;
        }
    </style>
</body>
</html> 