<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Building Tracker Login · JKD PINNacle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-ink-950 p-5 text-ink-100">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-display text-2xl font-bold text-white">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-brand-500 text-ink-950">J</span>
                <span>JKD <span class="text-brand-400">PINNACLE CONSTRUCTION</span></span>
            </a>
            <p class="mt-2 text-sm text-ink-400">Building Tracker — sign in to view your project updates</p>
        </div>

        <div class="surface p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.login.post') }}">
                @csrf
                <div class="mb-4">
                    <label class="field-label" for="email">Email</label>
                    <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" class="field">
                </div>
                <div class="mb-6">
                    <label class="field-label" for="password">Password</label>
                    <input id="password" name="password" type="password" required class="field">
                </div>
                <button type="submit" class="btn-primary btn w-full">Sign in</button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-ink-500">
            <a href="{{ url('/') }}" class="hover:text-brand-300">&larr; Back to website</a>
        </p>
    </div>
</body>
</html>
