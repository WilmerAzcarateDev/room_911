<?php

namespace App\enums;

enum LoginStatus : string
{
    case SUCCESS = 'succesful';
    case FAILED = 'failed';
    case UNIDENTIFIED = 'unidentified';
}
