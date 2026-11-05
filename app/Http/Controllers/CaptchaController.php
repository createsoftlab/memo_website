<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    // ==========================================
    // 1. GENERATE OFFLINE CAPTCHA IMAGE
    // ==========================================

    public function image(Request $request)
    {
        $key = 'captcha-image:' . $request->ip();

        abort_if(
            RateLimiter::tooManyAttempts($key, 30),
            429,
            'Too many CAPTCHA requests.'
        );

        RateLimiter::hit($key, 60);

        // Check GD extension
        if (
            !extension_loaded('gd') ||
            !function_exists('imagecreatetruecolor')
        ) {
            abort(500, 'PHP GD extension is not enabled.');
        }

        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[
                random_int(0, strlen($characters) - 1)
            ];
        }

        $width = 190;
        $height = 60;

        $image = \imagecreatetruecolor($width, $height);

        if ($image === false) {
            abort(500, 'Failed to create CAPTCHA image.');
        }

        $background = \imagecolorallocate(
            $image, 248, 246, 240
        );

        $foreground = \imagecolorallocate(
            $image, 65, 48, 35
        );

        $noise = \imagecolorallocate(
            $image, 190, 177, 155
        );

        \imagefill($image, 0, 0, $background);

        // Random lines
        for ($i = 0; $i < 7; $i++) {
            \imageline(
                $image,
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                $noise
            );
        }

        // CAPTCHA characters
        for ($i = 0; $i < strlen($code); $i++) {
            \imagestring(
                $image,
                5,
                15 + ($i * 28),
                random_int(17, 23),
                $code[$i],
                $foreground
            );
        }

        // Noise dots
        for ($i = 0; $i < 100; $i++) {
            \imagesetpixel(
                $image,
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                $noise
            );
        }

        // Generate PNG safely
        ob_start();

        try {
            $generated = \imagepng($image);
            $png = ob_get_contents();
        } finally {
            ob_end_clean();
            \imagedestroy($image);
        }

        if (!$generated || !$png) {
            abort(500, 'Failed to generate CAPTCHA PNG.');
        }

        // Store CAPTCHA after successful generation
        $request->session()->put(
            'registration_captcha',
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(5)->timestamp,
            ]
        );

        // Invalidate previous verification
        $request->session()->forget(
            'registration_captcha_verified'
        );

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // ==========================================
    // 2. VERIFY CAPTCHA
    // ==========================================

    public function verify(Request $request)
    {
        $request->validate([
            'captcha' => [
                'required',
                'string',
                'size:6',
            ],
        ]);

        $key = 'captcha-verify:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'valid' => false,
                'message' => 'Too many attempts. Please try again later.',
            ], 429);
        }

        $stored = $request->session()->get(
            'registration_captcha'
        );

        $valid = is_array($stored)
            && isset(
                $stored['code'],
                $stored['expires_at']
            )
            && now()->timestamp <= $stored['expires_at']
            && hash_equals(
                $stored['code'],
                strtoupper(trim($request->captcha))
            );

        if (!$valid) {
            RateLimiter::hit($key, 300);

            return response()->json([
                'valid' => false,
                'message' => 'Incorrect or expired CAPTCHA.',
            ], 422);
        }

        RateLimiter::clear($key);

        $request->session()->forget(
            'registration_captcha'
        );

        $request->session()->put(
            'registration_captcha_verified',
            [
                'expires_at' => now()->addMinutes(5)->timestamp,
            ]
        );

        return response()->json([
            'valid' => true,
            'message' => 'CAPTCHA verified successfully.',
        ]);
    }
}
