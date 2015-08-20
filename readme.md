# IHASCO Client API SDK

Create a new instance:


```php
$ihasco = Ihasco\ClientSDK\Manager::create('your-api-key');
```

All calls to the API return either a [`Ihasco\ClientSDK\Responses\Response`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Response.php) object or throw a [`Ihasco\ClientSDK\Exceptions\Exception`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Exceptions/Exception.php). 

## Programmes

### All programmes

```php
$response = $ihasco->programmes->all();
$allProgrammes = $response->getData();
```

Returns an array of [`Ihasco\ClientSDK\Responses\Programme`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Programme.php) objects

### One programme

```php
$response = $ihasco->programmes->one(int $programmeId);
$oneProgramme = $response->getData();
```

Returns a single [`Ihasco\ClientSDK\Responses\Programme`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Programme.php) object

## Results

### All results

```php
$response = $ihasco->results->all();
$allResults = $response->getData();
```

Returns an array of [`Ihasco\ClientSDK\Responses\Result`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Result.php) objects

### One result

```php
$response =  $ihasco->results->one(int $resultId);
$oneResult = $response->getData();
```

Returns a single [`Ihasco\ClientSDK\Responses\Result`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Result.php) object

## Users

### All users

```php
$response = $ihasco->users->all();
$allUsers = $response->getData();
```

Returns an array of [`Ihasco\ClientSDK\Responses\User`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/User.php) objects

### One user

Supply a userId or email address

```php
$response = $ihasco->users->one(mixed $userId);
$oneUser = $response->getData();
```

Returns a single [`Ihasco\ClientSDK\Responses\User`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/User.php) object

### User results

```php
$response = $ihasco->users->results(mixed $resultId, int $cursor = null);
$allResults = $response->getData();
```

Returns an array of [`Ihasco\ClientSDK\Responses\Result`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/Result.php) objects

### Create user

Send data as per [api spec](http://app.ihasco.co.uk/api#UsersAdd)

```php
$response = $ihasco->users->create(array $userData);
$oneUser = $response->getData();
```

Returns a single [`Ihasco\ClientSDK\Responses\User`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/User.php) object

### Edit user

Send data as per [api spec](http://app.ihasco.co.uk/api#UsersAdd)

```php
$response = $ihasco->users->update(int $userId, array $userData);
$oneUser = $response->getData();
```

Returns a single [`Ihasco\ClientSDK\Responses\User`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Responses/User.php) object

### Delete a user

```php
$response = $users->delete(int $id);
```

## Response Exceptions

Anything other than a 2xx response will result in a [`Ihasco\ClientSDK\Exceptions\Exception`](https://github.com/ihasco/PHP-API-Wrapper/blob/master/src/Ihasco/ClientSDK/Exceptions/Exception.php) being thrown. Possible exceptions are as follows:

```php
try {
    $response = $ihasco->programmes->all();
} catch(Ihasco\ClientSDK\Exceptions\CannotConnect $e) {
    // Cannot connect to server
} catch(Ihasco\ClientSDK\Exceptions\CannotAuthenticate $e) {
    // Bad API key
} catch(Ihasco\ClientSDK\Exceptions\InvalidResource $e) {
    // Non-existent resource
} catch(Ihasco\ClientSDK\Exceptions\ServerError $e) {
    // Something went wrong on the server
} catch(Ihasco\ClientSDK\Exceptions\BadMethod $e) {
    // Invalid HTTP method
} catch(Ihasco\ClientSDK\Exceptions\ValidationError $e) {
    // Something wrong with your submission
    var_dump($e->getErrors());
} catch(Exception $e) {
    // something else
}
```

## Pagination

```php
$hasPagination = $response->hasPagination(); // boolean

$nextPage = $response->getNextPage(); // Response or null

$prevPage = $response->getPrevPage(); // Response or null
```
