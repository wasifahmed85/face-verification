{{-- File: resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ config('app.name') }}</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        
        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 16px;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-logout:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            text-align: center;
        }
        
        .welcome-icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: wave 2s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(20deg); }
            75% { transform: rotate(-20deg); }
        }
        
        .welcome-section h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            color: #666;
            font-size: 16px;
        }
        
        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        
        .profile-header {
            display: flex;
            gap: 30px;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .profile-image-container {
            flex-shrink: 0;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .profile-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            border: 4px solid #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .profile-info {
            flex-grow: 1;
        }
        
        .profile-info h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            color: #666;
            font-size: 15px;
        }
        
        .info-item strong {
            color: #333;
            min-width: 140px;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #d4edda;
            color: #155724;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 15px;
        }
        
        .badge.verified {
            background: #d4edda;
            color: #155724;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .stat-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 8px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Info Card */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        
        .info-card h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-card p {
            color: #666;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 10px;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .info-list li {
            padding: 12px;
            margin: 8px 0;
            background: #f8f9fa;
            border-radius: 8px;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-list li::before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
        }
        
        /* Actions */
        .actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .navbar-user {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-logout {
                width: 100%;
                text-align: center;
            }
            
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-brand">
            <span>🎭</span>
            <span>{{ config('app.name', 'Face Verification') }}</span>
        </div>
        <div class="navbar-user">
            <span class="user-name">👋 {{ $user->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout →
                </button>
            </form>
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="container">
        {{-- Welcome Section --}}
        <div class="welcome-section">
            <div class="welcome-icon">🎉</div>
            <h1>Welcome Back, {{ $user->name }}!</h1>
            <p>Your account is secured with advanced face recognition technology</p>
        </div>

        {{-- Profile Card --}}
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-image-container">
                    @if($user->face_image)
                        <img src="{{ $user->face_image_url }}" 
                             alt="{{ $user->name }}" 
                             class="profile-image">
                    @else
                        <div class="profile-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    
                    <div class="info-item">
                        <strong>📧 Email:</strong>
                        <span>{{ $user->email }}</span>
                    </div>
                    
                    <div class="info-item">
                        <strong>📅 Member Since:</strong>
                        <span>{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <strong>🕐 Last Updated:</strong>
                        <span>{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    
                    @if($user->face_verified)
                        <div class="info-item">
                            <strong>🎭 Face Registered:</strong>
                            <span>{{ $user->face_registered_at ? $user->face_registered_at->format('F d, Y') : 'N/A' }}</span>
                        </div>
                    @endif
                    
                    @if($user->face_verified)
                        <span class="badge verified">
                            ✓ Face Verified
                        </span>
                    @endif
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="actions">
                <form action="{{ route('reset.face') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset face verification? You will need to register again.');">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        🔄 Reset Face Verification
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <h3>Account Active</h3>
                <p>Your account is fully activated and verified with biometric security</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🔒</div>
                <h3>High Security</h3>
                <p>Face recognition provides military-grade protection for your account</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <h3>Fast Access</h3>
                <p>Login in seconds with just your face - no passwords needed</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🤖</div>
                <h3>AI Powered</h3>
                <p>Advanced neural networks ensure accurate face recognition every time</p>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="info-card">
            <h2>
                <span>🛡️</span>
                About Your Security
            </h2>
            <p>
                Your account is protected with state-of-the-art face recognition technology. 
                Here's how we keep you safe:
            </p>
            
            <ul class="info-list">
                <li>
                    <strong>128-Dimensional Face Encoding:</strong> Your face is converted into a unique mathematical representation
                </li>
                <li>
                    <strong>Real-time Verification:</strong> Each login attempt is verified in real-time using AI
                </li>
                <li>
                    <strong>Secure Storage:</strong> Face data is encrypted and stored securely in our database
                </li>
                <li>
                    <strong>60% Match Threshold:</strong> Only faces with 60%+ similarity are accepted
                </li>
                <li>
                    <strong>No Password Required:</strong> Your face is your password - more secure and convenient
                </li>
                <li>
                    <strong>Privacy Protected:</strong> Face descriptors cannot be reverse-engineered into images
                </li>
            </ul>
            
            <p style="margin-top: 20px;">
                <strong>Technology Stack:</strong> Laravel 10, Face-API.js, TensorFlow.js, 
                TinyFaceDetector, FaceNet Recognition Model
            </p>
        </div>
    </div>
</body>
</html>