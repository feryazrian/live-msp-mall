                        <div class="comment-list" id="{{ 'comment'.$item->id }}">
                            <div class="d-table w-100">
                                <a href="{{ route('user.detail', ['username' => $item->user->username]) }}" class="d-table-cell">
                                    <div class="d-inline-block align-middle">
                                        <img src="{{ asset('uploads/photos/small-'.$item->user->photo) }}" class="img-rounded" />
                                    </div>
                                    <div class="d-inline-block align-middle">
                                        <div class="name">{{ $item->user->name }}</div>
                                    </div>
                                </a>
                                <div class="d-table-cell timestamp text-right">
                                    <div class="d-inline-block">{{ str_replace('yang lalu', '', $item->created_at->diffForHumans()) }}</div>
                                    
                                    @auth
                                    @if (Auth::user()->id == $item->user->id)
                                        <button type="button" class="comment-delete d-inline-block btn btn-xs ml-2" data-id="{{ $item->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    @endauth
                                </div>
                            </div>
                            <div class="content">{{ $item->content }}</div>
                        </div>