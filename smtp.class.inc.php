<?php
/**
 * Allows client<->server interaction
 * The comunication is based upon the SMPT standards defined in http://www.lesnikowski.com/mail/Rfc/rfc2821.txt
 */

class fakeSMTP
{
	public $logFile 		  = false;
	public $serverHello 	= '';
	public $mailFile 		  = false;
  private $ip           = '';

	public function __construct()
	{
    $this->ip = $this->detectIP();
	}

	public function receive()
	{
		$hasValidFrom 	  = false;
		$hasValidTo 		  = false;
		$receivingData 	  = false;

		$this->reply('220 '.$this->serverHello);
		

		$raw = "";

		while ($data = utf8_encode(fgets(STDIN))) 
		{
			$raw .= $data; // save to file

			$data = preg_replace('@\r\n@', "\n", $data);

			if (!$receivingData)
				 $this->log($data);
            

		   if (!$receivingData && preg_match('/^MAIL FROM:\s?<(.*)>/i', $data, $match))
			{
		      if (preg_match('/(.*)@\[.*\]/i', $match[1]) || $match[1] != '' || $this->validateEmail($match[1])) 
				{
					$this->reply('250 2.1.0 Ok');
					$hasValidFrom = true;
		      } 
				else 
				{
					$this->reply('551 5.1.7 Bad sender address syntax');
		     	}
		   } 
			elseif (!$receivingData && preg_match('/^RCPT TO:\s?<(.*)>/i', $data, $match)) 
			{
				if (!$hasValidFrom) 
				{
					$this->reply('503 5.5.1 Error: need MAIL command');
				} 
				else 
				{
					if (preg_match('/postmaster@\[.*\]/i', $match[1]) || $this->validateEmail($match[1])) 
					{
						$this->reply('250 2.1.5 Ok');
						$hasValidTo = true;
					} 
					else 
					{
						$this->reply('501 5.1.3 Bad recipient address syntax '.$match[1]);
					}
				}
			}
			elseif (!$receivingData && preg_match('/^RSET$/i', trim($data))) 
			{
        $this->reply('250 2.0.0 Ok');
        $hasValidFrom 	= false;
        $hasValidTo 	= false;
      } 
			elseif (!$receivingData && preg_match('/^NOOP$/i', trim($data))) 
			{
        $this->reply('250 2.0.0 Ok');
      } 
			elseif (!$receivingData && preg_match('/^VRFY (.*)/i', trim($data), $match)) 
			{
        $this->reply('250 2.0.0 '.$match[1]);
      } 
			elseif (!$receivingData && preg_match('/^DATA/i', trim($data))) 
			{
        if (!$hasValidTo)
          $this->reply('503 5.5.1 Error: need RCPT command');
        else 
				{
          $this->reply('354 Ok Send data ending with <CRLF>.<CRLF>');
          $receivingData = true;
        }
      } 
			elseif (!$receivingData && preg_match('/^(HELO|EHLO)/i', $data)) 
			{
        $this->reply('250 HELO ' . $this->ip);
      } 
			elseif (!$receivingData && preg_match('/^QUIT/i', trim($data))) 
			{
        break;
      } 
			elseif (!$receivingData) 
      {
        $this->reply('502 5.5.2 Error: command not recognized');
      } 
      elseif ($receivingData && $data == ".\n") 
      {
        /* Email Received, now let's look at it */
        $receivingData = false;
        $this->reply('250 2.0.0 Ok: queued as '.$this->generateRandom());

        // use new package!
        set_time_limit(5); // Just run the exit to prevent open threads / abuse
      } 
    }
	
		if ($this->mailFile)
			file_put_contents($this->mailFile, $raw);

    /* Say good bye */
    $this->reply('221 2.0.0 Bye '.$this->ip);
  }

  public function log($s)
  {
      if ($this->logFile) {
          file_put_contents($this->logFile, trim($s)."\n", FILE_APPEND);
      }
  }

  private function reply($s)
  {
      $this->log("REPLY:$s");
      fwrite(STDOUT, $s . "\r\n");
  }

  private function detectIP()
  {
      $raw = explode(':', stream_socket_get_name(STDIN, true));
      return $raw[0];
  }

  private function validateEmail($email)
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  private function generateRandom($length=10)
  {
    $password = '';
    $possible = '2346789BCDFGHJKLMNPQRTVWXYZ';
    $maxlength = strlen($possible);
    $i = 0;
    for ($i=0; $i < $length; $i++) {
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
      if (!strstr($password, $char))          
        $password .= $char;
    }
    return $password;
  }

}
