<?php

namespace App\Utils;

class HttpStatusCodeUtil
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const NOT_ACCEPTABLE = 406;
    const CONFLICT = 409;
    const UNPROCESSABLE_ENTITY = 422;
    const INTERNAL_SERVER_ERROR = 500;
    const SUSPENDED = 451;
    const SERVICE_UNAVAILABLE = 503;
    const TOO_MANY_ATTEMPTS = 429;
    const GONE = 410;
    const FAILED_DEPENDENCY = 424;
    const PRECONDITION_FAILED = 412;
}
