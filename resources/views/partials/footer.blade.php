<footer>
    <div class="d-flex flex-column align-items-center container-lg">
        {{-- <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Library</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
              </ol>
        </div> --}}
        <div class="d-flex justify-content-between container">

            <div>
                <h3>About</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat cum dolores corporis voluptatum
                    esse, reprehenderit consequatur facere id voluptatibus vel quidem vitae totam minima quis recusandae
                    saepe tempora harum tenetur!</p>
            </div>
            <!-- TODO add if statement -->
            <!--         
<div>
    <h3>Recent news</h3>
    <ul>
        <li>123</li>
        <li>123</li>
        <li>123</li>
    </ul>
</div>
<div>
    <h3>Recent news</h3>
    <ul>
        <li>123</li>
        <li>123</li>
        <li>123</li>
    </ul>
</div> -->
        </div>
        <hr />
        <div class="d-flex justify-content-between flex-responsive">
            <p>Copyright © 2020 Bankown. All rights reserved.</p>
            <ul class="links-legal d-flex flex-row mx-2">
                <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
                <li><a href="{{route('terms_of_use')}}">Terms of Use</a></li>
            </ul>
        </div>
    </div>
</footer>