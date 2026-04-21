<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
    <div class="flex justify-center">
        <div class="premium-welcome-card text-center">

            <!-- Welcome Text -->
            <h2 class="premium-welcome-title">
                Welcome Back 👋
            </h2>

            <p class="premium-welcome-subtitle">
                Manage your learners, events and system from one place.
            </p>

            <!-- Image -->
            <div class="mt-4">
                <img src="{{ asset('hello.svg') }}" class="premium-welcome-img" />
            </div>

        </div>
    </div>
</div>
</x-app-layout>
