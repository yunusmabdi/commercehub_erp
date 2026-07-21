<!DOCTYPE html>
<html>
<head>
    <title>CommerceHub POS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-amber-50 text-gray-900">

<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">
        CommerceHub POS
    </h1>

    <div class="grid grid-cols-12 gap-6">

        <div class="col-span-8">

            <livewire:p-o-s.product-search />

            <livewire:p-o-s.product-grid />

        </div>


        <div class="col-span-4">

            <livewire:p-o-s.shopping-cart />

        </div>

    </div>

</div>


@livewireScripts

</body>
</html>