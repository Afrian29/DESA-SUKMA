# Illuminate\Database\QueryException - Internal Server Error

SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'kades_name' cannot be null (Connection: mysql, SQL: update `village_profiles` set `kades_name` = ?, `sambutan_title` = ?, `sambutan_content` = ?, `video_url` = ?, `luas_wilayah` = ?, `umkm_count` = ?, `visi` = Untuk...., `misi` = INi Bagian
misi
desa, `village_profiles`.`updated_at` = 2025-12-13 17:14:32 where `id` = 1)

PHP 8.4.14
Laravel 12.40.2
127.0.0.1:8000

## Stack Trace

0 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:824
1 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:778
2 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:583
3 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:535
4 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3917
5 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:1266
6 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1316
7 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1233
8 - app\Http\Controllers\Admin\ProfileController.php:68
9 - vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
10 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
11 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
12 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
13 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
14 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
15 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
16 - vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
17 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
18 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
19 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
20 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
21 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
22 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
23 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
24 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
26 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
28 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
30 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
31 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
32 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
33 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
34 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
35 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
36 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
37 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
38 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
39 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
40 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
41 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
43 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
45 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
47 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
49 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
50 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
51 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
53 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
54 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
55 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
56 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
57 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
58 - public\index.php:20
59 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

POST /admin/profile/update

## Headers

* **host**: 127.0.0.1:8000
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **accept-language**: en-US,en;q=0.5
* **accept-encoding**: gzip, deflate, br, zstd
* **content-type**: application/x-www-form-urlencoded
* **content-length**: 98
* **origin**: http://127.0.0.1:8000
* **connection**: keep-alive
* **referer**: http://127.0.0.1:8000/admin/profile
* **cookie**: XSRF-TOKEN=eyJpdiI6ImUvNE5PRzUvUk1tL3VWcWdYV0lrT0E9PSIsInZhbHVlIjoiZHFxSno1RlNCOGFFd0I0NE1ETzVZUEJLSmRqUHJyL2hTM00wN3VNOWt0cTBVb3FPaVhqVVpHMlBodmNDVlRNbHQ0MWxqYWZjKzJsS09MemdWWmdoRUZRa203aE1xRWY2UzBINm9GT0xQQXNwaUMwUWNTU3o2TFYyTU1ibnVaVWMiLCJtYWMiOiJjODRmZDc4YTI4ODI0YjAzODI3ZGIzZjllMDExYzVmN2Y0NDMwODQ0ZTQzNWNiNWIyZjFkMDY4MWEwNTU4YWJkIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IldLZ05UNHAvNG1jZzRKRDkyV0sxMHc9PSIsInZhbHVlIjoiMjAvM2orMklYYjFsRTltdGptblZSYmV4VU5RUkFoMURQVVNuUWZHdGU2VG96SzZSMVBXTi9vQXdCNlVqNEhFeTRwTGJidUVVRWQ4M3M4aldTV21LdkQ3eEdpRlAzNVgwZ1FUYUxROE5Pa0dmQWtWSWVCZndqd1E0QmJORGhXOHUiLCJtYWMiOiIzYWVmNzNhYjQ3ZjBmYjNhZDNlODlkMzkxYzIzNjU1YmU2YTZkYTJhYjNkNzcyNTVhMjZmNzc4YmQzYmZlOTc3IiwidGFnIjoiIn0%3D
* **upgrade-insecure-requests**: 1
* **sec-fetch-dest**: document
* **sec-fetch-mode**: navigate
* **sec-fetch-site**: same-origin
* **sec-fetch-user**: ?1
* **priority**: u=0, i

## Route Context

controller: App\Http\Controllers\Admin\ProfileController@update
route name: admin.profile.update
middleware: web, auth

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'YyDBlOzsUTZFWae6FfArRztlUhEPY8oznbYC4CYR' limit 1 (4.15 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (0.93 ms)
* mysql - select * from `village_profiles` limit 1 (0.59 ms)
