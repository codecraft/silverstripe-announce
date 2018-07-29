<?php

namespace CodeCraft\Announce;

use CodeCraft\Announce\Model\Announcement;
use CodeCraft\Announce\Store\SessionStore;
use Exception;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayList;

class Announcements extends ArrayList
{

    /**
     * FQCN of an AnnouncementStoreInterface
     * @var string
     */
    private static $store = SessionStore::class;

    /**
     * @param array   $announcements
     * @param boolean $empty         Exclude stored announcements
     * @param boolean $stack         Set this as the announcements stack
     */
    public function __construct($announcements = [], $empty = false, $stack = true)
    {
        if (!is_array($announcements)) {
            throw new Exception('Announcements must be an array');
        }

        if (count($announcements)) {
            foreach ($announcements as $a) {
                if (!$a instanceof Announcement) {
                    throw new Exception('Invalid value supplied for announcement');
                }
            }
        }

        if (!$empty) {
            if ($stored = $this->getStore()->get()) {
                $announcements = array_merge($announcements, $stored);
            }
        }

        parent::__construct($announcements);

        if ($stack) {
            // Assign new announcement stack to the current controller
            Controller::curr()->setAnnouncements($this);
        }
    }

    /**
     * Get the announcement store
     * @return AnnouncementStoreInterface
     */
    public function getStore()
    {
        return Injector::inst()->create($this->config()->get('store'));
    }

    /**
     * Queue an announcement in the announce stack
     * @return Announcements              A new announcements instance
     */
    public static function queue()
    {
        $args = func_get_args();

        return Controller::curr()->getAnnouncements()->push(...$args);
    }

    /**
     * Get the announcement queue
     * @param  string $type
     * @return Announcements
     */
    public static function get()
    {
        return Controller::curr()->getAnnouncements();
    }

    /**
     * Clear the announcement queue
     * @return Announcements
     */
    public static function clear()
    {
        // Clear store
        $this->getStore()->clear();

        return static::create();
    }

    /**
     * Merges one Announcements list with another. If any item's names conflict,
     * the items from the $with announcements will overwrite this object's list.
     * @param  Announcements $with
     * @return Announcements
     */
    public function merge($with)
    {
        if (!$with instanceof Announcements) {
            throw new Exception('Must be an Announcements list');
        }

        foreach ($with->toArray() as $announcement) {
            $this->push($announcement);
        }

        return $this;
    }

    /**
     * Add an announcement to the announce stack
     * Will also accept arguments for @see Announcement::__construct()
     *
     * @param  Announcement $announcement
     * @return Announcements              A new announcements instance
     */
    public function push($announcement)
    {
        if (!$announcement instanceof Announcement) {
            $args = func_get_args();
            try {
                $announcement = new Announcement(...$args);
            } catch (Exception $e) {
                throw new Exception('Must supply Announcement object or valid arguments: ' . $e->getMessage());
            }
        }

        // Avoid duplicates
        $announcements = $this->toArray();
        $i = 0;
        foreach ($announcements as $a) {
            if ($a->getName() == $announcement->getName()) {
                unset($announcements[$i]);
            }

            $i++;
        }

        $announcements[] = $announcement;

        return Announcements::create($announcements, true);
    }

    /**
     * Get announcementsby type
     * @return array
     */
    public function getByType($type = null)
    {
        $announcements = $this->toArray();

        if ($type) {
            $matching = [];

            foreach ($announcements as $announcement) {
                if ($announcement->getType() == $type) {
                    $matching[] = $announcement;
                }
            }

            $announcements = $matching;
        }

        return $announcements;
    }

    /**
     * Get an announcement by its name
     * @param  string $name
     * @return Announcement|null
     */
    public function getByName($name)
    {
        if (!is_string($name)) {
            throw new Exception('Name must be a string');
        }

        $announcement = null;

        $announcements = $this->toArray();
        if (isset($announcements[$name])) {
            $announcement = $announcements[$name];
        }

        return $announcements;
    }

    /**
     * Remove an announcement by its name
     * @param  string $name
     * @return Announcements A new announcements instance
     */
    public function removeByName($name)
    {
        if (!is_string($name)) {
            throw new Exception('Name must be a string');
        }

        $announcements = $this->toArray();
        unset($announcements[$name]);

        return Announcements::create($announcements);
    }

    /**
     * Default template rendering of announcements
     */
    public function forTemplate()
    {
        $output = "";
        foreach ($this as $announcement) {
            $output .= $announcement->forTemplate();
        }

        // Clear store
        $this->getStore()->clear();

        return $output;
    }
}
