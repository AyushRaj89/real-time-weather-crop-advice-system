
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — CropCast</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0f0d;
            color: #e8f5e9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(64,145,108,0.08) 0%, transparent 60%);
        }

        .register-box { width: 100%; max-width: 500px; }

        /* ── Brand ── */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            justify-content: center;
            text-decoration: none;
        }
        .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #40916c, #74c69d);
            border-radius: 9px;
            display: grid;
            place-items: center;
            font-size: 18px;
        }
        .brand-name { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 800; color: #e8f5e9; }
        .brand-name span { color: #52b788; }

        /* ── Card ── */
        .card {
            background: #111a15;
            border: 1px solid #243328;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .card-title { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; margin-bottom: 4px; letter-spacing: -0.03em; }
        .card-sub { color: #4d6b57; font-size: 0.875rem; margin-bottom: 1.75rem; }
        .card-sub a { color: #52b788; text-decoration: none; }

        /* ── Form ── */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .form-group { margin-bottom: 1rem; }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #8aad96;
            margin-bottom: 5px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #4d6b57; font-size: 14px; pointer-events: none; }

        .form-control {
            width: 100%;
            padding: 10px 36px 10px 36px;
            background: #0a0f0d;
            border: 1px solid #243328;
            border-radius: 9px;
            color: #e8f5e9;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus { border-color: #40916c; box-shadow: 0 0 0 3px rgba(64,145,108,0.15); }
        .form-control::placeholder { color: #2e4035; }
        .form-control.is-invalid { border-color: #e63946; }
        .form-control.is-valid   { border-color: #52b788; }

        /* password toggle button */
        .toggle-pw {
            position: absolute;
            right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #4d6b57; cursor: pointer;
            font-size: 13px; padding: 2px 4px;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #52b788; }

        /* ── Errors ── */
        .form-error {
            color: #ff6b7a;
            font-size: 0.75rem;
            margin-top: 4px;
            display: flex;
            align-items: flex-start;
            gap: 4px;
            line-height: 1.4;
        }

        /* ── Global errors block ── */
        .errors-block {
            background: rgba(230,57,70,0.08);
            border: 1px solid rgba(230,57,70,0.25);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 1.25rem;
            font-size: 0.82rem;
            color: #ff6b7a;
        }
        .errors-block div { margin-bottom: 2px; }
        .errors-block div:last-child { margin-bottom: 0; }

        /* ── Password strength bar ── */
        .strength-bar-wrap {
            margin-top: 6px;
            height: 4px;
            background: #1c2b21;
            border-radius: 2px;
            overflow: hidden;
        }
        .strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s ease, background 0.3s ease;
        }
        .strength-label {
            font-size: 0.72rem;
            margin-top: 4px;
            font-weight: 600;
            letter-spacing: 0.04em;
            transition: color 0.3s;
        }

        /* ── Password rules checklist ── */
        .pw-rules {
            background: #0d1a11;
            border: 1px solid #243328;
            border-radius: 9px;
            padding: 10px 14px;
            margin-top: 8px;
            display: none;   /* shown on focus via JS */
        }

        .pw-rules-title {
            font-size: 0.72rem;
            color: #4d6b57;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }

        .pw-rule {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            color: #4d6b57;
            margin-bottom: 4px;
            transition: color 0.2s;
        }
        .pw-rule:last-child { margin-bottom: 0; }

        .pw-rule .rule-icon {
            width: 14px; height: 14px;
            border-radius: 50%;
            border: 1.5px solid #2e4035;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .pw-rule.passed { color: #52b788; }
        .pw-rule.passed .rule-icon {
            background: rgba(82,183,136,0.15);
            border-color: #52b788;
            color: #52b788;
        }

        /* ── Name hint ── */
        .field-hint {
            font-size: 0.72rem;
            color: #2e4035;
            margin-top: 4px;
        }

        /* ── Submit button ── */
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
            box-shadow: 0 4px 15px rgba(64,145,108,0.25);
            margin-top: 0.25rem;
        }
        .btn-submit:hover { background: linear-gradient(135deg, #52b788, #74c69d); transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        @media (max-width: 500px) {
            .form-row { grid-template-columns: 1fr; }
            body { padding: 1rem; }
        }
    </style>
</head>
<body>
<div class="register-box">

    {{-- Brand --}}
    <a href="{{ route('login') }}" class="brand">
        <div class="brand-logo">🌾</div>
        <span class="brand-name">Crop<span>Cast</span></span>
    </a>

    <div class="card">
        <h1 class="card-title">Create your account</h1>
        <p class="card-sub">Already registered? <a href="{{ route('login') }}">Sign in instead</a></p>

        {{-- Server-side errors block --}}
        @if($errors->any())
        <div class="errors-block">
            @foreach($errors->all() as $error)
            <div>⚠ {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" id="registerForm" novalidate>
            @csrf

            {{-- ── Full Name ── --}}
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <div class="input-wrap">
                    <span class="input-icon">👤</span>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        placeholder="e.g. Ravi Kumar"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                    >
                </div>
                <div class="field-hint">Letters and spaces only. Min 3 characters. Numbers not allowed.</div>
                @error('name')
                <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            {{-- ── Email ── --}}
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>
                @error('email')
                <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            {{-- ── Home City ── --}}
            <div class="form-group">
                <label class="form-label" for="default_city">Home City <span style="color:#2e4035;font-weight:400;">(optional)</span></label>
                <div class="input-wrap">
                    <span class="input-icon">🌍</span>
                    <input
                        type="text"
                        id="default_city"
                        name="default_city"
                        class="form-control {{ $errors->has('default_city') ? 'is-invalid' : '' }}"
                        placeholder="e.g. Mumbai, Delhi, London"
                        value="{{ old('default_city') }}"
                    >
                </div>
                <div class="field-hint">Letters, spaces, and hyphens only.</div>
                @error('default_city')
                <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            {{-- ── Password ── --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Create a strong password"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePassword('password', this)" title="Show/hide password">👁</button>
                </div>

                {{-- Strength bar --}}
                <div class="strength-bar-wrap">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="strength-label" id="strengthLabel" style="color:#2e4035;">Enter a password</div>

                {{-- Live rules checklist --}}
                <div class="pw-rules" id="pwRules">
                    <div class="pw-rules-title">Password must have:</div>
                    <div class="pw-rule" id="rule-length">
                        <span class="rule-icon" id="icon-length">✗</span>
                        At least 8 characters
                    </div>
                    <div class="pw-rule" id="rule-upper">
                        <span class="rule-icon" id="icon-upper">✗</span>
                        At least 1 uppercase letter (A–Z)
                    </div>
                    <div class="pw-rule" id="rule-lower">
                        <span class="rule-icon" id="icon-lower">✗</span>
                        At least 1 lowercase letter (a–z)
                    </div>
                    <div class="pw-rule" id="rule-number">
                        <span class="rule-icon" id="icon-number">✗</span>
                        At least 1 number (0–9)
                    </div>
                    <div class="pw-rule" id="rule-symbol">
                        <span class="rule-icon" id="icon-symbol">✗</span>
                        At least 1 special character (@, $, !, #, %, &…)
                    </div>
                </div>

                @error('password')
                <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            {{-- ── Confirm Password ── --}}
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Repeat your password"
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)" title="Show/hide password">👁</button>
                </div>
                <div class="field-hint" id="matchHint" style="color:#2e4035;"></div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                Create Account & Start Farming →
            </button>
        </form>
    </div>
</div>

<script>
// ── Toggle password visibility ───────────────────────────────────────────
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = '🙈';
    } else {
        field.type = 'password';
        btn.textContent = '👁';
    }
}

// ── Password rules checker ────────────────────────────────────────────────
const passwordInput = document.getElementById('password');
const confirmInput  = document.getElementById('password_confirmation');
const pwRules       = document.getElementById('pwRules');
const strengthBar   = document.getElementById('strengthBar');
const strengthLabel = document.getElementById('strengthLabel');
const matchHint     = document.getElementById('matchHint');

const rules = {
    length : { regex: /.{8,}/, el: document.getElementById('rule-length'),  icon: document.getElementById('icon-length')  },
    upper  : { regex: /[A-Z]/, el: document.getElementById('rule-upper'),   icon: document.getElementById('icon-upper')   },
    lower  : { regex: /[a-z]/, el: document.getElementById('rule-lower'),   icon: document.getElementById('icon-lower')   },
    number : { regex: /[0-9]/, el: document.getElementById('rule-number'),  icon: document.getElementById('icon-number')  },
    symbol : { regex: /[@$!%*?&#^()\-_=+\[\]{}|;:'",.<>\/\\`~]/, el: document.getElementById('rule-symbol'), icon: document.getElementById('icon-symbol') },
};

const strengthLevels = [
    { label: 'Very Weak',  color: '#e63946', width: '15%'  },
    { label: 'Weak',       color: '#f4a261', width: '30%'  },
    { label: 'Fair',       color: '#ffd166', width: '55%'  },
    { label: 'Strong',     color: '#52b788', width: '75%'  },
    { label: 'Very Strong',color: '#40916c', width: '100%' },
];

// Show rules checklist on focus
passwordInput.addEventListener('focus', () => {
    pwRules.style.display = 'block';
});

passwordInput.addEventListener('input', () => {
    const val = passwordInput.value;
    let passedCount = 0;

    // Check each rule
    Object.entries(rules).forEach(([key, rule]) => {
        const passed = rule.regex.test(val);
        if (passed) {
            passedCount++;
            rule.el.classList.add('passed');
            rule.icon.textContent = '✓';
        } else {
            rule.el.classList.remove('passed');
            rule.icon.textContent = '✗';
        }
    });

    // Update strength bar
    if (val.length === 0) {
        strengthBar.style.width = '0%';
        strengthLabel.textContent = 'Enter a password';
        strengthLabel.style.color = '#2e4035';
    } else {
        const level = strengthLevels[Math.min(passedCount, 4)];
        strengthBar.style.width    = level.width;
        strengthBar.style.background = level.color;
        strengthLabel.textContent  = level.label;
        strengthLabel.style.color  = level.color;
    }

    // Check confirm match if already typed
    checkMatch();
});

// ── Confirm password match check ──────────────────────────────────────────
confirmInput.addEventListener('input', checkMatch);

function checkMatch() {
    const pw  = passwordInput.value;
    const cpw = confirmInput.value;

    if (cpw.length === 0) {
        matchHint.textContent = '';
        confirmInput.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (pw === cpw) {
        matchHint.textContent = '✓ Passwords match';
        matchHint.style.color = '#52b788';
        confirmInput.classList.add('is-valid');
        confirmInput.classList.remove('is-invalid');
    } else {
        matchHint.textContent = '✗ Passwords do not match';
        matchHint.style.color = '#e63946';
        confirmInput.classList.add('is-invalid');
        confirmInput.classList.remove('is-valid');
    }
}

// ── Name validation — prevent numbers/symbols ────────────────────────────
const nameInput = document.getElementById('name');
nameInput.addEventListener('input', () => {
    const val = nameInput.value;
    const valid = /^[a-zA-Z\s]*$/.test(val);
    if (!valid) {
        nameInput.classList.add('is-invalid');
    } else if (val.length >= 3) {
        nameInput.classList.remove('is-invalid');
        nameInput.classList.add('is-valid');
    } else {
        nameInput.classList.remove('is-valid', 'is-invalid');
    }
});

// ── Client-side submit guard ─────────────────────────────────────────────
// (Server-side is the real guard — this just gives instant feedback)
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const pw  = passwordInput.value;
    const cpw = confirmInput.value;
    const name = nameInput.value;

    let hasError = false;

    if (!/^[a-zA-Z][a-zA-Z\s]{2,}$/.test(name)) {
        nameInput.classList.add('is-invalid');
        hasError = true;
    }

    if (pw !== cpw) {
        confirmInput.classList.add('is-invalid');
        hasError = true;
    }

    const allRulesPassed = Object.values(rules).every(r => r.regex.test(pw));
    if (!allRulesPassed) {
        passwordInput.classList.add('is-invalid');
        pwRules.style.display = 'block';
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});
</script>
</body>
</html>