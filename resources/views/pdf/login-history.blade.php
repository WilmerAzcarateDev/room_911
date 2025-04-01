<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <table class="w-full">
        <thead class="bg-slate-400">
            <tr>
                <th class="py-2 px-4">Date</th>
                <th class="py-2 px-4">Ip</th>
                <th class="py-2 px-4">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($login_attempts as $la)
                <tr class="{{ $loop->odd ? 'bg-slate-200' : '' }}">
                    <td class="py-1 px-2">{{ $la->created_at }}</td>
                    <td class="py-1 px-2">{{ $la->ip }}</td>
                    <td class="py-1 px-2">
                        @switch($la->status)
                            @case('succesfull')
                                <span class="text-green-600">{{ $la->status }}</span>
                                @break

                            @case('failed')
                                <span class="text-red-600">{{ $la->status }}</span>
                                @break

                            @case('unidentified')
                                <span class="text-yellow-600">{{ $la->status }}</span>
                                @break
                        
                            @default
                                @break
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-2xl">No login attempts</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>