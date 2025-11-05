{{-- File: resources/views/face/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login with Face - {{ config('app.name') }}</title>

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

        input[type="email"] {
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

        .similarity-score {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: none;
        }

        .similarity-score.good {
            background: rgba(40, 167, 69, 0.9);
        }

        .similarity-score.bad {
            background: rgba(220, 53, 69, 0.9);
        }

        .status-badge {
            position: absolute;
            top: 15px;
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

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .instructions {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #2e7d32;
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

        .match-indicator {
            text-align: center;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            font-weight: 600;
            display: none;
        }

        .match-indicator.matching {
            background: #d4edda;
            color: #155724;
        }

        .match-indicator.not-matching {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Face Login</h1>
            <p class="subtitle">Secure login with biometric verification</p>
        </div>

        <div id="statusMessage" class="status-message"></div>

        <form id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com" required autocomplete="email">
            </div>

            <div id="matchIndicator" class="match-indicator"></div>

            <div class="video-section">
                <h3>🎭 Face Verification</h3>

                <div class="instructions">
                    <strong>Position your face:</strong>
                    <ul>
                        <li>Look directly at camera</li>
                        <li>Ensure good lighting</li>
                        <li>Match your registered pose</li>
                    </ul>
                </div>

                <div class="video-container">
                    <video id="video" autoplay muted playsinline></video>
                    <canvas id="canvas"></canvas>

                    <div id="videoOverlay" class="video-overlay">
                        <div class="spinner"></div>
                        <span style="margin-top: 10px;">Starting camera...</span>
                    </div>

                    <div id="faceDetected" class="face-detected">✓ Face Detected</div>
                    <div id="similarityScore" class="similarity-score">Match: 0%</div>
                    <div id="statusBadge" class="status-badge">Initializing...</div>
                </div>
            </div>

            <button type="button" id="verifyBtn" class="btn btn-primary" disabled>
                <span id="btnText">Verify Face & Login</span>
            </button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register.face') }}">Register here</a>
        </div>
    </div>

    {{-- Load face-api.js from CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    
    <script>
        console.log('=== Face Login Started ===');

        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const verifyBtn = document.getElementById('verifyBtn');
        const btnText = document.getElementById('btnText');
        const statusMessage = document.getElementById('statusMessage');
        const faceDetectedDiv = document.getElementById('faceDetected');
        const similarityScoreDiv = document.getElementById('similarityScore');
        const statusBadge = document.getElementById('statusBadge');
        const videoOverlay = document.getElementById('videoOverlay');
        const matchIndicator = document.getElementById('matchIndicator');
        const emailInput = document.getElementById('email');

        // State
        let currentDescriptor = null;
        let storedDescriptor = null;
        let modelsLoaded = false;
        let stream = null;
        let detectionActive = false;

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
                showStatus('⏳ Loading AI models...', 'loading');
                statusBadge.textContent = 'Loading models...';
                videoOverlay.innerHTML = '<div class="spinner"></div><span style="margin-top:10px;">Loading AI models...</span>';

                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);

                modelsLoaded = true;
                console.log('✓ Models loaded!');
                
                showStatus('✓ AI models loaded! Starting camera...', 'success');
                statusBadge.textContent = 'Models loaded ✓';

                setTimeout(() => {
                    hideStatus();
                    startVideo();
                }, 1000);

            } catch (error) {
                console.error('Model loading failed:', error);
                showStatus('❌ Failed to load AI models', 'error');
                videoOverlay.innerHTML = `<div style="padding:20px;text-align:center;">❌<br>Failed to Load Models</div>`;
            }
        }

        async function startVideo() {
            try {
                statusBadge.textContent = 'Starting camera...';
                videoOverlay.innerHTML = '<div class="spinner"></div><span style="margin-top:10px;">Starting camera...</span>';

                stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 640, height: 480, facingMode: 'user' },
                    audio: false
                });

                video.srcObject = stream;
                
                video.onloadedmetadata = () => {
                    video.play().then(() => {
                        setTimeout(() => {
                            videoOverlay.style.display = 'none';
                            statusBadge.textContent = 'Camera ready ✓';
                            statusBadge.style.background = 'rgba(40, 167, 69, 0.9)';
                            detectFace();
                        }, 500);
                    });
                };

            } catch (error) {
                console.error('Camera error:', error);
                let msg = 'Camera access denied';
                if (error.name === 'NotAllowedError') msg = 'Please allow camera permission';
                
                showStatus('❌ ' + msg, 'error');
                videoOverlay.innerHTML = `<div style="padding:20px;text-align:center;">❌<br>${msg}</div>`;
            }
        }

        async function getUserDescriptor() {
            const email = emailInput.value.trim();
            if (!email) return null;

            try {
                const response = await fetch(`/api/get-face-descriptor?email=${encodeURIComponent(email)}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.descriptor) {
                        return new Float32Array(data.descriptor);
                    }
                }
            } catch (error) {
                console.error('Error fetching descriptor:', error);
            }
            return null;
        }

        async function detectFace() {
            if (!modelsLoaded || detectionActive) return;
            detectionActive = true;

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
                        currentDescriptor = detections.descriptor;
                        faceDetectedDiv.style.display = 'block';
                        statusBadge.textContent = 'Face detected ✓';

                        if (storedDescriptor) {
                            const distance = faceapi.euclideanDistance(currentDescriptor, storedDescriptor);
                            const similarity = Math.max(0, 1 - distance);
                            const percentage = Math.round(similarity * 100);

                            similarityScoreDiv.textContent = `Match: ${percentage}%`;
                            similarityScoreDiv.style.display = 'block';

                            if (similarity >= 0.6) {
                                similarityScoreDiv.className = 'similarity-score good';
                                verifyBtn.disabled = false;
                                matchIndicator.className = 'match-indicator matching';
                                matchIndicator.textContent = `✓ Face Match: ${percentage}%`;
                                matchIndicator.style.display = 'block';
                            } else {
                                similarityScoreDiv.className = 'similarity-score bad';
                                verifyBtn.disabled = true;
                                matchIndicator.className = 'match-indicator not-matching';
                                matchIndicator.textContent = `✗ Face Mismatch: ${percentage}%`;
                                matchIndicator.style.display = 'block';
                            }
                        } else {
                            verifyBtn.disabled = false;
                        }
                    } else {
                        currentDescriptor = null;
                        faceDetectedDiv.style.display = 'none';
                        similarityScoreDiv.style.display = 'none';
                        statusBadge.textContent = 'No face detected';
                        verifyBtn.disabled = true;
                        matchIndicator.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Detection error:', error);
                }
            }, 300);
        }

        emailInput.addEventListener('blur', async () => {
            const email = emailInput.value.trim();
            if (email) {
                statusBadge.textContent = 'Loading face data...';
                storedDescriptor = await getUserDescriptor();

                if (storedDescriptor) {
                    statusBadge.textContent = 'Face data loaded ✓';
                    showStatus('✓ Face data loaded. Please show your face.', 'success');
                    setTimeout(hideStatus, 3000);
                } else {
                    statusBadge.textContent = 'No face data found';
                    showStatus('❌ No face verification found for this email.', 'error');
                }
            }
        });

        verifyBtn.addEventListener('click', async () => {
            if (!currentDescriptor) {
                showStatus('❌ No face detected!', 'error');
                return;
            }

            const email = emailInput.value.trim();
            if (!email) {
                showStatus('❌ Please enter your email', 'error');
                return;
            }

            verifyBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Verifying...';
            showStatus('🔐 Verifying...', 'loading');

            let similarity = 0;
            if (storedDescriptor) {
                const distance = faceapi.euclideanDistance(currentDescriptor, storedDescriptor);
                similarity = Math.max(0, 1 - distance);
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch('{{ route("verify.face") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        face_descriptor: JSON.stringify(Array.from(currentDescriptor)),
                        similarity: similarity
                    })
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
                    throw new Error(data.message || 'Verification failed');
                }

            } catch (error) {
                console.error('Verification error:', error);
                showStatus('❌ ' + error.message, 'error');
                verifyBtn.disabled = false;
                btnText.textContent = 'Verify Face & Login';
            }
        });

        window.addEventListener('beforeunload', () => {
            if (stream) stream.getTracks().forEach(t => t.stop());
        });

        function initializeFaceAPI() {
            if (typeof faceapi !== 'undefined') {
                console.log('✓ face-api.js loaded');
                loadModels();
            } else {
                setTimeout(initializeFaceAPI, 100);
            }
        }

        initializeFaceAPI();
    </script>
</body>
</html>