<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ChatPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChatRealTimeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test A user can send message
     * 
     * @return void
     */
    public function a_user_can_see_messages_from_other_users()
    {
        $users = factory(\App\Models\User::class, 3)->create();

        $this->browse(function ($browserOne, $browserTwo, $browserThree) use ($users) {

             /**
             * Better way of doing it
             */

            $browsers = [$browserOne, $browserTwo, $browserThree];

            foreach($browsers as $index => $browser) {
                $browser->loginAs($users->get($index))
                    ->visit(new ChatPage);
            }

            $browserOne->typeMessage('Hello there')
                ->sendMessage();

            foreach(array_slice($browsers, 1, 2) as $index => $browser) {
                $browser->waitFor('@firstChatMessage')
                    ->with('@chatMessages', function ($messages) use ($users) {
                        $messages->assertSee('Hello there')
                            ->assertSee($users->get(0)->name);
                            // ->assertMissing('@ownMessage');
                    });
            }

            $browserThree->typeMessage('hey nigga')
            ->sendMessage();

            foreach(array_slice($browsers, 0, 1) as $index => $browser) {
                $browser->waitForText('hey nigga')
                    ->with('@chatMessages', function ($messages) use ($users) {
                        $messages->assertSee('hey nigga')
                            ->assertSee($users->get(2)->name);
                            // ->assertMissing('@ownMessage');
                    });
            }

        
       


            /**
             * Verbose way of doing it
             */

            // $browserOne->loginAs($users->get(0))
            //     ->visit(new ChatPage);

            // $browserTwo->loginAs($users->get(1))
            //     ->visit(new ChatPage);

            // $browserThree->loginAs($users->get(2))
            //     ->visit(new ChatPage);
            
            // $browserOne->typeMessage('Hi there')
            //     ->sendMessage();
            
            // $browserTwo->pause(3000)->with('@chatMessages', function ($messages) use ($users) {
            //     $messages->assertSee('Hi there')
            //         ->assertSee($users->get(0)->name);
            // });

            // $browserThree->pause(3000)->with('@chatMessages', function ($messages) use ($users) {
            //     $messages->assertSee('Hi there')
            //         ->assertSee($users->get(0)->name);
            // });
        });
    }
}
