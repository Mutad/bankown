<div class="card-plate plate d-flex flex-column justify-content-between border gray">
    <div class="d-flex justify-content-between align-items-center">
        <img src="{{asset('logo.png')}}" alt="" class="icon">
        <span class="text-muted font-weight-light">{{$card->type}}</span>
    </div>
    <div class="d-flex flex-column mt-3 justify-content-between">
        <p class="text-muted">{{$card->name}}</p>
        <p class="">{{$card->balance}} {{$card->currency}}</p>
    </div>
</div>