<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrToken;

class QrController extends Controller
{
    protected int $ttlSeconds = 60; // Token expiry in seconds

    public function showGenerate()
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return redirect('/');
        }
        $userId = Auth::id();
        $activeToken = $this->getActiveToken($userId);

        if ($activeToken) {
            $qrSvg = $this->makeQrSvg($activeToken->token);
            return view('user.generate_qr', [
                'qrSvg' => $qrSvg,
                'token' => $activeToken->token,
                'expiresAt' => $activeToken->expires_at,
                'ttlSeconds' => $this->ttlSeconds,
            ]);
        }

        return view('user.generate_qr', [
            'qrSvg' => null,
            'token' => null,
            'expiresAt' => null,
            'ttlSeconds' => $this->ttlSeconds,
        ]);
    }

    public function refresh(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $userId = Auth::id();
        $activeToken = $this->getActiveToken($userId);
        
        if ($activeToken) {
            $qrSvg = $this->makeQrSvg($activeToken->token);
            return response()->json([
                'token' => $activeToken->token,
                'expires_at' => $activeToken->expires_at,
                'qr_svg' => $qrSvg,
                'ttl_seconds' => $this->ttlSeconds,
                'reused' => true,
            ]);
        }

        $token = $this->mintToken($userId);
        $qrSvg = $this->makeQrSvg($token->token);
        return response()->json([
            'token' => $token->token,
            'expires_at' => $token->expires_at,
            'qr_svg' => $qrSvg,
            'ttl_seconds' => $this->ttlSeconds,
            'reused' => false,
        ]);
    }

    public function validateToken($token)
    {
        $qrToken = QrToken::where('token', $token)->first();

        if (!$qrToken) {
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Token invalid or expired.',
            ]);
        }

        if ($qrToken->isExpired()) {
            $qrToken->update(['status' => 'expired']);
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Token invalid or expired.',
            ]);
        }

        if ($qrToken->used_at) {
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Token already used.',
            ]);
        }

        $qrToken->update(['used_at' => now()]);

        return view('user.qr_validation', [
            'valid' => true,
            'reason' => 'Token valid.',
            'user_id' => $qrToken->user_id,
        ]);
    }

    public function expire(Request $request)
    {
        $token = $request->input('token');
        if ($token) {
            QrToken::where('token', $token)->update(['status' => 'expired']);
        }
        return response()->json(['success' => true]);
    }

    protected function mintToken(int $userId): QrToken
    {
        // Mark other active tokens as expired
        QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $token = Str::random(40);
        $expiresAt = now()->addSeconds($this->ttlSeconds)->timestamp;
        
        return QrToken::create([
            'user_id' => $userId,
            'token' => $token,
            'status' => 'active',
            'expires_at' => $expiresAt,
        ]);
    }

    protected function getActiveToken(int $userId): ?QrToken
    {
        $token = QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '>', time())
            ->whereNull('used_at')
            ->latest()
            ->first();

        return $token;
    }

    protected function makeQrSvg(string $token): string
    {
        $validationUrl = route('qr.validate', ['token' => $token]);
        return QrCode::size(200)->margin(0)->generate($validationUrl);
    }
}
