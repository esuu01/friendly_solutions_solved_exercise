<x-layouts.importer>
    <div class="flex flex-col">
        <div class="my-8">
            <h1 class="text-white text-3xl font-bold">Work order importer logs</h1>
            <h3 class="text-neutral-400 text-xl">Here you can check all importer logs.</h3>
        </div>

        <div class="px-10 py-8 border-t border-t-neutral-800">
            @if($logs->count() == 0)
                <h2 class="text-center text-white text-2xl">Nothing in database</h2>
            @else
                <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-neutral-800 border-b border-b-neutral-600">
                                <tr>
                                    <th scope="col" class="text-sm font-medium text-neutral-200 px-6 py-4 text-left">
                                        #
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-neutral-200 px-6 py-4 text-left">
                                        Type
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-neutral-200 px-6 py-4 text-left">
                                        Run at
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-neutral-200 px-6 py-4 text-left">
                                        Entries processed
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-neutral-200 px-6 py-4 text-left">
                                        Entries created
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $log)
                                    <tr class="hover:bg-neutral-700 transition-color duration-300 bg-neutral-800 border-b border-b-neutral-600">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-300">
                                            {{ $log->id }}
                                        </td>
                                        <td class="text-sm text-neutral-300 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $log->type }}
                                        </td>
                                        <td class="text-sm text-neutral-300 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $log->run_at }}
                                        </td>
                                        <td class="text-sm text-neutral-300 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $log->entries_processed }}
                                        </td>
                                        <td class="text-sm text-neutral-300 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $log->entries_created }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.importer>