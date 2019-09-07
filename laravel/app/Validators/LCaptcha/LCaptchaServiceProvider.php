<?php

namespace App\Validators\LCaptcha;

use Illuminate\Support\ServiceProvider;

class LCaptchaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['validator']
            ->extend(
                'lcaptcha',
                'App\\Validators\\LCaptcha\\LCaptchaValidator@validate'
            );

        \Blade::directive('lcaptcha', function () {
            return "<?php echo '<div class=\"lcaptcha\" data-sitekey=\"'.\Crypt::encryptString(csrf_token()).'\"></div><script src=\"'.route('lcaptcha-js',['v'=>date('U')]).'\"></script>'; ?>";
        });

        \Route::middleware('web')->get('/lcaptcha/js/lc.js', function() {
            view()->addNamespace('lcaptcha', __DIR__ . '/views/');
            return view('lcaptcha::js');
        })->name('lcaptcha-js');

        \Route::middleware('web')->get('/lcaptcha/api2/v/{v}', function($v) {
            try {
                if( \Crypt::decryptString($v) == csrf_token() ) {
                    view()->addNamespace('lcaptcha', __DIR__ . '/views/');
                    return view('lcaptcha::iframe');
                } else {
                    throw new \Illuminate\Contracts\Encryption\DecryptException();
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return \Response::make([
                    'status' => 'error',
                    'message' => 'The payload is invalid.'
                ], 500,['content-type'=>'application/json']);
            }
        })->name('lcaptcha-iframe');
    }
}