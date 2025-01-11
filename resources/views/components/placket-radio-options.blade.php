<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach (\App\Models\Placket::all() as $placket)
            <label class="relative cursor-pointer">
                <input
                    type="radio"
                    value="{{ $placket->id }}"
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                    class="peer sr-only"
                />
                
                <div class="rounded-lg border-2 border-gray-200 p-2 hover:border-primary-500 peer-checked:border-primary-500 peer-checked:ring-1 peer-checked:ring-primary-500">
                    <div class="flex flex-col items-center">
                        <img
                            src="{{ Storage::url($placket->image) }}"
                            alt="{{ $placket->name }}"
                            class="w-24 h-32 object-cover mb-2 flex items-center"
                        />
                    </div>
                </div>
            </label>
        @endforeach
    </div>
    
    @if ($errors->has($getStatePath()))
        <p class="text-sm text-danger-600 mt-1">{{ $errors->first($getStatePath()) }}</p>
    @endif
</x-dynamic-component>