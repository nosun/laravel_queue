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

    private $sendto;
    private $title;
    private $template;
    private $data;
	
	public function __construct($data)
	{
		$this->msg = $msg;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		Log::info('at '.time().' log by queue and the msg is:'.$this->msg);
		
	}
	
	public function failed(){
		
		
		
		
	}
	

	public function fire($job,$data){

		$job->delete;

	}
}
