<?php

namespace CodeCraft\Announce\Model;

use Exception;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\ViewableData;

class Announcement extends ViewableData
{

    const DEFAULT = 'default';
    const MODAL = 'modal';
    const TRAY = 'tray';
    const MESSAGE = 'message';
    const CALLOUT = 'callout';

    /**
     * @var boolean
     */
    protected $dismissable = true;

    /**
     * @var boolean
     */
    protected $storable = true;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $heading;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var ActionList
     */
    protected $actions;

    /**
     * @var string|array
     */
    protected $template;

    private static $casting = [
        'canDismiss' => 'Boolean',
        'Title' => 'HTMLFragment', // Needed for templates
        'getTitle' => 'HTMLFragment',
        'Content' => 'HTMLFragment', // Needed for templates
        'getContent' => 'HTMLFragment',
        'Heading' => 'HTMLFragment', // Needed for templates
        'getHeading' => 'HTMLFragment',
        'Footer' => 'HTMLFragment', // Needed for templates
        'getFooter' => 'HTMLFragment'
    ];

    /**
     * @param string $name    An identifier
     * @param string $title   The title to display
     * @param string $heading
     * @param string $content
     * @param string $footer
     * @param array  $actions
     */
    public function __construct($name, $title = null, $content = null, $heading = null, $footer = null, $actions = [], $type = self::DEFAULT)
    {
        // Name
        $this->setName($name);

        // Title
        if ($title) {
            $this->setTitle($title);
        }

        // Type
        $this->setType($type);

        // Heading
        if ($heading) {
            $this->setHeading($heading);
        }

        // Content
        if ($content) {
            $this->setContent($content);
        }

        // Foot
        if ($footer) {
            $this->setFooter($footer);
        }

        if (is_array($actions) && count($actions)) {
            foreach ($actions as $action) {
                $this->addAction($action);
            }
        }

        $actions = $this->getActions();

        $this->extend('updateDefaultActions', $actions);

        parent::__construct();
    }

    /**
     * @return boolean
     */
    public function canStore()
    {
        return $this->storable;
    }

    /**
     * @param  boolean      $bool
     * @return Announcement
     */
    public function setStoreable($bool)
    {
        $this->storable = $bool;
        return $this;
    }

    /**
     * @return boolean
     */
    public function canDismiss()
    {
        return $this->dismissable;
    }

    /**
     * Define if announcement can be dismissed
     * @param  boolean $bool
     * @return Announcement
     */
    public function setDismissable($bool)
    {
        $this->dismissable = $bool;
        return $this;
    }

    /**
     * Set the name of this announcement
     * @param  string       $name The name of this announcement
     * @return Announcement       $this
     */
    public function setName($name)
    {
        if (!is_string($name)) {
            throw new Exception('Name must be a string');
        }

        $this->name = preg_replace("/[^a-zA-Z0-9_]+/", "", $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the title of this announcement
     * @param  string       $title The title to display
     * @return Announcement        $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $type e.g Announcement::MODAL
     */
    public function setType($type)
    {
        if ($type !== self::DEFAULT &&
            $type !== self::MODAL &&
            $type !== self::TRAY &&
            $type !== self::MESSAGE &&
            $type !== self::CALLOUT
        ) {
            throw new Exception('Invalid announcement type');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the heading of this announcement
     * @param  string       $heading
     * @return Announcement          $this
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set the content of this announcement
     * @param  string       $content
     * @return Announcement          $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the footer of this announcement
     * @param  string       $footer
     * @return Announcement         $this
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param  Action       $action
     * @return Announcement         $this
     */
    public function addAction($action)
    {
        $this->getActions()->push($action);
    }

    /**
     * @return array
     */
    public function getActions()
    {
        if (!$this->actions) {
            $this->actions = ActionList::create();
        }

        return $this->actions;
    }

    /**
     * Set the SS template that this announcement should use to render with. The default is the FQCN
     * @param string|array $template The name of the template (without the .ss extension) or array
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Return the template to render this announcement with
     *
     * @return string|array
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function forTemplate()
    {
        $template = static::class;

        if ($this->template) {
            $template = $this->template;
        }

        return $this->renderWith($template);
    }
}
