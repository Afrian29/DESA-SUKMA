# Illuminate\Contracts\Container\BindingResolutionException - Internal Server Error

Target class [App\Http\Controllers\Admin\InstitutionController] does not exist.

PHP 8.4.14
Laravel 12.40.2
127.0.0.1:8000

## Stack Trace

0 - vendor\laravel\framework\src\Illuminate\Container\Container.php:1163
1 - vendor\laravel\framework\src\Illuminate\Container\Container.php:972
2 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1078
3 - vendor\laravel\framework\src\Illuminate\Container\Container.php:903
4 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1058
5 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:286
6 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:266
7 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
8 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
9 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
10 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
11 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
12 - vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
13 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
15 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
16 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
17 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
18 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
19 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
20 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
22 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
24 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
26 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
27 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
28 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
29 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
30 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
31 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
32 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
33 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
34 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
35 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
36 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
37 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
38 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
39 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
41 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
43 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
45 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
47 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
49 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
50 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
51 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
52 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
53 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
54 - public\index.php:20
55 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

POST /admin/institution

## Headers

* **host**: 127.0.0.1:8000
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **accept-language**: en-US,en;q=0.5
* **accept-encoding**: gzip, deflate, br, zstd
* **content-type**: application/x-www-form-urlencoded
* **content-length**: 141
* **origin**: http://127.0.0.1:8000
* **connection**: keep-alive
* **referer**: http://127.0.0.1:8000/admin/profile
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImNBS2RqRW56WGtPbElRVFBmUkxzUmc9PSIsInZhbHVlIjoiLzlObkpIc0VNU2JRQk10cGRxd1ZjVmFFd2N2YzVkdFlMT2xJdWdtT2g0SHA3WjJKMHJwMWdHdXl3VHNXeW9HRzV6WVI4cERQVUlBNHBNVXlzWFMvY09JMGJjNVJaTE51bkg3NEk1YnhDQ0hwZEx6b2hFcjNLLyszYmhQSHZzcmZTU2JNZmcvcTZmelpVc1AyanUxLzRweVhabDlzaFVFenkwQWlLL1VmdVZzNDhvTjg2Zy9hWXJ2OFBiRVdoWnRyd3Q0MW9jdjliQkQvSjJSYjRaQzVKZVhSN1JYY0RVNzFUcFhFcVFxQTJtTT0iLCJtYWMiOiIxYWI2MDJmMDI5OTFjODc4YjE1MjFhMzljMzliMDM3ODc2ODZjYWZjNDRhYzM2MjA3OWM5MGZkZjc5MWRhZjFmIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6InhPZnFkYnN1dU5LTGZvVnZYSVFRekE9PSIsInZhbHVlIjoibU5HOFowdEI4ZXZtdUpvdTE3eHphVXoxbFpFMEhmdXVaMEVINURtM0pmcHhyU3lOakdza0dVREl3Y2xobExhbGNyWGFBVkRxNjJwczE4cWFRUnViY0d2SVg2N2did0RRRGJYSGtCQi90bTlLZUZ2NkVQUVo2emNNYnBnbzlRNzciLCJtYWMiOiJjYmMxNzIxNjliOWQzYWI1OThlNzg0OWUyYjM5ZjNlNDA4YTA4NWI4YzE1MjgxMmI0Njc2NTdlYjkzNDJjNzkxIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IjRhbG10NkJZQXlRSG10SDlCOTlHaGc9PSIsInZhbHVlIjoiVHJtM1lNS2VCMzBUYkkxdTAzSVE3NTRQbG5yMVN3TTV0ZUptd1ZFZFZsY0liR3p1c3dFZ0c0R1AzSzV3QmFwKzA0T0FrV1hXcEF4Vjd1M1hYZVpua2F6QWhxaktHS2QzTm44ZUhTSHBPSTBIZVl3UFlRbmhnYmgyclRyTnZ2U1EiLCJtYWMiOiJmM2I4YmU5OTBiMjgzYTEwNWE4MGY1ZDJiZGM2ZjFjODRiOTE1ZGNiMmUzMjFkMjVlMTgyMmNjYjkzNjE4M2U4IiwidGFnIjoiIn0%3D
* **upgrade-insecure-requests**: 1
* **sec-fetch-dest**: document
* **sec-fetch-mode**: navigate
* **sec-fetch-site**: same-origin
* **sec-fetch-user**: ?1
* **priority**: u=0, i

## Route Context

controller: App\Http\Controllers\Admin\InstitutionController@store
route name: admin.institution.store
middleware: web, auth

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'tZLndxBtZJSNVpk8EsXKaLvnP2gOAY4ssRjRh0sL' limit 1 (3.55 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (0.66 ms)
