<nav x-data="{ open: false }" class="fixed border-none bg-[#F9F7F0] flex flex-col justify-between items-center w-24 p-1 h-screen">
    <a class="p-3" href="{{ route('accueil') }}"><img src="{{asset('img/letter-u.png')}}" alt=""></a>
    <a class="p-2 w-14" href=""><img src="{{asset('img/receive-mail.png')}}" alt=""></a>
    <a class="p-2 w-14" href=""><img src="{{asset('img/money-exchange.png')}}" alt=""></a>
    <a class="p-2 w-14" href=""><img src="{{asset('img/info.png')}}" alt=""></a>
    <a class="p-2 w-14" href="{{ route('conversations')}}"><img src="{{asset('img/chat.png')}}" alt=""></a>
    <a class="p-2 w-14" href="{{ route('profile.show')}}"><img src="{{asset('img/user.png')}}" alt=""></a>
    <a class="p-2 w-14" href="{{ route('logout') }}"><img src="{{asset('img/turn-off.png')}}" alt=""></a>
</nav>
