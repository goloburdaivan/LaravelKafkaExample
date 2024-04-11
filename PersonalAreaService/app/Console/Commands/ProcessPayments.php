<?php

namespace App\Console\Commands;

use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Exceptions\KafkaConsumerException;
use Junges\Kafka\Facades\Kafka;
use function Laravel\Prompts\error;

class ProcessPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:process-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processing payments from payment microservice';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $consumer = Kafka::createConsumer()
            ->subscribe('payments')
            ->withHandler(function (KafkaConsumerMessage  $message) {
                dump(json_decode($message->getBody()['data']));
            })
            ->withAutoCommit()
            ->withConsumerGroupId('payments')
            ->build();

        try {
            $consumer->consume();
        } catch (Exception|KafkaConsumerException $e) {
            error_log($e->getMessage());
        }
    }
}
