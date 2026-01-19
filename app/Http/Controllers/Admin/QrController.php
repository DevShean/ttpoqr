<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class QrController extends Controller
{
    protected int $ttlSeconds = 600; // Token expiry in seconds (10 minutes)

    public function index()
    {
        return view('admin.qr');
    }

    public function getTokens(Request $request)
    {
        $tokens = QrToken::with('user.profile')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($token) {
                return [
                    'id' => $token->id,
                    'token' => $token->token,
                    'status' => $token->status,
                    'expires_at' => $token->expires_at,
                    'created_at' => $token->created_at->toIso8601String(),
                    'used_at' => $token->used_at ? $token->used_at->toIso8601String() : null,
                    'user' => [
                        'id' => $token->user->id,
                        'email' => $token->user->email,
                        'profile' => [
                            'fname' => $token->user->profile?->fname ?? 'N/A',
                            'mname' => $token->user->profile?->mname ?? '',
                            'lname' => $token->user->profile?->lname ?? '',
                            'avatar_path' => $token->user->profile?->avatar_path ?? null,
                        ]
                    ]
                ];
            });

        return response()->json([
            'tokens' => $tokens,
            'total' => $tokens->count(),
            'timestamp' => time()
        ]);
    }

    public function checkQrToken(Request $request, User $user)
    {
        // Only allow admin to check QR for regular users
        if (!auth()->check() || auth()->user()->usertype_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->usertype_id != 2) {
            return response()->json(['error' => 'Can only check QR for regular users'], 400);
        }

        // Check if there's an active, non-expired token
        $existingToken = $this->getActiveToken($user->id);

        if ($existingToken) {
            $qrBase64 = $this->makeQrBase64($existingToken->token);
            return response()->json([
                'exists' => true,
                'token' => $existingToken->token,
                'expires_at' => $existingToken->expires_at,
                'qr_svg' => '<img src="' . $qrBase64 . '" alt="QR Code" style="max-width: 300px; height: auto;">',
                'ttl_seconds' => $this->ttlSeconds,
                'download_url' => route('admin.users.downloadQrPdf', ['user' => $user->id, 'token' => $existingToken->token]),
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function generateQr(Request $request, User $user)
    {
        // Only allow admin to generate QR for regular users
        if (!auth()->check() || auth()->user()->usertype_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->usertype_id != 2) {
            return response()->json(['error' => 'Can only generate QR for regular users'], 400);
        }

        // Check if there's an active, non-expired token
        $existingToken = $this->getActiveToken($user->id);
        $isNew = false;
        
        if ($existingToken) {
            $token = $existingToken;
            $message = 'Using existing QR code for ' . ($user->profile?->fname ?? $user->email);
        } else {
            // Generate new QR token
            $token = $this->mintToken($user->id);
            $isNew = true;
            $message = 'QR code generated successfully for ' . ($user->profile?->fname ?? $user->email);
        }

        // Generate QR code as base64 PNG for better PDF compatibility
        $qrBase64 = $this->makeQrBase64($token->token);

        return response()->json([
            'success' => true,
            'token' => $token->token,
            'expires_at' => $token->expires_at,
            'qr_svg' => '<img src="' . $qrBase64 . '" alt="QR Code" style="max-width: 300px; height: auto;">',
            'qr_base64' => $qrBase64,
            'ttl_seconds' => $this->ttlSeconds,
            'download_url' => route('admin.users.downloadQrPdf', ['user' => $user->id, 'token' => $token->token]),
            'message' => $message,
            'is_new' => $isNew
        ]);
    }

    public function downloadQrPdf(Request $request, User $user)
    {
        // Only allow admin
        if (!auth()->check() || auth()->user()->usertype_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = $request->query('token');
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 400);
        }

        // Verify token belongs to user
        $qrToken = QrToken::where('token', $token)
            ->where('user_id', $user->id)
            ->first();

        if (!$qrToken) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        // Generate QR code as base64 PNG
        $qrBase64 = $this->makeQrBase64($token);
        $userName = ($user->profile?->fname ?? 'User') . ' ' . ($user->profile?->lname ?? '');
        $validationUrl = route('qr.validate', ['token' => $token]);

        // Create HTML content for PDF with base64 image
        $html = view('admin.qr-pdf', [
            'qrBase64' => $qrBase64,
            'token' => $token,
            'userName' => trim($userName),
            'userEmail' => $user->email,
            'validationUrl' => $validationUrl,
            'expiresAt' => $qrToken->expires_at,
        ])->render();

        // Generate PDF using Dompdf
        $pdf = Pdf::loadHTML($html);
        $fileName = 'QR-Code-' . str_replace(' ', '-', trim($userName)) . '-' . date('Y-m-d-His') . '.pdf';

        return $pdf->download($fileName);
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

    protected function makeQrSvg(string $token): string
    {
        $validationUrl = route('qr.validate', ['token' => $token]);
        return QrCode::size(200)->margin(0)->generate($validationUrl);
    }

    protected function makeQrBase64(string $token): string
    {
        $validationUrl = route('qr.validate', ['token' => $token]);
        // Generate SVG and encode as data URI (works better with dompdf)
        $qrCode = QrCode::size(300)->margin(1)->generate($validationUrl);
        return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
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
}
