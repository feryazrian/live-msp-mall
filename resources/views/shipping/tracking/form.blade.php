                    <form method="POST" action="{{ route('shipping.tracking.store') }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <div class="form-group mb-4 {{ $errors->has('code') ? ' has-error' : '' }}">
                            <textarea placeholder="Nomor Resi" class="form-control" name="code" rows="9">{{ old('code') }}</textarea>
            
                        @if ($errors->has('code'))
                            <small id="code" class="form-text text-danger">
                                {{ $errors->first('code') }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Lacak Sekarang</button>

                    </form>