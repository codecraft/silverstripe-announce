<?php

namespace CodeCraft\Announce\Interfaces;

interface AnnouncementStoreInterface
{
    /**
     * Save the announcements to the store
     * @param  Announcements              $announcements
     * @return AnnouncementStoreInterface
     */
    public function set($announcements);

    /**
     * Clear the announcement store
     * @return AnnouncementStoreInterface
     */
    public function clear();

    /**
     * Get announcements from the store
     * @param  string        $name
     * @return Announcements
     */
    public function get($name = null);
}
