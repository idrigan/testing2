<?php

namespace Domain;


interface EventQueue
{

    public function publish(Event $event);

}