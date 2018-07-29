<?php

namespace CodeCraft\Announce\Model;

use SilverStripe\ORM\ArrayList;

class ActionList extends ArrayList
{

    /**
     * @param  string              $name
     * @return Action|null
     */
    public function getByName($name)
    {
        $action = null;

        foreach ($this->items as $item) {
            if ($action->Name == $name) {
                $action = $item;
            }
        }

        return $action;
    }

    /**
     * @param  Action $item
     * @return
     */
    public function push($item) {
        if (!$item instanceof Action) {
            throw new Exception('Must be an Action');
        }

        return parent::push($item);
    }

    /**
     * Default template rendering of announcements
     */
    public function forTemplate()
    {
        $output = "";
        foreach ($this as $action) {
            $output .= $action->forTemplate();
        }
        return $output;
    }
}
