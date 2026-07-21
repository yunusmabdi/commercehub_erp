<!DOCTYPE html>
<html>

<head>
    <title>CommerceHub POS Login</title>

    @vite(['resources/css/app.css'])

</head>


<body class="min-h-screen bg-[#F8F1E7] flex items-center justify-center">


<div class="w-full max-w-md">


    <!-- Logo / Header -->

    <div class="text-center mb-6">

        <div class="inline-flex items-center justify-center
                    w-20 h-20
                    rounded-full
                    bg-[#0F172A]
                    text-white
                    text-4xl
                    shadow-lg">

            🏪

        </div>


        <h1 class="mt-4 text-3xl font-bold text-[#0F172A]">
            CommerceHub POS
        </h1>


        <p class="text-[#0F172A] mt-2 font-medium">
            Cashier Login
        </p>

    </div>



    <!-- Login Card -->

    <div class="bg-[#0F172A]
                rounded-2xl
                shadow-xl
                p-8">


        <form method="POST" action="{{ route('pos.login.submit') }}">

            @csrf


            <div class="mb-5">

                <label class="block text-white mb-2 font-medium">
                    Email
                </label>


                <input
                    type="email"
                    name="email"
                    placeholder="cashier@example.com"
                    class="w-full
                           rounded-xl
                           px-4
                           py-3
                           bg-[#F8F1E7]
                           text-[#0F172A]
                           placeholder-[#64748B]
                           border-0
                           focus:ring-2
                           focus:ring-white">

            </div>



            <div class="mb-6">

                <label class="block text-white mb-2 font-medium">
                    Password
                </label>


                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full
                           rounded-xl
                           px-4
                           py-3
                           bg-[#F8F1E7]
                           text-[#0F172A]
                           placeholder-[#64748B]
                           border-0
                           focus:ring-2
                           focus:ring-white">

            </div>




            <button
                class="w-full
                       bg-white
                       text-[#0F172A]
                       font-bold
                       py-3
                       rounded-xl
                       hover:bg-[#F8F1E7]
                       transition">

                Login

            </button>



        </form>


    </div>


    <p class="text-center text-[#0F172A] text-sm mt-6 font-medium">

        © {{ date('Y') }} CommerceHub ERP

    </p>


</div>


</body>

</html>