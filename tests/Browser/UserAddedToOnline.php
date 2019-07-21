<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ChatPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserAddedToOnline extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test Users are added to online list when joined
     * @return void
     */
    public function users_are_added_to_the_online_list_when_joining()
    {
        $users = factory(\App\Models\User::class, 2)->create();

        $this->browse(function ($browserOne, $browserTwo) use ($users) {

            $browserOne->loginAs($users->get(0))
                ->visit(new ChatPage)
                ->with('@onlineList', function($online) use ($users) {
                    $online->waitForText($users->get(0)->name)
                    ->assertSee($users->get(0)->name)
                    ->assertSee('1 user online');
                }); 
                
                $browserTwo->loginAs($users->get(1))
                ->visit(new ChatPage)
                ->with('@onlineList', function($online) use ($users) {
                    $online->waitForText($users->get(1)->name)
                    ->assertSee($users->get(1)->name)
                    ->assertSee('2 users online');
                });     
        });
    }
}
