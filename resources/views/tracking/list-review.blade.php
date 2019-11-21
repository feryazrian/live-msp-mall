@if($transactionReview->isEmpty())
<p class="caption">Belum Ada Ulasan</p>
@endif

@foreach ($transactionReview as $review)
<div class="review">
    <div class="user">
        <a href="{{ url('/').'/'.$review->transaction->user->username }}">
            <div class="photo"><img src="@uploadsProfile('small-'.$review->transaction->user->photo)" /></div>
            <div class="profile">
                <div class="name">{{ $review->transaction->user->name }}</div>
                <div class="username">{{ '@'.$review->transaction->user->username }}</div>
            </div>
        </a>
        <div class="timestamp">{{ $review->created_at->diffForHumans() }}</div>
    </div>
    <div class="post">
        <div class="text">{{ $review->review }}</div>
        <div class="star">
            <span>{{ $review->rating.'.0' }}</span>

            @for ($x = 1; $x <= $review->rating; $x++)
            <i class="fa fa-star active" aria-hidden="true"></i>
            @endfor

            @php
            $ratingInactive = (5 - $review->rating);
            @endphp

            @for ($y = 1; $y <= $ratingInactive; $y++)
            <i class="fa fa-star" aria-hidden="true"></i>
            @endfor
        </div>
    </div>
</div>
@endforeach
