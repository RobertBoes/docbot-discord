<?php

include __DIR__.'/../vendor/autoload.php';

use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Docbot\MessageParser;


$discord = new Discord([
    'token' => getenv('DOCBOT_TOKEN'),
]);

// When the Bot is ready
$discord->on('ready', function (Discord $discord) {

    // Listen for messages
    $discord->on('message', function (Message $message, Discord $discord) {

        // If message is from a bot
        if ($message->author->bot) {
            // Do nothing
            return;
        }

        $parser = new MessageParser;
        $result = $parser($message);

        if ($result !== false){
            $message->reply($result);
        }
    });

    $command = new Command($discord, [
        'name' => 'docs',
        'description' => 'Laravel docs command',
        'options' => [
            [
                'name' => 'page',
                'description' => 'The docs page',
                'type' => 3,
                'required' => true,
            ]
        ]
    ]);
    $discord->application->commands->save($command);
});

$discord->listenCommand('docs', function (Interaction $interaction) use ($discord) {
    $page = $interaction->data->options->get('name', 'page')->value;

    if (in_array($page, MessageParser::$docs)) {
        $interaction->respondWithMessage(
            MessageBuilder::new()
                ->setContent("<https://laravel.com/docs/$page>")
        );

        return;
    }

    $suggestions = array_filter(MessageParser::$docs, function ($value) use ($page) {
        return strpos($value, $page[0]) === 0;
    });

    if (empty($suggestions)) {
        $interaction->respondWithMessage(
            MessageBuilder::new()
                ->setContent("Sorry, we couldn't find that page or anything related"),
            ephemeral: true
        );

        return;
    }

    $row = ActionRow::new();
    foreach ($suggestions as $suggestion) {
        $row->addComponent(
            Button::new(Button::STYLE_PRIMARY)
                ->setLabel($suggestion)
                ->setListener(function (Interaction $buttonInteraction) use ($suggestion, $interaction) {
                    var_dump("pressed $suggestion");

                    $interaction->updateOriginalResponse(
                        MessageBuilder::new()
                            ->setContent("You chose $suggestion (sorry, I'm just a bot, I can't delete this message...)")
                    );

                    $buttonInteraction->channel->sendMessage(
                        MessageBuilder::new()
                            ->setContent("<https://laravel.com/docs/$suggestion>")
                    );
                }, $discord, true)
        );
    }

    $interaction->respondWithMessage(
        builder: MessageBuilder::new()
            ->setContent('Whoops, that is not a page we know, did you mean any of the following pages?')
            ->addComponent($row),
        ephemeral: true,
    );
});

// Start the Bot (must be at the bottom)
$discord->run();