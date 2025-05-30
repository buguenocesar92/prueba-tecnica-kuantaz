<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_user_fillable_attributes(): void
    {
        $user = new User();
        
        $expected = [
            'name',
            'email',
            'password',
        ];
        
        $this->assertEquals($expected, $user->getFillable());
    }

    public function test_user_hidden_attributes(): void
    {
        $user = new User();
        
        $expected = [
            'password',
            'remember_token',
        ];
        
        $this->assertEquals($expected, $user->getHidden());
    }

    public function test_user_casts_method(): void
    {
        $user = new User();
        
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    public function test_user_uses_traits(): void
    {
        $user = new User();
        
        $traits = class_uses($user);
        
        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
        $this->assertContains('Illuminate\Notifications\Notifiable', $traits);
    }

    public function test_user_instantiation(): void
    {
        $user = new User();
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf('Illuminate\Foundation\Auth\User', $user);
    }
} 