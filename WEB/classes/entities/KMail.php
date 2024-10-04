<?
include_once("../WEB/lib/mail/class.phpmailer.php");

class KMail extends PHPMailer{
    function __construct($cbg,$exceptions = false) {
        parent::__construct($exceptions);
        
//        SET CONFIG PROFIDED
        $xmlfile = "../WEB/setup/web.xml";		
        $data = simplexml_load_file($xmlfile);
        $rconf = $data->mailerConfig->$cbg;
      
        $this->IsSMTP();
        $this->Host     = $rconf->mail_smtp;
        $this->Port     = $rconf->mail_port ? (int)$rconf->mail_port : 25;
        $this->SMTPAuth = $rconf->smtpauth == 1 ? TRUE :FALSE;     // turn on SMTP authentication
        $this->Username = $rconf->username_smtp;  
        $this->Password = $rconf->password_smtp; 

        $this->From     = $rconf->from_email;
        $this->FromName = $rconf->from_name;
        $this->SMTPSecure  = $rconf->smtpsecure;

        $this->WordWrap = $rconf->wordwarp;				// set word wrap
        $this->Priority = $rconf->priority;
        $this->CharSet=$rconf->charset;
        $this->IsHTML($rconf->ishtml == 1 ? TRUE :FALSE);
        
        $this->AltBody    = "To view the message, please use an HTML compatible email viewer!";
    }
}

?>
