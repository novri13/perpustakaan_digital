<x-filament::page>
    <h2 class="text-xl font-bold mb-4">📢 Notifikasi Anda</h2>

    @php
        $notifications = auth()->user()->notifications()->latest()->get();
    @endphp

    @if ($notifications->isEmpty())
        <div class="p-4 text-center text-gray-500">
            ✅ Tidak ada notifikasi baru
        </div>
    @else
        <div class="space-y-4">
            @foreach ($notifications as $notif)
                <div class="p-4 border rounded-lg @if($notif->read_at) bg-gray-50 @else bg-white shadow @endif">
                    <p class="text-sm">
                        {!! $notif->data['message'] !!}
                    </p>
                    <small class="text-gray-500">
                        {{ $notif->created_at->diffForHumans() }}
                    </small>

                    @if(!$notif->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="mt-2">
                            @csrf
                            <x-filament::button type="submit" size="xs" color="primary">
                                Tandai sudah dibaca
                            </x-filament::button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
