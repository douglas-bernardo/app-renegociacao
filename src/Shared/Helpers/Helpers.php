<?php

/**
 * ################################
 * ###   APPLICATION SETTINGS   ###
 * ################################
 */
// const CONF_DATE_FIN         = strtotime("2021-01-31");
const CONF_PATH_APP_INI     = __DIR__ . '/../Config/';
const CONF_CONTAINER_CONFIG = __DIR__ . '/../../../services.yaml';
const CONF_LOG_FILE         =  __DIR__ . '/../../../logs/log.log';

/**
 * PASSWORD
 */
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_MAX_LEN = 40;
const CONF_PASSWORD_DEFAULT = '12345678';

/**
 * DATES
 */
const CONF_DATE_BR = "d/m/Y H:i:s";
const CONF_DATE_APP = "Y-m-d H:i:s";


/**
 * ########################
 * ###   STATEMENTS CM  ###
 * ########################
 */

 /**
  * Reads queries located in the default 'resources' folder
  *
  * @param string $queryName
  * @return string|null
  */
 function file_get_string_sql(string $queryName): ? string
 {    
    $path = __DIR__ . "/../Resources/{$queryName}.sql";
    if(file_exists($path)){
        return file_get_contents($path);
    } else return null;
 }

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    return str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
}

function str_slug_underscore(string $string): string
{
    return str_replace('-', '_', str_slug($string));
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    return str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param integer $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = '...'):string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param integer $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = '...'):string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

function str_format_currency(string $value): string
{
    $source = array('.', ',');
    $replace = array('', '.');
    return str_replace($source, $replace, $value);
}


/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return boolean
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return boolean
 */
function is_passwd(string $password): bool
{
    return (mb_strlen($password) >= CONF_PASSWD_MIN_LEN) && (mb_strlen($password) <= CONF_PASSWD_MAX_LEN);
}