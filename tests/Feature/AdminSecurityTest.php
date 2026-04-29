<?php

use App\Models\User;

/**
 * Admin Security Feature Tests
 *
 * Verifies that the `admin` middleware correctly blocks non-admin users
 * from all dashboard routes and allows admins through.
 */

describe('Admin Security (Middleware)', function () {

    it('redirects guests from all admin routes', function () {
        $this->get(route('dashboard.index'))->assertRedirect(route('login'));
        $this->get(route('dashboard.products.index'))->assertRedirect(route('login'));
        $this->get(route('dashboard.categories.index'))->assertRedirect(route('login'));
    });

    it('blocks a customer from the admin dashboard with 403', function () {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->get(route('dashboard.index'))
            ->assertForbidden();
    });

    it('blocks a customer from the admin product management page', function () {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->get(route('dashboard.products.index'))
            ->assertForbidden();
    });

    it('blocks a customer from the product create page', function () {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->get(route('dashboard.products.create'))
            ->assertForbidden();
    });

    it('allows an admin to access the admin dashboard', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk();
    });

    it('allows an admin to access the product management page', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard.products.index'))
            ->assertOk();
    });

});
