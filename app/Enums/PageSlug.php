<?php

namespace App\Enums;

/**
 * The slugs of the pages whose content the site's own routes show; they are part of the public URLs.
 */
enum PageSlug: string
{
    case Home = 'home';
    case Events = 'events';
    case Members = 'members';
    case News = 'news';
    case SignUp = 'sign-up';
    case Library = 'library';
    case Zekeringen = 'zekeringen';
}
