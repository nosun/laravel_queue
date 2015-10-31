<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendEmail extends Command implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $level;
    private $receiver;
    private $template_id;
    private $message;
	
	public function __construct($notify)
	{
		$this->message     = $notify['message'];
		$this->template_id = $notify['template_id'];
		$this->receiver     = $notify['receiver'];
		$this->level       = $notify['level'];
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		Log::info('at '.time().' log by queue and the msg is:'.serialize($this->message));
	}
	
	public function failed(){
		
		
		
		
	}
	

	public function fire($job,$data){

		$job->delete;

	}
}
