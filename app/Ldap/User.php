<?php

namespace App\Ldap;

use Illuminate\Support\Str;
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
        'uin', 'netid', 'first_name', 'last_name', 'full_name', 'email', 'title', 'department'
    ];

    /**
     * @return string
     */
    public function getNetidAttribute(): string
    {
        return $this->getFirstAttribute('cn');
    }

    /**
     * @return string
     */
    public function getUinAttribute(): string
    {
        return $this->getFirstAttribute('extensionattribute1');
    }

    /**
     * @return string
     */
    public function getFirstNameAttribute(): string
    {
        return $this->getFirstAttribute('givenname');
    }

    /**
     * @return string
     */
    public function getLastNameAttribute(): string
    {
        return $this->getFirstAttribute('sn');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->getFirstAttribute('displayname');
    }

    /**
     * @return string
     */
    public function getEmailAttribute(): string
    {
        return $this->getFirstAttribute('mail');
    }

    /**
     * @return string
     */
    public function getTitleAttribute(): string
    {
        return $this->getFirstAttribute('title');
    }

    /**
     * @return string
     */
    public function getDepartmentAttribute(): string
    {
        return $this->getFirstAttribute('department');
    }
}
