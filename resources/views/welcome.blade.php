{{-- File: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Advanced Face Recognition</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: scroll 20s linear infinite;
        }
        
        @keyframes scroll {
            0% { transform: translateY(0); }
            100% { transform: translateY(100px); }
        }
        
        .container {
            max-width: 1100px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 60px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .logo {
            font-size: 100px;
            margin-bottom: 20px;
            animation: float 4s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-15px) rotate(-5deg); }
            75% { transform: translateY(-15px) rotate(5deg); }
        }
        
        h1 {
            color: #333;
            font-size: 48px;
            margin-bottom: 15px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .tagline {
            color: #666;
            font-size: 20px;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 35px 25px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }
        
        .feature-icon {
            font-size: 56px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 2s infinite;
        }
        
        .feature-card:nth-child(2) .feature-icon {
            animation-delay: 0.2s;
        }
        
        .feature-card:nth-child(3) .feature-icon {
            animation-delay: 0.4s;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .feature-card h3 {
            color: #333;
            font-size: 22px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .feature-card p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
        }
        
        /* CTA Buttons */
        .cta-section {
            text-align: center;
            margin: 50px 0 30px;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 18px 45px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn span {
            position: relative;
            z-index: 1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 3px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }
        
        /* Tech Stack */
        .tech-stack {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
        }
        
        .tech-stack h3 {
            color: #999;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .tech-badges {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 22px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Benefits Section */
        .benefits {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 16px;
            margin: 40px 0;
        }
        
        .benefits h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .benefits-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .benefit-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: transform 0.3s;
        }
        
        .benefit-item:hover {
            transform: translateX(5px);
        }
        
        .benefit-icon {
            font-size: 32px;
            flex-shrink: 0;
        }
        
        .benefit-text {
            flex-grow: 1;
        }
        
        .benefit-text h4 {
            color: #333;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .benefit-text p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
            color: #999;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 40px 25px; }
            h1 { font-size: 36px; }
            .tagline { font-size: 18px; }
            .logo { font-size: 80px; }
            .btn { padding: 16px 35px; font-size: 16px; }
            .features { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="container">
            {{-- Header --}}
            <div class="header">
                <div class="logo">🎭</div>
                <h1>Face Verification System</h1>
                <p class="tagline">
                    Experience the future of authentication with AI-powered facial recognition. 
                    Secure, fast, and password-free login.
                </p>
            </div>

            {{-- Features Grid --}}
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Military-Grade Security</h3>
                    <p>
                        Advanced biometric authentication with 128-dimensional face encoding 
                        ensures your account stays protected from unauthorized access.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Lightning Fast</h3>
                    <p>
                        Login in under 2 seconds with just your face. No more forgotten passwords 
                        or time-consuming authentication processes.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🤖</div>
                    <h3>AI Powered</h3>
                    <p>
                        State-of-the-art neural networks and TensorFlow technology provide 
                        99.9% accurate face recognition in real-time.
                    </p>
                </div>
            </div>

            {{-- Benefits Section --}}
            <div class="benefits">
                <h2>Why Choose Face Verification?</h2>
                <div class="benefits-list">
                    <div class="benefit-item">
                        <div class="benefit-icon">✅</div>
                        <div class="benefit-text">
                            <h4>No More Passwords</h4>
                            <p>Your face is your password - more secure and impossible to forget</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">🛡️</div>
                        <div class="benefit-text">
                            <h4>Privacy Protected</h4>
                            <p>Face data is encrypted and stored securely with bank-level encryption</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">📱</div>
                        <div class="benefit-text">
                            <h4>Works Everywhere</h4>
                            <p>Compatible with all devices that have a camera - desktop or mobile</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">⚙️</div>
                        <div class="benefit-text">
                            <h4>Easy Setup</h4>
                            <p>Register your face in seconds and start using biometric login immediately</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="cta-section">
                <div class="cta-buttons">
                    <a href="{{ route('register.face') }}" class="btn btn-primary">
                        <span>Get Started</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('login.face') }}" class="btn btn-secondary">
                        <span>🎭</span>
                        <span>Login with Face</span>
                    </a>
                </div>
            </div>

            {{-- Tech Stack --}}
            <div class="tech-stack">
                <h3>Built With Cutting-Edge Technology</h3>
                <div class="tech-badges">
                    <span class="badge">
                        <span>⚡</span>
                        <span>Laravel 10</span>
                    </span>
                    <span class="badge">
                        <span>🎭</span>
                        <span>Face-API.js</span>
                    </span>
                    <span class="badge">
                        <span>🤖</span>
                        <span>TensorFlow</span>
                    </span>
                    <span class="badge">
                        <span>🔒</span>
                        <span>128D Encoding</span>
                    </span>
                    <span class="badge">
                        <span>⚙️</span>
                        <span>Neural Networks</span>
                    </span>
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved. 
                    Built with Laravel 10 & Face Recognition AI
                </p>
            </div>
        </div>
    </div>
</body>
</html>