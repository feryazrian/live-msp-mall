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

                <div class="messages">
                    <div class="head">
                        <div class="title">{{ $user->name }}</div>
                    </div>

                    <div class="delete"><form></form></div>

                    <div class="main" id="messagescroll">
                        <div class="realtime">
                            <div class="result">

                            @foreach ($lists as $message)
                            @php
                            if ($message->sender->id == Auth::user()->id)
                            {
                                $class = 'right';
                            }

                            if ($message->sender->id != Auth::user()->id)
                            {
                                $class = 'left';
                            }

                            if ($message->created_at->format('Y-m-d') == date('Y-m-d'))
                            {
                                $timestamp = $message->created_at->format('H:i');
                            }

                            if ($message->created_at->format('Y-m-d') != date('Y-m-d'))
                            {
                                $timestamp = str_replace('yang lalu', '', $message->created_at->diffForHumans());
                            }
                            @endphp

                                <div class="message-content {{ $class }}">
                                    <div class="content">{{ nl2br($message->content) }}</div>
                                    <div class="timestamp">{{ $timestamp }}</div>
                                </div>
                            @endforeach
                                
                            </div>
                        </div>
                    </div>

                    <div class="foot">
                        <input type="hidden" id="message-id" name="id" value="{{ $user->id }}" />
                        <textarea class="inline-textarea" required id="message-store" name="content" placeholder="Ketikkan pesan yang ingin anda kirim disini (Tekan ENTER untuk kirim)" rows="1"></textarea>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<script type="text/javascript">
    // Message Height
    var bottom = $("#messagescroll").height();

    // Message Scroll
    $("#messagescroll").animate({ scrollTop: bottom }, "fast");

    // Message View
    $('#messagescroll').css('height',($(window).height()-330));

    // Message Store
    $('#message-store').on("keydown", function(e) {
        if (e.keyCode == 13 && e.shiftKey) { }
        else if ( e.keyCode == 13 ) {
            var id = $("#message-id").val();
            var content = $(this).val();
            var _token = $("meta[name=csrf-token]").attr("content");

            if (content != '') {
                $.post('{{ route("message.store") }}', { _token: _token, id: id, content: content }, function(result) { });
            }
           
            $(this).val('');
            
            // Content Scroll
            bottom = bottom + 150;
            $("#messagescroll").animate({ scrollTop: bottom }, "fast");
            
            return false;
        }
    });

    // Notification
    Echo.private('message.content.{{ Auth::user()->id.".".$receiverId }}')
    .listen('MessageContentNotification', (data) => {
        var content = '';
        
        $.each(data.message, function (index, element) {
            content += '<div class="message-content '+element.class+'"><div class="content">'+element.content+'</div><div class="timestamp">'+element.timestamp+'</div></div>';
        });

        $("#messagescroll > .realtime > .result").html(content);
        
        // Content Scroll
        bottom = bottom + 150;
        $("#messagescroll").animate({ scrollTop: bottom }, "fast");
    });
</script>

@endsection
