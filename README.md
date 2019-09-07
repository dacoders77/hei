# Roilti
X Collective - v1.9.1

### Requirements
- LAMP Stack
    - Linux Server (Ubuntu >= 16.04.5)
    - Apache >= 2.4.18
    - MySql >= 5.7.24
    - PHP >= 7.2
- PHP Extensions
    - OpenSSL
    - PDO
    - Mbstring
    - Tokenizer
    - XML
    - Ctype
    - JSON
    - BCMath
    - MySql
    - PHP Cli
    - cURL
    - opCache
    - sendmail
    - dom
    - imagemagik

### Setup
**1. Setup vHost**
Point the directory to `laravel/public`.
HTTPS setup is required, recommend a `http:// > https://` redirect.

**2. Setup database**
Setup a MySQL database.

**3. Install composer dependencies**
```sh
$ cd laravel
$ composer install
```
**4. Install gulp dependencies**
```sh
$ cd laravel
$ npm install
```
**5. Setup .env file**
Copy the .env.example file and rename to .env, then update information as needed

**6. Migrate**
Use PHP Artisan to setup the database with all the required tables and settings.
```sh
$ cd laravel
$ php artisan migrate
```

**7. Setup admin user**
Use PHP Artisan to setup a new Admin user.
*(Note: If PASSWORD is left blank random password will generate for you.)*
```sh
$ cd laravel
$ php artisan admin:create EMAIL PASSWORD
```

**8. Setup Campaign**
Use PHP Artisan to setup a new Campaign.
```sh
$ cd laravel
$ php artisan campaign:create
```
*(Note: Campaign will be automatically set to "Draft".)*

**9. Login to Dashboard**
Go to https://www.example.com/admin/login and use Admin User details you generated.

### Pull & Merge
After pulling down code or merging, always check to see if Composer Dependancies have been added, updated or removed.
```sh
$ cd laravel
$ composer install
```

Also check if an Artisan Migration has been flagged and action if needs be.
```sh
$ cd laravel
$ php artisan migrate
```

## Stripe API
See CheckoutController_1 for legacy code which sends token off to Stripe and then connects with Submission Table.

See example of JS code which opens Stripe Modal for checkout.
Function will submit form after successful tokenization.
```blade
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>

(function($){
    var handler = StripeCheckout.configure({
      key: '{{ config('stripe.key') }}',
      locale: 'auto',
      email: '{{ $submission->meta('email') }}',
      allowRememberMe: false,
      panelLabel: "Pay @{{amount}}",
      token: function(token) {
        stripeTokenHandler(token);
      },
      closed: function(){
        var form = $('#stripeform');
        form.find('input[type="submit"]').removeClass('hide');
        form.find('#submit_working').addClass('hide');
      }
    });

    $('input[type="submit"]').on('click',function(e){

        var form = $(this).closest('form');

        form.find('#submit_working').removeClass('hide');
        $(this).addClass('hide');

        handler.open({
            name: 'Yellow Tail',
            description: '2 SCANPAN FRY PANS FOR $50',
            currency: 'aud',
            amount: 5000
        });

        e.preventDefault();
    });

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function() {
      handler.close();
    });

    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('stripeform');

      var stripeToken = document.createElement('input');
      stripeToken.setAttribute('type', 'hidden');
      stripeToken.setAttribute('name', 'stripeToken');
      stripeToken.setAttribute('value', token.id);
      form.appendChild(stripeToken);

      var stripeEmail = document.createElement('input');
      stripeEmail.setAttribute('type', 'hidden');
      stripeEmail.setAttribute('name', 'stripeEmail');
      stripeEmail.setAttribute('value', token.email);
      form.appendChild(stripeEmail);

      var form = $('#stripeform');
      form.find('input[type="submit"]').remove();
      form.find('#submit_working').attr('id','x').removeClass('hide');

      // Submit the form
      $(form).submit();
    }
})(jQuery);

</script>
```