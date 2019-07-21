<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ChatPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersRemovedFromOnline extends DuskTestCase
{
    use DatabaseMigrations;
   
    /**
     * @test user removed from online whenleaving
     * 
     * @return void
     */
    public function users_are_removed_from_online_list_when_leaving()
    {
        $users = factory(\App\Models\User::class, 2)->create();

        $this->browse(function ($browserOne, $browserTwo) use ($users) {

            $browserOne->loginAs($users->get(0))
                ->visit(new ChatPage);

            $browserTwo->loginAs($users->get(1))
                ->visit(new ChatPage);

            $browserTwo->with('@onlineList', function ($online) use ($users) {
                $online->waitForText($users->get(1)->name)
                    ->assertSee($users->get(1)->name)
                    ->assertSee('2 users online');
            });

            $browserTwo->quit();

            $browserOne->with('@onlineList', function ($online) use ($users) {
                $online->pause(1000)
                    ->assertDontSee($users->get(1)->name)
                    ->assertSee('1 user online');
            });
            
        });
    }
}
