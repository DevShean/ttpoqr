<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class QrController extends Controller
{
    private int $ttlSeconds = 60;

    public function show(Request $request)
    {
        $userId = auth()->id();
        $activeToken = $this->getActiveToken($userId);

        return view('user.generate_qr', [
            'ttlSeconds' => $this->ttlSeconds,
            'qrSvg' => $activeToken ? $this->makeSvg(route('qr.validate', ['token' => $activeToken->token])) : null,
            'expiresAt' => $activeToken?->expires_at,
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

    public function refresh(Request $request)
    {
        $userId = auth()->id();
        
        // Mark any expired tokens as expired
        QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '<=', time())
            ->update(['status' => 'expired']);
        
        $activeToken = $this->getActiveToken($userId);

        if ($activeToken) {
            return response()->json([
                'token' => $activeToken->token,
                'qr_svg' => $this->makeSvg(route('qr.validate', ['token' => $activeToken->token])),
                'expires_at' => $activeToken->expires_at,
                'reused' => true,
            ]);
        }

        $newToken = $this->mintToken($userId);
        return response()->json([
            'token' => $newToken->token,
            'qr_svg' => $this->makeSvg(route('qr.validate', ['token' => $newToken->token])),
            'expires_at' => $newToken->expires_at,
            'reused' => false,
        ]);
    }

    public function validateToken(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Missing token parameter.',
                'user_id' => null,
            ]);
        }

        $qrToken = QrToken::where('token', $token)->first();

        if (!$qrToken) {
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Invalid or expired token.',
                'user_id' => null,
            ]);
        }

        // Check if token is expired by time
        if ($qrToken->expires_at <= time()) {
            $qrToken->update(['status' => 'expired']);
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Invalid or expired token.',
                'user_id' => null,
            ]);
        }

        // Check if token has already been used
        if ($qrToken->used_at) {
            return view('user.qr_validation', [
                'valid' => false,
                'reason' => 'Token already used.',
                'user_id' => $qrToken->user_id,
            ]);
        }

        // Token is valid - mark it as used and also mark as expired since it's been validated
        $qrToken->update([
            'used_at' => now(),
            'status' => 'expired',
        ]);

        return view('user.qr_validation', [
            'valid' => true,
            'reason' => null,
            'user_id' => $qrToken->user_id,
        ]);
    }

    private function mintToken(int $userId): QrToken
    {
        // Mark other active tokens as expired for this user
        QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $token = Str::random(40);
        $expiresAtTs = now()->addSeconds($this->ttlSeconds)->getTimestamp();

        return QrToken::create([
            'user_id' => $userId,
            'token' => $token,
            'status' => 'active',
            'expires_at' => $expiresAtTs,
        ]);
    }

    private function getActiveToken(int $userId): ?QrToken
    {
        // First, mark any expired tokens as expired status
        QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '<=', time())
            ->update(['status' => 'expired']);

        return QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '>', time())
            ->whereNull('used_at')
            ->latest()
            ->first();
    }

    public function checkStatus(Request $request)
    {
        $userId = auth()->id();
        
        // Mark any expired tokens as expired
        QrToken::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '<=', time())
            ->update(['status' => 'expired']);
        
        $activeToken = $this->getActiveToken($userId);

        if ($activeToken) {
            return response()->json([
                'has_active' => true,
                'token' => $activeToken->token,
                'expires_at' => $activeToken->expires_at,
                'time_remaining' => $activeToken->expires_at - time(),
            ]);
        }

        return response()->json([
            'has_active' => false,
            'token' => null,
            'expires_at' => null,
            'time_remaining' => 0,
        ]);
    }

    private function makeSvg(string $text): string
    {
        $renderer = new ImageRenderer(new RendererStyle(280), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        return $writer->writeString($text);
    }
}

