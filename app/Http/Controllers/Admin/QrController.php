<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrToken;
use Illuminate\Http\Request;

class QrController extends Controller
{
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
}
