<x-filament::page>
    <div class="max-w-3xl mx-auto space-y-6">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
            <x-filament::button type="submit" class="w-full">Simpan Perubahan</x-filament::button>
        </form>
    </div>
</x-filament::page>
