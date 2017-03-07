# JWT validation library for lcobucci/jwt v3

Easily validate your lcobucci/jwt JWTs, add custom validators when you need. 
This package is intended for use with lcobucci/jwt v3. V4 will include validation improvements, so
it might not require any additional packages to set up token validation.

# Installation
`composer require okneloper/jwt-validators`

# Validating tokens

```php
use Lcobucci\JWT\Builder;

$token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
    ->setAudience('http://example.org') // Configures the audience (aud claim)
    ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
    ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
    ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
    ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
    ->set('uid', 1) // Configures a new claim, called "uid"
    ->getToken(); // Retrieves the generated token


$validator = new \Okneloper\JwtValidators\Validator();
// require the iss claim is present
$validator->add(new ClaimPresenceValidator('iss'));
// require that token lifetime is not longer than 120 seconds 
$validator->addValidator(new LifetimeValidator(60));

// validate the token
if (!$validator->validates($token)) {
    $errors = $validator->getErrors();
}
```

# Included Validators

## ClaimPresenceValidator
Validates if a particular claim is present.
```php
$validator->add(new ClaimPresenceValidator('iss')); // require iss claim
$validator->add(new ClaimPresenceValidator('sub')); // require sub claim
```

## ExpirationValidator
Validates if exp claim is present and the token is not expired.
```php
$validator->add(new ExpirationValidator());
```

## LifetimeValidator
Validates if token lifetime is less or equal to the number of seconds. Handy
for validating tokens issued by other issuers.
```php 
$validator->addValidator(new LifetimeValidator(60)); // 60 seconds
```

## SignaturePresenceValidator
Validates if the token is signed. This will prevent an exception when trying
to verify signature on a token without one.
```php
$validator->addValidator(new SignaturePresenceValidator());
```

## UniqueJtiValidator
Validates if the token does not exist in a custom storage. To use it, you
need to pass it an instance of a call implementing `TokenExistenceChecker` 
interface. This is a simple interface that lets you define how to check if
the token exists on your records. Example usage:
```php
// find the user by uid stored in the token
$user = User::find($token->getClaim('uid'));
// example checker that will check the database if a token id
// previously provided by the $user has been processed before
$checker = new CustomChecker($user);
// add this validator same as any other
$validator->addValidator(new UniqueJtiValidator($checker));
```

# Writing custom validators
There are 2 ways to write a custom validator depenfing on the complexity of
you validator you require.

## ITokenValidator interface
A token validator a class that implements `ITokenValidator` interface. 
This interface has 2 methods:
```php
interface ITokenValidator
{
    /**
     * Returns true if the token validates against this validator
     * @param \Lcobucci\JWT\Token $token
     * @return bool
     */
    public function validates(\Lcobucci\JWT\Token $token, $breakOnFirstError = true);

    /**
     * @return string
     */
    public function getErrors();
}
```

Simply implement this interface and you can add this validator to your 
list of validators.

## TokenValidator class

To simplify the process of writing validators the library includes 
an abstract class that 
implements this interface and the Decorator pattern that lets you chain
multiple validators. For instance you want check that a claim exists before
you will validate the value. Splitting this logic into 2 separate validators
allows for clean and exact error messages. This helps developers 
consuming your api a lot.

All you need to do is define your error message and the validation method:

```php
class CustomValidator extends TokenValidator
{
    public function __construct()
    {
        // the second argument is optional
        parent::__construct("The username is too long", new ClaimPresenceValidator('username'));
    }

    protected function isValid(\Lcobucci\JWT\Token $token)
    {
        return strlen($token->getClaim('username')) <= 10;
    }
}
```
