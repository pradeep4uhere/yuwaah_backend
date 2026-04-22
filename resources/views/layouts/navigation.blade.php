<style>
img, video {
max-width: 56%;
height: auto;
}
</style>
<nav x-data="{ open: false }" class="premium-navbar">
    <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center min-h-[78px]">

            <!-- Left Section -->
            <div class="flex items-center gap-4 lg:gap-8">
                
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="premium-logo-wrap">
                        <x-application-logo class="block h-10 w-auto fill-current text-indigo-700" />
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex items-center gap-2 lg:gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="premium-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('profile.allevent') }}"
                       class="premium-nav-link {{ request()->routeIs('profile.allevent') ? 'active' : '' }}">
                        Events
                    </a>

                    <a href="{{ route('profile.alllearnerevent') }}"
                       class="premium-nav-link {{ request()->routeIs('profile.alllearnerevent') ? 'active' : '' }}">
                        Learners Events
                    </a>

                    <a href="{{ route('profile.alllearner') }}"
                       class="premium-nav-link {{ request()->routeIs('profile.alllearner') ? 'active' : '' }}">
                        Learner
                    </a>
                    <a href="{{ route('profile.yuthhubapilist') }}"
                       class="premium-nav-link {{ request()->routeIs('allusers') ? 'active' : '' }}">
                        Yuthhub API
                    </a>

                    <a href="{{ route('profile.allusers') }}"
                       class="premium-nav-link {{ request()->routeIs('allusers') ? 'active' : '' }}">
                        Users
                    </a>
                </div>
            </div>

            <!-- Right Section -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="premium-user-btn">
                            <div class="premium-user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <div class="text-start">
                                <div class="premium-user-name">{{ Auth::user()->name }}</div>
                                <div class="premium-user-role">Administrator</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="premium-mobile-btn">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden premium-mobile-menu">
        <div class="px-4 pt-3 pb-3 space-y-2">
            <a href="{{ route('dashboard') }}" class="premium-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('profile.allevent') }}" class="premium-mobile-link {{ request()->routeIs('profile.allevent') ? 'active' : '' }}">Events</a>
            <a href="{{ route('profile.alllearnerevent') }}" class="premium-mobile-link {{ request()->routeIs('profile.alllearnerevent') ? 'active' : '' }}">Learners Events</a>
            <a href="{{ route('profile.alllearner') }}" class="premium-mobile-link {{ request()->routeIs('profile.alllearner') ? 'active' : '' }}">Learner</a>
            <a href="{{ route('profile.allusers') }}" class="premium-mobile-link {{ request()->routeIs('allusers') ? 'active' : '' }}">Users</a>
        </div>

        <div class="border-t border-slate-200 px-4 py-4">
            <div class="mb-3">
                <div class="font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('profile.edit') }}" class="premium-mobile-link">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       class="premium-mobile-link"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>