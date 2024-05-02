    <nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 border-none bg-[#F9F7F0] flex flex-col w-24 p-1 h-screen">

        <a class="h-screen p-3" href="{{ route('accueil') }}"><img src="{{asset('img/letter-u.png')}}" alt=""></a>
        <a class="h-screen p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/receive-mail.png')}}" alt=""></a>
        <a class="h-screen p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/money-exchange.png')}}" alt=""></a>
        <a class="h-screen p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/info.png')}}" alt=""></a>
        <a class="h-screen p-2 m-auto my-3 w-14" href=""><img src="{{asset('img/chat.png')}}" alt=""></a>

        <a class="h-screen p-2 m-auto my-3 w-14" href="{{ route('profile.show')}}"><img src="{{asset('img/user.png')}}" alt=""></a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="p-2 m-auto my-3 w-14" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                this.closest('form').submit();">
                <img class="p-2 m-auto my-3 w-14" src="{{asset('img/turn-off.png')}}" alt="">
            </a>
        </form>


    </nav>
