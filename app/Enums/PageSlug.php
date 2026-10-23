<?php

namespace App\Enums;

/**
 * The slugs of the pages whose content the site's own routes show; they are part of the public URLs.
 */
enum PageSlug: string
{
    case Home = 'home';
    case Events = 'agenda';
    case Members = 'memberlist';
    case News = 'news';
    case SignUp = 'signup';
    case Library = 'library';
    case Zekeringen = 'zekeringen';
}
