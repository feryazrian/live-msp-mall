@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 col-lg-9 page-content py-4">

                <div class="page-title mb-4">{{ $pageTitle }}</div>
                
                <div class="message-lines mb-5 pb-5">

                @if (session('status'))
                    <div class="alert alert-success">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('status') }}
                    </div>
                @endif
            
                @if (session('warning'))
                    <div class="alert alert-danger">
                        <button class="close fui-cross" data-dismiss="alert"></button>
                        {{ session('warning') }}
                    </div>
                @endif
                                            
                @if ($lists->isEmpty())
                    <div class="notfound">Belum Ada Pesan Tersedia</div>
                @endif
                
                @foreach ($lists as $item)
                @php
                    if (Auth::user()->id == $item->receiver_id)
                    {
                        $user = $item->sender;
                        $status = $item->receiver_view;
                    }
                    if (Auth::user()->id == $item->sender_id)
                    {
                        $user = $item->receiver;
                        $status = $item->sender_view;
                    }
                @endphp

                    <a href="{{ route('message.detail', ['username' => $user->username]) }}" class="message-line">
                        <div class="image">
                            <img src="{{ asset('uploads/photos/small-'.$user->photo) }}" class="rounded-circle">
                        </div>
                        <div class="content">
                            <div class="title">{{ $user->name }}</div>
                            <div class="caption">{{ $item->content }}</div>
                        </div>
                        <div class="stats">
                        @if ($item->updated_at->format('Y-m-d') == date('Y-m-d'))
                            <div class="timestamp">{{ $item->updated_at->format('H:i') }}</div>
                        @else
                            <div class="timestamp">{{ str_replace('yang lalu', '', $item->updated_at->diffForHumans()) }}</div>
                        @endif
                        
                        @if ($status > 0)
                            <div class="count">
                                <span>{{ $status }}</span>
                            </div>
                        @endif
                        </div>
                    </a>
                @endforeach
                    
                <div class="text-center">
                    {{ $lists->links() }}
                </div>

                </div>
                
            </div>

        </div>

    </div>
</section>


<script type="text/javascript">
    // Notification
    Echo.private('message.{{ Auth::user()->id }}')
    .listen('MessageNotification', (data) => {
        var content = '';
        
        $.each(data.message, function (index, element) {
            if (element.status > 0)
            {
                content += '<a href="'+element.url+'" class="message-line"><div class="image"><img src="'+element.photo+'" class="rounded-circle"></div><div class="content"><div class="title">'+element.name+'</div><div class="caption">'+element.content+'</div></div><div class="stats"><div class="timestamp">'+element.timestamp+'</div><div class="count"><span>'+element.status+'</span></div></div></a>';
            }
            else
            {
                content += '<a href="'+element.url+'" class="message-line"><div class="image"><img src="'+element.photo+'" class="rounded-circle"></div><div class="content"><div class="title">'+element.name+'</div><div class="caption">'+element.content+'</div></div><div class="stats"><div class="timestamp">'+element.timestamp+'</div></div></a>';
            }
        });

        $(".message-lines").html(content);
    });
</script>

@endsection