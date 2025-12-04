# ParseError - Internal Server Error

syntax error, unexpected token "->"

PHP 8.4.14
Laravel 12.40.2
localhost:8000

## Stack Trace

0 - app\Http\Controllers\PendudukController.php:39
1 - vendor\composer\ClassLoader.php:427
2 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:1125
3 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:1062
4 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:834
5 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:816
6 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
7 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
8 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
9 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
10 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
11 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
12 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
13 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
15 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
16 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
18 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
20 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
22 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
24 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
26 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
28 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
30 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
31 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
32 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
33 - public\index.php:20
34 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

GET /admin/penduduk

## Headers

* **host**: localhost:8000
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **accept-language**: en-US,en;q=0.5
* **accept-encoding**: gzip, deflate, br, zstd
* **referer**: http://localhost:8000/admin/penduduk?search=sarip
* **connection**: keep-alive
* **cookie**: XSRF-TOKEN=eyJpdiI6IjVBcm5zOWtmRTRRSERVL3JZSDF5SFE9PSIsInZhbHVlIjoieUNiN3A5VTdrRmIwYVFPMld4Mmp1ZTIzVmhHYmhMZUtTa2E1dUoxMWZETnNWRmVQT1JjME0zUCtZT043c2haWkdiZTdZbGRYbFI5c2JUeXoycm5EaENmZXNGd0gvTXNoc0lLaW55RFl4VENNOU5mcUFaQ2UyaVRMTVFDdGRjZ0kiLCJtYWMiOiI5ZTgzNjM2NDMyYjBkYWVmYjMwMzhlODQ1MGYwODRiZTVkZThmOWFmMTUwZmQ3NzZhNWQxMWViMTQ5NjhkOTZmIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6Ik5zTzJPSUlUeWNhbUpkUkdlejNjTkE9PSIsInZhbHVlIjoiQWlhcjNWWWxNTWxhTWUzTDhVUGV6bjU1WVZTUmFzUmtra3phY21QK25iaUttajcrbElvRGZ4Z0RLYVlKbzBMZ3hyTjhvaXUyZ1RQWkNkaFk5NzVFNGJ5ZHdSUzRvRkpQWFN3Q21rTERjeG1qR1QycnFJTjB6U1B4empWdGdoSU8iLCJtYWMiOiJlNzM0NDc3YzNjNjIwMjBhMDUxZTIyNzNjNzI0ZWNjMzMxYTUwZWQ5NWFmNTI3YmViMjZhNDcyOWNjZDVjNThlIiwidGFnIjoiIn0%3D
* **upgrade-insecure-requests**: 1
* **sec-fetch-dest**: document
* **sec-fetch-mode**: navigate
* **sec-fetch-site**: same-origin
* **sec-fetch-user**: ?1
* **priority**: u=0, i

## Route Context

controller: App\Http\Controllers\PendudukController@index
route name: penduduk.index

## Route Parameters

No route parameter data available.

## Database Queries

No database queries detected.
