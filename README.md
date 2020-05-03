<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Execution

Open a terminal and go to the directory of project, rename the file .env.example to .env, execute composer install. When finished execute the command php artisan key:generate.

Finally, execute the command php artisan serve and go to this follow url: http://localhost:8000/

## First phase

You wil see a form with two inputs (one for plaintiff signatures and other for defendant signatures) If you click in submit button, the form make a call to api endpoint and will 
response with the winner of lawsuit. You can try with different options, if the character is not allowed (only K, N, V, # characters are allowed) the api will response with a error status code (422)

## Second phase

Is the same of the first phase, but the endpoint will response with the signatures you need to win to the opposite part 

## Code coverage

Also you can see a link to show a report of the code coverage done by unit test.