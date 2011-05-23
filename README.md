Functional PHP
==============



``php
use Functional;

if (all($users, function($user) {return $user->isActive();})) {
    // If all users are active, set them inactive
    invoke($users, 'setActive', array(true));
}
``


``php
use Functional;

if (any($users, function($user) use($me) {return $user->isFriendOf($me);})) {
    // Any of the users is friend of me
}
``

