<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="font: small/1.5 Arial,Helvetica,sans-serif;">
    <div style="margin:0;padding:0;font-family:'Open Sans',sans-serif" bgcolor="#F7F7F7">
		<table cellspacing="0" cellpadding="0" border="0" align="center" style="max-width:600px;border-collapse:collapse;background-color:#ffffff;border:solid #e0e0e0 1px">

              <tbody>
                  <tr>
                    <td style="padding:15px 25px 10px">
                    <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:collapse;color:#ffffff">
                        <tbody><tr>
                        <td width="210">
                            <a href="{{ url('/') }}" style="display:inline-block" target="_blank">
                              <img src="{{ asset('uploads/options/'.$logo) }}" alt="Logo" height="35">
                            </a>
                        </td>
                        </tr>
                    </tbody></table>
                    </td>
              </tr>

              <tr>
                <td style="padding:15px 24px 32px">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;font-size:15px">
                    <tbody><tr>
                      <td>
                        <h1 style="font-size:24px;line-height:1.42;letter-spacing:-0.4px;color:rgba(0,0,0,0.7);margin-top:0;margin-bottom:25px">Kode OTP Aktivasi Akun MSP Mall</h1>
                        <h2 style="display:inline-block;margin:0 0 24px;font-size:16px;line-height:1.25;letter-spacing:-0.3px;font-weight:600;color:rgba(0,0,0,0.7)">
                          <span style="font-weight:normal;color:rgba(0,0,0,0.54)">Hai, </span>Sobat MSP!</h2>
                        <span style="font-size:13px;line-height:1.57;color:rgba(0,0,0,0.54);display:block">Masukkan kode berikut untuk melakukan aktivasi akun MSP Mall.</span>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              
      
              <tr>
                <td>
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0px 0 32px">
                    <tbody><tr>
                      <td>
                        {{-- <a href="#m_-4953159629310277706_" style="width:310px;margin:0px auto;display:block;padding:18px 0 16px;box-sizing:border-box;text-decoration:none;font-size:32px;font-weight:600;text-align:center;border-radius:8px;background-color:#ffffff;border:solid 1px #e0e0e0;color:rgba(0,0,0,0.7);letter-spacing:4px;line-height:0.75">{{ $otpCode }}</a> --}}
                        <div style="width:300px;height:150px;margin:0px auto;display:block;padding:16px;box-sizing:border-box;text-decoration:none;font-size:32px;font-weight:600;text-align:center;border-radius:8px;background-color:#ffffff;border:solid 1px #e0e0e0;color:rgba(0,0,0,0.7);letter-spacing:4px;line-height:0.75">
                          <img src="{{ asset($otpImg) }}" alt="otp" height="100%" width="100%">
                        </div>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
      
              
              {{-- <tr>
                <td style="padding:0 24px">
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse;font-size:13px;color:rgba(0,0,0,0.54);line-height:1.54">
                    <tbody><tr>
                      <td>
                        Atau lanjut dengan klik <a href="" style="color:#42b549;text-decoration:none" target="_blank">masukan data diri.</a>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr> --}}
              
              <tr>
                <td style="padding:24px">
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse;font-size:12px;color:rgba(0,0,0,0.38);line-height:1.67">
                    <tbody><tr>
                      <td style="font-weight:600">Catatan:</td>
                    </tr>
                    <tr>
                      <td>
                        Kode di atas hanya berlaku selama {{ $expiry }} menit. Harap tidak menyebarkan kode kepada siapapun demi menjaga keamanan akun.  
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
      
              <tr>
                <td>
                  <hr style="border:none;border-top:1px solid #e8e8e8;margin:0px 25px">
                </td>
              </tr>
      
              
              <tr>
                <td style="padding:24px 24px 0px">
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse;font-size:13px;color:rgba(0,0,0,0.38)">
                    <tbody><tr>
                      <td style="padding:0px 0">Email dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini.</td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
              
      
              <tr>
                <td style="padding:24px">
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-radius:3px;background-color:rgba(213,0,0,0.05);border:solid 1.5px rgba(213,0,0,0.05);padding:20px 16px">
                    <tbody><tr>
                      <td>
                        <img src="" alt="" width="92" class="CToWUd">
                      </td>
                      <td>
                        <table style="color:rgba(0,0,0,0.54);font-size:12px;line-height:1.42;padding-left:16px">
                          <tbody><tr>
                            <td style="padding-bottom:16px">
                              <span style="font-weight:bold">Perhatian!</span> Kata sandi, kode verifikasi, dan kode OTP bersifat rahasia. Hati-hati untuk tidak memberikan data penting Anda kepada pihak yang mengatasnamakan MSP Mall atau yang tidak dapat dijamin keamanannya.
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
      
              
              {{-- <tr>
                <td style="padding:0 24px 20px">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:13px;color:#999999">
                    <tbody><tr>
                      <td>
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse">
                          <tbody><tr>
                            <td align="left">
                              <table border="0" style="border-collapse:collapse">
                                <tbody><tr>
                                  <td style="font-size:12px;color:rgba(0,0,0,0.7);padding-bottom:16px">Download Aplikasi MSP Mall
                                    <br>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse">
                                      <tbody><tr>
                                        <td>
                                          <a href="" target="_blank">
                                            <img src="https://ci4.googleusercontent.com/proxy/s9rO43jng1PllOt2gYJiYNoLEWxHkGlcTB95TACjViHPbMRCCSaDX6Rg9tK7CKhzqgt8cORec-Q-M1rbAo2EEem3OWr7RdnlpNRkrxqA4Di7--89xM2Aq7mt=s0-d-e1-ft#https://s3.amazonaws.com/www.betaoutcdn.com/205872016/07/1468233846.png" alt="Appstore" width="135" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                        <td>
                                          <a href="" target="_blank">
                                            <img src="https://ci3.googleusercontent.com/proxy/XfqkAezHVm9Clq6YmlEnRIeMgr6UidmUiTEkz2QAg2FMN6ja020ySye4LBo3LXWvbhkRcQp-5ZqnKBXoWu-N_bvP6oN3kMFkdZirLSWYm5Oh1sSndIAz55gF=s0-d-e1-ft#https://s3.amazonaws.com/www.betaoutcdn.com/205872016/07/1468233889.png" alt="Playstore" width="135" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                            <td align="right">
                              <table border="0" style="border-collapse:collapse">
                                <tbody><tr>
                                  <td style="font-size:12px;color:rgba(0,0,0,0.7);padding-bottom:16px" align="right">Ikuti Kami</td>
                                </tr>
                                <tr>
                                  <td style="padding:2px"></td>
                                </tr>
                                <tr>
                                  <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse">
                                      <tbody><tr>
                                        <td>
                                          <a href="" style="margin:0 4px;display:inline-block" target="_blank">
                                            <img src="https://lh3.googleusercontent.com/e9_oeT12X9MvafJsHCCvcZ7n4o0k2NAo6PT1PVgLHMhkrl3JssK60hAnqpAKOUhEQTK2tcfI0SQ9SWmBeDMIWAxDSo43DTOYeRNZh2MPlmkM3pqnWeHctl-H3ANJXCexdesTlwWDM4w9l62bgslQwID8UQ5W7p5Sm7Sp1-gPnvQ4rO221_0y3jM7Lg-VI2jg7pY6qOtdMFikbR5At9VvmG8mgPeayjHGj46rUoziSy23-G-ltRYmL_2QrNEtu23bVe6nPCG4Y4Zg-5Q9ajUt-CXUq-BrgaeV-edV9dtrQRT5fAVqIpRXq5aTFbfqR8g8htKGwIF6DCxciF4a8prCFEYiWQk6Ci40gcJ_cS8iCoNdWR_PwR6jGh0oQUWR_0HwGVZRuN66c5dafpYvqBfmgCKGrzvzju8SBZmGtunO4iQvHKPMMurBmoLiHplVeaJlGXQp_vIJSCDtxEu_JZqTuUlooCQLnkZk4hlJqablmEwYKCnAiVgU8tt5cY7Cy2k-s2lzyPepEYiFM5IZdkzo0hPKSVaDXv5Xhr0XiaHrPWPMVjOlSa9jG43RZaKjRQgN=w2880-h800" alt="Google plus" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                        <td>
                                          <a href="" style="margin:0 4px;display:inline-block" target="_blank">
                                            <img src="https://lh3.googleusercontent.com/avyH77UNRfZ9EHhSZDCQ5qsoupXix-TlUlu2vNM3WRrC4n9cuRdJ_3hKM2oRlFdVsxZQSgZdiCsA03piN8Mp-Vp0D5A6ygzSd09e-KEygvpxRLSQ_Jpl82XBj0RN84mElLAh3BDMy5xGHMNK4m4ytmxdvnPdrtNbJM7q1YIn1umVBnyQ1r_PdLzaEvFyg0v3V4cIuce5SFH59mqWn-krQjgpf5Z9vKtx_Dkr-0l5zCX84WOeY-O7fQf4F2t4Z9ZuzFTLM3x36IDyoCjrNyj37R09jHQFyUGVR53d3jCqMAai8LIfGL4iK8zYwUNgbPkeV_j2MOOsd_srfQck1QYEdWzsGGSVnuiUJtFRn4HQy5SrE9igvux26Ms30YZxahc1Ib3PMHNnEUt5WSaJwHLGTVYs0vN38M6lGkt2_yYQZZPFfONvX6J6OYXBS339Is33QHaRkJfE28xYf2F09u9UIjbubsU22_NX1iac4LnoJbj58IlAvNFGr7-hda51c6x2GQpZ-jdDjP8RYQMs2a1reYwBAPFTIi4KLbmWGtuOsmFIEoHEI7n2SmbV_uL-253I=w2880-h1450" alt="Facebook" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                        <td>
                                          <a href="" style="margin:0 4px;display:inline-block" target="_blank" >
                                            <img src="https://lh3.googleusercontent.com/daTUXCk1OgGNdxHZxF01RSaNET3rk7sqKNKoK8Yit1g-UtChTL3nfLVHhvqJ3DJfXkTVBf9FAlBV-zYJ1XyJxRgurRF4Xu-X28ZSryurzJ0uqM0g3xTsG_hHQY5AZcYBRDiL-mh9qsEs3j0WlZkv_zyM-Us8G1ZkwOS7LQl1_LOgrDax2UFxfl12CUACWpjOwbMWnLUaKJedBjjuDQiYD-DwfY6StgHVabYvY0L0CN89JgrM6MwRCZxQ15YCFkyrCy9Tgz5c8s2Lp87M1_-b1JdyyVWdrbcT9Fu4BfHA2dABpTtH9yMMn8QuHlkwue7Hr849N8HLvadyebKhWREZJhkGGuwa7K-uAbPDONh_j8-2501UjTZ6aqRcXh6GNAQneCT8_eYruP2_bu_ZaC_vP5D83dLX5aDBCq0_ADz6wp1wP2YDtTDP9JQ0cjq_CMgzw6x9XMiN-gjZU9J1dBlfLQIhIPh5XFuKkGqAMY7WBJ94dyBPPobQBU0ZTPgd4Lxr7y2Wfjxx2AxF05M3HUHFtSD7lIfPiHHVrYXhkebRUT5zuqvbV8o1VnSdTaBmvQ2r=w2880-h1450" alt="Twitter" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                        <td>
                                          <a href="" style="margin:0 0 0 4px;display:inline-block" target="_blank">
                                            <img src="https://lh3.googleusercontent.com/kB-s2J-0NxNqUI9NR2BGS9CcFABkJL53aOc314TVW3lXKl8gt2ZIPgkao_By4EUA1ywBNhcZEgLDPM92pTcoHPyuV6y0RKtBzYUx0X5of3MdKgyeev1JN2F7kykP74Z_1rXWQTU5oMDDqdZqApUksMb0SUduUr94THAM9XuCvH82bPsj6M8e6CNdzOBzG4lDYg9r2G5N2EfdrpWdJOO9q2pC0rMfPNGrAxp7AXoniT5xOSXr9NtSHI1FMfTMLXSZdX0ddeXCBd8owMwB2yPuOrSweB1fjehCksl2K7RtcNNb71gwkX3DikR9R1YtdtcAT2lyUnQ9bpop14S2m4aNtw7wt6dfSHZWCNIJQfbHbBFcL00dVSUGH6-h5LlBJDeWjaJkCACH76FvlQmrB_D3GeCZTZG013rZtkCvsutQyJ8XNGse8nqfKn12dBblpKMfkqyyxhEfgWY95DxkS5MbXEk3Hr1DdVKdbvuvX2qGiOWp6yiKPeGOxwJCMAz3Gn2Omhh70TDc8XcXz8tArm7vbIQzGFAkxYSlGQU7nkYJ3EzxRmJWFgZHGHwerbQp7Ka5=w2880-h800" alt="Instagram" height="40" class="CToWUd">
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr> --}}

              <tr>
                <td>
                  <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse;background-color:#f7f7f7;font-size:12px;color:rgba(0,0,0,0.54);border-top:1px solid #e0e0e0">
                    <tbody>
                      {{-- <tr>
                        <td width="560" align="center" style="padding:24px 0 8px">Jika butuh bantuan, gunakan halaman
                          <a href="https://mymspmall.id/page" style="color:#42b549;text-decoration:none" target="_blank">Kontak Kami</a>
                        </td>
                      </tr> --}}
                      <tr>
                        <td width="560" align="center" style="padding:8px 0 24px">
                          © {{ now()->year }}, MSP MALL
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              
      
            </tbody></table>
      </div>
</body>
</html>
