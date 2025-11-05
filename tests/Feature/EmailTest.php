<?php

use App\Mail\AdminPasswordReset;
use App\Mail\SaleCredentials;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('admin password reset email can be sent', function () {
    Mail::fake();

    $admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@example.com',
    ]);

    $newPassword = 'newpassword123';

    Mail::to($admin->email)->send(new AdminPasswordReset($admin, $newPassword));

    Mail::assertSent(AdminPasswordReset::class, function ($mail) use ($admin, $newPassword) {
        return $mail->hasTo($admin->email) &&
               $mail->admin->id === $admin->id &&
               $mail->newPassword === $newPassword;
    });
});

test('sale credentials email can be sent', function () {
    Mail::fake();

    $sale = Sale::factory()->create([
        'email' => 'sale@example.com',
    ]);

    $password = 'password123';

    Mail::to($sale->email)->send(new SaleCredentials($sale, $password));

    Mail::assertSent(SaleCredentials::class, function ($mail) use ($sale, $password) {
        return $mail->hasTo($sale->email) &&
               $mail->sale->id === $sale->id &&
               $mail->password === $password;
    });
});

test('admin password reset email has correct content', function () {
    $admin = User::factory()->create([
        'name' => 'Test Admin',
        'email' => 'admin@example.com',
    ]);

    $newPassword = 'securepassword';

    $mailable = new AdminPasswordReset($admin, $newPassword);
    $rendered = $mailable->render();

    expect($rendered)->toContain($admin->name);
    expect($rendered)->toContain($newPassword);
    expect($rendered)->toContain('Admin Password Reset');
});

test('sale credentials email has correct content', function () {
    $sale = Sale::factory()->create([
        'name' => 'Test Sale',
        'email' => 'sale@example.com',
    ]);

    $password = 'password123';

    $mailable = new SaleCredentials($sale, $password);
    $rendered = $mailable->render();

    expect($rendered)->toContain($sale->name);
    expect($rendered)->toContain($password);
    expect($rendered)->toContain('Sales Account Credentials');
});
