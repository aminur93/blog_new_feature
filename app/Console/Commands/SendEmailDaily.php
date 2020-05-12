<?php

namespace App\Console\Commands;

use App\Mail\EmailDaily;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use DB;

class SendEmailDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Send Successfully';

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
        $subscribe_user = DB::table('subscribers')->get();
        
        foreach ($subscribe_user as $us)
        {
            Mail::to($us->email)->queue(new EmailDaily());
        }
    
        $this->info('Email Update has been send successfully');
    }
}
