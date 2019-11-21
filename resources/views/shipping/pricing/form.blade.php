                    <form method="POST" action="{{ route('shipping.pricing.store') }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}

                        <div class="form-group mb-2 {{ $errors->has('kabupaten_id') ? ' has-error' : '' }}">
                            <select id="kabupaten" name="kabupaten_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Kota / Kabupaten Tujuan</option>
                                
                                @php $match=''; @endphp
                                @foreach ($places as $kabupaten)
                                @if ($match != $kabupaten->provinsi->name)
                                    <optgroup label="{{ $kabupaten->provinsi->name }}">
                                @endif
                                        <option value="{{ $kabupaten->id }}">{{ $kabupaten->name }}</option>
                                @if ($match != $kabupaten->provinsi->name)
                                    </optgroup>
                                @endif
                                @php $match = $kabupaten->provinsi->name; @endphp
                                @endforeach
                            </select>
                        
                        @if ($errors->has('kabupaten_id'))
                            <small id="kabupaten_id" class="form-text text-danger">
                                {{ str_replace('kabupaten_id', 'Kota / Kabupaten', $errors->first('kabupaten_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-2 {{ $errors->has('kecamatan_id') ? ' has-error' : '' }}">
                            <select id="kecamatan" name="kecamatan_id" class="form-control select select-smart select-secondary select-block">
                                <option value="">Kecamatan</option>
                            </select>
                        
                        @if ($errors->has('kecamatan_id'))
                            <small id="kecamatan_id" class="form-text text-danger">
                                {{ str_replace('kecamatan_id', 'Kecamatan', $errors->first('kecamatan_id')) }}
                            </small>
                        @endif
                        </div>

                        <div class="form-group mb-4 {{ $errors->has('postal_code') ? ' has-error' : '' }}">
                            <input type="text" name="postal_code" class="form-control" id="postal_code" aria-describedby="postal_code" placeholder="Kode Pos" required value="{{ old('postal_code') }}">
                        
                        @if ($errors->has('postal_code'))
                            <small id="postal_code" class="form-text text-danger">
                                {{ $errors->first('postal_code') }}
                            </small>
                        @endif
                        </div>
                        
                        <div class="form-group mb-4 {{ $errors->has('transaction') ? ' has-error' : '' }}">
                            <input type="number" required value="{{ old('transaction') }}" class="numeric form-control" name="transaction" placeholder="Nilai Transaksi (Rp)" />
                
                        @if ($errors->has('transaction'))
                            <small id="transaction" class="form-text text-danger">
                                {{ $errors->first('transaction') }}
                            </small>
                        @endif
                        </div>

                        <button type="submit" class="btn btn-rounded btn-primary btn-block">Cek Sekarang</button>

                    </form>