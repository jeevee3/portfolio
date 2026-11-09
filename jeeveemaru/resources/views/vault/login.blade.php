<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Notes</title>
    <style>
        body { margin:0; min-height:100vh; display:grid; place-items:center; background:#111; color:#eee; font-family:sans-serif; }
        form { display:flex; flex-direction:column; gap:10px; width:260px; }
        input, button { padding:12px; border:0; border-radius:6px; font-size:15px; }
        button { background:#516acc; color:#fff; cursor:pointer; }
        .err { color:#ff9a9a; font-size:13px; margin:0; }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('vault.attempt') }}">
        @csrf
        <input type="password" name="password" placeholder="Password" autofocus required>
        @error('password') <p class="err">{{ $message }}</p> @enderror
        <button type="submit">Unlock</button>
    </form>
</body>
</html>