@extends('layouts.app')

@section('title'){{ str_replace('[TITLE]', $pageTitle, $seo_title) }}@endsection

@section('description'){{ str_replace('[TITLE]', $pageTitle, $seo_description) }}@endsection

@section('content')

<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-T5GmPuLqDeCy2Kdk"></script>

<section class="page-section">
    <div class="container">

        <div class="row">

            <div class="col-lg-3 d-none d-lg-block py-4">
                <div class="sidebar">
                    @include('layouts.includes.sidenav-mobile')
                </div>
            </div>

            <div class="col-md-12 page-content col-lg-9 py-4">

                <div class="page-title mb-4">{{ $pageTitle }}</div>

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
    
                <div class="alert alert-success">Saldo {{ $pageSubTitle }} Anda saat ini <b class="text-red">{{ 'Rp '.number_format($myBalance,0,',','.') }}</b></div>

                <ul>
                    <li>Pencairan saldo <b>minimal sebesar Rp 10.000</b></li>
                    <li>Pencairan saldo hanya bisa dilakukan <b>maksimal 1 kali sehari</b></li>
                    <li>Pencairan saldo akan <b>diproses maksimal dalam 1-2 hari kerja</b></li>
                    <li><b>Harap mengisikan data pencairan dengan cermat dan benar.</b> {{ config('app.name') }} tidak bertanggung jawab apabila terjadi hal yang tidak diinginkan akibat kesalahan dalam pengisian data rekening bank yang meliputi nomor rekening, nama pemilik rekening dan nama bank</li>
                </ul>

            @if ($myBalance >= 10000)

                <form method="post" action="{{ route('balance.withdraw.store') }}" class="mb-5 pb-5">

                    {{ csrf_field() }}

                    <div class="form-group mb-2 {{ $errors->has('balance') ? ' has-error' : '' }}">
                        <input type="text" name="balance" class="numeric form-control" aria-describedby="balance" placeholder="Jumlah Pencairan Saldo (minimal Rp10.000)" required value="{{ old('balance') }}">
                    
                    @if ($errors->has('balance'))
                        <small id="balance" class="form-text text-danger">
                            {{ $errors->first('balance') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('bank_name') ? ' has-error' : '' }}">
                        <select name="bank_name" required class="form-control select select-smart select-secondary select-block text-left m-0">
                            <optgroup label="">
                                <option value="">Nama Bank Tujuan</option>
                            </optgroup>
                            <optgroup label="">
                                <option value="Bank Central Asia (BCA)">Bank Central Asia (BCA)</option>
                                <option value="Bank Mandiri">Bank Mandiri</option>
                                <option value="Bank Rakyat Indonesia (BRI)">Bank Rakyat Indonesia (BRI)</option>
                                <option value="Bank Negara Indonesia (BNI)">Bank Negara Indonesia (BNI)</option>
                            </optgroup>
                            <optgroup label="">
                                <option value="Anglomas International Bank">Anglomas International Bank</option>
                                <option value="BCA Syariah">BCA Syariah</option>
                                <option value="BII Syariah">BII Syariah</option>
                                <option value="Bangkok Bank">Bangkok Bank</option>
                                <option value="Bank ANZ Indonesia">Bank ANZ Indonesia</option>
                                <option value="Bank Agris">Bank Agris</option>
                                <option value="Bank Agroniaga">Bank Agroniaga</option>
                                <option value="Bank Andara">Bank Andara</option>
                                <option value="Bank Artha Graha International">Bank Artha Graha International</option>
                                <option value="Bank Artos Indonesia">Bank Artos Indonesia</option>
                                <option value="Bank BJB (Bandung)">Bank BJB (Bandung)</option>
                                <option value="Bank BJB Syariah">Bank BJB Syariah</option>
                                <option value="Bank BNI Syariah">Bank BNI Syariah</option>
                                <option value="Bank BNP Paribas Indonesia">Bank BNP Paribas Indonesia</option>
                                <option value="Bank BPD Aceh (Banda Aceh)">Bank BPD Aceh (Banda Aceh)</option>
                                <option value="Bank BPD Aceh Syariah">Bank BPD Aceh Syariah</option>
                                <option value="Bank BPD Bali (Denpasar)">Bank BPD Bali (Denpasar)</option>
                                <option value="Bank BPD DIY (Yogyakarta)">Bank BPD DIY (Yogyakarta)</option>
                                <option value="Bank BRI Syariah">Bank BRI Syariah</option>
                                <option value="Bank BTN Syariah">Bank BTN Syariah</option>
                                <option value="Bank Bengkulu (Bengkulu)">Bank Bengkulu (Bengkulu)</option>
                                <option value="Bank Bisnis Internasional">Bank Bisnis Internasional</option>
                                <option value="Bank Bukopin">Bank Bukopin</option>
                                <option value="Bank Bumi Arta">Bank Bumi Arta</option>
                                <option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
                                <option value="Bank Capital Indonesia">Bank Capital Indonesia</option>
                                <option value="Bank Chinatrust Indonesia">Bank Chinatrust Indonesia</option>
                                <option value="Bank Commonwealth">Bank Commonwealth</option>
                                <option value="Bank DBS Indonesia">Bank DBS Indonesia</option>
                                <option value="Bank DKI (Jakarta)">Bank DKI (Jakarta)</option>
                                <option value="Bank DKI Syariah">Bank DKI Syariah</option>
                                <option value="Bank Danamon Indonesia">Bank Danamon Indonesia</option>
                                <option value="Bank Danamon Syariah">Bank Danamon Syariah</option>
                                <option value="Bank Dipo International">Bank Dipo International</option>
                                <option value="Bank Ekonomi Raharja">Bank Ekonomi Raharja</option>
                                <option value="Bank Fama International">Bank Fama International</option>
                                <option value="Bank Ganesha">Bank Ganesha</option>
                                <option value="Bank Hana">Bank Hana</option>
                                <option value="Bank Harda International">Bank Harda International</option>
                                <option value="Bank ICB Bumiputra">Bank ICB Bumiputra</option>
                                <option value="Bank ICBC Indonesia">Bank ICBC Indonesia</option>
                                <option value="Bank Ina Perdana">Bank Ina Perdana</option>
                                <option value="Bank Index Selindo">Bank Index Selindo</option>
                                <option value="Bank International Indonesia (BII)">Bank International Indonesia (BII)</option>
                                <option value="Bank J Trust Indonesia">Bank J Trust Indonesia</option>
                                <option value="Bank Jambi (Jambi)">Bank Jambi (Jambi)</option>
                                <option value="Bank Jasa Jakarta">Bank Jasa Jakarta</option>
                                <option value="Bank Jateng (Semarang)">Bank Jateng (Semarang)</option>
                                <option value="Bank Jatim (Surabaya)">Bank Jatim (Surabaya)</option>
                                <option value="Bank KEB Indonesia">Bank KEB Indonesia</option>
                                <option value="Bank Kalbar (Pontianak)">Bank Kalbar (Pontianak)</option>
                                <option value="Bank Kalbar Syariah">Bank Kalbar Syariah</option>
                                <option value="Bank Kalsel (Banjarmasin)">Bank Kalsel (Banjarmasin)</option>
                                <option value="Bank Kalsel Syariah">Bank Kalsel Syariah</option>
                                <option value="Bank Kalteng (Palangka Raya)">Bank Kalteng (Palangka Raya)</option>
                                <option value="Bank Kaltim (Samarinda)">Bank Kaltim (Samarinda)</option>
                                <option value="Bank Kesejahteraan Ekonomi">Bank Kesejahteraan Ekonomi</option>
                                <option value="Bank Lampung (Bandar Lampung)">Bank Lampung (Bandar Lampung)</option>
                                <option value="Bank Liman International">Bank Liman International</option>
                                <option value="Bank Maluku (Ambon)">Bank Maluku (Ambon)</option>
                                <option value="Bank Maspion">Bank Maspion</option>
                                <option value="Bank Mayapada">Bank Mayapada</option>
                                <option value="Bank Maybank Indonesia">Bank Maybank Indonesia</option>
                                <option value="Bank Maybank Syariah Indonesia">Bank Maybank Syariah Indonesia</option>
                                <option value="Bank Mayora">Bank Mayora</option>
                                <option value="Bank Mega">Bank Mega</option>
                                <option value="Bank Mega Syariah">Bank Mega Syariah</option>
                                <option value="Bank Mestika Dharma">Bank Mestika Dharma</option>
                                <option value="Bank Metro Express">Bank Metro Express</option>
                                <option value="Bank Mitraniaga">Bank Mitraniaga</option>
                                <option value="Bank Mizuho Indonesia">Bank Mizuho Indonesia</option>
                                <option value="Bank Muamalat Indonesia">Bank Muamalat Indonesia</option>
                                <option value="Bank Multi Arta Sentosa">Bank Multi Arta Sentosa</option>
                                <option value="Bank NTB (Mataram)">Bank NTB (Mataram)</option>
                                <option value="Bank NTB Syariah">Bank NTB Syariah</option>
                                <option value="Bank NTT (Kupang)">Bank NTT (Kupang)</option>
                                <option value="Bank Nagari (Padang)">Bank Nagari (Padang)</option>
                                <option value="Bank Nationalnobu">Bank Nationalnobu</option>
                                <option value="Bank Nusantara Parahayangan">Bank Nusantara Parahayangan</option>
                                <option value="Bank OCBC NISP">Bank OCBC NISP</option>
                                <option value="Bank Papua (Jayapura)">Bank Papua (Jayapura)</option>
                                <option value="Bank Perkreditan Rakyat (BPR KS)">Bank Perkreditan Rakyat (BPR KS)</option>
                                <option value="Bank Permata">Bank Permata</option>
                                <option value="Bank Permata Syariah">Bank Permata Syariah</option>
                                <option value="Bank Pundi Indonesia">Bank Pundi Indonesia</option>
                                <option value="Bank QNB Kesawan">Bank QNB Kesawan</option>
                                <option value="Bank Rabobank International Indonesia">Bank Rabobank International Indonesia</option>
                                <option value="Bank Resona Perdania">Bank Resona Perdania</option>
                                <option value="Bank Riau Kepri (Pekanbaru)">Bank Riau Kepri (Pekanbaru)</option>
                                <option value="Bank Riau Kepri Syariah">Bank Riau Kepri Syariah</option>
                                <option value="Bank Royal Indonesia">Bank Royal Indonesia</option>
                                <option value="Bank SBI Indonesia">Bank SBI Indonesia</option>
                                <option value="Bank Sahabat Purba Danarta">Bank Sahabat Purba Danarta</option>
                                <option value="Bank Sinar Harapan Bali">Bank Sinar Harapan Bali</option>
                                <option value="Bank Sinarmas">Bank Sinarmas</option>
                                <option value="Bank Sulsel (Makassar)">Bank Sulsel (Makassar)</option>
                                <option value="Bank Sulteng (Palu)">Bank Sulteng (Palu)</option>
                                <option value="Bank Sultra (Kendari)">Bank Sultra (Kendari)</option>
                                <option value="Bank Sulut (Manado)">Bank Sulut (Manado)</option>
                                <option value="Bank Sumitomo Mitsui Indonesia">Bank Sumitomo Mitsui Indonesia</option>
                                <option value="Bank Sumsel Babel (Palembang)">Bank Sumsel Babel (Palembang)</option>
                                <option value="Bank Sumsel Babel Syariah">Bank Sumsel Babel Syariah</option>
                                <option value="Bank Sumut (Medan)">Bank Sumut (Medan)</option>
                                <option value="Bank Sumut Syariah">Bank Sumut Syariah</option>
                                <option value="Bank Syariah Bukopin">Bank Syariah Bukopin</option>
                                <option value="Bank Syariah Mandiri">Bank Syariah Mandiri</option>
                                <option value="Bank Tabungan Negara (BTN)">Bank Tabungan Negara (BTN)</option>
                                <option value="Bank Tabungan Pensiunan Nasional">Bank Tabungan Pensiunan Nasional</option>
                                <option value="Bank UOB Indonesia">Bank UOB Indonesia</option>
                                <option value="Bank Victoria International">Bank Victoria International</option>
                                <option value="Bank Victoria Syariah">Bank Victoria Syariah</option>
                                <option value="Bank Windu Kentjana International">Bank Windu Kentjana International</option>
                                <option value="Bank Woori Indonesia">Bank Woori Indonesia</option>
                                <option value="Bank Yudha Bhakti">Bank Yudha Bhakti</option>
                                <option value="Bank of America">Bank of America</option>
                                <option value="Bank of China">Bank of China</option>
                                <option value="Bank of India Indonesia">Bank of India Indonesia</option>
                                <option value="CIMB Niaga Syariah">CIMB Niaga Syariah</option>
                                <option value="Centrama Nasional Bank">Centrama Nasional Bank</option>
                                <option value="Citibank">Citibank</option>
                                <option value="Deutsche Bank">Deutsche Bank</option>
                                <option value="HSBC">HSBC</option>
                                <option value="HSBC Amanah">HSBC Amanah</option>
                                <option value="JPMorgan Chase">JPMorgan Chase</option>
                                <option value="OCBC NISP Syariah">OCBC NISP Syariah</option>
                                <option value="Panin Bank">Panin Bank</option>
                                <option value="Panin Bank Syariah">Panin Bank Syariah</option>
                                <option value="Prima Master Bank">Prima Master Bank</option>
                                <option value="Royal Bank of Scotland">Royal Bank of Scotland</option>
                                <option value="Standard Chartered">Standard Chartered</option>
                                <option value="The Bank of Tokyo Mitsubishi UFJ">The Bank of Tokyo Mitsubishi UFJ</option>
                            </optgroup>
                        </select>
                    
                    @if ($errors->has('bank_name'))
                        <small id="bank_name" class="form-text text-danger">
                            {{ $errors->first('bank_name') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('bank_holder') ? ' has-error' : '' }}">
                        <input type="text" name="bank_holder" class="form-control" aria-describedby="bank_holder" placeholder="Atas Nama" required value="{{ old('bank_holder') }}">
                    
                    @if ($errors->has('bank_holder'))
                        <small id="bank_holder" class="form-text text-danger">
                            {{ $errors->first('bank_holder') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group mb-2 {{ $errors->has('bank_number') ? ' has-error' : '' }}">
                        <input type="text" name="bank_number" class="numeric form-control" aria-describedby="bank_holder" placeholder="Nomor Rekening" required value="{{ old('bank_number') }}">
                    
                    @if ($errors->has('bank_number'))
                        <small id="bank_number" class="form-text text-danger">
                            {{ $errors->first('bank_number') }}
                        </small>
                    @endif
                    </div>

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" aria-describedby="bank_holder" placeholder="Password {{ config('app.name') }}" required>
                
                    @if ($errors->has('password'))
                        <small id="password" class="form-text text-danger">
                            {{ $errors->first('password') }}
                        </small>
                    @endif
                    </div>

                    <button type="submit" class="btn btn-rounded btn-block btn-primary">Withdraw Sekarang</button>
                </form>

            @else
                
                <div class="alert alert-danger">
                    Maaf, saldo anda tidak mencukupi untuk melakukan pencairan dana!! Pencairan dana minimal sebesar Rp 10.000
                </div>

            @endif

            </div>

        </div>

    </div>
</section>

@endsection
