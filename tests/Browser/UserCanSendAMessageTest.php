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
     *@test A user can send a message
     * @return void
     */
    public function a_user_can_send_a_message()
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
            })

            ->logout();
           
       });
    }
    /**
     * @test A user can send a multiline message
     * 
     * @return void
     */

    public function a_user_can_send_a_multiline_message()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new ChatPage)
                ->typeMessage('test message')
                ->keys('@body', '{shift}', '{enter}')
                ->append('@body', 'New line')
                ->sendMessage()
                ->assertSeeIn('.chat__messages', "test message\nNew line")
                ->logout();

        });
    }
}
