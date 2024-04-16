<?php

namespace Tests\Feature\Admin;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_nologin_admin_users_screen_can_not_rendered(): void
     {  
        $response = $this->get('/admin/users/index');
 
        $response->assertStatus("302");
     }

     public function test_login_user_admin_users_screen_can_not_rendered(): void
     {  
         $user = User::factory()->create();

         $this->actingAs($user);
         $response = $this->get('/admin/users/index');
         $response->assertStatus("302");
     }

     public function test_login_admin_admin_users_screen_can_be_rendered(): void
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();

         $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
         ]);

         $response = $this->actingAs($admin, 'admin');
         $response = $this->get('/admin/users/index');
         $response->assertStatus(200);
     }

     public function test_nologin_admin_users_detail_screen_can_not_rendered(): void
     {
        $response = $this->get('/admin/users/show');
 
        $response->assertStatus("302");
     }

     public function test_login_user_admin_users_detail_screen_can_not_rendered(): void
     {
      $user = User::factory() ->create();

      $this->actingAs($user);
      $response = $this->get('/admin/users/show');
      $response->assertStatus("302");
     }

     public function test_login_admin_admin_users_detail_screen_can_be_rendered(): void
     {  
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();

         $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
         ]);

         $response = $this->actingAs($admin, 'admin');
         $response = $this->get('/admin/users/show');
         $response->assertStatus(200);
     }
}
