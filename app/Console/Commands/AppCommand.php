<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use Illuminate\Console\Command;

class AppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'somanb:app-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command app-refresh';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Helper::sendTelegramMessage(config('somnab.telegram.chat_id'), 'Hello');

    }
}
