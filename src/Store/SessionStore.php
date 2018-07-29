<?php

namespace CodeCraft\Announce\Store;

use CodeCraft\Announce\Announcements;
use CodeCraft\Announce\Interfaces\AnnouncementStoreInterface;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Session;
use SilverStripe\Core\Config\Configurable;

class SessionStore implements AnnouncementStoreInterface
{
    use Configurable;

    private static $session_name = 'Announcements';

    protected function getSession()
    {
        return Controller::curr()->getRequest()->getSession();
    }

    /**
     * Save the announcements to the store
     * @param  array                      $list
     * @return AnnouncementStoreInterface
     */
    public function set($list)
    {
        $this->getSession()->set($this->config()->get('session_name'), $list);

        return $this;
    }

    /**
     * Clear the announcement store
     * @return AnnouncementStoreInterface
     */
    public function clear()
    {
        $this->getSession()->clear($this->config()->get('session_name'));
    }

    /**
     * Get announcements from the store
     * @param  string        $name
     * @return array|Announcement
     */
    public function get($name = null)
    {
        $stored = $this->getSession()->get($this->config()->get('session_name'));

        if ($stored && $name && isset($stored[$name])) {
            $stored = $stored[$name];
        }

        return $stored;
    }
}
