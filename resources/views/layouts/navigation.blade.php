    <nav x-data="{ open: false }" class="border-none bg-[#F9F7F0] flex flex-col w-24 p-1 h-full">
             <!-- Logo -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <img src="{{asset('img/letter-u.png')}}" alt="">
                </x-nav-link>

                <!-- Navigation Links -->

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <img src="{{asset('img/receive-mail.png')}}" alt="">
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <img src="{{asset('img/money-exchange.png')}}" alt="">
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <img src="{{asset('img/info.png')}}" alt="">
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <img src="{{asset('img/chat.png')}}" alt="">
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <img src="{{asset('img/user.png')}}" alt=""
                    </x-nav-link>


                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
    </nav>
