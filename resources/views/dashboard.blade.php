<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-navbar />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <div class="flex flex-wrap gap-3">

                    <x-table>

    <tr class="*:text-gray-900 *:first:font-medium">
        <td class="px-3 py-2 whitespace-nowrap">
            Nandor
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            04/06/1262
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            Vampire Warrior
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            $0
        </td>
    </tr>

    <tr class="*:text-gray-900 *:first:font-medium">
        <td class="px-3 py-2 whitespace-nowrap">
            Laszlo
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            19/10/1678
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            Vampire Gentleman
        </td>

        <td class="px-3 py-2 whitespace-nowrap">
            $0
        </td>
    </tr>

</x-table>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
