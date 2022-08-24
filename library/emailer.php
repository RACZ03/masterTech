<?php
include_once('email_template.php');
class Emailer
{
    var $recipients = array();
    var $EmailTemplate;
    var $EmailContents;

    public function __construct($to = false)
    {
        if($to !== false)
        {
            if(is_array($to))
            {
                foreach($to as $_to){ $this->recipients[$_to] = $_to; }
            }else
            {
                $this->recipients[$to] = $to; //1 Recip
            }
        }
    }

    function SetTemplate(EmailTemplate $EmailTemplate)
    {
        $this->EmailTemplate = $EmailTemplate;            
    }

    function send() 
    {
        $this->EmailTemplate->compile();
        //your email send code.
    }
}