<?php

namespace App\Http\Controllers;

use App\Models\VaultItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VaultController extends Controller
{
    /**
     * Is the vault currently unlocked in this session?
     */
    private function isUnlocked(): bool
    {
        $until = session('vault_until');

        return $until && now()->timestamp < $until;
    }

    /**
     * Check the password.
     */
    private function checkPassword(Request $request): bool
    {
        $hash = (string) config('vault.hash');

        return $hash !== '' && Hash::check((string) $request->password, $hash);
    }

    /**
     * Unlock the vault for the next X minutes.
     */
    private function unlock(Request $request): void
    {
        $request->session()->regenerate();

        session([
            'vault_until' => now()->addMinutes(config('vault.timeout'))->timestamp,
        ]);
    }

    /**
     * Show the password page (the secret URL).
     */
    public function loginForm()
    {
        if ($this->isUnlocked()) {
            return redirect()->route('vault.home');
        }

        return view('vault.login');
    }

    /**
     * Password submitted from the secret URL page.
     */
    public function attempt(Request $request)
    {
        if (! $this->checkPassword($request)) {
            return back()->withErrors(['password' => 'Wrong password.']);
        }

        $this->unlock($request);

        return redirect()->route('vault.home');
    }

    /**
     * Password submitted from the hidden trigger on the profile page.
     * Returns JSON. The vault address is only sent back if the password is right.
     */
    public function quick(Request $request)
    {
        if (! config('vault.path') || ! $this->checkPassword($request)) {
            return response()->json(['ok' => false], 422);
        }

        $this->unlock($request);

        return response()->json([
            'ok'  => true,
            'url' => route('vault.home'),
        ]);
    }

    /**
     * The vault: images only.
     */
    public function home()
    {
        return view('vault.home', [
            'items' => VaultItem::latest()->get(),
        ]);
    }

    /**
     * Upload one or more images (encrypted before saving).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'image|max:8192',
        ]);

        foreach ($request->file('images') as $file) {
            $name = 'vault/' . Str::random(40) . '.bin';

            Storage::disk('local')->put(
                $name,
                Crypt::encryptString(file_get_contents($file->getRealPath()))
            );

            VaultItem::create([
                'file' => $name,
                'mime' => $file->getMimeType(),
            ]);
        }

        return back();
    }

       /**
     * Decrypt and show one image.
     */
    public function image(VaultItem $item)
    {
        try {
            $data = Crypt::decryptString(Storage::disk('local')->get($item->file));
        } catch (\Throwable $e) {
            abort(404);
        }

        // throw away anything printed before the image (PHP notices, spaces, etc.)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        return response($data, 200, [
            'Content-Type'   => $item->mime ?: 'image/jpeg',
            'Content-Length' => strlen($data),
            'Cache-Control'  => 'no-store, private',
            'X-Robots-Tag'   => 'noindex',
        ]);
    }
    /**
     * Delete one image (file + database row).
     */
    public function delete(VaultItem $item)
    {
        Storage::disk('local')->delete($item->file);
        $item->delete();

        return back();
    }

    /**
     * Lock the vault right now.
     */
    public function lock()
    {
        session()->forget('vault_until');

        return redirect('/');
    }
}