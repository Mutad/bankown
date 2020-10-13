<div class="sidebar d-flex flex-column">
    {{-- <h3 class="font-weight-lighter"></h3> --}}
    {{-- @foreach (Auth::user()->cards as $card)
<a href="{{route('hub.card.single',['card'=>$card])}}" class="text-decoration-none p-0 {{Request::route('card')!==null?$card->id === Request::route('card')->id?'active':'':''}}">
        @include('modules.banking.card.single',['card'=>$card])
    </a>
    @endforeach
    @include('modules.banking.card.add-btn') --}}
    <ul>
        <li class="">Banking</li>
    </ul>
</div>