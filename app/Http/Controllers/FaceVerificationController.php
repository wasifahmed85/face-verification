<?php
/**
 * File: app/Http/Controllers/FaceVerificationController.php
 * 
 * Face Verification এর সব functionality এখানে আছে
 */

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
     * Registration page দেখাবে (face capture সহ)
     * 
     * @return \Illuminate\View\View
     */
    public function showRegisterFace()
    {
        // Already logged in থাকলে dashboard এ redirect
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('face.register');
    }

    /**
     * User registration এবং face data store করবে
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFaceData(Request $request)
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|max:255',
                'face_image' => 'required|string', // Base64 encoded image
                'face_descriptor' => 'required|string', // JSON string of 128D array
            ], [
                'name.required' => 'নাম দেওয়া আবশ্যক',
                'email.required' => 'ইমেইল দেওয়া আবশ্যক',
                'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে একাউন্ট আছে',
                'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে',
                'face_image.required' => 'মুখের ছবি প্রয়োজন',
                'face_descriptor.required' => 'মুখ সনাক্তকরণ ব্যর্থ হয়েছে',
            ]);

            // Base64 image কে decode করে save করা
            $faceImageData = $request->face_image;
            
            // Base64 header remove করা
            $faceImageData = preg_replace('/^data:image\/\w+;base64,/', '', $faceImageData);
            $faceImageData = str_replace(' ', '+', $faceImageData);
            
            // Unique filename তৈরি করা
            $imageName = 'face_' . uniqid() . '_' . time() . '.png';
            $imagePath = 'faces/' . $imageName;
            
            // Image save করা storage/app/public/faces/ directory তে
            Storage::disk('public')->put($imagePath, base64_decode($faceImageData));

            // Face descriptor validate করা (128 dimensional array হতে হবে)
            $descriptor = json_decode($request->face_descriptor, true);
            
            if (!is_array($descriptor) || count($descriptor) !== 128) {
                // Invalid descriptor হলে image delete করে error throw করা
                Storage::disk('public')->delete($imagePath);
                throw ValidationException::withMessages([
                    'face_descriptor' => 'Invalid face descriptor format'
                ]);
            }

            // User তৈরি করা
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'face_image' => $imagePath,
                'face_descriptor' => $descriptor, // Model automatically JSON encode করবে
                'face_verified' => true,
                'face_registered_at' => now(),
            ]);

            // Log করা
            Log::info('New user registered with face verification', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // User কে login করানো
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome ' . $user->name,
                'redirect' => route('dashboard')
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Error log করা
            Log::error('Face registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Login page দেখাবে (face verification সহ)
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginFace()
    {
        // Already logged in থাকলে dashboard এ redirect
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('face.login');
    }

    /**
     * Face verification করে user কে login করাবে
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyFace(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'email' => 'required|email',
                'face_descriptor' => 'required|string',
                'similarity' => 'nullable|numeric|min:0|max:1',
            ]);

            // User খুঁজে বের করা
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.'
                ], 404);
            }

            // Check করা user এর face verification setup আছে কিনা
            if (!$user->hasFaceVerification()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification is not set up for this account. Please register first.'
                ], 400);
            }

            // Frontend থেকে আসা similarity score
            $similarity = $request->similarity ?? 0;

            // Minimum similarity threshold (60% = 0.6)
            $threshold = 0.6;

            // Similarity check করা
            if ($similarity >= $threshold) {
                // Login করানো
                Auth::login($user);

                // Last login time update করা
                $user->update([
                    'updated_at' => now()
                ]);

                Log::info('User logged in with face verification', [
                    'user_id' => $user->id,
                    'similarity' => $similarity
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Face verified successfully! Welcome back, ' . $user->name,
                    'similarity' => round($similarity * 100, 2),
                    'redirect' => route('dashboard')
                ], 200);
            }

            // Face match না হলে
            Log::warning('Face verification failed', [
                'email' => $validated['email'],
                'similarity' => $similarity
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Face verification failed! Similarity: ' . round($similarity * 100, 2) . '%',
                'similarity' => round($similarity * 100, 2)
            ], 401);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Face verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }

    /**
     * User dashboard দেখাবে
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();

        return view('dashboard', compact('user'));
    }

    /**
     * User logout করবে
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * User এর stored face descriptor return করবে (API endpoint)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFaceDescriptor(Request $request)
    {
        try {
            $email = $request->query('email');

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email parameter required'
                ], 400);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if (!$user->hasFaceVerification()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification not set up for this user'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'descriptor' => $user->face_descriptor, // Already decoded by model accessor
                'face_verified' => $user->face_verified
            ], 200);

        } catch (\Exception $e) {
            Log::error('Get face descriptor error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve face data'
            ], 500);
        }
    }

    /**
     * User এর face verification reset করবে
     * 
     * @return \Illuminate\Http\RedirectResponse
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
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}