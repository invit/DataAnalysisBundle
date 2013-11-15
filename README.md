Invit Toolset Bundle
====================

1) Installing
-------------

add project to composer

add InvitToolsetBundle to AppKernel.php
```php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Invit\ToolsetBundle\InvitToolsetBundle(),
            // ...
        );
    }
```

add forms.html.twig to config.yml
```yml
# Twig Configuration
twig:
    form:
        resources:
            - 'InvitToolsetBundle:Form:fields.html.twig'
```

add javascript
```html
    <script src="{{ asset('bundles/invittoolset/js/toolset.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bundles/invittoolset/js/3rdParty/select2/select2.min.js') }}"></script>
```

add stylesheet
```html
    <link href="{{ asset('bundles/invittoolset/js/3rdParty/select2/select2.css') }}" rel="stylesheet" />
```