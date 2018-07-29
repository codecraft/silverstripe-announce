<?php

namespace CodeCraft\Announce\Model;

use Exception;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\ViewableData;

class Action extends ViewableData
{
    const DEFAULT = 'default';
    const BUTTON = 'button';
    const ANCHOR = 'anchor';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var array
     */
    protected $extraClasses = [];

    /**
     * @var array
     */
    protected $attributes = [];

    private static $casting = [
        'Content' => 'HTMLFragment', // Needed for templates
        'getContent' => 'HTMLFragment',
        'AttributesHTML' => 'HTMLFragment', // Needed for templates
        'getAttributesHTML' => 'HTMLFragment'
    ];

    /**
     * @param string $name    An identifier
     * @param string $content
     * @param string $type
     * @param string $link
     * @param string $extraClass
     * @param array  $actions
     */
    public function __construct($name, $content = null, $type = self::BUTTON, $link = null, $extraClass = null) {

        // Name
        $this->setName($name);

        // Content
        if ($content) {
            $this->setContent($content);
        }

        // Type
        if ($type) {
            $this->setType($type);
        }

        // Link
        if ($link) {
            $this->setLink($link);
        }

        // Extra class
        if ($extraClass) {
            $this->addExtraClass($extraClass);
        }

        parent::__construct();
    }

    /**
     * Set the name of this action
     * @param  string $name The name of this action
     * @return Action       $this
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
     * Set the content of this announcement
     * @param  string $content
     * @return Announcement             $this
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
     * @param string $type e.g Action::BUTTON
     */
    public function setType($type)
    {
        if ($type !== self::BUTTON &&
            $type !== self::ANCHOR &&
            $type !== self::HIDDEN
        ) {
            throw new Exception('Invalid action type');
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
     * Set the link of this action
     * @param  string $link
     * @return Action       $this
     */
    public function setLink($link)
    {
        if (!is_string($link)) {
            throw new Exception('Action link must be a string');
        }

        $this->attributes['href'] = $link;
        $this->link = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getExtraClasses()
    {
        return $this->extraClasses;
    }

    /**
     * Get all extra classes compiled into a single string
     * @return string
     */
    public function getExtraClassesString()
    {
        return implode(' ', $this->getExtraClasses());
    }

    /**
     * Add one or more CSS-classes to the FormField container.
     * Multiple class names should be space delimited.
     * @param string  $class
     * @return Action        $this
     */
    public function addExtraClass($class)
    {
        $classes = preg_split('/\s+/', $class);

        $extra = $this->getExtraClasses();

        foreach ($classes as $class) {
            $extra[$class] = $class;
        }

        $this->extraClasses = $extra;

        return $this;
    }

    /**
     * Remove one or more CSS-classes from the FormField container.
     *
     * @param string $class
     *
     * @return $this
     */
    public function removeExtraClass($class)
    {
        $classes = preg_split('/\s+/', $class);

        $extra = $this->getExtraClasses();

        foreach ($classes as $class) {
            unset($extra[$class]);
        }

        $this->extraClasses = $extra;

        return $this;
    }

    /**
     * Set an HTML attribute on the action element, mostly an input tag.
     * @param  string $name
     * @param  string $value
     * @return Action        $this
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function getAttribute($name)
    {
        $attributes = $this->getAttributes();

        if (isset($attributes[$name])) {
            return $attributes[$name];
        }

        return null;
    }


    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [
            'class' => $this->getExtraClassesString()
        ];

        if ($this->getType() == self::DEFAULT || $this->getType() == self::BUTTON) {
            $attributes['type'] = 'button';
        }

        $attributes = array_merge($attributes, $this->attributes);

        return $attributes;
    }

    /**
     * @return string
     */
    public function getAttributesHTML($attributes = null)
    {
        $attributes = (array) $this->getAttributes();

        $attributes = array_filter($attributes, function ($v) {
            return ($v || $v === 0 || $v === '0');
        });

        // Create markup
        $parts = array();

        foreach ($attributes as $name => $value) {
            if ($value === true) {
                $parts[] = sprintf('%s="%s"', $name, $name);
            } else {
                $parts[] = sprintf('%s="%s"', $name, Convert::raw2att($value));
            }
        }

        return implode(' ', $parts);
    }

    /**
     * @return string
     */
    public function forTemplate()
    {
        return $this->renderWith(static::class);
    }
}
