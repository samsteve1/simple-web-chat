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

    /**
     *@test A user can't send an empty message
     * 
     * @return void
     */
    public function a_user_cant_send_an_empty_message()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new ChatPage);

                foreach(['     '] as $empty) {
                    $browser->typeMessage($empty)
                        ->sendMessage()
                        ->assertDontSeeIn('.chat__messages', $user->name);

                }

                $browser->keys('@body', '{shift}', '{enter}')
                    ->keys('@body', '{shift}', '{enter}')
                    ->sendMessage()
                    ->assertDontSee('.chat__messages', $user->name)
                    ->logout();
        });
    }

    /**
     * @test Messages are ordered by latest first
     * 
     * @return void
     */
    public function messages_are_ordered_by_lastest_first()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new ChatPage);
            
                foreach(['one', 'two', 'three'] as $message) {
                    $browser->typeMessage($message)
                        ->sendMessage()
                        ->waitFor('@firstChatMessage')
                        ->assertSeeIn('@firstChatMessage', $message);
                }
        });
    }

    /**
     * @test user's messages are highlighted
     * 
     * @return void
     */
    public function a_users_message_is_highlighted_as_their_own()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new ChatPage)
                ->typeMessage('My message')
                ->sendMessage()
                ->waitFor('@ownMessage')
                ->with('@ownMessage', function ($message) use ($user) {
                    $message->assertSee('My message')
                        ->assertSee($user->name);
                })
                ->logout();
        });
    }
}
