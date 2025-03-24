<?php

namespace App\enums;

enum LoginStatus : string
{
    case SUCCESS = 'succesfull';
    case FAILED = 'failed';
    case UNIDENTIFIED = 'unidentified';
}
