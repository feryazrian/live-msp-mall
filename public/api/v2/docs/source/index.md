---
title: API Reference

language_tabs:
- javascript
- bash

includes:
- responses

search: true

toc_footers:
# - <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/127.0.0.1/api/v2/docs/collection.json)

<!-- END_INFO -->

#Authentication

API untuk melakukan otorisasi user agar dapat mengakses ke API yang membutuhkan otorisasi berupa token.
<!-- START_578a341941eaa5bd685796302fd4c3a7 -->
## Request OTP (One Time Password)
API ini digunakan untuk melakukan generate kode OTP yang akan dikirimkan ke email / No. Hp user.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/otp/request");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "action": "register",
    "user": "user@mail.com",
    "type": 120
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/otp/request" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"action":"register","user":"user@mail.com","type":120}'

```


> Example response (200):

```json
{
    "code": 201,
    "status": "OK",
    "message": "Kode OTP berhasil dibuat dan telah dikirim ke user@mail.com"
}
```

### HTTP Request
`POST api/v2/authenticate/otp/request`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    action | string |  required  | <b>Default </b>: (register, aktivasi, reset).
    user | string |  required  | Dapat berupa email atau No.Hp.
    type | integer |  optional  | Type sebagai penanda user provider yang digunakan email / No. HP: 120 = email, 252 = phone.

<!-- END_578a341941eaa5bd685796302fd4c3a7 -->

<!-- START_62498e87aac157b60640028fd6ed467c -->
## Verify OTP (One Time Password)
API ini digunakan untuk untuk memvalidasi kode otp yang diterima oleh user dengan yang ada di sistem.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/otp/verify");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "action": "register",
    "user": "user@mail.com",
    "otp": 123456,
    "type": 120
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/otp/verify" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"action":"register","user":"user@mail.com","otp":123456,"type":120}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Kode OTP berhasil divalidasi"
}
```

### HTTP Request
`POST api/v2/authenticate/otp/verify`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    action | string |  required  | <b>Default </b>: (register, aktivasi, reset).
    user | string |  required  | Dapat berupa email atau No.Hp.
    otp | integer |  required  | Parameter otp hanya dibutuhkan ketika action <b>verify</b>.
    type | integer |  optional  | Type sebagai penanda user provider yang digunakan email / No. HP: 120 = email, 252 = phone.

<!-- END_62498e87aac157b60640028fd6ed467c -->

<!-- START_c21345f9c1c130baf70c5796cbcc10c5 -->
## Login
Login menggunakan email atau no handphone untuk mendapatkan JWT token. Token yang didapat kemudian digunakan untuk mengakses API yang membutuhkan Authorization berupa token.

Note: Sebelum login pastikan sudah melakukan register sebelumnya

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/login");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "username": "user@mail.com",
    "password": "aut",
    "client_id": 1,
    "ip": "127.0.0.1",
    "user_agent": "Google Chrome"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/login" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"username":"user@mail.com","password":"aut","client_id":1,"ip":"127.0.0.1","user_agent":"Google Chrome"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Login Success",
    "items": {
        "token_type": "Bearer ",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvYXV0aGVudGljYXRlIiwiaWF0IjoxNTYzODc1ODQ5LCJleHAiOjE1NjM5NjIyNDksImp0aSI6ImV5SnBkaUk2SW1FemVXUXhObkl5ZDA5MVZXMWFhVEpJVW1oM1RVRTlQU0lzSW5aaGJIVmxJam9pTXl0dVducFBPVkZNTVRGaFpFcEtXWFJ6Y21wdFFUMDlJaXdpYldGaklqb2lPV0pqT1dSbU1UYzNPR0l3WW1ObU1UTTNaalkzWXpsbFlXUmtPVEl3TkRVeE5UZ3lOR0ZoTVdVeFlXUmpZekEyWm1VeU5HWXpZemxoTTJGak5HWTJNaUo5IiwidXNlcm5hbWUiOiJyaW9fZHdpX3ByYWJvd28iLCJuYW1lIjoiUmlvIER3aSBQcmFib3dvIiwiZW1haWwiOiJyaXlvLnM5NEBnbWFpbC5jb20iLCJhZG1pbiI6dHJ1ZSwibWVyY2hhbnQiOnRydWUsInJvbGVfaWQiOjQsImFjdGl2YXRlZCI6dHJ1ZX0.b_OIn8MHNKx_-PB3f_pSXelsMk2MW4plo4Kjdq6wpZI",
        "expires_in": 1563962249
    }
}
```

### HTTP Request
`POST api/v2/authenticate/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    username | string |  required  | Username wajib diisi dan dapat berupa email atau No. Hp.
    password | string |  required  | password wajib diisi dan dapat minimal 8 karakter.
    client_id | integer |  required  | Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ]
    ip | string |  optional  | IP address untuk disimpan sebagai log user
    user_agent | string |  optional  | User Agent untuk disimpan sebagai log user

<!-- END_c21345f9c1c130baf70c5796cbcc10c5 -->

<!-- START_1eb36dd4d29403a532e56dfde3515a30 -->
## Register
Register berlaku jika user sudah melakukan request OTP dan OTP tersebut telah terverifikasi, kemudian melengkapi data diri sesuai field.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/register");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "email": "user@mail.com",
    "phone": "08123456789",
    "name": "rem",
    "password": "tempora",
    "birth_date": "1991-12-21",
    "birth_place": "vero",
    "gender": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/register" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"email":"user@mail.com","phone":"08123456789","name":"rem","password":"tempora","birth_date":"1991-12-21","birth_place":"vero","gender":"1"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Login Success",
    "items": {
        "token_type": "Bearer ",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvYXV0aGVudGljYXRlIiwiaWF0IjoxNTYzODc1ODQ5LCJleHAiOjE1NjM5NjIyNDksImp0aSI6ImV5SnBkaUk2SW1FemVXUXhObkl5ZDA5MVZXMWFhVEpJVW1oM1RVRTlQU0lzSW5aaGJIVmxJam9pTXl0dVducFBPVkZNTVRGaFpFcEtXWFJ6Y21wdFFUMDlJaXdpYldGaklqb2lPV0pqT1dSbU1UYzNPR0l3WW1ObU1UTTNaalkzWXpsbFlXUmtPVEl3TkRVeE5UZ3lOR0ZoTVdVeFlXUmpZekEyWm1VeU5HWXpZemxoTTJGak5HWTJNaUo5IiwidXNlcm5hbWUiOiJyaW9fZHdpX3ByYWJvd28iLCJuYW1lIjoiUmlvIER3aSBQcmFib3dvIiwiZW1haWwiOiJyaXlvLnM5NEBnbWFpbC5jb20iLCJhZG1pbiI6dHJ1ZSwibWVyY2hhbnQiOnRydWUsInJvbGVfaWQiOjQsImFjdGl2YXRlZCI6dHJ1ZX0.b_OIn8MHNKx_-PB3f_pSXelsMk2MW4plo4Kjdq6wpZI",
        "expires_in": 1563962249
    }
}
```

### HTTP Request
`POST api/v2/authenticate/register`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | Email wajib diisi.
    phone | string |  required  | Phone wajib diisi.
    name | string |  required  | Name wajib diisi Nama lengkap user.
    password | string |  required  | Password wajib diisi dan dapat minimal 8 karakter.
    birth_date | string |  required  | Tanggal Lahir wajib diisi.
    birth_place | string |  required  | Tempat Lahir wajib diisi.
    gender | string |  required  | Jenis Kelamin wajib diisi. Value: [1 => 'Pria', 2 => 'Wanita']

<!-- END_1eb36dd4d29403a532e56dfde3515a30 -->

<!-- START_fa4d7c0a99e7fff7fa72d2fed654278b -->
## Social Login
Login / register menggunakan akun social media google atau facebook. API ini digunakan untuk mengenerate link url untuk login ke akun media sosial.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/provider");

    let params = {
            "name": "facebook",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/authenticate/provider" \
    -H "Authorization: Bearer {token}"
```


> Example response:

```json
null
```

### HTTP Request
`GET api/v2/authenticate/provider`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    name |  optional  | string required <b>Default </b>: (facebook, google).

<!-- END_fa4d7c0a99e7fff7fa72d2fed654278b -->

<!-- START_9a2a5f952c9592d7e181f7c69190f56f -->
## Social Login Facebook Callback
Callback untuk menerima data user ketika berhasil login dari Facebook.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/provider/facebook/callback");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/authenticate/provider/facebook/callback" \
    -H "Authorization: Bearer {token}"
```


> Example response:

```json
null
```

### HTTP Request
`GET api/v2/authenticate/provider/facebook/callback`


<!-- END_9a2a5f952c9592d7e181f7c69190f56f -->

<!-- START_9eb205197d7bfdaa582d117fe7ae096a -->
## Social Login Google Callback
Callback untuk menerima data user ketika berhasil login dari Google.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/provider/google/callback");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/authenticate/provider/google/callback" \
    -H "Authorization: Bearer {token}"
```


> Example response:

```json
null
```

### HTTP Request
`GET api/v2/authenticate/provider/google/callback`


<!-- END_9eb205197d7bfdaa582d117fe7ae096a -->

<!-- START_ecac97b5155d5fe347970ee373f6008f -->
## Reset Password
Reset Password digunakan untuk user yang ingin mengganti password tapi tidak login dan harus verifikasi OTP terlebih dahulu.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/password/reset");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "client_id": 1,
    "email": "user@mail.com",
    "old_password": "loremipsum",
    "new_password": "loremipsum2",
    "ip": "127.0.0.1",
    "user_agent": "Google Chrome"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/password/reset" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"client_id":1,"email":"user@mail.com","old_password":"loremipsum","new_password":"loremipsum2","ip":"127.0.0.1","user_agent":"Google Chrome"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Password anda berhasil di perbaharui."
}
```

### HTTP Request
`POST api/v2/authenticate/password/reset`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    client_id | integer |  required  | Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ]
    email | string |  required  | Email wajib diisi
    old_password | string |  required  | Password Lama wajib diisi min:8 Karakter
    new_password | string |  required  | Password Baru wajib diisi min:8 Karakter
    ip | string |  optional  | IP address untuk disimpan sebagai log user
    user_agent | string |  optional  | User Agent untuk disimpan sebagai log user

<!-- END_ecac97b5155d5fe347970ee373f6008f -->

<!-- START_645b36b70b24f031ee5ea40face85e2f -->
## Refresh
Refresh token untuk mengenerate token baru dan mengexpired token lama

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/refresh");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "client_id": 1,
    "ip": "127.0.0.1",
    "user_agent": "Google Chrome"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/refresh" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"client_id":1,"ip":"127.0.0.1","user_agent":"Google Chrome"}'

```



### HTTP Request
`POST api/v2/authenticate/refresh`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    client_id | integer |  required  | Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ]
    ip | string |  optional  | IP address untuk disimpan sebagai log user
    user_agent | string |  optional  | User Agent untuk disimpan sebagai log user

<!-- END_645b36b70b24f031ee5ea40face85e2f -->

<!-- START_94d055d15f7a81e83cd02d9b5217afb8 -->
## Logout
Logout untuk menghapus dan menonaktifkan jwt user

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/logout");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/logout" \
    -H "Authorization: Bearer {token}"
```



### HTTP Request
`POST api/v2/authenticate/logout`


<!-- END_94d055d15f7a81e83cd02d9b5217afb8 -->

<!-- START_1d98aa03fa3cadbf11ab21f5459e0a93 -->
## Change Password
Change Password digunakan untuk user yang ingin mengganti password dengan syarat sudah login terlebih dahulu.

> Example request:

```javascript
const url = new URL("/api/v2/authenticate/password/change");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "client_id": 1,
    "old_password": "loremipsum",
    "new_password": "loremipsum2",
    "ip": "127.0.0.1",
    "user_agent": "Google Chrome"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/authenticate/password/change" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"client_id":1,"old_password":"loremipsum","new_password":"loremipsum2","ip":"127.0.0.1","user_agent":"Google Chrome"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Password anda berhasil di perbaharui."
}
```

### HTTP Request
`POST api/v2/authenticate/password/change`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    client_id | integer |  required  | Client ID wajib diisi dan default [1 = 'web', 2 = 'mobile', 3 = 'android', 4 = 'ios' ]
    old_password | string |  required  | Password Lama wajib diisi min:8 Karakter
    new_password | string |  required  | Password Baru wajib diisi min:8 Karakter
    ip | string |  optional  | IP address untuk disimpan sebagai log user
    user_agent | string |  optional  | User Agent untuk disimpan sebagai log user

<!-- END_1d98aa03fa3cadbf11ab21f5459e0a93 -->

#Banners

API untuk menampilkan list banner.
<!-- START_3cc1cbe1bbeff8f553b58bbaaef8002c -->
## Banner List
Menampilkan semua banner list untuk ditampilkan di halaman utama / beranda.

> Example request:

```javascript
const url = new URL("/api/v2/banner/list");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/banner/list" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "list": [
            {
                "id": 5,
                "position_id": 1,
                "name": "Slide 2",
                "photo": "98e082a7eadbcf0175e1c0c1feb6426a.jpg",
                "url": "https:\/\/mymspmall.id"
            },
            {
                "id": 22,
                "position_id": 1,
                "name": "Promo Ongkir",
                "photo": "53c3c77a7093e40b8386a35ab92683b7.jpeg",
                "url": "https:\/\/mymspmall.id"
            },
            {
                "id": 15,
                "position_id": 1,
                "name": "Histeria Vaganza",
                "photo": "fabe76374b4a539af0f4690e07f70c01.jpeg",
                "url": "http:\/\/localhost:8000\/page\/syarat-ketentuan-histeria-vaganza"
            }
        ],
        "image_path": "http:\/\/127.0.0.1:8000\/uploads\/slides"
    }
}
```

### HTTP Request
`GET api/v2/banner/list`


<!-- END_3cc1cbe1bbeff8f553b58bbaaef8002c -->

<!-- START_767e122213c47009572270e209698f0c -->
## Banner Digital
Menampilkan semua banner digital untuk ditampilkan di bagian iklan digital.

> Example request:

```javascript
const url = new URL("/api/v2/banner/digital");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/banner/digital" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "id": 1,
            "title": "Promo Cashback 100%",
            "description": "<p>Pada tanggal 1 Agustus 2019, MSP Mall akan melaunching Top Up Pulsa & Paket Data. Maka dari itu MSP Mall akan memberikan Promo Cash Back 100%.<br><\/p>",
            "image_path": "uploads\/Banner\/f2a0122a2b3f32cea6281b41f871b80e.png",
            "link": "https:\/\/mymspmall.id",
            "flag": 1,
            "publish_date": "2019-08-22 07:00:00",
            "end_date": "2019-08-25 08:00:00",
            "created_at": "2019-08-01 00:42:47",
            "updated_at": "2019-08-07 20:34:02",
            "deleted_at": null,
            "slug": "promo-cashback-100"
        },
        {
            "id": 2,
            "title": "banner2",
            "description": "<p>Deskripsi banner 2<br><\/p>",
            "image_path": "uploads\/Banner\/26b3e580b3617349022a1c17b295c951.jpg",
            "link": "https:\/\/mymspmall.id",
            "flag": 1,
            "publish_date": "2019-08-15 12:00:00",
            "end_date": "2019-08-30 12:00:00",
            "created_at": "2019-08-22 15:36:15",
            "updated_at": "2019-08-22 15:36:44",
            "deleted_at": null,
            "slug": "banner2"
        }
    ]
}
```

### HTTP Request
`GET api/v2/banner/digital`


<!-- END_767e122213c47009572270e209698f0c -->

#Digital

API untuk memproses digital seperti ppob dll.
<!-- START_20d1930090e2db5faff35278d7c6d512 -->
## Price List
Menampilkan semua digital price list seperti berdasarkan type &amp; provider

> Example request:

```javascript
const url = new URL("/api/v2/digital/pricelist");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "type": "consequatur",
    "provider": "sunt"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/digital/pricelist" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"type":"consequatur","provider":"sunt"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "pulsa_code": "haxis10000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "10000",
            "pulsa_price": 10800,
            "pulsa_type": "pulsa",
            "masaaktif": "15",
            "status": "active"
        },
        {
            "pulsa_code": "haxis100000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "100000",
            "pulsa_price": 100000,
            "pulsa_type": "pulsa",
            "masaaktif": "90",
            "status": "active"
        },
        {
            "pulsa_code": "haxis15000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "15000",
            "pulsa_price": 15000,
            "pulsa_type": "pulsa",
            "masaaktif": "0",
            "status": "active"
        },
        {
            "pulsa_code": "haxis200000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "200000",
            "pulsa_price": 200000,
            "pulsa_type": "pulsa",
            "masaaktif": "120",
            "status": "active"
        },
        {
            "pulsa_code": "haxis25000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "25000",
            "pulsa_price": 26000,
            "pulsa_type": "pulsa",
            "masaaktif": "30",
            "status": "active"
        },
        {
            "pulsa_code": "haxis5000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "5000",
            "pulsa_price": 5900,
            "pulsa_type": "pulsa",
            "masaaktif": "7",
            "status": "active"
        },
        {
            "pulsa_code": "haxis50000",
            "pulsa_op": "AXIS",
            "pulsa_nominal": "50000",
            "pulsa_price": 51000,
            "pulsa_type": "pulsa",
            "masaaktif": "60",
            "status": "active"
        }
    ]
}
```

### HTTP Request
`GET api/v2/digital/pricelist`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    type | string |  required  | menentukan type digital yang akan ditampilkan seperti pulsa,data
    provider | string |  required  | menentukan provider apa yang akan ditampilkan seperti INDOSAT, XL, AXIS, TELKOMSEL, SMARTFREN, TREE

<!-- END_20d1930090e2db5faff35278d7c6d512 -->

<!-- START_2cc718db4484b8f001b4964756b32c6a -->
## Price List Detail
Menampilkan detail price list seperti berdasarkan type &amp; pulsa_code

> Example request:

```javascript
const url = new URL("/api/v2/digital/pricelistdetail");

let headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "type": "pulsa",
    "pulsa_code": "haxis50000"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X POST "/api/v2/digital/pricelistdetail" \
    -H "Authorization: Bearer {token}" \
    -H "Content-Type: application/json" \
    -d '{"type":"pulsa","pulsa_code":"haxis50000"}'

```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "pulsa_code": "haxis50000",
        "pulsa_op": "AXIS",
        "pulsa_nominal": "50000",
        "pulsa_price": 51000,
        "pulsa_type": "pulsa",
        "masaaktif": "60",
        "status": "active"
    }
}
```

### HTTP Request
`POST api/v2/digital/pricelistdetail`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    type | string |  required  | type wajib diisi seperti pulsa atau data.
    pulsa_code | string |  required  | pulsa_code wajib seperti INDOSAT(hindosat, isatdata) ; XL ( xld, xldata ) ; AXIS ( haxis, axisdata ) ; TELKOMSEL ( htelkomsel, tseldata) ; SMARTFREN ( hsmart ) ;  THREE ( hthree, threedata). 

<!-- END_2cc718db4484b8f001b4964756b32c6a -->

#Miscellaneous List

API untuk mengakses beraneka macam list.
<!-- START_307e71b70aa1f4209f760f9753ae9f72 -->
## Category List
Menampilkan semua kategori produk.

> Example request:

```javascript
const url = new URL("/api/v2/list/category");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/list/category" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "id": 1,
            "parent_id": null,
            "name": "Kebutuhan Harian",
            "slug": "kebutuhan-harian",
            "icon": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories399f6fa45bf585ebdd93df8658569f04.png",
            "cover": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories4b6ad3246d2cefd428aa1e7d8cf788d8.png",
            "background": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories17728d85f7f3d3535ac6859a2bc626ec.jpg",
            "highlight": 1,
            "created_at": "2018-11-02 05:00:00",
            "updated_at": "2019-05-11 16:38:58",
            "child": []
        },
        {
            "id": 8,
            "parent_id": null,
            "name": "Peralatan Elektronik",
            "slug": "peralatan-elektronik",
            "icon": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories1ecf8a0a6d51bbfc63863c7d6622bb71.png",
            "cover": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "background": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "highlight": 1,
            "created_at": "2018-10-02 05:00:00",
            "updated_at": "2019-08-09 11:54:12",
            "child": [
                {
                    "id": 7,
                    "user_id": 1,
                    "parent_id": 8,
                    "name": "Hobi & Koleksi",
                    "slug": "hobi-koleksi",
                    "icon": "64539ae351fc0d842eed2c8f85159379.png",
                    "cover": null,
                    "background": null,
                    "highlight": 0,
                    "created_at": "2018-10-02 05:00:00",
                    "updated_at": "2019-09-07 10:22:54"
                },
                {
                    "id": 9,
                    "user_id": 1,
                    "parent_id": 8,
                    "name": "HP, Komp & Aks",
                    "slug": "hp-komp-aks",
                    "icon": "49d9886f46a933496e1adc2abc20ab51.png",
                    "cover": "9208211a4ee586be7cb6c9dbf8c176ef.jpeg",
                    "background": "8a8ef872198185a81fd1615a2bf8fb53.jpg",
                    "highlight": 0,
                    "created_at": "2018-10-02 05:00:00",
                    "updated_at": "2019-08-09 11:55:28"
                }
            ]
        },
        {
            "id": 10,
            "parent_id": null,
            "name": "Olahraga & Outdoor",
            "slug": "olahraga-outdoor",
            "icon": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories0315c110571cd6c765a9d9dbad569482.png",
            "cover": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "background": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "highlight": 0,
            "created_at": "2018-10-02 05:00:00",
            "updated_at": "2019-09-07 10:23:48",
            "child": [
                {
                    "id": 11,
                    "user_id": 1,
                    "parent_id": 10,
                    "name": "Otomotif",
                    "slug": "otomotif",
                    "icon": "f89a8903b4a23f6fe5b71b8dd4432329.png",
                    "cover": "eb0ba41b0a4dc19e896b8aef1651821a.jpeg",
                    "background": null,
                    "highlight": 0,
                    "created_at": "2018-10-02 05:00:00",
                    "updated_at": "2019-09-07 10:23:23"
                }
            ]
        },
        {
            "id": 12,
            "parent_id": null,
            "name": "E-Voucher",
            "slug": "e-voucher",
            "icon": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories1583668f730d96c8d6fd4264c312a55a.png",
            "cover": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "background": "\/Users\/admin\/MONSPACE\/live-msp-mall\/public\/uploads\/categories",
            "highlight": 0,
            "created_at": "2018-10-02 05:00:00",
            "updated_at": "2019-04-08 12:40:50",
            "child": []
        }
    ]
}
```

### HTTP Request
`GET api/v2/list/category`


<!-- END_307e71b70aa1f4209f760f9753ae9f72 -->

<!-- START_da5a0cbf6677eca63d92a19faa3ff639 -->
## Footer List
Menampilkan semua data footer berupa link.

> Example request:

```javascript
const url = new URL("/api/v2/list/footer");

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/list/footer" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "id": 1,
            "name": "MyMSPMall",
            "slug": "mymspmall",
            "position_id": 1,
            "created_at": "2018-11-23 15:37:28",
            "updated_at": "2018-11-23 15:37:28",
            "links": [
                {
                    "name": "Beriklan Sekarang",
                    "url": "http:\/\/localhost:8000\/ads"
                },
                {
                    "name": "MSP Forum",
                    "url": "http:\/\/forum.mymspmall.id"
                },
                {
                    "name": "Tentang Kami",
                    "url": "http:\/\/localhost:8000\/page\/tentang-kami"
                },
                {
                    "name": "Syarat & Ketentuan Histeria Vaganza",
                    "url": "http:\/\/localhost:8000\/page\/syarat-ketentuan-histeria-vaganza"
                }
            ]
        },
        {
            "id": 2,
            "name": "Pembelian",
            "slug": "pembelian",
            "position_id": 1,
            "created_at": "2018-11-23 15:37:36",
            "updated_at": "2018-11-23 15:37:36",
            "links": [
                {
                    "name": "Panduan Belanja",
                    "url": "http:\/\/localhost:8000\/page\/panduan-belanja"
                },
                {
                    "name": "Kebijakan Pengiriman",
                    "url": "http:\/\/localhost:8000\/page\/kebijakan-pengiriman"
                },
                {
                    "name": "Kebijakan Pengembalian",
                    "url": "http:\/\/localhost:8000\/page\/kebijakan-pengembalian"
                }
            ]
        },
        {
            "id": 3,
            "name": "Penjualan",
            "slug": "penjualan",
            "position_id": 1,
            "created_at": "2018-11-23 15:37:42",
            "updated_at": "2018-11-23 15:37:42",
            "links": [
                {
                    "name": "Menjadi Merchant",
                    "url": "http:\/\/localhost:8000\/merchant\/join"
                }
            ]
        },
        {
            "id": 4,
            "name": "Bantuan",
            "slug": "bantuan",
            "position_id": 1,
            "created_at": "2018-11-23 15:37:49",
            "updated_at": "2018-11-23 15:37:49",
            "links": [
                {
                    "name": "Reset Password",
                    "url": "http:\/\/localhost:8000\/password\/reset"
                },
                {
                    "name": "Syarat & Ketentuan",
                    "url": "http:\/\/localhost:8000\/page\/syarat-ketentuan"
                },
                {
                    "name": "Kebijakan Privasi",
                    "url": "http:\/\/localhost:8000\/page\/kebijakan-privasi"
                },
                {
                    "name": "Panduan Keamanan",
                    "url": "http:\/\/localhost:8000\/page\/panduan-keamanan"
                }
            ]
        }
    ]
}
```

### HTTP Request
`GET api/v2/list/footer`


<!-- END_da5a0cbf6677eca63d92a19faa3ff639 -->

#Products

API untuk mengakses list data produk terbaru, trpopuler, produk promo, promo musiman dan sebagainya.
<!-- START_0c70807fd369533c715e6925b1805a52 -->
## Flash Sale
Menampilkan semua produk yang sedang flash sale.

> Example request:

```javascript
const url = new URL("/api/v2/product/flash-sale");

    let params = {
            "limit": "16",
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/flash-sale" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "countdown": {
            "expiry_at": "2019-08-24 20:00:00",
            "expiry_timestamp": 1566651600
        },
        "image_path": "http:\/\/localhost:8000\/uploads\/products",
        "current_page": 1,
        "data": [
            {
                "type_id": 1,
                "category_id": 1,
                "condition_id": 1,
                "name": "Beras 9Bako 5Kg",
                "slug": "beras-9bako-5kg",
                "weight": 5000,
                "price": 58000,
                "discount": 65000,
                "stock": 13,
                "sold": 6,
                "description": "Beras 9Bako 5Kg",
                "point": 2,
                "rating": 3,
                "review": 6,
                "status": 1,
                "action_id": 1,
                "action_content": null,
                "sale": 1,
                "preorder": 0,
                "preorder_target": null,
                "preorder_expired": null,
                "voucher_expired": null,
                "max_amount_per_days": 0,
                "photo": "2a4ff504624e8dc66b2afae19e6ae6b3.png",
                "seller": {
                    "type_id": 4,
                    "name": "MONSMART",
                    "referral_name": null,
                    "category_id": 10,
                    "additional_id": 1,
                    "finance_id": 16,
                    "shipping_pos": 1,
                    "shipping_jne": 1,
                    "shipping_tiki": 1,
                    "status": 1,
                    "merchant": {
                        "merchant_id": 14,
                        "name": "Merchant Store",
                        "address": "Kota Medan",
                        "provinsi_id": 12,
                        "kabupaten_id": 1275,
                        "kecamatan_id": 1275140,
                        "desa_id": 1275140005,
                        "postal_code": 20115
                    }
                },
                "category": {
                    "parent_id": null,
                    "name": "Kebutuhan Harian",
                    "slug": "kebutuhan-harian",
                    "icon": "399f6fa45bf585ebdd93df8658569f04.png",
                    "cover": "4b6ad3246d2cefd428aa1e7d8cf788d8.png",
                    "background": "17728d85f7f3d3535ac6859a2bc626ec.jpg",
                    "highlight": 1
                }
            },
            {
                "type_id": 1,
                "category_id": 1,
                "condition_id": 1,
                "name": "Pencuci Piring Sunlight 800ml (2Pcs)",
                "slug": "pencuci-piring-sunlight-800ml-2pcs",
                "weight": 1600,
                "price": 34000,
                "discount": 35020,
                "stock": 3,
                "sold": 10,
                "description": "PENCUCI PIRING SUNLIGHT 800ML BANDED\r\n\r\nHarga yang tercantum sudah mendapatkan 2 bungkus sunlight",
                "point": 2,
                "rating": 4,
                "review": 9,
                "status": 1,
                "action_id": 2,
                "action_content": null,
                "sale": 1,
                "preorder": 0,
                "preorder_target": null,
                "preorder_expired": null,
                "voucher_expired": null,
                "max_amount_per_days": 0,
                "photo": "f9f59970c75bf434542dff89172d486a.png",
                "seller": {
                    "type_id": 4,
                    "name": "MONSMART",
                    "referral_name": null,
                    "category_id": 10,
                    "additional_id": 1,
                    "finance_id": 16,
                    "shipping_pos": 1,
                    "shipping_jne": 1,
                    "shipping_tiki": 1,
                    "status": 1,
                    "merchant": {
                        "merchant_id": 14,
                        "name": "Merchant Store",
                        "address": "Kota Medan",
                        "provinsi_id": 12,
                        "kabupaten_id": 1275,
                        "kecamatan_id": 1275140,
                        "desa_id": 1275140005,
                        "postal_code": 20115
                    }
                },
                "category": {
                    "parent_id": null,
                    "name": "Kebutuhan Harian",
                    "slug": "kebutuhan-harian",
                    "icon": "399f6fa45bf585ebdd93df8658569f04.png",
                    "cover": "4b6ad3246d2cefd428aa1e7d8cf788d8.png",
                    "background": "17728d85f7f3d3535ac6859a2bc626ec.jpg",
                    "highlight": 1
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/flash-sale?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/flash-sale?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/v2\/product\/flash-sale",
        "per_page": "5",
        "prev_page_url": null,
        "to": 4,
        "total": 4
    }
}
```

### HTTP Request
`GET api/v2/product/flash-sale`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan.
    page |  optional  | default hanya menampilkan halaman pertama.

<!-- END_0c70807fd369533c715e6925b1805a52 -->

<!-- START_9b68ab1695f9e2aa3c6002f9d4be688d -->
## Group Buy Promo
Menampilkan semua produk yang ada dalam group buy promo.

> Example request:

```javascript
const url = new URL("/api/v2/product/group-buy-promo");

    let params = {
            "limit": "16",
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/group-buy-promo" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "image_path": null,
        "current_page": 1,
        "data": [
            {
                "type_id": 1,
                "category_id": 2,
                "condition_id": 1,
                "name": "Dildo",
                "slug": "dildo",
                "weight": 1,
                "price": 17000,
                "discount": 20000,
                "stock": 12,
                "sold": 0,
                "description": "Test Dildo",
                "point": 0,
                "rating": 0,
                "review": 0,
                "status": 1,
                "action_id": 1,
                "action_content": null,
                "sale": 0,
                "preorder": 1,
                "preorder_target": 2,
                "preorder_expired": "2019-08-30 15:37:33",
                "voucher_expired": null,
                "max_amount_per_days": 0,
                "photo": "150c98871f28864114949bdbdb50ae4a.jpg",
                "seller": {
                    "type_id": 2,
                    "name": "RShop",
                    "referral_name": null,
                    "category_id": 12,
                    "additional_id": 8,
                    "finance_id": 197,
                    "shipping_pos": 1,
                    "shipping_jne": 1,
                    "shipping_tiki": 1,
                    "status": 1,
                    "merchant": {
                        "merchant_id": 201,
                        "name": "Merchant Store",
                        "address": "Kota Medan",
                        "provinsi_id": 12,
                        "kabupaten_id": 1275,
                        "kecamatan_id": 1275150,
                        "desa_id": 1275150008,
                        "postal_code": 22222
                    }
                },
                "category": {
                    "parent_id": null,
                    "name": "Kesehatan",
                    "slug": "kesehatan",
                    "icon": "449774d5bdae195e3f0ce24ce33df3c6.png",
                    "cover": "ad81fab6fcfc225f7cf8bdbee8252283.png",
                    "background": "0e420f44be44a3059cf9dea86de6851e.jpg",
                    "highlight": 1
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/group-buy-promo?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/group-buy-promo?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/v2\/product\/group-buy-promo",
        "per_page": "5",
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/v2/product/group-buy-promo`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan.
    page |  optional  | default hanya menampilkan halaman pertama.

<!-- END_9b68ab1695f9e2aa3c6002f9d4be688d -->

<!-- START_d82935da822d88cc5a351007f854f8b3 -->
## Category Highlight
Menampilkan semua produk kategori yang di tampilkan di halaman home.

> Example request:

```javascript
const url = new URL("/api/v2/product/category-highlight");

    let params = {
            "limit": "16",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/category-highlight" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "parent_id": null,
            "name": "Kesehatan",
            "slug": "kesehatan",
            "icon": "449774d5bdae195e3f0ce24ce33df3c6.png",
            "cover": "ad81fab6fcfc225f7cf8bdbee8252283.png",
            "background": "0e420f44be44a3059cf9dea86de6851e.jpg",
            "highlight": 1,
            "products": [
                {
                    "type_id": 1,
                    "category_id": 2,
                    "condition_id": 2,
                    "name": "Yao Tong Pian",
                    "slug": "yao-tong-pian",
                    "weight": 90,
                    "price": 30500,
                    "discount": 40500,
                    "stock": 25,
                    "sold": 0,
                    "description": "Kpib 00805\r\n\r\nBotol Berisi 100 tablet\r\n\r\nKhasiat \r\n- Membantu meringankan sakit pinggang\r\n- Memelihara kesehatan fungsi ginjal\r\n- Memperlancar peredaran darah\r\n- Menghilangkan rasa sakit\r\n\r\nDosis\r\nDiminum 3 kali sehari , sekali minum 6 tablet. \r\nDiminum dengan air hangat dan dicampur garam sedikit.\r\n\r\nYao Tong Wan atau Anti-Lumbago berkhasiat membantu meredakan sakit pinggang. Terutama sakit pinggang yang disebabkan oleh menurunnya kesehatan fungsi ginjal, cidera otot, dan kelelahan.\r\n\r\nKhasiat :\r\nRamuan Herbal alami dari You Tong Wan dibuat khusus untuk membantu menyehatkan ginjal dan hati yang berakibat pada sirkulasi darah lebih baik sehingga menguatkan dan merelaksasikan otot pinggang. Selain itu ramuan ini juga mengandung Dispaci Radix yang berfungsi menguatkan tulang.",
                    "point": null,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "52e7139235d9c2747424acc41ad5458c.jpg",
                    "seller": {
                        "type_id": 1,
                        "name": "Noe Shop",
                        "referral_name": null,
                        "category_id": 2,
                        "additional_id": 3,
                        "finance_id": 157,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 160,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275200,
                            "desa_id": 1275200002,
                            "postal_code": 20255
                        }
                    }
                },
                {
                    "type_id": 1,
                    "category_id": 2,
                    "condition_id": 1,
                    "name": "Fufang Ejiao Jiang- obat demam berdarah & trombosit",
                    "slug": "fufang-ejiao-jiang-obat-demam-berdarah-trombosit",
                    "weight": 20,
                    "price": 180000,
                    "discount": 190000,
                    "stock": 25,
                    "sold": 0,
                    "description": "Penjelasan Singkat :\r\n- Banyak digunakan sebagai penambah darah dan energi\r\n- Dapat membantu meningkatkan daya tahan tubuh\r\n- Dapat membantu meningkatkan jumlah trombosit\r\n- Cocok digunakan bagi penderita anemia, sulit tidur, kurang nafsu makan, dll\r\n\r\nFufang Ejiao Jiang, Merupakan obat penambah darah yang terbuat dari bahan - bahan herbal berkualitas tinggi. \r\n\r\nKhasiat :\r\n- Membantu mempercepat pemulihan DBD dan meningkatkan kadar trombosit\r\n- Mengandung protein, asam amino, kalsium, besi, seng , dan alkaloid saponin yang berkhasiat untuk mengatasi masalah anemia\r\n- Membantu melancarkan HAID yang tidak teratur serta nyeri HAID\r\n- Membantu meningkatkan kadar sel darah merah, sel darah putih, dan trombosit serta mengurangi efek samping KEMOTERAPI\r\n- Mengandung bahan yang berkhasiat menambah stamina dan mengembalikan energi setelah melahirkan.\r\n\r\nCara Pakai : Diminum sehari 3 x 20 mL.\r\n\r\nPerhatian :\r\nBila telah disimpan dalam jangka waktu teretentu akan terjadi endapan yang tidak mengganggu mutu dan khasiat, kocok dahulu sebelum minum, tidak boleh digunakan bersama dengan obat yang menghambat pembekuan darah seperti warfarin dan acetosal, tidak dianjurkan untuk wanita hamil dan menyusui serta ana-anak, hati-hati jika digunakan pada penderita hi[ertensi atau diabetes, tutup yang rapat dan disimpan di tempat yang sejuk.\r\n\r\nKomposisi : \r\nCorii Asini Colla 11 mL, Codonopsis Pilosulae Radix 2 mL, Crataegus Pinnatifida 1 mL, Rehmanniae Glutinosa Radix 1.6 mL, Panax Ginseng Radix 4.4 mL\r\n\r\nKemasan : Dus berisi 12 botol @ 20 ml.\r\n\r\nDiproduksi Oleh : Shandong DongE EJiao Co.,Ltd\r\n\r\nDistributor : PT. Saras Subur Abadi\r\n\r\nBPOM No : TI 164 650 681",
                    "point": null,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "d93098af1289dd3e22130af3c6ccfd47.jpg",
                    "seller": {
                        "type_id": 1,
                        "name": "Noe Shop",
                        "referral_name": null,
                        "category_id": 2,
                        "additional_id": 3,
                        "finance_id": 157,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 160,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275200,
                            "desa_id": 1275200002,
                            "postal_code": 20255
                        }
                    }
                }
            ]
        },
        {
            "parent_id": null,
            "name": "Peralatan Elektronik",
            "slug": "peralatan-elektronik",
            "icon": "1ecf8a0a6d51bbfc63863c7d6622bb71.png",
            "cover": null,
            "background": null,
            "highlight": 1,
            "products": [
                {
                    "type_id": 1,
                    "category_id": 8,
                    "condition_id": 1,
                    "name": "Playstation 4 Fat 500GB",
                    "slug": "playstation-4-fat-500gb",
                    "weight": 6000,
                    "price": 3600000,
                    "discount": 4000000,
                    "stock": 15,
                    "sold": 0,
                    "description": "PS4 FAT - Versi TERBARU.\r\nHARDISK INTERNAL 500GB.\r\nBISA MAIN GAME TERBARU DAN ONLINE PS4.\r\nSEGEL VOID SONY, REGION JEPANG.\r\nBARANG MULUS & KONDISI FIT, DIJAMIN.\r\nSERI CUH 1000A,1100A,1200A (Dikirim sesuai ketersedian stok barang).\r\nMESIN REKONDISI\/REFURBISHED TOKO.\r\n\r\nKELENGKAPAN PRODUK:\r\n1 UNIT PS4 FAT 500GB REFURBISHED TOKO.\r\n1 BUAH STICK PS4 (HITAM).\r\n1 KABEL POWER.\r\n1 KABEL HDMI.\r\n1 KABEL USB STICK PS4.\r\n1 DUS PS4.\r\n\r\n( Pengiriman dilengkapi oleh Dus Tambahan, Bubble Wrap, Packing Safety)\r\nSebelum dikirim, semua barang sudah kami test terlebih dahulu.\r\n\r\n**GARANSI MESIN 1 BULAN SPAREPART (BISA TUKAR UNIT BARU) JIKA ADA KERUSAKAN DARI PABRIK.",
                    "point": 0,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "03c53827915cb824b00be855047d6d45.jpg",
                    "seller": {
                        "type_id": 1,
                        "name": "Wannabe",
                        "referral_name": null,
                        "category_id": 11,
                        "additional_id": 10,
                        "finance_id": 126,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 128,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275160,
                            "desa_id": 1275160008,
                            "postal_code": 20216
                        }
                    }
                },
                {
                    "type_id": 1,
                    "category_id": 8,
                    "condition_id": 1,
                    "name": "Timbangan Dapur digital \/ Timbangan kopi \/ timbangan sayur1kg 0.1g",
                    "slug": "timbangan-dapur-digital-timbangan-kopi-timbangan-sayur1kg-01g",
                    "weight": 500,
                    "price": 125000,
                    "discount": 135000,
                    "stock": 20,
                    "sold": 0,
                    "description": "PE00634\r\n\r\nTimbangan elektronik yang dapat memuat berat hingga 1kg dengan keakuratan 0.1g. timbangan beralasan lempengan besi ini sangat kokoh dah kuat. Timbangan ini dapat digunakan untuk menimbang berat emas, koin, dll.\r\n\r\nFeatures\r\nBlue LCD Display\r\nTimbangan digital ini memiliki layar LCD berwarna biru sebagai penunjuk angka sehingga tetap dapat digunakan ditempat yang kurang cahaya.\r\n\r\n1kg Load\r\nTimbangan mini ini dapat mengukur hingga berat 1kg dengan tingkat keakuratan 0.1g.\r\n\r\nEasy To Use\r\nDesain yang kecil dan mudah untuk dibawa kemana saja.\r\n\r\nPackage Contents\r\nBarang-barang yang Anda dapat dalam kotak produk:\r\n\r\n1 x Scale\r\n1 x Plastic Holder\r\n2 x AAA Battery\r\n\r\nSpek\r\nBattery Type 2 x AAA\r\nDimension130 x 109 x 20 mm\r\nOMHAKFSV",
                    "point": null,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "b57894f4e0bf95638bc94ed7b3a5c541.jpg",
                    "seller": {
                        "type_id": 1,
                        "name": "StarShop",
                        "referral_name": null,
                        "category_id": 8,
                        "additional_id": 7,
                        "finance_id": 159,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 162,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275150,
                            "desa_id": 1275150008,
                            "postal_code": 20239
                        }
                    }
                }
            ]
        }
    ]
}
```

### HTTP Request
`GET api/v2/product/category-highlight`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan per kategori.

<!-- END_d82935da822d88cc5a351007f854f8b3 -->

<!-- START_2676d237efc09035949ecfde4474eb51 -->
## Category Product Detail
Menampilkan semua produk detail produk berdasarkan kategori produk.

> Example request:

```javascript
const url = new URL("/api/v2/product/category/slug-field");

    let params = {
            "limit": "16",
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/category/slug-field" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "current_page": 1,
        "data": [
            {
                "parent_id": null,
                "name": "Peralatan Elektronik",
                "slug": "peralatan-elektronik",
                "icon": "1ecf8a0a6d51bbfc63863c7d6622bb71.png",
                "cover": null,
                "background": null,
                "highlight": 1,
                "products": [
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "NETGEAR R7800, Smart Wifi Router",
                        "slug": "netgear-r7800-smart-wifi-router",
                        "weight": 1000,
                        "price": 4200000,
                        "discount": 4500000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00590",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "e704ff5d57eb3190174e063185d90a28.jpg",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    },
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "Original Ruizu mp3 player",
                        "slug": "original-ruizu-mp3-player",
                        "weight": 300,
                        "price": 518900,
                        "discount": 529000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00206\r\n\r\ngaransi : 1 bulan Ruizu X02 merupakan Digital Audio Player untuk memutar musik\r\ndengan kualitas HiFi. Mendukung format file MP3, WMA, FLAC, WAV, WMA, APE,\r\nAudible, OGG, APE, AAC-LC, ACELP. DAP ini juga memiliki fungsi lain seperti\r\nkalender, ebook reader, sound recorder, FM Radio, Timer, Sleep Time, dll.\r\nFeatures Lossless Music Playback Support MP3 player ini dapat memainkan file\r\nmusik lossless seperti FLAC. Large Battery Capacity Kapasitas baterai mencapai\r\n80 jam penggunaan. Anda tidak perlu repot mengisi baterai MP3 player ini setiap\r\nharinya. 8GB Internal + TF Card Support Memiliki kapasitas penyimpanan 8GB dan\r\njuga memori tambahan dengan menambah kartu micro SD. Anda dapat menyimpan semua\r\nlagu favorit Anda di MP3 player ini. Many Function DAP ini juga memiliki fungsi\r\nlain seperti kalender, ebook reader, sound recorder, FM Radio, Timer, Sleep\r\nTime, dll. Package Contents Barang-barang yang Anda dapat dalam kotak produk: 1\r\nx MP3 Player 1 x USB Cable 1 x Manual spes : Connection : 3.5mm Jack Battery\r\nType : Built-in rechargeable lithium battery Software Support : Audio support:\r\nAPE, MP3, WAV, WMA E-book support: TXT Picture support: BMP, JPEG, JPG Dimension\r\n: 90 x 38 x 6 mm Others : Storage: 8GB Internal + TF Card Support Display: 1.9 x\r\n1.4 Inch Resolution: 160 x 128 px FM Radio",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "17994b22b4dbffab05111c9383ccdf50.png",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik",
        "per_page": "5",
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/v2/product/category/{slug}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan per kategori.
    page |  optional  | default hanya menampilkan halaman pertama.

<!-- END_2676d237efc09035949ecfde4474eb51 -->

<!-- START_ee5d7cf2338164516e31e885640b24da -->
## Seasonal Promo
Menampilkan semua produk Promo Musiman untuk ditampilkan di halaman home.

> Example request:

```javascript
const url = new URL("/api/v2/product/seasonal-promo");

    let params = {
            "limit": "16",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/seasonal-promo" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": [
        {
            "name": "Promo Murah Meriha",
            "slug": "promo-murah-meriha",
            "background": "dd52991d9422a184061c3af8d03932f6.jpg",
            "expired": "2019-08-27 12:00:00",
            "products": [
                {
                    "type_id": 1,
                    "category_id": 5,
                    "condition_id": 1,
                    "name": "Fiza Heels",
                    "slug": "fiza-heels",
                    "weight": 500,
                    "price": 95000,
                    "discount": 105000,
                    "stock": 12,
                    "sold": 0,
                    "description": "High Heels dengan Bahan Kulit sintetis Kilat\r\nSize 36 - 40\r\nHeels : 5 cm\r\nKualitas Baik",
                    "point": 5,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 2,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "94ade43bdaad08eeff07dea1832230f2.png",
                    "seller": {
                        "type_id": 2,
                        "name": "Toko Icha",
                        "referral_name": null,
                        "category_id": 6,
                        "additional_id": 6,
                        "finance_id": 28,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 27,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275060,
                            "desa_id": 1275060011,
                            "postal_code": 20212
                        }
                    },
                    "category": {
                        "parent_id": null,
                        "name": "Fashion",
                        "slug": "fashion",
                        "icon": "55cdc64320773c64fac306ea9473ab7a.png",
                        "cover": "8ba0abab89ba682de506e8b908120ad0.png",
                        "background": "c665d1b9670e21b31036ee3d393ab5fa.jpg",
                        "highlight": 1
                    }
                },
                {
                    "type_id": 1,
                    "category_id": 4,
                    "condition_id": 1,
                    "name": "Bialetti Moka Express 1 Cup",
                    "slug": "bialetti-moka-express-1-cup",
                    "weight": 500,
                    "price": 390000,
                    "discount": 450000,
                    "stock": 10,
                    "sold": 0,
                    "description": "Bialetti Moka Express adalah moka pot yang mampu menghasilkan kopi dengan hasil terbaik. Didesain dengan begitu apik dan modern menggunakan food grade aluminium alloy yang aman bagi kesehatan tentunya akan menjadi teman setia anda dalam menikmati kopi sehari-hari. Membuat kopi nikmat tentunya bukan lagi sesuatu yang meribetkan karena moka pot yang satu ini akan memudahkan anda menikmati espresso dalam hitungan menit. Bialetti Moka Express terdiri dari bagian inti yaitu wadah air, saringan kopi dan teko pada bagian atas tempat kopi dihasilkan. Jangan khawatir bahwa moka pot ini susah dibersihkan karena yang anda butuhkan hanya membasuhnya dengan air hangat dan keringkan sebelum disimpan.",
                    "point": 5,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "3e19d2283fbf8fb275e6ef076bcc0f1f.jpg",
                    "seller": {
                        "type_id": 1,
                        "name": "Jimm's Brew",
                        "referral_name": null,
                        "category_id": 4,
                        "additional_id": 1,
                        "finance_id": 34,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 33,
                            "name": "Merchant Store",
                            "address": "Kabupaten Deli Serdang",
                            "provinsi_id": 12,
                            "kabupaten_id": 1212,
                            "kecamatan_id": 1212230,
                            "desa_id": 1212230007,
                            "postal_code": 20352
                        }
                    },
                    "category": {
                        "parent_id": null,
                        "name": "Keperluan Rumah",
                        "slug": "keperluan-rumah",
                        "icon": "85cfa5f30a84a629280359bb4a3246e1.png",
                        "cover": "0c41bf633d4e20bf4644f46b4e884dc6.jpeg",
                        "background": null,
                        "highlight": 0
                    }
                }
            ]
        },
        {
            "name": "Promo Musiman",
            "slug": "promo-musiman",
            "background": "69187eb19524757445728fcfbf0de2bc.jpg",
            "expired": "2019-08-31 12:00:00",
            "products": [
                {
                    "type_id": 1,
                    "category_id": 1,
                    "condition_id": 1,
                    "name": "Beras Gajah 10Kg Elephas Maximus",
                    "slug": "beras-gajah-10kg-elephas-maximus",
                    "weight": 10000,
                    "price": 116500,
                    "discount": 120000,
                    "stock": 13,
                    "sold": 18,
                    "description": "BERAS GAJAH 10KG SAK ELEPHAS MAXIMUM",
                    "point": 3,
                    "rating": 4,
                    "review": 8,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": null,
                    "max_amount_per_days": 0,
                    "photo": "d7b593d7c8cb820c85ef05b30ffbbcad.png",
                    "seller": {
                        "type_id": 4,
                        "name": "MONSMART",
                        "referral_name": null,
                        "category_id": 10,
                        "additional_id": 1,
                        "finance_id": 16,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 14,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275140,
                            "desa_id": 1275140005,
                            "postal_code": 20115
                        }
                    },
                    "category": {
                        "parent_id": null,
                        "name": "Kebutuhan Harian",
                        "slug": "kebutuhan-harian",
                        "icon": "399f6fa45bf585ebdd93df8658569f04.png",
                        "cover": "4b6ad3246d2cefd428aa1e7d8cf788d8.png",
                        "background": "17728d85f7f3d3535ac6859a2bc626ec.jpg",
                        "highlight": 1
                    }
                },
                {
                    "type_id": 2,
                    "category_id": 12,
                    "condition_id": 1,
                    "name": "Certification",
                    "slug": "certification",
                    "weight": 0,
                    "price": 15000,
                    "discount": null,
                    "stock": 99,
                    "sold": 0,
                    "description": "Sertifikat belajar Sql",
                    "point": null,
                    "rating": 0,
                    "review": 0,
                    "status": 1,
                    "action_id": 1,
                    "action_content": null,
                    "sale": 0,
                    "preorder": 0,
                    "preorder_target": null,
                    "preorder_expired": null,
                    "voucher_expired": "2019-08-04 16:45:12",
                    "max_amount_per_days": 5,
                    "photo": "1c6b6e0d2bd824aa2c4d4677ae480aaf.png",
                    "seller": {
                        "type_id": 2,
                        "name": "RShop",
                        "referral_name": null,
                        "category_id": 12,
                        "additional_id": 8,
                        "finance_id": 197,
                        "shipping_pos": 1,
                        "shipping_jne": 1,
                        "shipping_tiki": 1,
                        "status": 1,
                        "merchant": {
                            "merchant_id": 201,
                            "name": "Merchant Store",
                            "address": "Kota Medan",
                            "provinsi_id": 12,
                            "kabupaten_id": 1275,
                            "kecamatan_id": 1275150,
                            "desa_id": 1275150008,
                            "postal_code": 22222
                        }
                    },
                    "category": {
                        "parent_id": null,
                        "name": "E-Voucher",
                        "slug": "e-voucher",
                        "icon": "1583668f730d96c8d6fd4264c312a55a.png",
                        "cover": null,
                        "background": null,
                        "highlight": 0
                    }
                }
            ]
        }
    ]
}
```

### HTTP Request
`GET api/v2/product/seasonal-promo`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan per promo.

<!-- END_ee5d7cf2338164516e31e885640b24da -->

<!-- START_a4652e15c04e100f024bf4599adc0899 -->
## Seasonal Promo Product Detail
Menampilkan semua detail produk promo musiman berdasarkan kategori promo.

> Example request:

```javascript
const url = new URL("/api/v2/product/seasonal-promo/slug-field");

    let params = {
            "limit": "16",
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/seasonal-promo/slug-field" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "current_page": 1,
        "data": [
            {
                "parent_id": null,
                "name": "Peralatan Elektronik",
                "slug": "peralatan-elektronik",
                "icon": "1ecf8a0a6d51bbfc63863c7d6622bb71.png",
                "cover": null,
                "background": null,
                "highlight": 1,
                "products": [
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "NETGEAR R7800, Smart Wifi Router",
                        "slug": "netgear-r7800-smart-wifi-router",
                        "weight": 1000,
                        "price": 4200000,
                        "discount": 4500000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00590",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "e704ff5d57eb3190174e063185d90a28.jpg",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    },
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "Original Ruizu mp3 player",
                        "slug": "original-ruizu-mp3-player",
                        "weight": 300,
                        "price": 518900,
                        "discount": 529000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00206\r\n\r\ngaransi : 1 bulan Ruizu X02 merupakan Digital Audio Player untuk memutar musik\r\ndengan kualitas HiFi. Mendukung format file MP3, WMA, FLAC, WAV, WMA, APE,\r\nAudible, OGG, APE, AAC-LC, ACELP. DAP ini juga memiliki fungsi lain seperti\r\nkalender, ebook reader, sound recorder, FM Radio, Timer, Sleep Time, dll.\r\nFeatures Lossless Music Playback Support MP3 player ini dapat memainkan file\r\nmusik lossless seperti FLAC. Large Battery Capacity Kapasitas baterai mencapai\r\n80 jam penggunaan. Anda tidak perlu repot mengisi baterai MP3 player ini setiap\r\nharinya. 8GB Internal + TF Card Support Memiliki kapasitas penyimpanan 8GB dan\r\njuga memori tambahan dengan menambah kartu micro SD. Anda dapat menyimpan semua\r\nlagu favorit Anda di MP3 player ini. Many Function DAP ini juga memiliki fungsi\r\nlain seperti kalender, ebook reader, sound recorder, FM Radio, Timer, Sleep\r\nTime, dll. Package Contents Barang-barang yang Anda dapat dalam kotak produk: 1\r\nx MP3 Player 1 x USB Cable 1 x Manual spes : Connection : 3.5mm Jack Battery\r\nType : Built-in rechargeable lithium battery Software Support : Audio support:\r\nAPE, MP3, WAV, WMA E-book support: TXT Picture support: BMP, JPEG, JPG Dimension\r\n: 90 x 38 x 6 mm Others : Storage: 8GB Internal + TF Card Support Display: 1.9 x\r\n1.4 Inch Resolution: 160 x 128 px FM Radio",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "17994b22b4dbffab05111c9383ccdf50.png",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik",
        "per_page": "5",
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/v2/product/seasonal-promo/{slug}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 12 data yang ditampilkan per kategori.
    page |  optional  | default hanya menampilkan halaman pertama.

<!-- END_a4652e15c04e100f024bf4599adc0899 -->

<!-- START_98e7a9c5abb1ccc0d239042167cf536e -->
## Recommendation
Menampilkan semua produk rekomendasi untuk user login atau pun guest.

> Example request:

```javascript
const url = new URL("/api/v2/product/recommendation");

    let params = {
            "limit": "24",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```bash
curl -X GET -G "/api/v2/product/recommendation" \
    -H "Authorization: Bearer {token}"
```


> Example response (200):

```json
{
    "code": 200,
    "status": "OK",
    "message": "Data berhasil ditampilkan",
    "items": {
        "current_page": 1,
        "data": [
            {
                "parent_id": null,
                "name": "Peralatan Elektronik",
                "slug": "peralatan-elektronik",
                "icon": "1ecf8a0a6d51bbfc63863c7d6622bb71.png",
                "cover": null,
                "background": null,
                "highlight": 1,
                "products": [
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "NETGEAR R7800, Smart Wifi Router",
                        "slug": "netgear-r7800-smart-wifi-router",
                        "weight": 1000,
                        "price": 4200000,
                        "discount": 4500000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00590",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "e704ff5d57eb3190174e063185d90a28.jpg",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    },
                    {
                        "type_id": 1,
                        "category_id": 8,
                        "condition_id": 1,
                        "name": "Original Ruizu mp3 player",
                        "slug": "original-ruizu-mp3-player",
                        "weight": 300,
                        "price": 518900,
                        "discount": 529000,
                        "stock": 20,
                        "sold": 0,
                        "description": "PE00206\r\n\r\ngaransi : 1 bulan Ruizu X02 merupakan Digital Audio Player untuk memutar musik\r\ndengan kualitas HiFi. Mendukung format file MP3, WMA, FLAC, WAV, WMA, APE,\r\nAudible, OGG, APE, AAC-LC, ACELP. DAP ini juga memiliki fungsi lain seperti\r\nkalender, ebook reader, sound recorder, FM Radio, Timer, Sleep Time, dll.\r\nFeatures Lossless Music Playback Support MP3 player ini dapat memainkan file\r\nmusik lossless seperti FLAC. Large Battery Capacity Kapasitas baterai mencapai\r\n80 jam penggunaan. Anda tidak perlu repot mengisi baterai MP3 player ini setiap\r\nharinya. 8GB Internal + TF Card Support Memiliki kapasitas penyimpanan 8GB dan\r\njuga memori tambahan dengan menambah kartu micro SD. Anda dapat menyimpan semua\r\nlagu favorit Anda di MP3 player ini. Many Function DAP ini juga memiliki fungsi\r\nlain seperti kalender, ebook reader, sound recorder, FM Radio, Timer, Sleep\r\nTime, dll. Package Contents Barang-barang yang Anda dapat dalam kotak produk: 1\r\nx MP3 Player 1 x USB Cable 1 x Manual spes : Connection : 3.5mm Jack Battery\r\nType : Built-in rechargeable lithium battery Software Support : Audio support:\r\nAPE, MP3, WAV, WMA E-book support: TXT Picture support: BMP, JPEG, JPG Dimension\r\n: 90 x 38 x 6 mm Others : Storage: 8GB Internal + TF Card Support Display: 1.9 x\r\n1.4 Inch Resolution: 160 x 128 px FM Radio",
                        "point": null,
                        "rating": 0,
                        "review": 0,
                        "status": 1,
                        "action_id": 1,
                        "action_content": null,
                        "sale": 0,
                        "preorder": 0,
                        "preorder_target": null,
                        "preorder_expired": null,
                        "voucher_expired": null,
                        "max_amount_per_days": 0,
                        "photo": "17994b22b4dbffab05111c9383ccdf50.png",
                        "seller": {
                            "type_id": 1,
                            "name": "StarShop",
                            "referral_name": null,
                            "category_id": 8,
                            "additional_id": 7,
                            "finance_id": 159,
                            "shipping_pos": 1,
                            "shipping_jne": 1,
                            "shipping_tiki": 1,
                            "status": 1,
                            "merchant": {
                                "merchant_id": 162,
                                "name": "Merchant Store",
                                "address": "Kota Medan",
                                "provinsi_id": 12,
                                "kabupaten_id": 1275,
                                "kecamatan_id": 1275150,
                                "desa_id": 1275150008,
                                "postal_code": 20239
                            }
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/v2\/product\/category-highlight\/peralatan-elektronik",
        "per_page": "5",
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/v2/product/recommendation`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | default 30 data yang ditampilkan per kategori.

<!-- END_98e7a9c5abb1ccc0d239042167cf536e -->


