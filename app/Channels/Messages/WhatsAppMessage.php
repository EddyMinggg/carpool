<?php

namespace App\Channels\Messages;

class WhatsAppMessage
{
    public $contentSid;
    public $contentVariables;

    public function content($contentSid, $contentVariables)
    {
        $this->contentSid = $contentSid;
        $this->contentVariables = $contentVariables;

        return $this;
    }
}
