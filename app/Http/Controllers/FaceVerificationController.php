<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FaceVerificationController extends Controller
{
    /**
     * Show the face registration page.
     */
    public function showRegisterFace()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('face.register');
    }

    /**
     * Handle user registration and store face data.
     */
    public function storeFaceData(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|max:255',
                'face_image' => 'required|string',
                'face_descriptor' => 'required|string',
            ], [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'This email is already registered',
                'password.min' => 'Password must be at least 8 characters',
                'face_image.required' => 'Face image is required',
                'face_descriptor.required' => 'Face recognition failed',
            ]);

            // Decode base64 image
            $faceImageData = preg_replace('/^data:image\/\w+;base64,/', '', $request->face_image);
            $faceImageData = str_replace(' ', '+', $faceImageData);

            $imageName = 'face_' . uniqid() . '_' . time() . '.png';
            $imagePath = 'faces/' . $imageName;

            // Save the image in storage/app/public/faces/
            Storage::disk('public')->put($imagePath, base64_decode($faceImageData));

            // Validate descriptor (must be a 128D array)
            $descriptor = json_decode($request->face_descriptor, true);
            if (!is_array($descriptor) || count($descriptor) !== 128) {
                Storage::disk('public')->delete($imagePath);
                throw ValidationException::withMessages([
                    'face_descriptor' => 'Invalid face descriptor format',
                ]);
            }

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'face_image' => $imagePath,
                'face_descriptor' => $descriptor,
                'face_verified' => true,
                'face_registered_at' => now(),
            ]);

            Log::info('New user registered with face verification', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome ' . $user->name,
                'redirect' => route('dashboard'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Face registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Show the face login page.
     */
    public function showLoginFace()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('face.login');
    }

    /**
     * Verify user face and log them in.
     */
    public function verifyFace(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'face_descriptor' => 'required|string',
                'similarity' => 'nullable|numeric|min:0|max:1',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.',
                ], 404);
            }

            if (!$user->hasFaceVerification()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification is not set up for this account. Please register first.',
                ], 400);
            }

            $similarity = $request->similarity ?? 0;
            $threshold = 0.6; // 60% minimum similarity

            if ($similarity >= $threshold) {
                Auth::login($user);

                $user->update([
                    'updated_at' => now(),
                ]);

                Log::info('User logged in with face verification', [
                    'user_id' => $user->id,
                    'similarity' => $similarity,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Face verified successfully! Welcome back, ' . $user->name,
                    'similarity' => round($similarity * 100, 2),
                    'redirect' => route('dashboard'),
                ], 200);
            }

            Log::warning('Face verification failed', [
                'email' => $validated['email'],
                'similarity' => $similarity,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Face verification failed! Similarity: ' . round($similarity * 100, 2) . '%',
                'similarity' => round($similarity * 100, 2),
            ], 401);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Face verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Display user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['user_id' => $userId]);

        return redirect()->route('login.face')->with('success', 'Logged out successfully!');
    }

    /**
     * Return the stored face descriptor of a user (API endpoint).
     */
    public function getFaceDescriptor(Request $request)
    {
        try {
            $email = $request->query('email');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email parameter is required',
                ], 400);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            if (!$user->hasFaceVerification()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification not set up for this user',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'descriptor' => $user->face_descriptor,
                'face_verified' => $user->face_verified,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get face descriptor error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve face data',
            ], 500);
        }
    }

    /**
     * Reset user's face verification data.
     */
    public function resetFaceVerification()
    {
        try {
            $user = Auth::user();

            if ($user->resetFaceVerification()) {
                Log::info('Face verification reset', ['user_id' => $user->id]);

                return redirect()->route('register.face')
                    ->with('success', 'Face verification has been reset. Please register again.');
            }

            return back()->with('error', 'Failed to reset face verification.');
        } catch (\Exception $e) {
            Log::error('Reset face verification error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}
