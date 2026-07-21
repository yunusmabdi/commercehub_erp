<!DOCTYPE html>
<html>

<head>
    <title>CommerceHub POS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>


<body class="min-h-screen bg-[#F8F1E7] text-[#0F172A]">


<div class="p-6">


    <!-- POS Navbar -->

    <nav class="bg-[#0F172A]
                rounded-2xl
                shadow-lg
                p-5
                mb-6
                flex
                items-center
                justify-between">


        <!-- Logo -->

        <div class="flex items-center gap-4">


            <div class="bg-white
                        w-14
                        h-14
                        rounded-xl
                        flex
                        items-center
                        justify-center
                        text-3xl">

                🏪

            </div>



            <div>

                <h1 class="text-3xl font-bold text-white">
                    CommerceHub POS
                </h1>


                <p class="text-[#F8F1E7]">
                    Welcome!
                </p>


                <span class="inline-block
                             mt-1
                             bg-white
                             text-[#0F172A]
                             px-3
                             py-1
                             rounded-full
                             text-xs
                             font-bold">

                    Cashier

                </span>


            </div>


        </div>




        <!-- Logout -->

        <form method="POST" action="{{ route('pos.logout') }}">

            @csrf


            <button
                class="
                bg-white
                text-[#0F172A]
                px-6
                py-3
                rounded-xl
                font-bold
                shadow
                hover:bg-[#F8F1E7]
                transition">


                Logout


            </button>


        </form>


    </nav>





    <!-- POS Workspace -->


    <div class="grid grid-cols-12 gap-6">


        <!-- Products -->

        <div class="col-span-8">


            <div class="bg-white
                        rounded-2xl
                        shadow
                        p-5
                        mb-5">


                <livewire:p-o-s.product-search />


            </div>



            <div class="bg-white
                        rounded-2xl
                        shadow
                        p-5">


                <livewire:p-o-s.product-grid />


            </div>


        </div>





        <!-- Cart -->


        <div class="col-span-4">


            <div class="bg-[#0F172A]
                        rounded-2xl
                        shadow-xl
                        p-5
                        text-white">


                <livewire:p-o-s.shopping-cart />


            </div>


        </div>


    </div>



</div>


@livewireScripts

</body>

</html>