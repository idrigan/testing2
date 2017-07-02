<?php

namespace Src\Domain;


interface EventQueue
{

    public function publish(Event $event);

}