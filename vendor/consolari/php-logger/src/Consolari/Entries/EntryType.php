<?php

namespace Consolari\Entries;

/**
 * Describes log levels
 */
class EntryType
{
    const ARRAYENTRY = 'array';
    const CHART = 'chart';
    const EMAIL = 'email';
    const JSON = 'json';
    const REQUEST = 'request';
    const STRING = 'string';
    const SQL = 'sql';
    const TABLE = 'table';
    const URL = 'url';
    const XML = 'xml';
}