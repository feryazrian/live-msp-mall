                        <div class="review-list">
                            <div class="d-table w-100">
                                <a href="{{ route('user.detail', ['username' => $item->transaction->user->username]) }}" class="d-table-cell">
                                    <div class="d-inline-block align-middle">
                                        <img src="{{ asset('uploads/photos/small-'.$item->transaction->user->photo) }}" class="img-rounded" />
                                    </div>
                                    <div class="d-inline-block align-middle">
                                        <div class="name">{{ $item->transaction->user->name }}</div>
                                    </div>
                                </a>
                                <div class="d-table-cell timestamp text-right">{{ str_replace('yang lalu', '', $item->created_at->diffForHumans()) }}</div>
                            </div>
                            <div class="content">{{ $item->review }}</div>
                            <div class="stars">
                            @for ($a=0; $a<$item->rating; $a++)
                                <i class="fas fa-star"></i>
                            @endfor
                                
                            @php
                                $inactive = (5 - $item->rating);
                            @endphp
                    
                            @for ($b=0; $b<$inactive; $b++)
                                <i class="fas fa-star inactive"></i>
                            @endfor
                    
                                <span class="stats ml-1">{{ $item->rating }} Bintang</span>
                            </div>
                        </div>