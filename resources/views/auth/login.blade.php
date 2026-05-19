
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — CropCast</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0f0d;
            color: #e8f5e9;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left panel */
        .hero {
            position: relative;
            background: linear-gradient(160deg, #0d2015 0%, #0a0f0d 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
            border-right: 1px solid #243328;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 30% 40%, rgba(64,145,108,0.15) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 70% 70%, rgba(82,183,136,0.08) 0%, transparent 60%);
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #40916c, #74c69d);
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #e8f5e9;
        }

        .brand-name span { color: #52b788; }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-tag {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(82,183,136,0.12);
            border: 1px solid rgba(82,183,136,0.25);
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #74c69d;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        .hero-title .accent { color: #52b788; }

        .hero-sub {
            color: #8aad96;
            font-size: 1rem;
            line-height: 1.6;
            max-width: 380px;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .hero-stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #52b788;
        }

        .hero-stat-label {
            font-size: 0.78rem;
            color: #4d6b57;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Floating cards decoration */
        .deco-cards {
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 12px;
            opacity: 0.5;
            z-index: 0;
        }

        .deco-card {
            background: rgba(22,32,25,0.9);
            border: 1px solid #243328;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.8rem;
            color: #4d6b57;
            backdrop-filter: blur(8px);
        }

        /* Right panel - form */
        .form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 4rem;
            background: #0a0f0d;
        }

        .auth-box { width: 100%; max-width: 380px; }

        .auth-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }

        .auth-sub {
            color: #4d6b57;
            font-size: 0.875rem;
            margin-bottom: 2.5rem;
        }

        .auth-sub a { color: #52b788; text-decoration: none; }
        .auth-sub a:hover { text-decoration: underline; }

        .form-group { margin-bottom: 1.1rem; }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #8aad96;
            margin-bottom: 6px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #4d6b57;
            width: 16px;
            height: 16px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            background: #111a15;
            border: 1px solid #243328;
            border-radius: 10px;
            color: #e8f5e9;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #40916c;
            box-shadow: 0 0 0 3px rgba(64,145,108,0.15);
        }

        .form-control::placeholder { color: #2e4035; }

        .form-error {
            color: #ff6b7a;
            font-size: 0.78rem;
            margin-top: 4px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .form-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #40916c;
            cursor: pointer;
        }

        .form-check label {
            font-size: 0.85rem;
            color: #4d6b57;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #40916c, #52b788);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(64,145,108,0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #52b788, #74c69d);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(64,145,108,0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            color: #2e4035;
            font-size: 0.78rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #243328;
        }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .hero { display: none; }
            .form-panel { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-brand">
            <div class="brand-logo">🌾</div>
            <span class="brand-name">Crop<span>Cast</span></span>
        </div>

        <div class="hero-content">
            <div class="hero-tag">🛰️ Live Weather Intelligence</div>
            <h1 class="hero-title">
                Smarter farming<br>starts with<br><span class="accent">better data.</span>
            </h1>
            <p class="hero-sub">
                Real-time weather analysis paired with AI-driven crop recommendations.
                Know what to grow before you sow.
            </p>
        </div>

        <div class="hero-stats">
            <div>
                <div class="hero-stat-value">8+</div>
                <div class="hero-stat-label">Crop Types</div>
            </div>
            <div>
                <div class="hero-stat-value">Live</div>
                <div class="hero-stat-label">Weather Data</div>
            </div>
            <div>
                <div class="hero-stat-value">Smart</div>
                <div class="hero-stat-label">AI Alerts</div>
            </div>
        </div>
    </div>

    <div class="form-panel">
        <div class="auth-box">
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-sub">Don't have an account? <a href="{{ route('register') }}">Create one free</a></p>

            @if($errors->any())
            <div style="background:rgba(230,57,70,0.1);border:1px solid rgba(230,57,70,0.3);border-radius:10px;padding:12px 16px;margin-bottom:1.25rem;color:#ff6b7a;font-size:0.875rem;">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>

                <button type="submit" class="btn-submit">Sign in to CropCast →</button>
            </form>

            <!-- <div class="divider">Demo credentials</div>
            <div style="background:#111a15;border:1px solid #243328;border-radius:10px;padding:12px 16px;font-size:0.82rem;color:#4d6b57;">
                <strong style="color:#8aad96;">Admin:</strong> admin@cropweather.com / password<br>
                <strong style="color:#8aad96;">User:</strong> demo@cropweather.com / password
            </div> -->
        </div>
    </div>
</body>
</html>