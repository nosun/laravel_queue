<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Mail;

class SendEmail extends Command implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $level;
    private $receiver;
    private $template;
    private $message;
	
	public function __construct($notify)
	{
		$this->message     = $notify['message'];
		$this->template    = $notify['template'];
		$this->receiver    = $notify['receiver'];
		$this->level       = $notify['level'];
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$message = $this->message;
		foreach($this->receiver as $user){
			Mail::send($this->template, ['user' => $user,'data' => $message], function ($mail) use ($user,$message) {
					$mail->to($user->email, $user->name)->subject($message['subject']);
			});
		}

		Log::info('at '.time().' log by queue and the msg is:'.serialize($this->message));
	}
	
	public function failed(){
		
		
		
		
	}
	

	public function fire($job,$data){

		$job->delete;

	}
}
