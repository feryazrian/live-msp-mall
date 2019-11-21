<div class="product-card">
    <a href="{{ route('user.detail', ['username' => $item->username]) }}" class="image rounded">
        <img src="{{ asset('uploads/photos/medium-'.$item->photo) }}">
    </a>
    <div class="content">
        <a href="{{ route('user.detail', ['username' => $item->username]) }}" class="title">{{ $item->name }}</a>
        <div class="location">{{ '@'.$item->username }}</div>
        @if (!empty($item->kabupaten->name))
            <div class='location'>{{ $item->kabupaten->name }}</div>
        @endif
    </div>
</div>