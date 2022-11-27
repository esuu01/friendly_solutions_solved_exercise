<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work order importer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body, html, * {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="bg-neutral-900 flex flex-col">
<header class="container mx-auto py-6 border-b border-b-neutral-800">
    <nav class="flex justify-center">
        <ul class="flex gap-4">
            <li>
                <a
                    href="{{ route("importers.index") }}"
                    class="py-2 px-4 rounded-md text-white transition-colors duration-300 {{ \Illuminate\Support\Facades\Route::current()->getName() == "importers.index" ? " hover:bg-neutral-500/30 bg-neutral-500/40" : " hover:bg-neutral-500/40" }}"
                >
                    Importer
                </a>
            </li>

            <li>
                <a href="{{ route("importers.logs") }}" class="py-2 px-4 rounded-md text-white transition-colors duration-300 {{ \Illuminate\Support\Facades\Route::current()->getName() == "importers.logs" ? " hover:bg-neutral-500/30 bg-neutral-500/40" : " hover:bg-neutral-500/40" }}">
                    Logs
                </a>
            </li>
        </ul>
    </nav>
</header>

<main class="container mx-auto mt-4">
    {{ $slot }}
</main>

<footer>

</footer>
</body>
</html>