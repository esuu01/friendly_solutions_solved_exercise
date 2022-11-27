<x-layouts.importer>
    <div class="flex flex-col">
        <div class="my-8">
            <h1 class="text-white text-3xl font-bold">Work order importer</h1>
            <h3 class="text-neutral-400 text-xl">Select file with work orders and press submit button. After that action you will receive CSV report of which data was pushed to database.</h3>
        </div>

        <div class="px-10 py-8 border-t border-t-neutral-800">
            <form method="POST" action="{{ route("importers.store") }}" enctype="multipart/form-data">
                <div class="flex flex-col pb-8 border-b border-b-neutral-800">
                    <label for="work_order" class="text-neutral-200 text-lg mb-4">Select file to parse</label>
                    <input type="file" name="work_order" id="work_order" class=" text-neutral-400" accept="text/html" />
                </div>

                <button type="submit" class="mt-8 py-2 px-4 rounded-md text-white transition-colors duration-300 hover:bg-neutral-500/30 bg-neutral-500/40">
                    Submit file
                </button>
            </form>
        </div>
    </div>
</x-layouts.importer>