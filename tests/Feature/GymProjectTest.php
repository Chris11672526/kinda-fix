<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymProjectTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_public_and_auth_pages_render(): void
    {
        $this->get('/')->assertOk();
        $this->get('/customer/login')->assertOk();
        $this->get('/customer/register')->assertOk();
        $this->get('/admin/login')->assertOk();
    }
}
