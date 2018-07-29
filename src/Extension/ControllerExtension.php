<?php

namespace CodeCraft\Announce\Extension;

use CodeCraft\Announce\Announcements;
use SilverStripe\ORM\DataExtension;

class ControllerExtension extends DataExtension
{

    /**
     * @var Announcements
     */
    protected $announcements;

    /**
     * @return Announcements
     */
    public function getAnnouncements()
    {
        if (!$this->announcements) {
            $this->announcements = Announcements::create();
        }

        return $this->announcements;
    }

    /**
     * @param Announcements $announcements
     */
    public function setAnnouncements($announcements)
    {

        // Filter for announcements to add to store
        $store = array_filter($announcements->toArray(), function($a) {
            return $a->canStore();
        });

        // Store announcements
        $announcements->getStore()->set($store);

        // Stack announcements
        return $this->announcements = $announcements;
    }
}
