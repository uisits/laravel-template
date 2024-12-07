<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];

    /**
     * @var array|string[]
     */
    protected array $appends = [
        'uin', 'netid', 'first_name', 'last_name', 'full_name', 'email', 'title', 'department',
    ];

    public function getNetidAttribute(): string
    {
        return $this->getFirstAttribute('cn');
    }

    public function getUinAttribute(): string
    {
        return $this->getFirstAttribute('extensionattribute1');
    }

    public function getFirstNameAttribute(): string
    {
        return $this->getFirstAttribute('givenname');
    }

    public function getLastNameAttribute(): string
    {
        return $this->getFirstAttribute('sn');
    }

    public function getFullNameAttribute(): string
    {
        return $this->getFirstAttribute('displayname');
    }

    public function getEmailAttribute(): string
    {
        return $this->getFirstAttribute('mail');
    }

    public function getTitleAttribute(): string
    {
        return $this->getFirstAttribute('title');
    }

    public function getDepartmentAttribute(): string
    {
        return $this->getFirstAttribute('department');
    }
}