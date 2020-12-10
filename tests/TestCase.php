<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use MarvinRabe\LaravelGraphQLTest\TestGraphQL;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use TestGraphQL;
}
