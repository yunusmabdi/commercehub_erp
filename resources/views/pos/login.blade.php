<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CommerceHub POS Login</title>

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center px-6">

<div class="w-full max-w-md">

    <!-- Logo -->

    <div class="text-center mb-8">

        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#0F172A] shadow-xl">

            <span class="text-4xl">🏪</span>

        </div>

        <h1 class="mt-6 text-4xl font-bold tracking-tight text-[#0F172A]">
            CommerceHub
        </h1>

        <p class="mt-2 text-slate-500">
            Point of Sale System
        </p>

    </div>

    <!-- Card -->

    <div class="rounded-3xl bg-white border border-slate-200 shadow-2xl p-8">

        <div class="mb-8">

            <h2 class="text-2xl font-bold text-slate-900">
                Welcome Back
            </h2>

            <p class="mt-1 text-slate-500">
                Sign in to continue to your POS terminal.
            </p>

        </div>

        <form method="POST" action="{{ route('pos.login.submit') }}" class="space-y-6">

            @csrf

            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="cashier@example.com"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-[#0F172A] focus:ring-4 focus:ring-slate-200">

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 outline-none transition focus:border-[#0F172A] focus:ring-4 focus:ring-slate-200">

                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-[#0F172A] py-3 font-semibold text-white shadow-lg transition duration-200 hover:bg-slate-800 hover:shadow-xl">

                Sign In

            </button>

        </form>

    </div>

    <p class="mt-8 text-center text-sm text-slate-500">

        © {{ date('Y') }} CommerceHub ERP • Powered by @Abdi

    </p>

</div>

</body>

</html>