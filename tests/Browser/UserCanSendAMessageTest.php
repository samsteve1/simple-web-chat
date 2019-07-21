<?php

namespace Tests\Browser;
use Tests\Browser\Pages\ChatPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\RefreshDatabase;



class UserCanSendAMessageTest extends DuskTestCase
{
    use DatabaseMigrations; //RefreshDatabaseState;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testUserCanSendAMessage()
    {
        $user = factory(\App\Models\User::class)->create();

       $this->browse(function (Browser $browser) use($user)  {
          $browser->loginAs($user)
            ->visit(new ChatPage)
            ->typeMessage('Hello world')
            ->sendMessage()
            ->assertInputValue('@body', '')
            ->with('.chat__messages', function ($messages) use($user) {
                $messages->assertSee('Hello world')
                ->assertSee($user->name);
            });
           
       });
    }
}
