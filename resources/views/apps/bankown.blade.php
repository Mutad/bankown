@extends('app')

@section('body')
<div>
    <ul class="list-group" style="color: black">
        @foreach (App\TelegramUser::all() as $user)
        <li class="list-group-item">
            <h3>{{$user->username}}</h3>
            <h4>Cards</h4>
            <ul class="list-group-flush">
                @forelse ($user->cards as $card)
                <li class="list-group-item">{{$card->name}} : {{$card->getBalance()}}</li>
                @empty
                <li class="list-group-item">No cards found</li>
                @endforelse
            </ul>

            <h4>Contacts</h4>

            <ul class="list-group-flush">
                @forelse ($user->contacts as $contact)
                <li class="list-group-item">{{$contact->username}}</li>
                @empty
                <li class="list-group-item">No contacts found</li>
                @endforelse
            </ul>
        </li>
        @endforeach
    </ul>
</div>
@endsection