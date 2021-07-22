<?php

namespace App\Exception\Api;

use App\Exception\ValidationException;

class ApiValidationException extends ValidationException implements ApiThrowable
{

}