{{-- File: resources/views/face/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register with Face - {{ config('app.name') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        label .required {
            color: #dc3545;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .video-section {
            margin: 25px 0;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }

        .video-section h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .video-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
        }

        #canvas {
            display: none;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
            padding: 20px;
            text-align: center;
        }

        .face-detected {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            display: none;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .status-badge {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            margin-top: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .status-message {
            padding: 12px 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 14px;
            font-weight: 500;
            display: none;
        }

        .status-message.loading {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .status-message.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .status-message.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .instructions {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #1565c0;
        }

        .instructions ul {
            margin: 10px 0 0 20px;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🎭</div>
            <h1>Face Registration</h1>
            <p class="subtitle">Create your account with secure face verification</p>
        </div>

        <div id="statusMessage" class="status-message"></div>

        <form id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required minlength="2" autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required minlength="8" autocomplete="new-password">
            </div>

            <div class="video-section">
                <h3>📸 Face Verification</h3>

                <div class="instructions">
                    <strong>Instructions:</strong>
                    <ul>
                        <li>Look directly at the camera</li>
                        <li>Ensure good lighting</li>
                        <li>Remove sunglasses/mask</li>
                        <li>Keep face centered</li>
                    </ul>
                </div>

                <div class="video-container">
                    <video id="video" autoplay muted playsinline></video>
                    <canvas id="canvas"></canvas>

                    <div id="videoOverlay" class="video-overlay">
                        <div class="spinner"></div>
                        <span style="margin-top: 10px;">Initializing...</span>
                    </div>

                    <div id="faceDetected" class="face-detected">✓ Face Detected</div>
                    <div id="statusBadge" class="status-badge">Initializing...</div>
                </div>
            </div>

            <button type="button" id="captureBtn" class="btn btn-primary" disabled>
                <span id="btnText">Capture Face & Register</span>
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login.face') }}">Login here</a>
        </div>
    </div>

    {{-- Load face-api.js from CDN with models --}}
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    
    <script>
        console.log('=== Face Registration Started ===');

        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const btnText = document.getElementById('btnText');
        const statusMessage = document.getElementById('statusMessage');
        const faceDetectedDiv = document.getElementById('faceDetected');
        const statusBadge = document.getElementById('statusBadge');
        const videoOverlay = document.getElementById('videoOverlay');

        // State
        let faceDescriptor = null;
        let modelsLoaded = false;
        let stream = null;

        function showStatus(message, type = 'loading') {
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
            statusMessage.style.display = 'block';
        }

        function hideStatus() {
            statusMessage.style.display = 'none';
        }

        async function loadModels() {
            try {
                showStatus('⏳ Loading AI models from CDN...', 'loading');
                statusBadge.textContent = 'Loading models...';
                videoOverlay.innerHTML = '<div class="spinner"></div><span style="margin-top:10px;">Loading AI models...</span>';

                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                
                console.log('Loading models from CDN:', MODEL_URL);

                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);

                modelsLoaded = true;
                console.log('✓ Models loaded successfully!');
                
                showStatus('✓ AI models loaded! Starting camera...', 'success');
                statusBadge.textContent = 'Models loaded ✓';

                setTimeout(() => {
                    hideStatus();
                    startVideo();
                }, 1000);

            } catch (error) {
                console.error('Model loading failed:', error);
                showStatus('❌ Failed to load AI models', 'error');
                statusBadge.textContent = 'Model loading failed';
                videoOverlay.innerHTML = `<div style="padding:20px;text-align:center;">
                    ❌<br>Failed to Load AI Models<br>
                    <small style="color:#ff6b6b;">${error.message}</small>
                </div>`;
            }
        }

        async function startVideo() {
            try {
                console.log('Starting camera...');
                statusBadge.textContent = 'Starting camera...';
                videoOverlay.innerHTML = '<div class="spinner"></div><span style="margin-top:10px;">Starting camera...</span>';

                stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        width: { ideal: 640 }, 
                        height: { ideal: 480 }, 
                        facingMode: 'user' 
                    },
                    audio: false
                });

                video.srcObject = stream;
                
                video.onloadedmetadata = () => {
                    video.play().then(() => {
                        console.log('✓ Video playing');
                        setTimeout(() => {
                            videoOverlay.style.display = 'none';
                            statusBadge.textContent = 'Camera ready ✓';
                            statusBadge.style.background = 'rgba(40, 167, 69, 0.9)';
                            detectFace();
                        }, 500);
                    }).catch(err => {
                        console.error('Play error:', err);
                    });
                };

            } catch (error) {
                console.error('Camera error:', error);
                let msg = 'Camera access denied';
                if (error.name === 'NotAllowedError') msg = 'Please allow camera permission';
                else if (error.name === 'NotFoundError') msg = 'No camera found';
                
                showStatus('❌ ' + msg, 'error');
                statusBadge.textContent = msg;
                videoOverlay.innerHTML = `<div style="padding:20px;text-align:center;">❌<br>${msg}</div>`;
            }
        }

        async function detectFace() {
            if (!modelsLoaded) return;

            console.log('Starting face detection...');
            
            setInterval(async () => {
                try {
                    const detections = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                            inputSize: 224,
                            scoreThreshold: 0.5
                        }))
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detections) {
                        faceDescriptor = detections.descriptor;
                        faceDetectedDiv.style.display = 'block';
                        statusBadge.textContent = 'Face detected ✓';
                        statusBadge.style.background = 'rgba(40, 167, 69, 0.9)';
                        captureBtn.disabled = false;
                    } else {
                        faceDescriptor = null;
                        faceDetectedDiv.style.display = 'none';
                        statusBadge.textContent = 'No face detected';
                        statusBadge.style.background = 'rgba(220, 53, 69, 0.9)';
                        captureBtn.disabled = true;
                    }
                } catch (error) {
                    console.error('Detection error:', error);
                }
            }, 300);
        }

        captureBtn.addEventListener('click', async () => {
            if (!faceDescriptor) {
                showStatus('❌ No face detected!', 'error');
                return;
            }

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!name || name.length < 2) {
                showStatus('❌ Name must be at least 2 characters', 'error');
                return;
            }

            if (!email || !email.includes('@')) {
                showStatus('❌ Valid email required', 'error');
                return;
            }

            if (!password || password.length < 8) {
                showStatus('❌ Password must be at least 8 characters', 'error');
                return;
            }

            captureBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Processing...';
            showStatus('📸 Registering...', 'loading');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const imageData = canvas.toDataURL('image/png');

            const formData = {
                name, email, password,
                face_image: imageData,
                face_descriptor: JSON.stringify(Array.from(faceDescriptor))
            };

            try {
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                console.log('Sending request with CSRF token...');

                const response = await fetch('{{ route("register.face.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showStatus('✓ ' + data.message, 'success');
                    btnText.textContent = 'Success! Redirecting...';
                    
                    if (stream) stream.getTracks().forEach(t => t.stop());
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Registration failed');
                }

            } catch (error) {
                console.error('Registration error:', error);
                showStatus('❌ ' + error.message, 'error');
                captureBtn.disabled = false;
                btnText.textContent = 'Capture Face & Register';
            }
        });

        window.addEventListener('beforeunload', () => {
            if (stream) stream.getTracks().forEach(t => t.stop());
        });

        // Initialize
        function initializeFaceAPI() {
            if (typeof faceapi !== 'undefined') {
                console.log('✓ face-api.js loaded');
                loadModels();
            } else {
                console.log('Waiting for face-api.js...');
                setTimeout(initializeFaceAPI, 100);
            }
        }

        initializeFaceAPI();
    </script>
</body>
</html>