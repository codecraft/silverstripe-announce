# silverstripe-announce

Announce things to the page controller & view in SilverStripe. Can be used to power plain messages, modals, alerts, and callouts.

Multiple announcements can be stacked.

Announcements can be templated to match component libraries such as Bootstrap.

## Installation

**Note:** For a similar module in SilverStripe 3, consider using [axyr/silverstripe-flashmessage](https://github.com/axyr/silverstripe-flashmessage)

### Requirements

* SilverStripe >= 4.0
* PHP >= 5.6

**Composer**

`composer require jakxnz/silverstripe-announce`

**SilverStripe**

`dev/build?flush=all` your SilverStripe project.

## Introduction

Create announcements with Titles, Headings, Contents, Footers and Actions. Control whether they should be stored for later responses. Control whether they should be dismissable.

Announcements are retained at StilverStripe's `Controller`, and stored in the Announcement store (default is `$_SESSION`).

Each announcement is made available to the view via a SilverStripe `Controller` extension.

Only one set of announcements can be stacked at a time.

The announcement store can be replaced with a custom class that implements `AnnouncementStoreInterface`;

## Usage

Queue a new announcement
```
Announcements::queue('AnnouncementName', 'Announcement Title', 'This is an announcement message');
```
`Accounments::queue()` will accept all arguements for an `Announcement`

Queue an existing announcement

```
$msg = Announcement::create('AnnouncementName', 'Announcement Title', 'This is an announcement message');

Announcements::queue($msg);
```

Get all current announcements
```
$announcements = Announcements::get();
```

Get an announcement by name
```
Announcements::get()->getByName('AnnouncementName');
```

Get an announcement by type
```
Announcements::get()->getByType(Announcement::DEFAULT);
```

Clear the announcements queue
```
Announcements::clear();
```

Display announcements in the top scope of a template
```
<% if $Announcements %>
    $Announcements
<% end_if %>
```

### Customise an announcement

Example of all options

```
use CodeCraft\Announce\Model\Announcement;
use CodeCraft\Announce\Model\Action;

...

$announcement = Announcement::create(
    $name = 'AnnouncementName',
    $title = 'Announcement Title',
    $content = 'A plain or html string',
    $heading = 'A plain or html string',
    $footer = 'A plain or html string',
    $actions = [
        Action::create(
            $name = 'ActionName',
            $content = 'A plain or html string',
            $type = Action::BUTTON,
            $link = 'https://www.google.com/',
            $extraClass = 'btn btn-primary'
        )
    ],
    $type = Announcement::DEFAULT
)
    // Store announcement
    ->setStoreable(true)

    // Dismissable
    ->setDismissable(true)

    // Name
    ->setName('AnnouncementName')

    // Title
    ->setTitle('Announcement Title')
    ->setTitle('<p>Announcement Title</p>')

    // Content
    ->setContent('Announcement body')
    ->setContent('<p>Announcement body</p>')

    // Heading
    ->setHeading('Announcement heading')
    ->setHeading('<p>Announcement heading</p>')

    // Footer
    ->setFooter('Announcement footer')
    ->setFooter('<p>Announcement footer</p>')

    // Action
    ->addAction(
        Action::create()
            ->setName('ActionName')
            ->setContent('OK')
            ->setContent('<p>OK</p>')
            ->setType(Action::BUTTON)
            ->setLink('http://www.google.com/')
            ->addExtraClass('btn btn-primary')
            ->setAttribute('title', 'A helpful title')
    )

    // Type
    ->setType(Announcement::DEFAULT)

    // Template
    ->setTemplate('templates\CodeCraft\Announce\Announcement');
```

### Announcement store

Access the announcement store
```
Announcements::get()->getStore();
```

Expect announcements to be stored as an `array`

#### Set storable

Announcements are stored by default, so that they can be included in the next relevant response.

Define if the announcement should be stored
```
$msg = Announcement::create('AnnouncementName', 'Un-stored Announcement', 'This announcement is not stored')
    ->setStoreable(false);
```

### Custom announcement template

Set a custom template for all announcements by [overloading templates](#overloading-templates)

Set a custom template for any announcement
```
$msg = Announcement::create('AnnouncementName', 'Announcement Title', 'This is an announcement message')
    ->setTemplate('MyTemplate');
```

### Overloading templates

**Announcement template**

Overload the default template for all announcement actions by overloading `templates/CodeCraft/Announce/Model/Announcement`

**Announcement Action template**

Overload the default template for all announcement actions by overloading `templates/CodeCraft/Announce/Model/Action`

Read more about SilverStripe [Template Inheritance](https://docs.silverstripe.org/en/4/developer_guides/templates/template_inheritance/)

### Announcements by name

Each announcement name is distinct. Call announcements by name

**Back-end**
```
Announcements::get()->getByName('AnnouncementName');
```

**Template**
```
$Announcements.ByName('AnnouncementName');
```

### Announcements by type

Set any announcement's type with one of the available types; `Announcement::DEFAULT`, `Announcement::MODAL`, `Announcement::TRAY`, `Announcement::MESSAGE`, or `Announcement::CALLOUT`.

```
$msg = Announcement::create('Name')->setType(Announcement::MODAL);
```

Call announcements by type

**Back-end**
```
Announcements::get()->getByType(Announcement::MODAL);
```

**Template**
```
$Announcements.ByType('modal');
```
_Can be 'default', 'modal', 'tray', 'message', 'callout'_

## Limitations

* Does not have any CMS editable functionality

## License

Modified BSD License

Copyright (c) 2018, Jackson Darlow

Read the [license](https://github.com/codecraft/silverstripe-announce/blob/master/LICENSE)

## To do

* Tests
* Add redundancies for cases where announcement store is ahead of announcement stack
* Add a way to disable the store
* Create a database announcement store
* Create a redis announcement store
* Replace Announcement type constants with an object-oriented model
    * Create an email Announcement type
* An announcement history for user-focused announcements
    * Flag announcement when viewed by user
* Add CMS editable announcements, similiar to [nzta/silverstripe-sitebanner](https://github.com/NZTA/silverstripe-sitebanner)
 * Version CMS editable announcements

