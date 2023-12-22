<?php

namespace Superban\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\RateLimiter;
use Orchestra\Testbench\TestCase;
use Superban\Middleware\SuperbanMiddleware;

class SuperbanMiddlewareTest extends TestCase
{
    public function testMiddlewareBlocksRequestsAfterLimit()
    {
        // Use a mock for RateLimiter
        $rateLimiterMock = $this->getMockBuilder(RateLimiter::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set up a mock response
        $responseMock = $this->getMockBuilder(\Illuminate\Http\Response::class)
            ->getMock();
        $responseMock->method('status')->willReturn(403);

        // Set up a mock request with a specific key
        $request = Request::create('/anotherroute', 'GET');
        $request->headers->set('X-Real-IP', '127.0.0.1');

        $requests = 2;
        $minutes = 1; 
        $banMinutes = 5;

        // Set up the RateLimiter mock expectations
        $rateLimiterMock->expects($this->exactly($requests + 1))
            ->method('tooManyAttempts')
            ->willReturnOnConsecutiveCalls(false, false, true); // Return true on the third call

        // Create an instance of SuperbanMiddleware with the mock RateLimiter
        $middleware = new SuperbanMiddleware($rateLimiterMock);

        // Act
        $response = $middleware->handle($request, function () use ($responseMock) {
            return $responseMock;
        }, $requests, $minutes, $banMinutes);

        // Assert
        $this->assertEquals(403, $response->status());

        // Check if the ban key is stored in the cache
        $banKey = 'ban:' . $middleware->getResolvedKey($request);

        // dump(Cache::get($banKey)); 
        $this->assertTrue(Cache::has($banKey));
        $this->assertTrue(Cache::get($banKey));
    }
}
