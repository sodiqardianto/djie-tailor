{{-- <style>
/* Base container styles */
.collar-selector {
    display: flex;
    gap: 2rem;
    padding: 1.5rem;
    flex-wrap: wrap;
}

/* Label container */
.collar-option {
    cursor: pointer;
    position: relative;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
}

/* Option container */
.collar-option-container {
    position: relative;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    width: 250px;  /* Increased from 180px */
    height: 250px; /* Increased from 180px */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    transition: all 0.2s ease-in-out;
}

/* SVG container */
.collar-svg {
    width: 220px;  /* Increased from 140px */
    height: 220px; /* Increased from 140px */
    display: flex;
    align-items: center;
    justify-content: center;
}

/* SVG scaling */
.collar-svg img {
    width: 100%;
    height: 100%;
    transform: scale(1.2); /* Additional scaling for the SVG itself */
}

/* Hide radio input */
.collar-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Hover states */
.collar-option-container:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Selected state */
.collar-input:checked + .collar-option-container {
    border-color: #3b82f6;
    background-color: #eff6ff;
    box-shadow: 0 0 0 2px #3b82f6;
}

/* Focus state for accessibility */
.collar-input:focus + .collar-option-container {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .collar-option-container {
        background-color: #1f2937;
        border-color: #374151;
    }
    
    .collar-input:checked + .collar-option-container {
        background-color: #acacac;
        border-color: #60a5fa;
    }
}

</style>

<div class="collar-selector">
    @foreach (\App\Models\Collar::all() as $collar)
        <label class="collar-option">
            <input
                type="radio"
                name="collar_model"
                value="{{ $collar->id }}"
                class="collar-input"
            />
            <div class="collar-option-container">
                <div class="collar-svg">
                    <img src="{{ asset('storage/' . $collar->image) }}" alt="{{ $collar->name }}" />
                </div>
            </div>
        </label>
    @endforeach
</div> --}}

<style>
    .collar-svg img {
        width: 100%;
        height: 100%;
        transform: scale(1.2); /* Additional scaling for the SVG itself */
    }
</style>

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach (\App\Models\Collar::all() as $collar)
            <label class="relative cursor-pointer hover:border-red-500">
                <input
                    type="radio"
                    value="{{ $collar->id }}"
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                    class="peer sr-only"
                />
                
                <div class="rounded-lg border-2 border-gray-200 p-2 hover:border-primary-500 peer-checked:border-primary-500 peer-checked:ring-1 peer-checked:ring-primary-500">
                    <div class="flex flex-col items-center">
                        <img
                            src="{{ Storage::url($collar->image) }}"
                            alt="{{ $collar->name }}"
                            class="w-64 h-24 object-cover mb-2"
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