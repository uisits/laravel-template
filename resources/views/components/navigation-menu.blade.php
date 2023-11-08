<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-mark class="block h-9 w-auto"/>
                    </a>
                    <a href="{{ route('home') }}">{{ env('APP_NAME') }}</a>
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @if(auth()->check())
                    @if (auth()->user()->can('admin'))
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            Admin Actions
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <!-- User Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('User') }}
                                        </div>
                                        <x-dropdown-link class="flex items-center" href="{{ route('user.index') }}">
                                            <x-slot name="icon">
                                                <span class="pr-2 material-icons">group</span>
                                            </x-slot>
                                            {{ __('Users') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link class="flex items-center" href="{{ route('role.index') }}">
                                            <x-slot name="icon">
                                                <span class="pr-2 material-icons">settings</span>
                                            </x-slot>
                                            {{ __('Roles') }}
                                        </x-dropdown-link>
                                        @if(auth()->user()->can('super admin'))
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('App Management') }}
                                            </div>
                                            <x-dropdown-link class="flex items-center" target="_blank" href="{{ route('telescope') }}">
                                                <x-slot name="icon">
                                                    <span class="pr-2 material-icons">tune</span>
                                                </x-slot>
                                                {{ __('Laravel Telescope') }}
                                            </x-dropdown-link>
    {{--                                        <x-dropdown-link target="_blank" href="{{ route('horizon.index') }}">--}}
    {{--                                            <x-slot name="icon">--}}
    {{--                                                <span class="pr-2 material-icons">dns</span>--}}
    {{--                                            </x-slot>--}}
    {{--                                            {{ __('Horizon') }}--}}
    {{--                                        </x-dropdown-link>--}}
                                        @endcan
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                @endif

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    @if (auth()->check())
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ auth()->user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ url('/') }}/help">
                                    {{ __('Help') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ url('/') }}/feedback">
                                    {{ __('Feedback') }}
                                </x-dropdown-link>
                                @if(auth()->user()->isImpersonating())
                                    <form action="{{ route('impersonate.destroy', auth()->user()->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="delete">
                                        <x-button type="submit">
                                            {{ __('Stop Impersonation') }}
                                        </x-button>
                                    </form>
                                @endif
                                <x-dropdown-link href="{{ route('logout') }}">
                                    {{ __('Logout') }}
                                </x-dropdown-link>
                            </x-slot>

                        </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 p-2 items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            @if(auth()->check())
                @if(auth()->user()->isImpersonating())
                    <form action="{{ route('impersonate.destroy', auth()->user()->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete">
                        <x-button type="submit">
                            {{ __('Stop Impersonation') }}
                        </x-button>
                    </form>
                @endif
                @can('admin')
                    <x-responsive-nav-link href="{{ route('user.index') }}" :active="request()->routeIs('user.index')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                        @can('super admin')
                            <x-responsive-nav-link href="{{ route('role.index') }}" :active="request()->routeIs('role.index')">
                                {{ __('Roles') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('telescope') }}">
                                <x-slot name="icon">
                                    <span class="material-icons">page_info</span>
                                </x-slot>
                                {{ __('Telescope') }}
                            </x-responsive-nav-link>
                        @endcan
                    <x-responsive-nav-link href="{{ url('/') }}/help">
                        {{ __('Help') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ url('/') }}/feedback">
                        {{ __('Feedback') }}
                    </x-responsive-nav-link>
                @endcan
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @if(auth()->check())
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('logout') }}">
                        {{ __('Logout') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endif
    </div>
</nav>
